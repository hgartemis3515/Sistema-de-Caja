$(function() {
    $(".btn_planbase").click(save);
    get_lista_planbase(); 
});

function save(){
    var datastring = $("#frm_planbase").serializeArray();
    $.ajax({
        url : '/facturacionv8/gestiondeplanbase/save',
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
                get_lista_planbase();
								$("#frm_planbase")[0].reset();
								$("#idplanbase").val('');

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
				// $(light).unblock();
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
            //  $(light).unblock();
		});

    });
}
function get_lista_planbase(){
    $.ajax({
        url : '/facturacionv8/gestiondeplanbase/get_lista_planbase',
        method :  'POST',
        dataType : "json"
    }).then(function(data){
			if(data.respuesta == 'ok') {
				$('#tbl_lista_planbase').DataTable({
						data: data.lista,
						"bDestroy": true
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
		})
  });
}

function edit_planbase(idplanbase){
	var light = $("#content_lista_planbase");

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
        url : '/facturacionv8/gestiondeplanbase/get_data_planbase',
        method :  'POST',
        data: {idplanbase: idplanbase},
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			$("#idplanbase").val(data.plan.idplanbase);
			$("#nombre_plan").val(data.plan.nombre);
			$("#num_dias").val(data.plan.num_dias);
			$("#limite_doc").val(data.plan.limite_mes_doc);
			$("#tipo_plan").val(data.plan.tipo);
			$("#precio_base").val(data.plan.precio_base);
			$("#porcentaje").val(data.plan.porcentaje);
			$("#nota").val(data.plan.nota);
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

function eliminar_planbase(idplanbase){
	var light = $("#content_lista_planbase");

	$(light).block({
	message: '<div class="loading"> \
					<div class="loading-bar"></div> \
					<div class="loading-bar"></div> \
					<div class="loading-bar"></div> \
					<div class="loading-bar"></div> \
				</div> <p> <br />Un momento ...</p>',

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
        url : '/facturacionv8/gestiondeplanbase/eliminar_plan',
        method :  'POST',
        data: {idplanbase: idplanbase},
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
				get_lista_planbase();
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