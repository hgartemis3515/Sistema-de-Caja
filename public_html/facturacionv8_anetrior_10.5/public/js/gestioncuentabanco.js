$(function() {
		$(".btn_cuentabanco").click(save);
		$('#moneda').select2({
			minimumResultsForSearch: -1,
			placeholder: "Selecciona un tipo de moneda"
		});  
		$('#tipo_cuenta').select2({
			minimumResultsForSearch: -1
		}); 
		
    get_lista_cuentabanco(); 
});

function save(){
    var datastring = $("#frm_cuentabanco").serializeArray();
    $.ajax({
        url : '/facturacionv8/gestioncuentadebanco/save',
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
							get_lista_cuentabanco();
							$("#frm_cuentabanco")[0].reset();
							$("#moneda").select2("val", 0);
							$("#tipo_cuenta").select2("val", 0);
							$("#idcuentabanco").val('');
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
function get_lista_cuentabanco(){
		var light = $("#content_lista_cuentabanco");

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
        url : '/facturacionv8/gestioncuentadebanco/get_lista_cuenta',
        method :  'POST',
        dataType : "json"
    }).then(function(data){
			if(data.respuesta == 'ok') {
				$('#tbl_lista_cuentabanco').DataTable({
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

function edit_cuentabanco(idcuentabanco){
	var light = $("#content_lista_cuentabanco");

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
        url : '/facturacionv8/gestioncuentadebanco/get_data_cuenta',
        method :  'POST',
        data: {idcuentabanco: idcuentabanco},
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			$("#idcuentabanco").val(data.cuenta.id_cuentabanco);
			$("#moneda").val(data.cuenta.id_codigomoneda).trigger("change").trigger("select2:select");
			$("#tipo_cuenta").val(data.cuenta.tipo_cuenta).trigger("change").trigger("select2:select");
			$("#entidad_financiera").val(data.cuenta.id_entidadfinanciera).trigger("change").trigger("select2:select");
			$("#nombre_banco").val(data.cuenta.nombre_banco);
			$("#nombre_titular").val(data.cuenta.nombre_titular);
			$("#num_cuenta").val(data.cuenta.nro_cuenta);
			$("#cci").val(data.cuenta.cci);
			$("#descripcion").val(data.cuenta.descripcion);
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

function eliminar_cuentabanco(idcuentabanco){
	var light = $("#content_lista_cuentabanco");

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
        url : '/facturacionv8/gestioncuentadebanco/eliminar_cuenta',
        method :  'POST',
        data: {idcuentabanco: idcuentabanco},
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
				get_lista_cuentabanco();
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