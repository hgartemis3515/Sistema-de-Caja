function get_tipocambio_by_date(input, date = '') {
	$.ajax({
        url : '/facturacionv8/apisunat/get_tipo_cambio_by_date',
		method :  'POST',
		data: {fecha: date},
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			input.val(data.venta);
		} else {
			swal({
				title: data.titulo,
				text: data.mensaje,
				html: true,
				type: "error",
				confirmButtonText: "Ok",
				confirmButtonColor: "#2196F3"
			}, function(){
				
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
			
		});
    });
}

function get_tipocambio_by_date_venta(input, date = '') {
	$.ajax({
        url : '/facturacionv8/apisunat/get_tipo_cambio_by_date',
		method :  'POST',
		data: {fecha: date},
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			input.val(data.venta);
		} else {
			swal({
				title: data.titulo,
				text: data.mensaje,
				html: true,
				type: "error",
				confirmButtonText: "Ok",
				confirmButtonColor: "#2196F3"
			}, function(){
				
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
			
		});
    });
}

function get_tiponotadebito(select) {
	$.ajax({
        url : '/facturacionv8/apisunat/get_sunat_tiponotadebito',
        method :  'POST',
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			$.each(data.lista, function(key, item) {
				select.append('<option value="' + item.id_tiponotadebito + '">' + item.descripcion + '</option>');
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
			
		});
    });
}

function get_tiponotacredito(select) {
	$.ajax({
        url : '/facturacionv8/apisunat/get_sunat_tiponotacredito',
        method :  'POST',
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			$.each(data.lista, function(key, item) {
				select.append('<option value="' + item.id_tiponotacredito + '">' + item.descripcion + '</option>');
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
			
		});
    });
}

function get_tipo_cambio(input_text) {
    $.ajax({
        url : '/facturacionv8/apisunat/get_tipo_cambio',
        method :  'POST',
        dataType : "json"
    }).then(function(data){
        if(data.respuesta == 'ok') {
            input_text.val(data.venta); 
        } else {
            swal({   
                title:'Error',   
                text: data.mensaje,
                html: true,
                type: "error", 
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Ok",
            }, function() {
				
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
			
		});
    });
}

function get_lista_tipoafectacionigv(select) { 
    $.ajax({
        url : '/facturacionv8/apisunat/get_lista_tipoafectacionigv',
        method :  'POST',
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			$.each(data.lista, function(key, item) {
				select.append('<option value="' + item.id_tipoafectacionigv + '">' + item.descripcion + '</option>');
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
			
		});
    });
}

function get_lista_codigodetraccion(select) {
	$.ajax({
        url : '/facturacionv8/apisunat/get_codigodetraccion',
        method :  'POST',
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			$.each(data.lista, function(key, item) {
				select.append('<option value="' + item.id_cod_detraccion + '"> ' + item.id_cod_detraccion + ' - ' + item.descripcion + '</option>');
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
			
		});
    });
}

function get_lista_tipo_doc_identidad(select, seleccion = -1) {
	$.ajax({
        url : '/facturacionv8/apisunat/get_lista_tipo_doc_identidad',
        method :  'POST',
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			$.each(data.lista, function(key, item) {
				select.append('<option value="' + item.id_tipodocidentidad + '">' + item.nombre + '</option>');
			});
			if(seleccion >= 0) {
				select.val(seleccion).trigger("change").trigger("select2:select");
			}
		} else {
			swal({
				title: 'Error',
				text: data.mensaje,
				html: true,
				type: "error",
				confirmButtonText: "Ok",
				confirmButtonColor: "#2196F3"
			}, function(){
				
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
			
		});
    });
}

function get_lista_unidades_medida(select, seleccion = 7) {
    $.ajax({
        url : '/facturacionv8/apisunat/get_unidadesmedida',
        method :  'POST',
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			$.each(data.lista, function(key, item) {
				select.append('<option value="' + item.idunidad + '">' + item.nombre + '</option>');
			});

			if(seleccion > 0) {
				select.val(seleccion).trigger("change").trigger("select2:select");
			}
		} else {
			swal({
				title: 'Error',
				text: data.mensaje,
				html: true,
				type: "error",
				confirmButtonText: "Ok",
				confirmButtonColor: "#2196F3"
			}, function(){
				
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
			
		});
    });
}

function inicializar_buscador_ubigeos(select) {
    select.select2({
        language: "es",
        minimumResultsForSearch: Infinity,
        ajax: {
          type: "post",
          url: "/facturacionv8/herramientas/get_sugerencias_ubigeos",
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
              q: params.term, // search term
              page: params.page
            };
          },
          processResults: function (data, params) {
            // parse the results into the format expected by Select2
            // since we are using custom formatting functions we do not need to
            // alter the remote JSON data, except to indicate that infinite
            // scrolling can be used
            params.page = params.page || 1;
  
            return {
              results: data.items,
              pagination: {
                more: (params.page * 30) < data.total_count
              }
            };
          },
          cache: true
        },
        escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
            minimumInputLength: 1
    });
}

function get_lista_ubigeos(select, seleccion = -1) {
	$.ajax({
        url : '/facturacionv8/apisunat/get_lista_ubigeos',
        method :  'POST',
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			select.empty();
			select.append('<option value="">Selecciona un Código de Ubigeo</option>');
			$.each(data.lista, function(key, item) {
				select.append('<option value="' + item.codigo_ubigeo + '">' + item.departamento + ' - ' + item.provincia + ' - ' + item.distrito + '</option>');
			});
			if(seleccion >= 0) {
				select.val(seleccion).trigger("change").trigger("select2:select");
			}
		} else {
			swal({
				title: 'Error',
				text: data.mensaje,
				html: true,
				type: "error",
				confirmButtonText: "Ok",
				confirmButtonColor: "#2196F3"
			}, function(){
				
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
			
		});
    });
}

function get_lista_monedas(select) {
	$.ajax({
        url : '/facturacionv8/apisunat/get_lista_monedas',
        method :  'POST',
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			$.each(data.lista, function(key, item) {
                if(item.nombre == 'Soles') {
                    select.append('<option data-simbolo="' + item.simbolo + '" value="' + item.id_codigomoneda + '" selected>' + item.nombre + ' (' + item.simbolo + ')</option>');
                } else {
                    select.append('<option data-simbolo="' + item.simbolo + '" value="' + item.id_codigomoneda + '">' + item.nombre + ' (' + item.simbolo + ')</option>');
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
			
		});
    });
}