$(function(){
    active_noti();
    
});
function active_noti(){
	
	$.ajax({
		url : '/facturacionv8/notificacion/notificaciones',
		method :  'POST',
		dataType : "json"
	}).then(function(data){
        if(data.respuesta == 'ok') {
            $(".docs_pendientes_envio").html(data.docs_pendientes);
        }	

	}, function(reason){
		swal({
			title: 'Error',
			text: 'Es posible que se haya cerrado la sessión, <a style="color: #1c81d1;" href="/facturacionv8/login" target="_blank">haz click aquí para ingresar de nuevo</a>, luego de iniciar sessión, puedes regresar a esta pantalla y recargar la página y continuar...',
			html: true,
			type: "error",
			confirmButtonText: "Ok",
			confirmButtonColor: "#2196F3"
		})
	});
}

function fecha_tope(){
	
	$.ajax({
		url : '/facturacionv8/notificacion/get_fecha_tope',
		method :  'POST',
		dataType : "json"
	}).then(function(data){
        if(data.respuesta == 'ok') {
           
        }	

	}, function(reason){
		swal({
			title: 'Error',
			text: 'Es posible que se haya cerrado la sessión, <a style="color: #1c81d1;" href="/facturacionv8/login" target="_blank">haz click aquí para ingresar de nuevo</a>, luego de iniciar sessión, puedes regresar a esta pantalla y recargar la página y continuar...',
			html: true,
			type: "error",
			confirmButtonText: "Ok",
			confirmButtonColor: "#2196F3"
		})
	});
}
