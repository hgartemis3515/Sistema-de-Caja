$(function(){
	$('.js-example-basic-single').select2();
	//$('.btn_savecompany').click(save_company);
    $('.btn_saveuser').click(save_user);
    $(".search_document").click(consultar_numero_doc);

	$('.btn_show_pass_login').on('click', function(e) {
		var current = $(this).attr('action');
		if (current == 'hide') {
			$('#password_login').attr('type', 'text');
			$('.icon-eye-blocked').attr('class', 'icon-eye');
			$('.btn_show_pass_login').attr('action','show');
		}
		if (current == 'show') {
			$('#password_login').attr('type', 'password');
			$('.icon-eye').attr('class', 'icon-eye-blocked');
			$('.btn_show_pass_login').attr('action','hide');
		} 
    });
    
    $('.btn_show_pass_sol').on('click', function(e) {
		var current = $(this).attr('action');
		if (current == 'hide') {
			$('#password_sol').attr('type', 'text');
			$('.icon-eye-blocked').attr('class', 'icon-eye');
			$('.btn_show_pass_sol').attr('action','show');
		}
		if (current == 'show') {
			$('#password_sol').attr('type', 'password');
			$('.icon-eye').attr('class', 'icon-eye-blocked');
			$('.btn_show_pass_sol').attr('action','hide');
		}
    });

	if($("#informar_condpago_sunat").bootstrapSwitch('state') == false) {
		$("#content_modificar_cuotas_sunat").hide("slide");
	} else {
		$("#content_modificar_cuotas_sunat").show("slide");
	}

	$('#informar_condpago_sunat').on('switchChange.bootstrapSwitch', function(event, state) {
        if(state) {
			$("#content_modificar_cuotas_sunat").show("slide");
		} else {
			$("#content_modificar_cuotas_sunat").hide("slide");
		}
    });

	$('.btn_show_pass_sol_busq_cpe').on('click', function(e) {
		var current = $(this).attr('action');
		if (current == 'hide') {
			$('#pass_sol_busq_cpe').attr('type', 'text');
			$('#icon_eye_blocked_pass_sol_busq_cpe').attr('class', 'icon-eye');
			$('.btn_show_pass_sol_busq_cpe').attr('action','show');
		}
		if (current == 'show') {
			$('#pass_sol_busq_cpe').attr('type', 'password');
			$('#icon_eye_blocked_pass_sol_busq_cpe').attr('class', 'icon-eye-blocked');
			$('.btn_show_pass_sol_busq_cpe').attr('action','hide');
		}
    });
    
    $('.btn_show_pass_certificado').on('click', function(e) {
		var current = $(this).attr('action');
		if (current == 'hide') {
			$('#password_certificado').attr('type', 'text');
			$('.icon-eye-blocked').attr('class', 'icon-eye');
			$('.btn_show_pass_certificado').attr('action','show');
		}
		if (current == 'show') {
			$('#password_certificado').attr('type', 'password');
			$('.icon-eye').attr('class', 'icon-eye-blocked');
			$('.btn_show_pass_certificado').attr('action','hide');
		}
	});

	// Default file input style
	$(".file-styled").uniform({
		fileButtonClass: 'action btn btn-primary'
	});

	$(".switch").bootstrapSwitch();
	$('#opcion_tipo_proceso').on('switchChange.bootstrapSwitch', function(event, state) {
		valida_switch_tipo_proceso_sunat(state);
	});
	var state = $("#opcion_tipo_proceso").bootstrapSwitch('state');
	valida_switch_tipo_proceso_sunat(state);
	

    $('.select2').select2();
    $(".select_opcion").select2({ minimumResultsForSearch: -1 });
	inicializar_controles();
	inicializar_subida_imagen();

	var id_contribuyente = parseInt($("#id_contribuyente").val()) + 0;
    if(id_contribuyente > 0) {
        get_data_contribuyente(id_contribuyente);
	}
	$(".btn_savecompany").click(guardar_contribuyente);
        
    $(".btn_generar_token").click(function(){
        generar_token_contribuyente($("#token_contribuyente"), 37, $(".token_contribuyente > i"));
    });

});

function valida_switch_tipo_proceso_sunat(state) {
	var tipo_certificado = $("#input_tipo_certificado").val();
	var tipo_empresa_sunat = $("#input_tipo_empresa_sunat").val();
	if (!state) {
		$(".only_produccion").show("slide");
		if(tipo_certificado == 'propio' || tipo_certificado == '') {
			$(".only_certificado_propio").show("slide");
		} else {
			$(".only_certificado_propio").hide("slide");
		}

		if(tipo_empresa_sunat == 'prico') {
			$(".only_prico").show("slide");
		} else {
			$(".only_prico").hide("slide");
		}
	} else {
		$(".only_produccion").hide("slide");
		$(".only_prico").hide("slide");
	}
}

function generar_token_contribuyente(input, length = 37, icon_button_action = '') {
    let class_icon_button_action = '';
    if(icon_button_action.length){
        icon_button_action.parent().prop('disabled', true);
        class_icon_button_action = icon_button_action.attr('class');
        icon_button_action.attr('class', 'icon-spinner2 spinner');
    }

	$.ajax({
        url : '/facturacionv8/configcompany/generar_token_contribuyente',
		method :  'POST',
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			
			swal({   
				title: 'Necesitamos de tu Confirmación',   
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
					//id: f8sjHDGDSAjdh
					$.ajax({
						url : '/facturacionv8/configcompany/generar_token_contribuyente',
						method :  'POST',
						data: {confirmacion: 'si'},
						dataType : "json"
					}).then(
						function(data) {
							if(data.respuesta == 'ok') {
								swal({   
									title: data.titulo,   
									text: data.mensaje,
									html: true,
									type: "success",   
									confirmButtonColor: "#563d7c",
									confirmButtonText: "Ok"
								}, function(){  
									input.val(data.token);
                                    if(icon_button_action.length){
                                        icon_button_action.attr('class', class_icon_button_action);
                                        icon_button_action.parent().prop('disabled', false);
                                    }
                                });
                                
							} else {
								swal({   
									title: data.titulo,   
									text: data.mensaje,
									html: true,
									type: "error",   
									confirmButtonColor: "#563d7c",   
									confirmButtonText: "OK"
								});
							}
						},
						function(reason){
							swal({   
								title: 'ERROR',   
								text: reason,
								html: true,
								type: "error",   
								confirmButtonColor: "#563d7c",   
								confirmButtonText: "OK"
							});
						}
					);
				} else {
					swal({   
						title: 'Está Bien!',   
						text: 'No hicimos ningún cambio!',
						html: true,
						type: "error",   
						confirmButtonColor: "#563d7c",   
						confirmButtonText: "OK"
					});
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

function guardar_contribuyente(){
	var light = $("#content_panel_company");

    $(light).block({
		message: '<div class="loading"> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
					</div> <p> <br />Guardando ...</p>',
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

    var datastring = $("#frm_configcompany").serializeArray();
    var formData = new FormData();

	var tipo_empresa_sunat = $("#tipo_empresa_sunat").val();
	
    $.each(datastring, function( index, control ) {
        formData.append(control.name, control.value);
    });

	if(tipo_empresa_sunat != 'prico') {
		formData.append("archivo", $("#file_certificado")[0].files[0]);
		formData.append('usuario_sol', $("#usuario_sol").val());
		formData.append('password_sol', $("#password_sol").val());
		formData.append('password_certificado', $("#password_certificado").val());

		formData.append('sunat_u_sol_principal', $("#sunat_u_sol_principal").val());
		formData.append('sunat_p_sol_principal', $("#sunat_p_sol_principal").val());
		formData.append('sunat_client_id', $("#sunat_client_id").val());
		formData.append('sunat_client_secret', $("#sunat_client_secret").val());
	} else {
		formData.append('nombre_ose', $("#nombre_ose").val());
		formData.append('u_prueba_ose', $("#u_prueba_ose").val());
		formData.append('c_prueba_ose', $("#c_prueba_ose").val());
		formData.append('url_prueba_ose', $("#url_prueba_ose").val());

		formData.append('u_produccion_ose', $("#u_produccion_ose").val());
		formData.append('c_produccion_ose', $("#c_produccion_ose").val());
		formData.append('url_produccion_ose', $("#url_produccion_ose").val());
	}

	formData.append('user_sol_busq_cpe', $("#user_sol_busq_cpe").val());
	formData.append('pass_sol_busq_cpe', $("#pass_sol_busq_cpe").val());

	formData.append('informar_condpago_sunat', ($("#informar_condpago_sunat").bootstrapSwitch('state') == false)?'no':'si');
	formData.append('permitir_cambio_cuotas', ($("#permitir_cambio_cuotas").bootstrapSwitch('state') == false)?'no':'si');

	
    $.ajax({
        url: "/facturacionv8/configcompany/guardar",
        type: "post",
        dataType: "json",
        data: formData,
        cache: false,
        contentType: false,
        processData: false
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
            });
        } else {
            swal({   
                title:'Error',   
                text: data.mensaje,
                html: true,
                type: "error", 
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Ok",
            }, function() {
				$(light).unblock();
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
			$(light).unblock();
		});
    });
}

function get_data_contribuyente(id_contribuyente){
    var light = $("#content_panel_company");

    $(light).block({
		message: '<div class="loading"> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
					</div> <p> <br />Guardando ...</p>',
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
        url : '/facturacionv8/gestiondecontribuyentes/get_data_contribuyente',
        data: {id_contribuyente: id_contribuyente},
        method :  'POST',
        dataType : "json"
    }).then(function(data){
        if(data.respuesta == 'ok') {

            $("#contribuyente_text_razonsocial").html(data.contribuyente.razon_social);
            $("#contribuyente_text_ruc").html(data.contribuyente.ruc);
            $(".contribuyente_img_logo").attr("src", data.contribuyente.img_logo);
            if(data.contribuyente.logo_350 != null && data.contribuyente.logo_350 != '') {
                $(".img_logo350x167").attr("src", data.contribuyente.logo_350);
            }
            $("#ruta_logo").val(data.contribuyente.img_logo);
            $("#id_contribuyente").val(data.contribuyente.id_contribuyente);
            $("#ruc").val(data.contribuyente.ruc);
            $("#razon_social").val(data.contribuyente.razon_social);
            $("#nombre_comercial").val(data.contribuyente.nombre_comercial);
            $("#email_empresa").val(data.contribuyente.email);
            $("#telefono").val(data.contribuyente.telefono);
            //$("#ubigeo").val(data.contribuyente.codigo_ubigeo).trigger("change").trigger("select2:select");
            get_lista_ubigeos($("#ubigeo"), data.contribuyente.codigo_ubigeo);
            $("#urbanizacion").val(data.contribuyente.urbanizacion);
            $("#direccionfiscal").val(data.contribuyente.direccion_fiscal);
            $("#usuario_sol").val(data.contribuyente.usuario_sol);
            $("#token_contribuyente").val(data.contribuyente.token);
			$("#url_facebook").val(data.contribuyente.url_facebook);
			$("#url_twitter").val(data.contribuyente.url_twitter);
			$("#url_tiktok").val(data.contribuyente.url_tiktok);
			$("#url_instagram").val(data.contribuyente.url_instagram);
			$("#url_youtube").val(data.contribuyente.url_youtube);

			$("#sunat_u_sol_principal").val(data.contribuyente.sunat_u_sol_principal);
			$("#sunat_p_sol_principal").val(data.contribuyente.sunat_p_sol_principal);
			$("#sunat_client_id").val(data.contribuyente.sunat_client_id);
			$("#sunat_client_secret").val(data.contribuyente.sunat_client_secret);

			$("#user_sol_busq_cpe").val(data.contribuyente.user_sol_busq_cpe);
						
            $("#password_sol").val('');
            $("#password_certificado").val('');
			$("#pass_sol_busq_cpe").val('');


            if(data.contribuyente.tipo_envio_sunat == 'produccion') {
                $('#opcion_tipo_proceso').bootstrapSwitch('state', false);
            } else {
                $('#opcion_tipo_proceso').bootstrapSwitch('state', true);
            }

            $(".datos_acceso_content").hide();

            $(light).unblock();

        } else {
            swal({   
                title:'Ok',   
                text: data.mensaje,
                html: true,
                type: "error", 
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Ok",
            }, function() {
				$(light).unblock();
				
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
            $(light).unblock();
        });
    });
}

function inicializar_controles() {

    // Primary
    $(".control-primary").uniform({
        radioClass: 'choice',
        wrapperClass: 'border-primary-600 text-primary-800'
    });

    // Danger
    $(".control-danger").uniform({
        radioClass: 'choice',
        wrapperClass: 'border-danger-600 text-danger-800'
    });

    // Success
    $(".control-success").uniform({
        radioClass: 'choice',
        wrapperClass: 'border-success-600 text-success-800'
    });

    // Warning
    $(".control-warning").uniform({
        radioClass: 'choice',
        wrapperClass: 'border-warning-600 text-warning-800'
    });

    // Info
    $(".control-info").uniform({
        radioClass: 'choice',
        wrapperClass: 'border-info-600 text-info-800'
    });

}

function save_company(){
	var datastring = $("#frm_configcompany").serializeArray();
	$.ajax({
		type: "POST",
		data: datastring,
		url: "/facturacionv8/configcompany/insertcompany",
		success: function(data){
		if(data.respuesta == 'ok')
		{
		}
		else
		{
			swal({
				title: "¡Error!",
				text: '"'+data.mensaje+'"',
				type: "error",
				html: true
			});
		}
		},
		dataType: "json"
	});
}

function save_user(){
	var datastring = $("#frm_loginaccess").serializeArray();
	$.ajax({
		type: "POST",
		data: datastring,
		url: "/facturacionv8/configcompany/insertuser",
		success: function(data){
		if(data.respuesta == 'ok')
		{
		}
		else
		{
			swal({
				title: "¡Error!",
				text: '"'+data.mensaje+'"',
				type: "error",
				html: true
			});
		}
		},
		dataType: "json"
	});
}

function inicializar_subida_imagen() {
	$('.btn_subirimagen').click(function() {
        ratio = 1 / 1;
        ancho_corte_width = 150;
        alto_corte_height = 150;
        imagetipo = 'img';
        $(".tamanio_texto_img").html('150x150px');
        $('.fileinput-remove').trigger('click');
        $('.previewrecorteimg').html('');
        $('#vm_cargar_imagen').modal('show');
    });
    
    $('.btn_subirimagen_350_167').click(function() {
        ratio = 350 / 167;
        ancho_corte_width = 350;
        alto_corte_height = 167;
        imagetipo = 'logo350x167';
        $(".tamanio_texto_img").html('350x167px');
        $('.fileinput-remove').trigger('click');
        $('.previewrecorteimg').html('');
        $('#vm_cargar_imagen').modal('show');
	});
	
	inicializacion_plugin_para_recortar_imagenes($('.previewrecorteimg'), '.kv-file-content .file-preview-image', $('.file-input'), $("#btn_guardarimagen"));

    //ratio plugin crooped
    ratio = 1 / 1;
	ancho_corte_width = 310;
	alto_corte_height = 310;
	imagetipo = 'img';
	
	$("#vm_cargar_imagen").on("hidden.bs.modal", function () {
        $('.modal:visible').css("overflow-y","auto");
	});
	
}

function consultar_numero_doc() {
    $("#icon_search_document").hide();
    $("#icon_searching_document").show();
	$(".search_document").prop('disabled', true);
	var num_doc = $("#ruc").val();
	
    $.ajax({
        url : '/facturacionv8/gestiondecontribuyentes/get_data_api_busquedas',
        data: {tipo_doc: 6, num_doc: num_doc},
        method :  'POST',
        dataType : "json"
    }).then(function(data){
        if(data.respuesta == 'ok') {
			$("#razon_social").val(data.data.razon_social);
			$("#nombre_comercial").val(data.data.nombre_comercial);
			$("#telefono").val(data.data.telefono);
            $("#direccionfiscal").val(data.data.direccion);
            if(data.data.codigo_ubigeo != '') {
                get_lista_ubigeos($("#ubigeo"), data.data.codigo_ubigeo);
            }
            $("#razon_social").trigger("input");
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
        $("#icon_search_document").show();
        $("#icon_searching_document").hide();
        $(".search_document").prop('disabled', false);

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

function eliminar_logo(tipo_logo) {
    var light = $("#graficos_personalizados");

    $(light).block({
		message: '<div class="loading"> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
					</div> <p> <br />Espera un Momento! ...</p>',
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
        url : '/facturacionv8/configcompany/eliminar_logo',
        method :  'POST',
        data: {tipo_logo: tipo_logo},
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			
			swal({   
				title: 'Necesitamos de tu Confirmación',   
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
					$.ajax({
						url : '/facturacionv8/configcompany/eliminar_logo',
                        method :  'POST',
                        data: {tipo_logo: tipo_logo, confirmacion: 'si'},
                        dataType : "json"
					}).then(
						function(data) {
							if(data.respuesta == 'ok') {

								swal({   
									title: data.titulo,   
									text: data.mensaje,
									html: true,
									type: "success",   
									confirmButtonColor: "#563d7c",
									confirmButtonText: "Ok"
								}, function(){  
									
								});
								$(light).unblock();
								return;
							} else {
								swal({   
									title: data.titulo,   
									text: data.mensaje,
									html: true,
									type: "error",   
									confirmButtonColor: "#563d7c",   
									confirmButtonText: "OK"
								});
								$(light).unblock();
								return;
							}
						},
						function(reason){
							swal({   
								title: 'ERROR',   
								text: reason,
								html: true,
								type: "error",   
								confirmButtonColor: "#563d7c",   
								confirmButtonText: "OK"
							});
							$(light).unblock();
							return;
						}
					);
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