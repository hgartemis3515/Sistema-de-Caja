$(function() {
    // Primary
    $(".control-primary").uniform({
        radioClass: 'choice',
        wrapperClass: 'border-primary-600 text-primary-800'
    });
	
	$("#informar_condpago_sunat").bootstrapSwitch();
	
	var mostrar_aviso = $("#var_mostrar_aviso").val();
	var rol_usuario = $("#var_tipo_usuario").val();

	if(mostrar_aviso == 'no') {

	} else {
		if(rol_usuario == 'admin') {
			$('#vm_modal_aviso').modal("show");
		}
	}

	$("#btn_guardar_condpago_sunat").click(guardar_configuracion_condicion_pago);
});

function guardar_configuracion_condicion_pago() {
	var light = $("#contenido_modal_aviso");

	$(light).block({
		message: '<div class="loading"> \
				<div class="loading-bar"></div> \
				<div class="loading-bar"></div> \
				<div class="loading-bar"></div> \
				<div class="loading-bar"></div> \
			</div> <p> <br />Procesando ...</p>',

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

	var datastring = $("#form_modal_aviso").serializeArray();

	$.ajax({
        url : '/facturacionv8/dashboard/save_config_modalidad_pago',
        method :  'POST',
		dataType : "json",
		data: datastring
    }).then(function(data){

		if(data.respuesta == 'ok') {
			swal({   
				title: data.titulo,   
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
					datastring.push({name: 'confirmacion', value: 'si'});
					$.ajax({
						url : '/facturacionv8/dashboard/save_config_modalidad_pago',
						method :  'POST',
						dataType : "json",
						data: datastring
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
								$('#vm_modal_aviso').modal("hide");
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