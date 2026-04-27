$(function() {
	inicializar_checkboxes();
	$('.js-example-basic-single').select2();
	$('.btn_guardaruser').click(registrar_contribuyente);
	$('#show-passwd').on('click', function(e) {
		var current = $(this).attr('action');
		if (current == 'hide') {
			$('#contrasena').attr('type', 'text');
			$('.icon-eye-blocked').attr('class', 'icon-eye');
			$('#show-passwd').attr('action','show');
		}
		if (current == 'show') {
			$('#contrasena').attr('type', 'password');
			$('.icon-eye').attr('class', 'icon-eye-blocked');
			$('#show-passwd').attr('action','hide');
		}
	});
	$('#show-passwd2').on('click', function(e) {
		var current = $(this).attr('action');
		if (current == 'hide') {
			$('#contrasena_register').attr('type', 'text');
			$('.icon-eye-blocked').attr('class', 'icon-eye');
			$('#show-passwd2').attr('action','show');
		}
		if (current == 'show') {
			$('#contrasena_register').attr('type', 'password');
			$('.icon-eye').attr('class', 'icon-eye-blocked');
			$('#show-passwd2').attr('action','hide');
		}
	})

	verificar_accion();

	$(".btn_cambiar_password").click(cambiar_password);
     
});

function cambiar_password() {
	var light = $(".page-container");

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
	
	var datastring = $("#frm_recover_password").serializeArray();

	if (grecaptcha.getResponse() == ""){
        $(light).unblock();
        swal({
            title: "Problemas!",
            text: 'Debes Verificar que no eres un Robot!.',
            type: "error",
            confirmButtonText: "Ok"
        });
        return false;
    }

	$.ajax({
        url : '/facturacionv8/login/cambiar_password',
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
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Ok",
            }, function() {
				window.location.reload("/facturacionv8/login");
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
				grecaptcha.reset();
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
			grecaptcha.reset();
		});
    });
}

function verificar_accion() {
	var accion = $("#accion_usuario").val();
	if(accion == 'register') {
		$(".btn-sing-up").trigger('click');
	}
}
function inicializar_checkboxes() {
	$(".control-success").uniform({
		radioClass: "choice",
		wrapperClass: "border-success-600 text-success-800"
	});
}

function enableBtn() {
	$(".btn_guardaruser").prop('disabled', false);
}

function habilitar_login() {
	$(".btn-login").prop('disabled', false);
}

function registrar_contribuyente() {
	var light = $(".page-container");

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
	
	var datastring = $("#frm_singup").serializeArray();

	if (grecaptcha.getResponse() == ""){
        $(light).unblock();
        swal({
            title: "Problemas!",
            text: 'Debes Verificar que no eres un Robot!.',
            type: "error",
            confirmButtonText: "Ok"
        });
        return false;
    }

	$.ajax({
        url : '/facturacionv8/login/registrar_usuario',
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
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Ok",
            }, function() {
				window.location.reload();
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
				grecaptcha.reset();
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
			grecaptcha.reset();
		});
    });
}
function register_user222(){
	var datastring = $("#frm_singup").serializeArray();
	console.log(datastring);
	return false;
	$.ajax({
        url : "/facturacionv8/login/registrar_usuario",
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
                confirmButtonColor: "#3F51B5",   
                confirmButtonText: "Ok",
            }, function() {
				
            });
        } else {
            swal({   
                title:'Error',   
                text: data.mensaje,
                html: true,
                type: "error", 
                confirmButtonColor: "#3F51B5",   
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
			confirmButtonColor: "#3F51B5",   
			confirmButtonText: "Ok"
		}, function() {
		
		});
    });
}