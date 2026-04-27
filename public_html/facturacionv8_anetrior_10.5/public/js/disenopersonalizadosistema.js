$(function() {
	$('.select2').select2({
        minimumResultsForSearch: -1
	});
    $('.multiselect').multiselect();

	$("#btn_save_plantilla").click(guardar_plantilla);
	inicializar_subida_imagen();
	get_lista_plantillas();
});

function guardar_plantilla() {
	var light = $("#content_panel_plantilla");

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
	
	swal({   
		title: 'Necesitamos de tu Confirmación',   
		text: '¿Realmente desear guardar los cambios?',
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
			var datastring = $("#frm_plantilla_pdf").serializeArray();
			datastring.push({ name: "tipos_documentos_ids", value: $("#tipos_documentos").val() });

			$.ajax({
				url : '/facturacionv8/gestionplantillaspdf/guardar_plantilla',
				method :  'POST',
				data: datastring,
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
							cancelButtonColor: "#4CAF50",
							confirmButtonText: "Ok",
							showCancelButton: false
						}, function(isconfirmed){
							$("#id_plantilla_pdf").val('');
							$("#nombre_plantilla").val('');
							$("#src_img_upload").val('');
							$("#img_upload_preview").attr("src",'/facturacionv8/img/svg/preview_pdf.svg');
							get_lista_plantillas();
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
			$(light).unblock();
			return;
		}
	});
}

function inicializar_subida_imagen() {
	$('.btn_subirimagen').click(function() {
		ratio = 791 / 1004;
        ancho_corte_width = 791;
        alto_corte_height = 1004;
        $(".tamanio_texto_img").html('791x1004px');
        imagetipo = 'img_preview_plantilla';
        $('.fileinput-remove').trigger('click');
        $('.previewrecorteimg').html('');
        $('#vm_cargar_imagen').modal('show');
    });
	
	inicializacion_plugin_para_recortar_imagenes($('.previewrecorteimg'), '.kv-file-content .file-preview-image', $('.file-input'), $("#btn_guardarimagen"));

    //ratio plugin crooped
    ratio = 791 / 1004;
	ancho_corte_width = 791;
	alto_corte_height = 1004;
	imagetipo = 'img_preview_plantilla';
	
	$("#vm_cargar_imagen").on("hidden.bs.modal", function () {
        $('.modal:visible').css("overflow-y","auto");
	});
	
}

function get_lista_plantillas() {
	var light = $('#content_lista_plantillapdf');
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
        url : '/facturacionv8/gestionplantillaspdf/get_lista_plantillaspdf',
        method :  'POST',
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			$('#tbl_lista_plantillapdf').DataTable({
                data: data.lista,
                "bDestroy": true
            });
            //$('.tbl_lista_productos').on('click', '.btn_editar_producto', get_all_data_producto);
            $(light).unblock();
		} else {
			swal({
				title: 'Error',
				text: data.mensaje,
				html: true,
				type: "error",
				confirmButtonText: "Ok",
				confirmButtonColor: "#3F51B5"
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
			confirmButtonColor: "#3F51B5"
		}, function(){
			$(light).unblock();
		});
    });
}

function editar_plantilla(idplantillapdf){
	var light = $("#content_lista_plantillapdf");

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
        url : '/facturacionv8/gestionplantillaspdf/get_data_plantillapdf',
        method :  'POST',
        data: {idplantillapdf: idplantillapdf},
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			$('option', $("#tipos_documentos")).each(function(element) {
				$("#tipos_documentos").multiselect('deselect', $(this).val());
				$.uniform.update();
			});

			$("#id_plantilla_pdf").val(data.plantillapdf.id_plantillapdf);
			$("#nombre_plantilla").val(data.plantillapdf.nombre);
			$("#src_img_upload").val(data.plantillapdf.preview);
			$("#select_tamanio").val(data.plantillapdf.tamanio).trigger("change").trigger("select2:select");
			$("#select_categoria").val(data.plantillapdf.tipo).trigger("change").trigger("select2:select");
			$("#img_upload_preview").attr("src",data.plantillapdf.preview);
			
			data.tipo_docs_validos.forEach(function(tipo_doc_valido, index) {
				if(tipo_doc_valido == '03') {
					$('#tipos_documentos').multiselect('select', '03'),
					$.uniform.update();
				}
				if(tipo_doc_valido == '01') {
					$('#tipos_documentos').multiselect('select', '01'),
					$.uniform.update();
				}

				if(tipo_doc_valido == '07') {
					$('#tipos_documentos').multiselect('select', '07'),
					$.uniform.update();
				}

				if(tipo_doc_valido == '08') {
					$('#tipos_documentos').multiselect('select', '08'),
					$.uniform.update();
				}

				if(tipo_doc_valido == '77') {
					$('#tipos_documentos').multiselect('select', '77'),
					$.uniform.update();
				}
				if(tipo_doc_valido == '88') {
					$('#tipos_documentos').multiselect('select', '88'),
					$.uniform.update();
				}
				if(tipo_doc_valido == '09') {
					$('#tipos_documentos').multiselect('select', '09'),
					$.uniform.update();
				}
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

function eliminar_plantilla(idplantillapdf){
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
	swal({   
		title: 'Necesitamos de tu Confirmación',   
		text: '¿Realmente desear eliminar la plantilla?',
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
				url : '/facturacionv8/gestionplantillaspdf/eliminar_plantillapdf',
				method :  'POST',
				data: {idplantillapdf: idplantillapdf},
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
						get_lista_plantillas();
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
	});
	
}