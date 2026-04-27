$(function(){
    
	$('.select2').select2();

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
    
	inicializar_subida_imagen();
	$(".switch").bootstrapSwitch();
	$('#opcion_tipo_proceso').on('switchChange.bootstrapSwitch', function(event, state) {
        if (!state) {
            $(".tipo_proceso_content").show("slide");
        } else {
            $(".tipo_proceso_content").hide("slide");
        }
	});
	get_lista_ubigeos($("#ubigeo"));
	$(".btn_savecompany").click(guardar_contribuyente);
    $(".search_document").click(consultar_numero_doc);
    get_patrocinadores($("#idpatrocinador"));

    $("#ruc").on('input', function() {
        var valor = this.value;
        $("#contribuyente_text_ruc").html(valor);
    }).on('change', function() {
        var valor = this.value;
        $("#contribuyente_text_ruc").html(valor);
    });

    $("#razon_social").on('input', function() {
        var valor = this.value;
        $("#contribuyente_text_razonsocial").html(valor);
    }).on('change', function() {
        var valor = this.value;
        $("#contribuyente_text_razonsocial").html(valor);
    });

    var id_contribuyente = parseInt($("#id_contribuyente").val()) + 0;
    if(id_contribuyente > 0) {
        get_data_contribuyente(id_contribuyente);
    }

    inicializar_controles();
    
});

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
            $("#contribuyente_img_logo").attr("src", data.contribuyente.img_logo);
            $("#ruta_logo").val(data.contribuyente.img_logo);
            $("#id_contribuyente").val(data.contribuyente.id_contribuyente);
            $("#ruc").val(data.contribuyente.ruc);
            $("#razon_social").val(data.contribuyente.razon_social);
            $("#nombre_comercial").val(data.contribuyente.nombre_comercial);
            $("#telefono").val(data.contribuyente.telefono);
            //$("#ubigeo").val(data.contribuyente.codigo_ubigeo).trigger("change").trigger("select2:select");
            get_lista_ubigeos($("#ubigeo"), data.contribuyente.codigo_ubigeo);
            $("#urbanizacion").val(data.contribuyente.urbanizacion);
            $("#direccionfiscal").val(data.contribuyente.direccion_fiscal);
            $("#idpatrocinador").val(data.contribuyente.id_patrocinador).trigger("change").trigger("select2:select");
            $("#usuario_sol").val(data.contribuyente.usuario_sol);
            $("#password_sol").val('');
            $("#password_certificado").val('');

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
            if(data.data.codigo_ubigeo != '') {
                get_lista_ubigeos($("#ubigeo"), data.data.codigo_ubigeo);
            }
            $("#direccionfiscal").val(data.data.direccion);
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
    formData.append("archivo", $("#file_certificado")[0].files[0]);
    $.each(datastring, function( index, control ) {
        formData.append(control.name, control.value);
    });

    $.ajax({
        url: "/facturacionv8/gestiondecontribuyentes/guardar",
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
				window.location.href = "/facturacionv8/gestiondecontribuyentes";
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

function get_patrocinadores(select) {
	$.ajax({
        url : '/facturacionv8/gestiondecontribuyentes/get_patrocinadores',
        method :  'POST',
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			$.each(data.lista, function(key, item) {
				select.append('<option value="' + item.id_contribuyente + '">' + item.value + '</option>');
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

function inicializar_subida_imagen() {
	$('#btn_subirimagen').click(function() {
        ratio = 1 / 1;
        ancho_corte_width = 310;
        alto_corte_height = 310;
        imagetipo = 'img';
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