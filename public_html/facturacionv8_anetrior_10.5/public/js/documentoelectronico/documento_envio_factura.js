$(function(){
	$("#btn_guardar_doc_electronico").click(guardar_documento_electronico);
	$("#new_document_msg_guardado").click(function() {
			$("#modal_msg_guardado").modal('hide');
			limpiar_documento_electronico();
		}
	);
});

function guardar_documento_electronico() {
	//calcular_totales_documento();
	
	var light = $('#cuerpo_comprobante');
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

	var gridData = jQuery("#detalle_documento").getRowData();
	gridData = limpiar_data_post(gridData);
	var postData = JSON.stringify(gridData);

	var data_anticipos = JSON.stringify(get_json_anticipos());

	var datastring = $("#frm_documentoelectronico").serializeArray();
	//una posible solución aquí es agregarle un texto a la izquierda y derecha de postData para que empiece a funcionar:
	//https://stackoverflow.com/questions/19130285/post-returns-403-if-url-is-in-data
	datastring.push({ name: "detalle", value: postData });
	var data_cuotas = JSON.stringify(get_json_cuotas());
	datastring.push({ name: "data_cuotas", value: data_cuotas });
	datastring.push({ name: "lista_etiquetas", value: $("#select_etiquetas").val() });
	datastring.push({ name: "anticipos", value: data_anticipos });

	var html_opt_anular_origen = '';
	if($("#origen_id_tipodoc_electronico").val() != '') {
		console.log("si ingresa paso 1");
		if($("#origen_id_tipodoc_electronico").val() == '77') {
			console.log("si ingresa paso 2");
			html_opt_anular_origen = '\
			<div class="col-md-12">\
				<div class="checkbox checkbox-switch" style="margin-bottom: 20px;">\
					<label  class="text-primary" style="font-size: 16px; margin-right: 12px;">\
						¿Deseas Anular La Nota de Venta: ' + $("#origen_numero_comprobante").val() + '?\
					</label>\
					<input name="opcion_anular_docnooficial" id="opcion_anular_docnooficial" type="checkbox" data-on-color="success" data-off-color="danger" data-on-text="No" data-off-text="SI" checked="checked">\
				</div>\
			</div>\
			';
		}

		if($("#origen_id_tipodoc_electronico").val() == '88') {
			console.log("si ingresa paso 3");
			html_opt_anular_origen = '\
			<div class="col-md-12">\
				<div class="checkbox checkbox-switch" style="margin-bottom: 20px;">\
					<label class="text-primary" style="font-size: 16px; margin-right: 12px;">\
						¿Deseas Anular La Cotización: ' + $("#origen_numero_comprobante").val() + '?\
					</label>\
					<input name="opcion_anular_docnooficial" id="opcion_anular_docnooficial" type="checkbox" data-on-color="success" data-off-color="danger" data-on-text="No" data-off-text="SI" checked="checked">\
				</div>\
			</div>\
			';
		}
	}

	$.ajax({
        url : '/facturacionv8/documentoelectronico/guardar_documento',
		method :  'POST',
		data: datastring,
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			var html_confirm = '\
			<div class="row">\
				<div class="col-md-12">\
					<strong>Se creará el documento electrónico con los siguientes datos!</strong>\
				</div>\
				<div class="col-md-12">\
					' + $("#content_resumen_doc_electronico").html() + '   \
				</div>\
				' + html_opt_anular_origen + '\
				<div>\
					<strong><span class="text-success" style="font-size: 16px;">¿Desea Continuar con el Documento?</span></strong>\
				</div>\
			</div>';

			if($("#id_tipodoc_electronico").val() != '') {
				$("#opcion_anular_docnooficial").bootstrapSwitch('destroy');
			}
			
			swal({   
				title: 'Necesitamos de tu Confirmación',   
				text: html_confirm,
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
					var confirmado = 'si';
					datastring.push({name: 'confirmacion', value: confirmado});

					if($("#id_tipodoc_electronico").val() != '') {
						var opcion_anular_docnooficial = $("#opcion_anular_docnooficial").bootstrapSwitch('state');
						if(opcion_anular_docnooficial === true) {
							datastring.push({name: 'anular_doc_origen', value: 'no'});
						} else {
							datastring.push({name: 'anular_doc_origen', value: 'si'});
						}
					} else {
						datastring.push({name: 'anular_doc_origen', value: 'no'});
					}
					
					$.ajax({
						url : '/facturacionv8/documentoelectronico/guardar_documento',
						method :  'POST',
						data: datastring,
						dataType : "json"
					}).then(
						function(data) {
							if(data.respuesta == 'ok') {
								swal.close();
								$("#titulo_msg_guardado").html('<i class="icon-checkmark-circle position-left"></i> ' + data.titulo);
								$("#mensaje_msg_guardado").html(data.mensaje);
								var url_pdf_ticket = "";
								var url_pdf_a4 = "";
								
								url_pdf_ticket = data.url_relativa_ticket;
								url_pdf_a4 = data.url_relativa_a4;
								
								$("#content_pdf_preview_ticket").html('<div class="pdf_preview"><embed src="' + url_pdf_ticket + '" type="application/pdf" width="100%" height="300px" /></div>');
								$("#content_pdf_preview_a4").html('<div class="pdf_preview"><embed src="' + url_pdf_a4 + '" type="application/pdf" width="100%" height="300px" /></div>');
								$("#enlace_ticket_msg_guardado").attr("href", url_pdf_ticket);
								
								if(data.url_whatsapp !== null && data.url_whatsapp !== '') {
									if(is_mobil()) {
										if(data.url_whatsapp_api !== null && data.url_whatsapp_api !== '') {
											$("#enlace_whatsapp_msg_guardado").attr("href", data.url_whatsapp_api);
											$("#enlace_whatsapp_msg_guardado").show();
										} else {
											$("#enlace_whatsapp_msg_guardado").attr("href", data.url_whatsapp);
											$("#enlace_whatsapp_msg_guardado").show();
										}
									} else {
										if(data.url_whatsapp_web !== null && data.url_whatsapp_web !== '') {
											$("#enlace_whatsapp_msg_guardado").attr("href", data.url_whatsapp_web);
											$("#enlace_whatsapp_msg_guardado").show();
										} else {
											$("#enlace_whatsapp_msg_guardado").attr("href", data.url_whatsapp);
											$("#enlace_whatsapp_msg_guardado").show();
										}
									}
								} else {
									$("#enlace_whatsapp_msg_guardado").hide();
								}
								
								$("#enlace_a4_msg_guardado").attr("href", url_pdf_a4);
								$("#enlace_guiaremision_msg_guardado").attr("href", "/facturacionv8/guiaderemision/index/" + data.documento.id_tipodoc_electronico + "/" + data.documento.serie_comprobante + "/" + data.documento.numero_comprobante);

								$(light).unblock();
								$("#modal_msg_guardado").modal({
									backdrop: 'static',
									keyboard: false
								});
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

			if($("#id_tipodoc_electronico").val() != '') {
				$("#opcion_anular_docnooficial").bootstrapSwitch();
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

function limpiar_data_post(datagrid) {
	var nueva_data = [];
	$.each(datagrid, function(key, item) {
		let item_limpio = {
			afecto_icbper: item.afecto_icbper,
			cantidad: item.cantidad,
			codigo: item.codigo,
			descripcion: item.descripcion,
			estado: item.estado,
			html_lista_precios: '',
			id_cod_moneda: item.id_cod_moneda,
			id_tipoafectacionigv: item.id_tipoafectacionigv,
			idarticulo: item.idarticulo,
			idpresentacion: item.idpresentacion,
			idunidadmedida: item.idunidadmedida,
			igv: item.igv,
			importe: item.importe,
			item_detraccion_codigo: item.item_detraccion_codigo,
			item_detraccion_porcentaje: item.item_detraccion_porcentaje,
			precio: item.precio,
			row_identificadador: item.row_identificadador,
			select_unidadmedida: '',
			subtotal: item.subtotal,
			subtotal_icbper: item.subtotal_icbper,
			text_tipo_afecigv: item.text_tipo_afecigv,
			tipo_unidad: item.tipo_unidad,
			unidadmedida: item.unidadmedida,

			p_unit_sin_igv: item.p_unit_sin_igv,
			factor_igv_sunat: item.factor_igv_sunat,
			tipo_unidad: item.tipo_unidad
		};
		nueva_data.push(item_limpio);
	});

	return nueva_data;
}

function html_block() {
	var html = {
		message: '<div class="loader"></div> <p><br />Guardando su documento...</p>',
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
	};

	return html;
}