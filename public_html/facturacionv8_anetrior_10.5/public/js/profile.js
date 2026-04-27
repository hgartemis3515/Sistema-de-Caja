$(function() {
	inicializar_subida_imagen(); 
	$(".btn_saveuser").click(save_profile);
	$(".btn_guardaremail").click(save_email);
	$(".btn_guardarpass").click(save_pass);
	$(".btn_generar_codigo").click(function(){
		generar_codigo($("#txt_codigo"), 8);
	});
    $('#show-passwd').on('click', function(e) {
		var current = $(this).attr('action');
		if (current == 'hide') {
			$('#new_password').attr('type', 'text');
			$('.icon-eye-blocked').attr('class', 'icon-eye');
			$('#show-passwd').attr('action','show');
		}
		if (current == 'show') {
			$('#new_password').attr('type', 'password');
			$('.icon-eye').attr('class', 'icon-eye-blocked');
			$('#show-passwd').attr('action','hide');
		}
	});

	//seguridad y acceso
	$('.input_email').hide();
	$('.btn_guardaremail').hide();
	$('.btn_cerraremail').hide();
	//pass
	$('.input-pass').hide();
	$('#password').hide();
	$('.btn_guardarpass').hide();
	$('.btn_cerrarpass').hide();
	//Para btn_email
	$('.edit_email').on('click', function(e) {
		$('.label_email').hide();
		$('.edit_email').hide();
		$('.input_email').fadeIn( "slow" );
		$('.btn_guardaremail').fadeIn( "slow" );
		$('.btn_cerraremail').fadeIn( "slow" );
		$('.info-access').attr('class', 'info-access2');
	});
	$('.btn_cerraremail').on('click', function(e) {
		$('.input_email').hide();
		$('.btn_guardaremail').hide();
		$('.btn_cerraremail').hide();
		$('.label_email').fadeIn( "slow" );
		$('.edit_email').fadeIn( "slow" );
		$('.info-access2').attr('class', 'info-access');
	});
	//Para btn_password
	$('.edit_password').on('click', function(e) {
		$('.label_password').hide();
		$('.edit_password').hide();
		$('.input-pass').fadeIn( "slow" );
		$('.btn_guardarpass').fadeIn( "slow" );
		$('.btn_cerrarpass').fadeIn( "slow" );
		$('#password').fadeIn( "slow" );
		$('.info-password').attr('class', 'info-password2');
	});
	$('.btn_cerrarpass').on('click', function(e) {
		$('.label_password').fadeIn( "slow" );
		$('.edit_password').fadeIn( "slow" );
		$('.input-pass').hide();
		$('.btn_guardarpass').hide();
		$('.btn_cerrarpass').hide();
		$('#password').hide();
		$('.info-password2').attr('class', 'info-password');
	});

	inicializar_subida_imagen();

	//get_list_suscripcion();
});

function inicializar_subida_imagen() {
	$('#btn_subirimagen').click(function() {
        ratio = 1 / 1;
        ancho_corte_width = 310;
        alto_corte_height = 310;
        imagetipo = 'img_profile';
        $('.fileinput-remove').trigger('click');
        $('.previewrecorteimg').html('');
        $('#vm_cargar_imagen').modal('show');
	});
	 
	inicializacion_plugin_para_recortar_imagenes($('.previewrecorteimg'), '.kv-file-content .file-preview-image', $('.file-input'), $("#btn_guardarimagen"));

    //ratio plugin crooped
    ratio = 1 / 1;
	ancho_corte_width = 310;
	alto_corte_height = 310;
	imagetipo = 'img_profile';
	
	$("#vm_cargar_imagen").on("hidden.bs.modal", function () {
        $('.modal:visible').css("overflow-y","auto");
	});
	
}

function save_profile(){
	var light = $("#content_panel_usuario");

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
	
	var datastring = $("#frm_profile").serializeArray();
	$.ajax({
		url: '/facturacionv8/profile/save',
		data: datastring,
		method: 'POST',
		dataType: 'json'
	}).then(function(data){
		if(data.respuesta == 'ok'){
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
		}
		else {
			swal({
				title: data.titulo,
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
function save_email(){

	var datastring = $("#frm_email").serializeArray();
	$.ajax({
		url: '/facturacionv8/profile/save_email',
		data: datastring,
		method: 'POST',
		dataType: 'json'
	}).then(function(data){
		if(data.respuesta == 'ok'){
			swal({   
                title: data.titulo,   
                text: data.mensaje,
                html: true,
                type: "success", 
                confirmButtonColor: "#3F51B5",   
                confirmButtonText: "Ok",
            }, function() {
						window.location.reload();
            });
		}
		else {
			swal({
				title: data.titulo,
				text: data.mensaje,
				html: true,
				type: "error",
				confirmButtonText: "Ok",
				confirmButtonColor: "#3F51B5"
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
			confirmButtonColor: "#3F51B5"
		}, function(){
		});
    });
	
}
function save_pass(){

	var datastring = $("#frm_password").serializeArray();
	$.ajax({
		url: '/facturacionv8/profile/save_password',
		data: datastring,
		method: 'POST',
		dataType: 'json'
	}).then(function(data){
		if(data.respuesta == 'ok'){
			swal({   
                title: data.titulo,   
                text: data.mensaje,
                html: true,
                type: "success", 
                confirmButtonColor: "#3F51B5",   
                confirmButtonText: "Ok",
            }, function() {
				window.location.reload();
            });
		}
		else {
			swal({
				title: data.titulo,
				text: data.mensaje,
				html: true,
				type: "error",
				confirmButtonText: "Ok",
				confirmButtonColor: "#3F51B5"
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
			confirmButtonColor: "#3F51B5"
		}, function(){
		});
    });
	
}

function get_list_suscripcion(){
	var light = $('.content-table');
	$(light).block({
		message: '<div class="loader"></div> <p><br />Espere un momento...</p>',
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
		url : '/facturacionv8/profile/get_lista_suscripcion',
		method :  'POST',
		dataType : "json"
}).then(function(data){
	if(data.respuesta == 'ok') {
		$("#alert_plan").html(data.fin_plan);
		$('#tbl_lista_usuarios').DataTable({
				data: data.lista,
				"bDestroy": true,
				"order": [[ 2, "desc" ]]
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