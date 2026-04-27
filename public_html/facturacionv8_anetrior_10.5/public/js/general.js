$(function() {
    inicializar_menu(); 
});

function inicializar_menu() {
    $(".menu_contiene_items").each(function(key, element){
        let class_li = $(element).attr('class');
        if(typeof class_li !== 'undefined') {
            class_li = class_li.replace(/active/gi, '');
            $(element).attr('class', class_li);
        }
    });

    $(".item_individual").each(function(key, element){
        let class_li = $(element).attr('class');
        if(typeof class_li !== 'undefined') {
            class_li = class_li.replace(/active/gi, '');
            $(element).attr('class', class_li);
        }
    });

    $(".submenu_item").each(function(key, element){
        let class_li = $(element).attr('class');
        if(typeof class_li !== 'undefined') {
            class_li = class_li.replace(/active/gi, '');
            $(element).attr('class', class_li);
        }
    });

    var pathname = window.location.pathname.substring(1, window.location.pathname.length);
    let primer_caracter = pathname.charAt(0);
    let ultimo_caracter = pathname.charAt(pathname.length - 1);
    if(primer_caracter == '/') {
        pathname = pathname.substr(1);
    }
    if(ultimo_caracter == '/') {
        pathname = pathname.slice(0, -1);
    }
    
    var array_pathname = pathname.split('/');
    let n = 1;
    $("#sidebar_principal a").each(function(){
        let url = $(this).attr('href');
        primer_caracter = url.charAt(0);
        ultimo_caracter = url.charAt(url.length - 1);

        if(primer_caracter == '/') {
            url = url.substr(1);
        }

        if(ultimo_caracter == '/') {
            url = url.slice(0, -1);
        }

        if(url == pathname) {
            let class_contenedor = $(this).parent().attr('class');
            if(typeof class_contenedor !== 'undefined') {
                if(class_contenedor.indexOf('item_individual') !== -1) {
                    $(this).parent().attr('class', class_contenedor + ' active');
                    return false;
                } else if (class_contenedor.indexOf('submenu_item') !== -1) {
                    $(this).parent().attr('class', class_contenedor + ' active');
                    $(this).parent().parent().show();
                    $(this).parent().parent().parent().attr('class', $(this).parent().parent().parent().attr('class') + ' active');
                }
            }
        }
    });
}

function get_data_sucursal(idsucursal, input_serie, tipo_doc_electronico) {
    $.ajax({
        url : '/facturacionv8/herramientas/get_data_sucursal',
        method :  'POST',
        data: {idsucursal: idsucursal},
        dataType : "json"
    }).then(function(data){
        if(data.respuesta == 'ok') {
            if(tipo_doc_electronico == 'factura') {
                input_serie.val(data.sucursal.factura_serie);
            } else if(tipo_doc_electronico == 'boleta') {
                input_serie.val(data.sucursal.boleta_serie);
            } else if(tipo_doc_electronico == 'notacredito_factura') {
                input_serie.val(data.sucursal.notacredito_factura_serie);
            } else if(tipo_doc_electronico == 'notadebito_factura') {
                input_serie.val(data.sucursal.notadebito_factura_serie);
            } else if(tipo_doc_electronico == 'notacredito_boleta') {
                input_serie.val(data.sucursal.notacredito_boleta_serie);
            } else if(tipo_doc_electronico == 'notadebito_boleta') {
                input_serie.val(data.sucursal.notadebito_boleta_serie);
            } else {

            }

            if(data.total_sucurles > 1) {
                $(".info_sucursal_seleccionada").html('<i class="icon-info3 text-size-mini position-left"></i> ' + 'Almacén de Sucursal: ' + data.sucursal.nombre + ' ('+ data.sucursal.idsucursal +')');
            } else {
                $(".info_sucursal_seleccionada").hide();
            }
			
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

function consultar_numero_doc(input_idusuario, num_doc, input_nombre, input_direccion, input_ubigeo, tipo_doc, input_email) {
    $("#icon_search_document").hide();
    $("#icon_searching_document").show();
    $(".search_document").prop('disabled', true);
    input_idusuario.val('');
    $.ajax({
        url : '/facturacionv8/herramientas/get_data_cliente',
        data: {tipo_doc: tipo_doc, num_doc: num_doc},
        method :  'POST',
        dataType : "json"
    }).then(function(data){
        if(data.respuesta == 'ok') {
            if(tipo_doc == 1) { //DNI
                if(data.encontrado == true) {
                    if(data.api == true) {
                        input_nombre.val(data.data.nombre);
                    } else {
                        input_idusuario.val(data.data.idcliente);
                        input_nombre.val(data.data.razon_social);
                        input_direccion.val(data.data.direccion_fiscal);
                        input_email.val(data.data.email);
                        input_ubigeo.val(data.data.id_cod_ubigeo).trigger("change").trigger("select2:select");
                    }
                }
            } else if(tipo_doc == 6) { //RUC
                if(data.encontrado == true) {
                    if(data.api == true) {
                        input_nombre.val(data.data.razon_social);
                        input_direccion.val(data.data.direccion);
                        if(typeof data.data.codigo_ubigeo !== 'undefined' && data.data.codigo_ubigeo != '') {
                            input_ubigeo.val(data.data.codigo_ubigeo).trigger("change").trigger("select2:select");
                        }
                    } else {
                        input_idusuario.val(data.data.idcliente);
                        input_nombre.val(data.data.razon_social);
                        input_direccion.val(data.data.direccion_fiscal);
                        input_email.val(data.data.email);
                        input_ubigeo.val(data.data.id_cod_ubigeo).trigger("change").trigger("select2:select");
                    }
                }
            } else {
                if(data.encontrado == true) {
                    input_nombre.val(data.data.razon_social);
                    input_direccion.val(data.data.direccion_fiscal);
                    input_ubigeo.val(data.data.id_cod_ubigeo).trigger("change").trigger("select2:select");
                }
            }

            $("#icon_search_document").show();
            $("#icon_searching_document").hide();
            $(".search_document").prop('disabled', false);
        } else {
            swal({
                title: 'ERROR',
                text: 'No se logró encontrar el usuario, ingrese los datos completos del cliente!',
                html: true,
                type: "error",
                confirmButtonText: "Ok",
                confirmButtonColor: "#2196F3"
            }, function(){
                $("#icon_search_document").show();
                $("#icon_searching_document").hide();
                $(".search_document").prop('disabled', false);
            });
        }
    }, function(reason){
        swal({
            title: 'ERROR',
            text: 'Error al conectarse a la SUNAT, recarga la página e inténtalo nuevamente!',
            html: true,
            type: "error",
            confirmButtonText: "Ok",
            confirmButtonColor: "#2196F3"
        }, function(){
            $("#icon_search_document").show();
            $("#icon_searching_document").hide();
            $(".search_document").prop('disabled', false);
        });
    });
}

function generar_codigo(input, length, icon_button_action = '') {
    let class_icon_button_action = '';
    if(icon_button_action.length){
        icon_button_action.parent().prop('disabled', true);
        class_icon_button_action = icon_button_action.attr('class');
        console.log(class_icon_button_action);
        icon_button_action.attr('class', 'icon-spinner2 spinner');
    }

	$.ajax({
        url : '/facturacionv8/herramientas/generartoken',
        method :  'POST',
        data: {length: length},
        dataType : "json"
    }).then(function(data){
        if(data.respuesta == 'ok') {
            input.val(data.token);
            if(icon_button_action.length){
                icon_button_action.attr('class', class_icon_button_action);
                icon_button_action.parent().prop('disabled', false);
            }
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

function get_lista_vendedores(select) {
    $.ajax({
        url : '/facturacionv8/herramientas/get_lista_usuarios',
        method :  'POST',
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			$.each(data.lista, function(key, item) {
				select.append('<option value="' + item.idusuario + '">' + item.nombre_completo + '</option>');
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

function get_lista_sucursales(select, seleccionar = 'no') {
	$.ajax({
        url : '/facturacionv8/herramientas/get_lista_sucursales',
        method :  'POST',
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			$.each(data.lista, function(key, item) {
				select.append('<option value="' + item.idsucursal + '">' + item.nombre + ' (ID: ' + item.idsucursal +')' + '</option>');
            });
            if(seleccionar == 'no' || data.idsucursal_usuario == null) {
                select.trigger("change").trigger("select2:select");
            } else {
                select.val(data.idsucursal_usuario).trigger("change").trigger("select2:select");
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

function round_math(numero, decimales = 0) {
    const flotante = parseFloat(numero);
    
    // Validar si el valor es un número
    if (isNaN(flotante)) {
        return 0; // Retornar 0 o cualquier valor por defecto
    }
    
    const factor = Math.pow(10, decimales);
    return Math.round(flotante * factor) / factor;
}

function get_lista_categorias(select, idcategoria = '') {
    select.empty();
    $.ajax({
        url : '/facturacionv8/producto/get_lista_categorias',
        method :  'POST',
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			$.each(data.lista, function(key, item) {
				select.append('<option value="' + item.idcategoria + '">' + item.nombre + '</option>');
            });
            if(idcategoria == '') {
                select.trigger("change").trigger("select2:select");
            } else {
                select.val(idcategoria).trigger("change").trigger("select2:select");
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

function calcular_edad(FechaNacimiento) {

    var fechaNace = new Date(FechaNacimiento);
    var fechaActual = new Date()

    var mes = fechaActual.getMonth();
    var dia = fechaActual.getDate();
    var año = fechaActual.getFullYear();

    fechaActual.setDate(dia);
    fechaActual.setMonth(mes);
    fechaActual.setFullYear(año);

    edad = Math.floor(((fechaActual - fechaNace) / (1000 * 60 * 60 * 24) / 365));
   
    return edad;
}

function is_mobil() {
    if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
        return true;
    } else{
        return false;
    }
}

function get_uid() {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = Math.random()*16|0, v = c == 'x' ? r : (r&0x3|0x8);
        return v.toString(16);
    });
}