$(function(){
	$('.js-example-basic-single').select2();
	$('.btn_savecategory').click(save_category);
	$(".btn_generar_codigo").click(function(){
		generar_codigo($("#txt_codigo"));
	});
	get_lista_categorias();
});

function editar_categoria(idcategoria) {
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

	$.ajax({
        url : '/facturacionv8/category/get_data_categoria',
		method :  'POST',
		data: {idcategoria: idcategoria},
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			$(light).unblock();
			$("#idcategoria").val(data.categoria.idcategoria);
			$("#txt_codigo").val(data.categoria.codigo);
			$("#nombre_categoria").val(data.categoria.nombre);
			$("#descripcion_categoria").val(data.categoria.descripcion);
			$("#codigosunat").val(data.categoria.id_codigoproducto).trigger("change").trigger("select2:select");

			$("#codigo_cuenta_contable").val(data.categoria.codigo_cuenta_contable);
			$("#codigo_centro_costo").val(data.categoria.codigo_centro_costo);
			$("#codigo_presupuesto").val(data.categoria.codigo_presupuesto);
			
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

function eliminar_categoria(idcategoria) {
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
        text: "¿Estás seguro que deseas eliminar esta categoría?",
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
                url: '/facturacionv8/category/eliminar_categoria',
                data: {idcategoria: idcategoria},
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
                        get_lista_categorias();
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

function get_lista_categorias() {
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

	$.ajax({
        url : '/facturacionv8/category/get_lista_categorias',
        method :  'POST',
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			$('#tbl_lista_categorias').DataTable({
                data: data.lista,
                "bDestroy": true
            });
            //$('.tbl_lista_productos').on('click', '.btn_editar_producto', get_all_data_producto);
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

function save_category(){
	var light = $("#content_panel_category");

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
	
	var datastring = $("#frm_category").serializeArray();
	
	$.ajax({
        url : '/facturacionv8/category/insert',
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
				$("#frm_category")[0].reset();
				$("#idcategoria").val('');
				get_lista_categorias();
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