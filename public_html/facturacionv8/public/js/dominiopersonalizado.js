$(function(){
	$("#dominio_personalizado").on('input', function() {
		var dominio = $("#dominio_personalizado").val();
		$("#url_nuevo_dominio").html('http://' + dominio );
	});
	
	$('#btn_logo461x95').click(function() {
        ratio = 461 / 95;
        ancho_corte_width = 461;
        alto_corte_height = 95;
        imagetipo = 'logo461x95';
        $('.fileinput-remove').trigger('click');
        $('.previewrecorteimg').html('');
        $('#vm_cargar_imagen').modal('show');
	});

	$('#btn_logo291x60').click(function() {
        ratio = 291 / 60;
        ancho_corte_width = 291;
        alto_corte_height = 60;
        imagetipo = 'logo291x60';
        $('.fileinput-remove').trigger('click');
        $('.previewrecorteimg').html('');
        $('#vm_cargar_imagen').modal('show');
	});

	$('#btn_logo56x56').click(function() {
        ratio = 1 / 1;
        ancho_corte_width = 56;
        alto_corte_height = 56;
        imagetipo = 'logo56x56';
        $('.fileinput-remove').trigger('click');
        $('.previewrecorteimg').html('');
        $('#vm_cargar_imagen').modal('show');
	});
	
	inicializacion_plugin_para_recortar_imagenes($('.previewrecorteimg'), '.kv-file-content .file-preview-image', $('.file-input'), $("#btn_guardarimagen"));

	$("#vm_cargar_imagen").on("hidden.bs.modal", function () {
        $('.modal:visible').css("overflow-y","auto");
    });
    
    $("#btn_guardar_dominio").click(guardar_dominio);
});

function guardar_dominio() {
	var light = $('#content_dominio_personalizado');
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
	
	var logo461x95 = $("#url_image_logo461x95").val();
	var logo291x60 = $("#url_image_logo291x60").val();
	var logo56x56 = $("#url_image_logo56x56").val();
    var dominio = $("#dominio_personalizado").val();
    var id_contribuyente = $("#id_contribuyente").val(); 
    var captcha_key_private = $("#captcha_key_private").val();
    var captcha_key_public = $("#captcha_key_public").val();

	$.ajax({
        url : '/facturacionv8/dominiopersonalizado/guardar',
		method :  'POST',
		data: {id_contribuyente: id_contribuyente, logo461x95: logo461x95, logo291x60: logo291x60, logo56x56: logo56x56, dominio: dominio, captcha_key_private: captcha_key_private, captcha_key_public: captcha_key_public},
        dataType : "json"
    }).then(function(data){
        if(data.respuesta == 'ok') {
			swal({   
                title:'Ok',   
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
                title:'Error',   
                text: data.mensaje,
                html: true,
                type: "error", 
                confirmButtonColor: "#00BCD4",   
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
			confirmButtonColor: "#00BCD4",   
			confirmButtonText: "Ok"
		}, function() {
			$(light).unblock();
		});
    });
}