function recaptchaResponseOk() {
	if (typeof window.APP_DEV_LOCAL !== 'undefined' && window.APP_DEV_LOCAL === true) {
		return true;
	}
	if (typeof grecaptcha === 'undefined' || typeof grecaptcha.getResponse !== 'function') {
		return false;
	}
	var t = '';
	try {
		t = grecaptcha.getResponse(0) || grecaptcha.getResponse();
	} catch (e) {
		try { t = grecaptcha.getResponse(); } catch (e2) {}
	}
	return t !== '' && t != null;
}

function recaptchaSafeReset() {
	if (typeof grecaptcha !== 'undefined' && typeof grecaptcha.reset === 'function') {
		grecaptcha.reset();
	}
}

function getLoginSubmitButton() {
	var $b = $('.frm_login button[type="submit"]').first();
	if ($b.length) {
		return $b;
	}
	return $('.btn-login').first();
}

/** Google reCAPTCHA invoca por nombre en window (iframe → callback global). */
function enableLoginSubmitIfAllowed() {
	var $btn = getLoginSubmitButton();
	if (!$btn.length) {
		return;
	}
	var allow = (typeof window.APP_DEV_LOCAL !== 'undefined' && window.APP_DEV_LOCAL === true);
	if (!allow) {
		allow = recaptchaResponseOk();
	}
	if (allow) {
		$btn.prop('disabled', false).removeAttr('disabled').removeClass('disabled');
	}
}

function habilitar_login() {
	enableLoginSubmitIfAllowed();
}

window.habilitar_login = habilitar_login;

$(function() {
	try {
		inicializar_checkboxes();
	} catch (e) { /* sin .control-success en login */ }
	try {
		$('.js-example-basic-single').select2();
	} catch (e) { /* sin select2 o sin nodos */ }
	enableLoginSubmitIfAllowed();
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

	$(window).on('load', enableLoginSubmitIfAllowed);

	var pollLoginBtn = setInterval(enableLoginSubmitIfAllowed, 350);
	setTimeout(function() {
		clearInterval(pollLoginBtn);
	}, 120000);
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

	if (!recaptchaResponseOk()){
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
				recaptchaSafeReset();
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
			recaptchaSafeReset();
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

	if (!recaptchaResponseOk()){
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
				recaptchaSafeReset();
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
			recaptchaSafeReset();
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