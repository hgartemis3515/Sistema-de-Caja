$(function() {
    $('.btn_unidades').click(save);
    get_lista_unidades(); 
});

function save(){
   var datastring = $("#frm_unidades").serializeArray();
    $.ajax({
        url : '/facturacionv8/gestionunidades/guardar_unidades',
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
                get_lista_unidades();
                    $("#frm_unidades")[0].reset();
                    $("#idunidad").val('');
            });
		} else {
			swal({
				title: 'Error',
				text: data.mensaje,
				html: true,
				type: "error",
				confirmButtonText: "Ok",
				confirmButtonColor: "#2196F3"
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
		});

    });
}
function get_lista_unidades(){
		var light = $("#content_lista_unidades");

		$(light).block({
		message: '<div class="loading"> \
                    <div class="loading-bar"></div> \
                    <div class="loading-bar"></div> \
                    <div class="loading-bar"></div> \
                    <div class="loading-bar"></div> \
                </div> <p> <br />Un momento, estamos recuperando la data ...</p>',

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
        url : '/facturacionv8/gestionunidades/get_lista_unidades',
        method :  'POST',
        dataType : "json"
    }).then(function(data){
			if(data.respuesta == 'ok') {
				$('#tbl_lista_unidades').DataTable({
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
		})
  });
}

function edit_unidades(idunidad){
	var light = $("#content_lista_unidades");

	$(light).block({
	message: '<div class="loading"> \
					<div class="loading-bar"></div> \
					<div class="loading-bar"></div> \
					<div class="loading-bar"></div> \
					<div class="loading-bar"></div> \
				</div> <p> <br />Un momento, estamos recuperando la data ...</p>',

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
        url : '/facturacionv8/gestionunidades/get_data_unidades',
        method :  'POST',
        data: {idunidad: idunidad},
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			$("#idunidad").val(data.unidad_medidas.idunidad);
			$("#codigo").val(data.unidad_medidas.codigo);
			$("#nombre_unidad").val(data.unidad_medidas.nombre);
			$("#simbolo_unidad").val(data.unidad_medidas.simbolo);

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

