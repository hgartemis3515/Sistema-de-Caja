var dataTable_lista_clientes;
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
	
	$("#type_document").on('change', function() {
		var id_document = this.value; 
		var text_document = $("#type_document option:selected").text();
		$(".type_id").html(text_document);
		//1: dni
		//6: ruc
		$("#doc_id").keyup(function(e){
			if(e.keyCode == 13) {
				e.preventDefault;
				$(".search_document").trigger("click");
			}
		});

		if(id_document == '1' || id_document == '6') {
			$("#btn_buscar_datos_api").show();
			$("#contenido_boton_busqueda").removeClass();
			$("#contenido_boton_busqueda").addClass( "input-group" );
		} else {
			$("#btn_buscar_datos_api").hide();
			$("#contenido_boton_busqueda").removeClass();
			$("#contenido_boton_busqueda").addClass( "form-group" );
		}
	});
	$('.js-example-basic-single').select2();
	$('.btn_saveclient').click(guardar_cliente);

	$(".btn_generar_codigo").click(function(){
		generar_codigo($("#txt_codigo"), 8);
	});

	$("#doc_id").on('input', function() {
        $(".lbl_estado_empresa").hide();
    }).on('change', function() {
        $(".lbl_estado_empresa").hide();
    });

	$(".search_document").click(function() {
        var num_doc = $("#doc_id").val();
		if(num_doc != '') {
            var tipo_doc = $("#type_document").val();
            if(tipo_doc == 1 || tipo_doc == 6) { //1: DNI //6: RUC
                consultar_numero_doc($("#idcliente"), num_doc, $("#razon_social"), $("#direccionfiscal"), $("#ubigeo"), tipo_doc, $("#email"));
            }
		}
	});

	get_lista_clientes();

	$(".file-styled").uniform({
        fileButtonClass: 'action btn btn-primary'
    }); 

	$("#btn_importar_data").click(iniciar_proceso_importacion);
});

function iniciar_proceso_importacion(){
	var light = $("#content_vm_importar_clientes");

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

    var datastring = $("#vm_frm_importar_clientes").serializeArray();
    var formData = new FormData();
    formData.append("archivo", $("#file_data_import")[0].files[0]);
    $.each(datastring, function( index, control ) {
        formData.append(control.name, control.value);
    });
    
    $.ajax({
        url : '/facturacionv8/client/importar_clientes',
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
                        url: "/facturacionv8/client/importar_clientes",
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
                                get_lista_clientes();
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

function editar_cliente(idcliente) {
	var light = $('#contenido_lista_clientes');
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

	$.ajax({
        url : '/facturacionv8/client/get_data_cliente',
		method :  'POST',
		data: {idcliente: idcliente},
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			$(light).unblock();
			$("#idcliente").val(data.cliente.idcliente);
			$("#type_document").val(data.cliente.id_tipodocidentidad).trigger("change").trigger("select2:select");
			$("#doc_id").val(data.cliente.num_doc);
			$("#razon_social").val(data.cliente.razon_social);
			$("#direccionfiscal").val(data.cliente.direccion_fiscal);
			$("#ubigeo").val(data.cliente.id_cod_ubigeo).trigger("change").trigger("select2:select");
			$("#txt_codigo").val(data.cliente.codigo);
			$("#email").val(data.cliente.email);
			$("#celular").val(data.cliente.celular);
			$("#telefono").val(data.cliente.telefono);
			$("#cuenta_detraccion").val(data.cliente.num_cuenta_detraccion);
			$("#detalle_adicional").val(data.cliente.detalle_adicional);
			
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

function eliminar_cliente(idcliente) {
	var light = $('#content_lista_categorias');
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
	swal({
        title: "Confirmación!",
        text: "¿Estás seguro que deseas eliminar este cliente?",
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
				url : '/facturacionv8/client/eliminar_cliente',
				method :  'POST',
				data: {idcliente: idcliente},
				dataType : "json"
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
						$(light).unblock();
						get_lista_clientes();
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
        }else{
            $(light).unblock();
            return;
        }
      
    });
	
}

function get_lista_clientes() {
	var light = $("#contenido_lista_clientes");

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

	dataTable_lista_clientes = $('#tbl_lista_clientes').DataTable( {
        "processing": true,
        "dom": 'Blfrtip',
		"serverSide": true,
		"ajax":{
			url :"/facturacionv8/client/get_lista_clientes", // json datasource
			type: "post",
			error: function(){
				$(".tbl_lista_clientes-error").html("");
				$("#tbl_lista_clientes").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
				$("#tbl_lista_clientes_processing").css("display","none");
			}
        },
        "bDestroy": true,
        "lengthMenu": [ [10, 25, 50, 100, 300, 600], [10, 25, 50, 100, 300, 600] ],
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
				text: '<i class="icon-upload4 position-left"></i> Importar y/o Actualizar</span>',
				className: 'btn bg-info',
				action: function ( e, dt, node, config ) {
					$("#vm_importar_clientes").modal('show');
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
            { "data": "idcliente", "visible": false},
			{ "data": "id_tipodocidentidad", "visible": true},
			{ "data": "codigo", "visible": false},
			{ "data": "num_doc", "visible": true},
			{ "data": "razon_social", "visible": true},
			{ "data": "direccion_fiscal", "visible": false},
			{ "data": "id_cod_ubigeo", "visible": false},

			{ "data": "departamento", "visible": false},
			{ "data": "provincia", "visible": false},
			{ "data": "distrito", "visible": false},

			{ "data": "email", "visible": true},
			{ "data": "celular", "visible": true},
			{ "data": "detalle_adicional", "visible": false},
			{ "data": "fecha_registro", "visible": false},
			{ "data": "estado", "visible": false},
			{ "data": "sexo", "visible": false},
			{ "data": "fecha_nac", "visible": false},
			{ "data": "opciones", "visible": true}
          ],
        stateSave: false,
        initComplete: function(){
            $(light).unblock();
        }
    });
}

function guardar_cliente(){
	var light = $("#contenido_datos_clientes");

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
	
	var datastring = $("#frm_addclient").serializeArray();
	
	$.ajax({
        url : "/facturacionv8/client/insert",
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
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Ok",
            }, function() {
				$(light).unblock();
				//$("#frm_addclient")[0].reset();
				$('#content_panel_usuario').find('input:text').val('');
				$("#txt_codigo").val("");
				$("#doc_id").val("");
				$("#razon_social").val("");
				$("#direccionfiscal").val("");
				$("#email").val("");
				$("#telefono").val("");
				$("#celular").val("");
				$("#cuenta_detraccion").val("");
				$("#detalle_adicional").val("");

				$("#idcliente").val('');
				get_lista_clientes();
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
}