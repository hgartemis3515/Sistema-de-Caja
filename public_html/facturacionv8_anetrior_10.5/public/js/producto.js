$(function() {
    $.extend( $.fn.dataTable.defaults, {
        autoWidth: false,
        dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        language: {
            search: '<span>Filter:</span> _INPUT_',
            searchPlaceholder: 'Type to filter...',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
        }
    });
    
    $('.select2').select2({
        minimumResultsForSearch: -1
    });   
    $("#id_categoria").select2();
    $('.id_unidad_medida').select2();   
    $('#id_cod_moneda').select2({
        minimumResultsForSearch: -1
	});
    $('.btn_product').click(agregar_product);
    $('.btn_agregar_categoria').click(agregar_categoria);
    $(".btn_generar_codigo").click(function(){
       generar_codigo($("#txt_codigo"), 7, $(".btn_generar_codigo > i"));
    });
    $(".btn_generar_categoria").click(function(){
        generar_codigo($("#txt_codigo_categoria"), 7, $(".btn_generar_categoria > i"));
     });
     
    get_lista_productos();
    get_lista_tipoafectacionigv($("#producto_tipo_afect_igv"));
    get_lista_unidades_medida($("#id_unidad_medida"));
    get_lista_codigodetraccion($("#id_cod_detraccion"));
    get_lista_monedas($("#id_cod_moneda"));
    get_lista_categorias($("#id_categoria"));
    get_lista_almacenes($("#select_sucursal"), 'si');
    edit_product();
   $("#id_unidad_medida").on('change', function() {
        let id_unidad = $("#id_unidad_medida").val();
        if(id_unidad == 20) {
            $(".inputs_solo_productos ").hide('slide');
        } else {
            $(".inputs_solo_productos ").show('slide');
        }
    });
   
    $('.select_codigoubigeo').select2();
    
    $('.select').select2({ minimumResultsForSearch: -1 });
    
    $(".btn_agregarproducto").click(function() {
        $('.opciones_producto a[href="#buscar_producto"]').tab('show');
        $(".content_propiedades_producto").hide();
        $("#vm_agregar_articulo").modal("show");
    });
    $("#valor_con_igv").on('input', function() {
        calcular_precio_sin_igv();
    });
    
    $("#valor_sin_igv").on('input', function() {
        calcular_precio_con_igv();
    });

    $('#producto_tipo_afect_igv').on('change', function() {
        var id_tipoafectacionigv = parseInt($('#producto_tipo_afect_igv').val()) + 0;
        if(id_tipoafectacionigv != 10) {
            $("#text_precio_inc_igv").html('Precio Unitario de Venta');
            $("#content_preciounidad_inc_igv").removeClass();
            $("#content_preciounidad_sin_igv").hide();

            $("#content_preciounidad_inc_igv").attr('class', 'col-md-6');
        } else {
            $("#text_precio_inc_igv").html('Precio Unitario de Venta (Inc.IGV)');
            $("#content_preciounidad_inc_igv").removeClass();
            $("#content_preciounidad_sin_igv").show();

            $("#content_preciounidad_inc_igv").attr('class', 'col-md-3');
        }
    });

    /*
    Dropzone.options.dropzoneSingle = {
        paramName: "file", // The name that will be used to transfer the file
        maxFilesize: 1, // MB
        maxFiles: 1,
        dictDefaultMessage: 'Arrastra aquí el Excel <span>o Haz Click!</span>',
        autoProcessQueue: false,
        init: function() {
            this.on('addedfile', function(file){
                if (this.fileTracker) {
                this.removeFile(this.fileTracker);
            }
                this.fileTracker = file;
            });
        }
    };
    */

   $(".file-styled").uniform({
        fileButtonClass: 'action btn btn-primary'
    });

    $("#btn_importar_data").click(iniciar_proceso_importacion);
    $(".switch").bootstrapSwitch();
});

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

function iniciar_proceso_importacion(){
	var light = $("#content_panel_productos");

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

function calcular_precio_sin_igv() {
    var igv_percent = parseFloat((18/100) + 1);
    if($('#valor_con_igv').val() == '' || $('#valor_con_igv').val() <= 0 || isNaN($('#valor_con_igv').val())) {
        precioarticulo = 0;
    } else {
        precioarticulo = parseFloat($('#valor_con_igv').val());
    }

    var precio_sin_igv = round_math(parseFloat(precioarticulo) / parseFloat(igv_percent), 2);
    $("#valor_sin_igv").val(precio_sin_igv);

}

function calcular_precio_con_igv() {
    var igv_percent = 1.18;
    if($('#valor_sin_igv').val() == '' || $('#valor_sin_igv').val() <= 0 || isNaN($('#valor_sin_igv').val())) {
        precioarticulo = 0;
    } else {
        precioarticulo = parseFloat($('#valor_sin_igv').val());
    }

    var precio_sin_igv = round_math(parseFloat(precioarticulo) * parseFloat(igv_percent), 2);
    $("#valor_con_igv").val(precio_sin_igv);

}
function get_lista_productos(){
    $.ajax({
        url : '/facturacionv8/producto/get_lista_productos',
        method :  'POST',
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
            
            // Setup - add a text input to each footer cell
            $('#tbl_lista_productos tfoot th').each( function () {
                var title = $('#tbl_lista_productos thead th').eq( $(this).index() ).text();
                $(this).html( '<input class="form-control form-control-sm" type="text" placeholder="'+title+'" />' );
            } );

            // DataTable
            var table = $('#tbl_lista_productos').DataTable({
                data: data.lista,
                buttons: {
                    dom: {
                        button: {
                            className: 'btn btn-default'
                        }
                    },
                    buttons: [
                        'copyHtml5',
                        'excelHtml5',
                        'csvHtml5',
                        'pdfHtml5'
                    ]
				},
                "bDestroy": true,
                orderCellsTop: true,
                fixedHeader: true
            }); 

            // Apply the search
            table.columns().eq( 0 ).each( function ( colIdx ) {
                $( 'input', table.column( colIdx ).footer() ).on( 'keyup change', function () {
                    table
                        .column( colIdx )
                        .search( this.value )
                        .draw();
                } );
            } );
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

function get_lista_categorias2(select) {
    $.ajax({
        url : '/facturacionv8/producto/get_lista_categorias',
        method :  'POST',
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			$.each(data.lista, function(key, item) {
                select.append('<option value="' + item.idcategoria + '">' + item.nombre + '</option>');
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

function agregar_product(){
    var light = $('#content_panel_productos');
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
    var datastring = $("#frm_product").serializeArray();
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
				$(light).unblock();
                $("#idproducto").val('');
                $("#txt_codigo").val('');
                $("#stock").val('');
                $("#stock_minimo").val('');
                $("#nota_producto").val('');
                $("#nombre_servicio").val('');
                $("#valor_con_igv").val(0);
                $("#valor_sin_igv").val(0);
                get_lista_productos();
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
function edit_product(){
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
    var idproducto = $("#idproducto").val();
	if(idproducto == '' || idproducto <= 0) {
		$(light).unblock();
		return false;
	}
	
	$.ajax({
        url: '/facturacionv8/producto/get_data_producto',
        data: {idproducto: idproducto},
		method :  'POST',
        dataType : "json"
    }).then(function(data){
        if(data.respuesta == 'ok') {
            $(light).unblock();
            $("#idproducto").val(data.producto.idproducto);
            $("#txt_codigo").val(data.producto.codigo);
            $("#nombre_servicio").val(data.producto.nombre);
            $("#producto_tipo_afect_igv").val(data.producto.id_tipoafectacionigv).trigger("change").trigger("select2:select");

            if(data.producto.id_tipoafectacionigv == 10) {
                $("#valor_con_igv").val(data.producto.valor_con_igv);
                $("#valor_sin_igv").val(data.producto.valor_sin_igv);
            } else {
                $("#valor_con_igv").val(data.producto.valor_sin_igv);
            }
            $("#valor_de_compra").val(data.producto.precio_compra);
            $("#id_categoria").val(data.producto.id_categoria).trigger("change").trigger("select2:select");
            $("#id_cod_detraccion").val(data.producto.id_cod_detraccion).trigger("change").trigger("select2:select");
            $("#id_unidad_medida").val(data.producto.id_unidad_medida).trigger("change").trigger("select2:select");
            $("#id_cod_moneda").val(data.producto.id_cod_moneda).trigger("change").trigger("select2:select");
            $("#stock").val(data.producto.stock);
            $("#stock_minimo").val(data.producto.stock_minimo);
            $("#nota_producto").val(data.producto.nota);

            if(parseInt(data.producto.idsucursal) > 0) {
                $("#select_sucursal").val(data.producto.idsucursal).trigger("change").trigger("select2:select");
            }

            $(".valores_no_editables").hide();
            
        } else {
          /*  swal({   
                title:'Error',   
                text: data.mensaje,
                html: true,
                type: "error", 
                confirmButtonColor: "#00BCD4",   
                confirmButtonText: "Ok",
            }, function() {
				$(light).unblock();
            });*/
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
		}, function() {
			$(light).unblock();
		});
    });
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
                        get_lista_productos();
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
	var datastring = $("#frm_category").serializeArray();
	$.ajax({
        url :  '/facturacionv8/category/insert',
		method :  'POST',
		data: datastring,
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
                $('#modal_categoria').modal('hide');
                get_lista_categorias($("#id_categoria"));
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
    });
}