$(function() {
    $("#btn_importar_data").click(iniciar_proceso_importacion_cpe);
});
function iniciar_proceso_importacion_cpe(){
	var light = $("#content_vm_importar_cpe");

    $(light).block({
		message: '<div class="loading"> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
					</div> <p> <br />Iniciando proceso de importación ...</p>',
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

    var datastring = $("#frm_importar_cpe").serializeArray();
    var formData = new FormData();
    formData.append("archivo", $("#file_data_import")[0].files[0]);
    $.each(datastring, function( index, control ) {
        formData.append(control.name, control.value);
    });
    
    $.ajax({
        url : '/facturacionv8/importacioncpe/iniciar_proceso_importacion',
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
                        url: "/facturacionv8/importacioncpe/iniciar_proceso_importacion",
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