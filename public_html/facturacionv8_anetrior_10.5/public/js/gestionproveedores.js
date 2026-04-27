$(function() {
	
	
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
	$('.btn_saveproveedor').click(guardar_proveedor);

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

	get_lista_proveedores();
});

function editar_proveedor(idproveedor) {
	var light = $('#contenido_lista_proveedor');
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
        url : '/facturacionv8/gestiondeproveedores/get_data_proveedores',
		method :  'POST',
		data: {idproveedor: idproveedor},
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			$(light).unblock();
			$("#idproveedor").val(data.proveedores.id_proveedor);
			$("#type_document").val(data.proveedores.id_tipodocidentidad).trigger("change").trigger("select2:select");
			$("#doc_id").val(data.proveedores.num_doc);
			$("#razon_social").val(data.proveedores.razon_social);
			$("#direccionfiscal").val(data.proveedores.direccion_fiscal);
			$("#ubigeo").val(data.proveedores.id_cod_ubigeo).trigger("change").trigger("select2:select");
			$("#txt_codigo").val(data.proveedores.codigo);
			$("#email").val(data.proveedores.email);
			$("#celular").val(data.proveedores.celular);
			$("#detalle_adicional").val(data.proveedores.detalle_adicional);
			
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

function eliminar_proveedor(idproveedor) {
	var light = $('#contenido_lista_proveedor');
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
        text: "¿Estás seguro que deseas eliminar este proveedor?",
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
				url : '/facturacionv8/gestiondeproveedores/eliminar_proveedores',
				method :  'POST',
				data: {idproveedor: idproveedor},
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
						get_lista_proveedores();
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

function get_lista_proveedores() {
	var light = $('#contenido_lista_proveedor');
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
        url : '/facturacionv8/gestiondeproveedores/get_lista_proveedores',
        method :  'POST',
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			$('#tbl_lista_proveedor').DataTable({
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
                "bDestroy": true
            }); 
            $(light).unblock();
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

function guardar_proveedor(){
	var light = $("#contenido_datos_proveedores");

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
	
	var datastring = $("#frm_addproveedor").serializeArray();
	
	$.ajax({
        url : "/facturacionv8/gestiondeproveedores/insert",
		method :  'POST',
		data: datastring,
        dataType : "json"
    }).then(function(data){
        if(data.respuesta == 'ok') {
			swal({   
                title:data.titulo,   
                text: data.mensaje,
                html: true,
                type: "success", 
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Ok",
            }, function() {
				$(light).unblock();
				$("#frm_addproveedor")[0].reset();
				$("#idproveedor").val('');
				$("#ubigeo").select2("val", 0);
				$("#type_document").select2("val", 0);
				get_lista_proveedores();
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