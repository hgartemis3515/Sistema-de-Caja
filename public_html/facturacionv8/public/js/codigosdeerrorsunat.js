$(function() {
	get_lista_errores();
});

function get_lista_errores(){
    $.ajax({
        url : '/facturacionv8/codigosdeerrorsunat/get_lista_errores',
        method :  'POST',
        dataType : "json"
    }).then(function(data){
			if(data.respuesta == 'ok') {
				$('#tbl_lista_errores').DataTable({
						data: data.lista,
						"bDestroy": true,
						"paging": false,
						"oLanguage": {
							"sSearch": "Busca Aquí por Código o Descripción: "
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
					//$(light).unblock();
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
             //$(light).unblock();
		})
  });
}