$(function(){
	get_lista();
});

function cambiar_estado_sucursal(idsucursal, accion) {
	var light = $('#contenido_lista_sucursales'); 
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

	var text_message = '¿Realmente Deseas Activar la Sucursal?';
	if(accion == 'desactivar') {
		text_message = '¿Realmente Deseas Inhabilitar la Sucursal?, ya no podrás emitir comprobantes con esta sucursal y tampoco esta serie.';
	}

	swal({
        title: "Necesitamos Tu Confirmación",
        text: text_message,
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
				url : '/facturacionv8/branchoffice/cambiar_estado_sucursal',
				method :  'POST',
				data: {idsucursal: idsucursal},
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
						window.location.reload();
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

function get_lista() {
	var light = $('#contenido_lista_sucursales'); 
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
        url : '/facturacionv8/branchoffice/get_lista_sucursales',
        method :  'POST',
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			$('#tbl_lista_sucursales').DataTable({
                data: data.lista,
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