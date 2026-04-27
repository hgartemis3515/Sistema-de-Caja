$(function(){
	$(".select_minimizado").select2({
        minimumResultsForSearch: -1
	});

	$('.btn_logo461x95').click(function() {
        ratio = 461 / 95;
        ancho_corte_width = 461;
        alto_corte_height = 95;
        imagetipo = 'img_logo_461';
        $('.fileinput-remove').trigger('click');
		$('.previewrecorteimg').html('');
		$('#dimensiones_imagen').html('461x95');
        $('#vm_cargar_imagen').modal('show');
	});

	$('.btn_logo291x60').click(function() {
        ratio = 291 / 60;
        ancho_corte_width = 291;
        alto_corte_height = 60;
		imagetipo = 'img_logo_291';
		$('#dimensiones_imagen').html('291x60');
        $('.fileinput-remove').trigger('click');
        $('.previewrecorteimg').html('');
        $('#vm_cargar_imagen').modal('show');
	});

	$('.btn_logo56x56').click(function() {
        ratio = 1 / 1;
        ancho_corte_width = 56;
        alto_corte_height = 56;
		imagetipo = 'img_logo_56';
		$('#dimensiones_imagen').html('56x56');
        $('.fileinput-remove').trigger('click');
        $('.previewrecorteimg').html('');
        $('#vm_cargar_imagen').modal('show');
	});

	inicializacion_plugin_para_recortar_imagenes($('.previewrecorteimg'), '.kv-file-content .file-preview-image', $('.file-input'), $("#btn_guardarimagen"));

	$("#vm_cargar_imagen").on("hidden.bs.modal", function () {
        $('.modal:visible').css("overflow-y","auto");
    });

    $("#select_idcategoria").on('change', function() {
		let idcategoria = $("#select_idcategoria").val();
		get_lista_plantillas(idcategoria);
    });
    
    $("#btn_guardar_dominio").click(guardar_data);
    get_paginas();
});

function abrir_lista_plantillas() {
    $("#vm01_seleccionarplantilla").modal('show');
    get_lista_plantillas();
}

function asignar_home(iduserpage) {
    swal({   
        title: '¿Deseas Cambiar la Home?',   
        text: '¿Realmente Deseas Cambiar la Home por la Página Seleccionada?',
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
            $.ajax({
                url : '/facturacionv8/miwebsite/cambiar_home',
                data: {iduserpage: iduserpage},
                method :  'POST',
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
                        get_paginas();
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
    });
}

function eliminar_pagina(iduserpage) {
    swal({   
        title: '¿Deseas Eliminar la Página?',   
        text: 'Este cambio no se puede deshacer, ¿realmente deseas eliminar la página?',
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
            $.ajax({
                url : '/facturacionv8/miwebsite/eliminar_pagina',
                data: {iduserpage: iduserpage},
                method :  'POST',
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
                        get_paginas();
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
    });
}

function seleccionar_plantilla(id_plantilla) {
    swal({   
        title: '¿Realmente Deseas Seleccionar Esta Plantilla?',   
        text: 'Creamos para ti, una página tomando como base la plantilla seleccionada... ¿Deseas Continuar?',
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
            $.ajax({
                url : '/facturacionv8/miwebsite/crear_nueva_pagina',
                data: {id_plantilla: id_plantilla},
                method :  'POST',
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
                        $("#vm01_seleccionarplantilla").modal('hide');
                        get_paginas();
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
    });
}

function get_paginas() {
    var light = $('#content_mispaginas');
    $(light).block({
    message: '<div class="loading"> \
                <div class="loading-bar"></div> \
                <div class="loading-bar"></div> \
                <div class="loading-bar"></div> \
                <div class="loading-bar"></div> \
            </div> <p> <br />recuperando plantillas ...</p>',

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
		url : '/facturacionv8/miwebsite/get_paginas',
        method :  'POST',
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
            $("#content_mispaginas").html('');
            
            /*
			data.paginas.forEach(function(pagina, index) {
                var item_pagina = '\
                <div class="panel panel-flat img-border-content">\
                    <div class="panel-body text-center">\
                        <div class="img-preview-pag">';
                            if(pagina.is_home != 'si') {
                                item_pagina = item_pagina + '<img class="w-100 h-100" src="https://api.microlink.io?url=https://facturalaya.com/facturacionv8/p/'+ pagina.id_contribuyente +'/' + pagina.idpagina + '&screenshot=true&meta=false&embed=screenshot.url" alt="">';
                            } else {
                                item_pagina = item_pagina + '<img class="w-100 h-100" src="https://api.apiflash.com/v1/urltoimage?access_key=9347e0b5c43249918caa0c1a8e3dd165&url=https://facturalaya.com/facturacionv8/p/'+ pagina.id_contribuyente +'/' + pagina.idpagina + '" alt="">';
                            }
                            item_pagina = item_pagina + '\
                            <div class="overlay">\
                                <div class="text"><a href="javascript:void(0);" onclick="ver_enlace_pagina(' + pagina.idpagina + ')" rel="noopener noreferrer"><i class="fa fa-link fa-1x" aria-hidden="true"></i></a></div>\
                            </div>\
                        </div>\
                        <div class="buttons mt-2">\
                            <div class="btn-group">\
                                <a href="/facturacionv8/editorweb/index/' + pagina.idpagina + '" target="_blank" class="btn bg-indigo legitRipple"><i class="icon-pencil3"></i></a>\
                                    <a href="javascript:void(0);" onclick="eliminar_pagina(' + pagina.idpagina + ')" class="btn bg-indigo legitRipple"><i class="icon-cross2"></i></a>\
                                    <a href="/facturacionv8/p/'+ pagina.id_contribuyente +'/' + pagina.idpagina + '" target="_blank" class="btn bg-indigo  legitRipple"><i class="icon-eye"></i></a>\
                                    <a href="javascript:void(0);" onclick="modificar_codigo(' + pagina.idpagina + ')" class="btn bg-indigo legitRipple"><i class="icon-cross2"></i></a>';
                                    if(pagina.is_home != 'si') {
                                        item_pagina = item_pagina + '<a href="javascript:void(0);" onclick="asignar_home(' + pagina.idpagina + ')" class="btn bg-indigo legitRipple border-radius-right"><i class="icon-home2"></i></a>';
                                    }
                                    item_pagina = item_pagina + '\
                                    <button style="display:none;" type="button" class="btn bg-indigo dropdown-toggle legitRipple" data-toggle="dropdown" aria-expanded="false"><span class="caret"></span></button>\
                                    <ul class="dropdown-menu dropdown-menu-right">\
                                        <li><a href="#"><i class="icon-menu7"></i> Action</a></li>\
                                        <li><a href="#"><i class="icon-screen-full"></i> Another action</a></li>\
                                        <li><a href="#"><i class="icon-mail5"></i> One more action</a></li>\
                                        <li class="divider"></li>\
                                        <li><a href="#"><i class="icon-gear"></i> Separated line</a></li>\
                                    </ul>\
                            </div>\
                        </div>\
                    </div>\
                </div>\
                ';

                if(pagina.is_home != 'si') {
                    $("#content_mispaginas").append('<div class="col-lg-4 col-md-6 col-sm-6 ">' + item_pagina + '</div>');
                } else {
                    $("#content_pagina_home").html(item_pagina);
                }
            });
            */
            
           data.lista_paginas.pagination.data.forEach(function(pagina, index) {
            var item_pagina = '\
            <div class="panel panel-flat img-border-content">\
                <div class="panel-body text-center">\
                    <div class="img-preview-pag">';
                        item_pagina = item_pagina + '<img class="w-100 h-100" src="https://facturalaya.com/website/storage/projects/1/' + pagina.uuid + '/thumbnail.png" alt="">';
                        item_pagina = item_pagina + '\
                        <div class="overlay">\
                            <div class="text"><a href="javascript:void(0);" onclick="ver_enlace_pagina(1)" rel="noopener noreferrer"><i class="fa fa-link fa-1x" aria-hidden="true"></i></a></div>\
                        </div>\
                    </div>\
                    <div class="buttons mt-2">\
                        <div class="btn-group">\
                            <a href="/website/builder/' + pagina.id + '" target="_blank" class="btn bg-indigo legitRipple"><i class="icon-pencil3"></i></a>\
                            <a href="/website/sites/'+ pagina.slug + '" target="_blank" class="btn bg-indigo legitRipple border-radius-right"><i class="icon-eye"></i></a>';
                            
                            item_pagina = item_pagina + '\
                        </div>\
                    </div>\
                </div>\
            </div>\
            ';

            $("#content_mispaginas").append('<div class="col-lg-4 col-md-6 col-sm-6 ">' + item_pagina + '</div>');
        });

            $("#content_mispaginas").append('\
            <div class="col-lg-4 col-md-6 col-sm-6">\
                <div class="panel panel-flat img-border-content">\
                    <div class="panel-body text-center">\
                        <div class="img_content thumb" onclick="openInNewTab(' + "'" + "/website/dashboard" + "'" +')">\
                            <div class="content-img">\
                                <span class="text-new"><i class="fa fa-gear"></i>Gestionar Páginas</span>\
                            </div>\
                        </div>\
                    </div>\
                </div>\
            </div>\
            ');

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

function openInNewTab(url) {
    var win = window.open(url, '_blank');
    win.focus();
  }

function guardar_data() {
    var light = $('#content_panel_miwebsite');
    $(light).block({
    message: '<div class="loading"> \
                <div class="loading-bar"></div> \
                <div class="loading-bar"></div> \
                <div class="loading-bar"></div> \
                <div class="loading-bar"></div> \
            </div> <p> <br />Guardando Datos ...</p>',

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

    var dominio_personalizado = $("#dominio_personalizado").val();
    var txt_logo_461 = $("#txt_logo_461").val();
    var txt_logo_291 = $("#txt_logo_291").val();
    var txt_logo_56 = $("#txt_logo_56").val();

	$.ajax({
		url : '/facturacionv8/miwebsite/guardar_data',
		data: {dominio_personalizado: dominio_personalizado, txt_logo_461: txt_logo_461, txt_logo_291: txt_logo_291, txt_logo_56: txt_logo_56},
        method :  'POST',
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
				$(light).unblock();
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

function inicializar_subida_imagen() {
	$('#btn_subirimagen').click(function() {
        ratio = 590 / 300;
		ancho_corte_width = 590;
        alto_corte_height = 300;
        imagetipo = 'img_preview_plantilla';
        $('.fileinput-remove').trigger('click');
        $('.previewrecorteimg').html('');
        $('#vm_cargar_imagen').modal('show');
	});
	
	inicializacion_plugin_para_recortar_imagenes($('.previewrecorteimg'), '.kv-file-content .file-preview-image', $('.file-input'), $("#btn_guardarimagen"));
    
    $("#vm_cargar_imagen").on("hidden.bs.modal", function () {
        $('.modal:visible').css("overflow-y","auto");
	});
}

function get_lista_plantillas(idcategoria = 0) {
    var light = $('#vm01_body_seleccionarplantilla');
    $(light).block({
    message: '<div class="loading"> \
                <div class="loading-bar"></div> \
                <div class="loading-bar"></div> \
                <div class="loading-bar"></div> \
                <div class="loading-bar"></div> \
            </div> <p> <br />Un momento, estamos recuperando las plantillas ...</p>',

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
		url : '/facturacionv8/miwebsite/get_templates',
		data: {idcategoria: idcategoria},
        method :  'POST',
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
            $("#content_plantillas").html('');
			data.lista.forEach(function(plantilla, index) {
                var item_plantilla = '\
                <div class="col-lg-4">\
                    <div class="panel panel-flat">\
                        <div class="panel-body text-center p-1">\
                            <div class="img-preview-pag">\
                                <img class="w-100 h-100" src="'+ plantilla.imagen +'" alt="">\
                            </div>\
                            <div class="buttons mt-2">\
                                <a href="/facturacionv8/gestiondeplantillas/preview_template/'+ plantilla.id +'" target="_blank" class="btn btn-primary btn-min mr-2"><i class="icon-eye"></i></a>\
                                <a href="javascript:void(0);" onclick="seleccionar_plantilla('+ plantilla.id +')" class="btn btn-success btn-min text-uppercase font-weight-bold"><i class="icon-checkmark4 mr-2"></i>Elegir</a>\
                            </div>\
                        </div>\
                    </div>\
                </div>\
                ';
                $("#content_plantillas").append(item_plantilla);
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