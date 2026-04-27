function extraer_plantillas(tipo_documento, tamanio, seleccionado, nombre_documento) {
	$("#vm_nombre_cpe").html(nombre_documento);
	$("#content_plantillas_normales").html('');
	$("#content_plantillas_personalizadas").html('');
	$("#vm_tipo_doc_electronico").val(tipo_documento);
	$("#vm_tamanio_pdf").val(tamanio);

	$.ajax({
        url : '/facturacionv8/branchoffice/get_plantillas_user',
        method :  'POST',
		data: {tipo_documento: tipo_documento, tamanio: tamanio},
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			data.normales.forEach(function(plantilla, index) {
				var html_seleccionado = '';
				if(parseInt(plantilla.id_plantillapdf) == seleccionado) {
					html_seleccionado = 'checked';
				}

				var template_html = '\
				<div class="col-lg-2 col-md-2 col-4 mb-5">\
					<div class="thumbnail">\
						<div class="thumb">\
							<a title="id: '+ plantilla.id_plantillapdf +'" href="'+ plantilla.preview +'" data-popup="lightbox">\
								<img title="id: '+ plantilla.id_plantillapdf +'" src="'+ plantilla.preview +'" alt="">\
								<span class="zoom-image"><i class="fa fa-eye"></i></span>\
							</a>\
						</div>\
					</div>\
					\
					<div class="radio">\
						<label>\
							<input type="radio" id="opcionplantillapdf" name="opcionplantillapdf" class="control-primary opcionplantillapdf" data-image="'+ plantilla.preview +'" value="'+ plantilla.id_plantillapdf +'" ' + html_seleccionado + '>\
							'+ plantilla.nombre +'\
						</label>\
					</div>\
				</div>\
				';

				$("#content_plantillas_normales").append(template_html);
			});

			data.personalizadas.forEach(function(plantilla, index) {
				var html_seleccionado = '';
				if(parseInt(plantilla.id_plantillapdf) == seleccionado) {
					html_seleccionado = 'checked';
				}

				var template_html = '\
				<div class="col-lg-2 mb-5">\
					<div class="thumbnail">\
						<div class="thumb">\
							<a title="id: '+ plantilla.id_plantillapdf +'" target="_blank" href="'+ plantilla.preview +'" data-popup="lightbox">\
								<img title="id: '+ plantilla.id_plantillapdf +'" src="'+ plantilla.preview +'" alt="">\
								<span class="zoom-image"><i class="fa fa-eye"></i></span>\
							</a>\
						</div>\
					</div>\
					\
					<div class="radio">\
						<label>\
							<input type="radio" id="opcionplantillapdf" name="opcionplantillapdf" class="control-primary opcionplantillapdf" data-image="'+ plantilla.preview +'" value="'+ plantilla.id_plantillapdf +'" ' + html_seleccionado + '>\
							'+ plantilla.nombre +'\
						</label>\
					</div>\
				</div>\
				';

				$("#content_plantillas_personalizadas").append(template_html);
			});

			$("#vm_seleccion_de_plantillas").modal('show');
		} else {
			swal({
				title: 'Error',
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

function guardar_nueva_plantilla() {
	var id_documento = $("#vm_tipo_doc_electronico").val();
	var tamanio_pdf = $("#vm_tamanio_pdf").val();
	var id_plantilla_pdf = $("input[name=opcionplantillapdf]:checked").val();
	var url_image = $("input[name=opcionplantillapdf]:checked").data('image');

	if(id_documento != '' && tamanio_pdf != '') {
		if(tamanio_pdf == 'a4') {
			if(id_documento == '01') {
				$("#factura_urlimage_a4").attr('href', url_image);
				$("#factura_preview_a4").attr('src', url_image);
				$("#id_plantillapdf_factura_a4").val(id_plantilla_pdf);
				$("#btn_accion_factura_a4").attr('onclick', 'extraer_plantillas("' + id_documento + '", "' + tamanio_pdf + '", ' + id_plantilla_pdf + ', "FACTURAS - EN TAMAÑO A4")');
			}

			if(id_documento == '03') {
				$("#boleta_urlimage_a4").attr('href', url_image);
				$("#boleta_preview_a4").attr('src', url_image);
				$("#id_plantillapdf_boleta_a4").val(id_plantilla_pdf);
				$("#btn_accion_boleta_a4").attr('onclick', 'extraer_plantillas("' + id_documento + '", "' + tamanio_pdf + '", ' + id_plantilla_pdf + ', "BOLETAS - EN TAMAÑO A4")');
			}

			if(id_documento == '07') {
				$("#notacredito_urlimage_a4").attr('href', url_image);
				$("#notacredito_preview_a4").attr('src', url_image);
				$("#id_plantillapdf_notacredito_a4").val(id_plantilla_pdf);
				$("#btn_accion_notacredito_a4").attr('onclick', 'extraer_plantillas("' + id_documento + '", "' + tamanio_pdf + '", ' + id_plantilla_pdf + ', "NOTA DE CRÉDITO - EN TAMAÑO A4")');
			}

			if(id_documento == '08') {
				$("#notadebito_urlimage_a4").attr('href', url_image);
				$("#notadebito_preview_a4").attr('src', url_image);
				$("#id_plantillapdf_notadebito_a4").val(id_plantilla_pdf);
				$("#btn_accion_notadebito_a4").attr('onclick', 'extraer_plantillas("' + id_documento + '", "' + tamanio_pdf + '", ' + id_plantilla_pdf + ', "NOTA DE DÉBITO - EN TAMAÑO A4")');
			}

			if(id_documento == '09') {
				$("#guiaremision_urlimage_a4").attr('href', url_image);
				$("#guiaremision_preview_a4").attr('src', url_image);
				$("#id_plantillapdf_guiaremision_a4").val(id_plantilla_pdf);
				$("#btn_accion_guiaremision_a4").attr('onclick', 'extraer_plantillas("' + id_documento + '", "' + tamanio_pdf + '", ' + id_plantilla_pdf + ', "GUÍA DE REMISIÓN - EN TAMAÑO A4")');
			}

			if(id_documento == '77') {
				$("#notaventa_urlimage_a4").attr('href', url_image);
				$("#notaventa_preview_a4").attr('src', url_image);
				$("#id_plantillapdf_notaventa_a4").val(id_plantilla_pdf);
				$("#btn_accion_notaventa_a4").attr('onclick', 'extraer_plantillas("' + id_documento + '", "' + tamanio_pdf + '", ' + id_plantilla_pdf + ', "NOTA DE VENTA - EN TAMAÑO A4")');
			}

			if(id_documento == '88') {
				$("#cotizacion_urlimage_a4").attr('href', url_image);
				$("#cotizacion_preview_a4").attr('src', url_image);
				$("#id_plantillapdf_cotizacion_a4").val(id_plantilla_pdf);
				$("#btn_accion_cotizacion_a4").attr('onclick', 'extraer_plantillas("' + id_documento + '", "' + tamanio_pdf + '", ' + id_plantilla_pdf + ', "COTIZACIÓN - EN TAMAÑO A4")');
			}
		}

		if(tamanio_pdf == 'ticket') {
			if(id_documento == '01') {
				$("#factura_urlimage_ticket").attr('href', url_image);
				$("#factura_preview_ticket").attr('src', url_image);
				$("#id_plantillapdf_factura_ticket").val(id_plantilla_pdf);
				$("#btn_accion_factura_ticket").attr('onclick', 'extraer_plantillas("' + id_documento + '", "' + tamanio_pdf + '", ' + id_plantilla_pdf + ', "FACTURAS - EN TAMAÑO TICKET")');
			}

			if(id_documento == '03') {
				$("#boleta_urlimage_ticket").attr('href', url_image);
				$("#boleta_preview_ticket").attr('src', url_image);
				$("#id_plantillapdf_boleta_ticket").val(id_plantilla_pdf);
				$("#btn_accion_boleta_ticket").attr('onclick', 'extraer_plantillas("' + id_documento + '", "' + tamanio_pdf + '", ' + id_plantilla_pdf + ', "BOLETAS - EN TAMAÑO TICKET")');
			}

			if(id_documento == '07') {
				$("#notacredito_urlimage_ticket").attr('href', url_image);
				$("#notacredito_preview_ticket").attr('src', url_image);
				$("#id_plantillapdf_notacredito_ticket").val(id_plantilla_pdf);
				$("#btn_accion_notacredito_ticket").attr('onclick', 'extraer_plantillas("' + id_documento + '", "' + tamanio_pdf + '", ' + id_plantilla_pdf + ', "NOTA DE CRÉDITO - EN TAMAÑO TICKET")');
			}

			if(id_documento == '08') {
				$("#notadebito_urlimage_ticket").attr('href', url_image);
				$("#notadebito_preview_ticket").attr('src', url_image);
				$("#id_plantillapdf_notadebito_ticket").val(id_plantilla_pdf);
				$("#btn_accion_notadebito_ticket").attr('onclick', 'extraer_plantillas("' + id_documento + '", "' + tamanio_pdf + '", ' + id_plantilla_pdf + ', "NOTA DE DÉBITO - EN TAMAÑO TICKET")');
			}

			if(id_documento == '09') {
				$("#guiaremision_urlimage_ticket").attr('href', url_image);
				$("#guiaremision_preview_ticket").attr('src', url_image);
				$("#id_plantillapdf_guiaremision_ticket").val(id_plantilla_pdf);
				$("#btn_accion_guiaremision_ticket").attr('onclick', 'extraer_plantillas("' + id_documento + '", "' + tamanio_pdf + '", ' + id_plantilla_pdf + ', "GUÍA DE REMISIÓN - EN TAMAÑO TICKET")');
			}

			if(id_documento == '77') {
				$("#notaventa_urlimage_ticket").attr('href', url_image);
				$("#notaventa_preview_ticket").attr('src', url_image);
				$("#id_plantillapdf_notaventa_ticket").val(id_plantilla_pdf);
				$("#btn_accion_notaventa_ticket").attr('onclick', 'extraer_plantillas("' + id_documento + '", "' + tamanio_pdf + '", ' + id_plantilla_pdf + ', "NOTA DE VENTA - EN TAMAÑO TICKET")');
			}

			if(id_documento == '88') {
				$("#cotizacion_urlimage_ticket").attr('href', url_image);
				$("#cotizacion_preview_ticket").attr('src', url_image);
				$("#id_plantillapdf_cotizacion_ticket").val(id_plantilla_pdf);
				$("#btn_accion_cotizacion_ticket").attr('onclick', 'extraer_plantillas("' + id_documento + '", "' + tamanio_pdf + '", ' + id_plantilla_pdf + ', "COTIZACIÓN - EN TAMAÑO TICKET")');
			}
		}
	}

	$("#vm_seleccion_de_plantillas").modal('hide');
}