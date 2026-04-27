var dataTable_suscripciones;
var dataTable_docs_contribuyentes;
$(function(){
	$.extend( $.fn.dataTable.defaults, {
        autoWidth: false,
        dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        language: {
            search: '<span>Filter:</span> _INPUT_',
            searchPlaceholder: 'Escribe para Filtrar...',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
        }
	});

	get_lista_contribuyentes();
	//get_lista_inactivos();

	$("#valor_con_igv").on('input', function() {
        calcular_precio_sin_igv();
    });
    
    $("#valor_sin_igv").on('input', function() {
        calcular_precio_con_igv();
	});

	$("#txt_num_dias").on('input', function() {
		var fecha = new Date($('#fecha_expiracion_date').val());
		var dias = parseInt($("#txt_num_dias").val()); // Número de días a agregar
		fecha.setDate(fecha.getDate() + dias);
		$("#proxima_fecha_expiracion").html('El ' + formatDate(fecha));
	});

	$(".control_fecha_expira_cert").daterangepicker({
		singleDatePicker: true, 
		locale: { format: 'DD/MM/YYYY' },
		parentEl: $('#vm_cambiar_tipo_certificado')
	});
	$(".select2").select2();
	$(".select_lista_planes_base").select2();
	$(".select_plan_suscripcion").select2();
	$(".select2_condicionpago").select2();
	
	$(".select_condicionpago").on('change', function (e) {
		var condicionpago = this.value;
		if(condicionpago == 'contado') {
			$("#condicionpago_tipo_tarjeta").hide();
			$("#condicionpago_tipo_banco").hide();
			$("#condicionpago_numoperacion").hide();

			$("#condicionpago_contenido").removeClass();
			$("#condicionpago_contenido").attr('class', 'col-md-12');
		} else if (condicionpago == 'tarjeta') {
			$("#condicionpago_tipo_tarjeta").show();
			$("#condicionpago_tipo_banco").hide();
			$("#condicionpago_numoperacion").show();

			$("#condicionpago_contenido").removeClass();
			$("#condicionpago_contenido").attr('class', 'col-md-4');
		} else if (condicionpago == 'transferencia') {
			$("#condicionpago_tipo_tarjeta").hide();
			$("#condicionpago_tipo_banco").show();
			$("#condicionpago_numoperacion").show();

			$("#condicionpago_contenido").removeClass();
			$("#condicionpago_contenido").attr('class', 'col-md-4');
		}
	});

	$(".select_condicionpago").trigger("change").trigger("select2:select");

	inicializar_rango_gechas();
	
	$(".btn_validar_suscripcion").click(function() {
		let id_suscripcion = $("#id_suscripcion").val();
		let nota = $("#nota_validar_suscripcion").val();
		verificar_pago(id_suscripcion, nota);
	});

	$(".control_fecha_suscrip_expira").daterangepicker({
		singleDatePicker: true, 
		locale: { format: 'DD/MM/YYYY' },
		parentEl: $('#vm_cambiar_fecha_expira')
	});

	$("#btn_cambiar_fechaexpira").click(guardar_fecha_expira);
	$("#btn_guardar_expiracert").click(guardar_expira_cert);

	var switches = Array.prototype.slice.call(document.querySelectorAll('.switch2'));
    switches.forEach(function(html) {
        var switchery = new Switchery(html, {color: '#4CAF50'});
    });

    $(".switch").bootstrapSwitch();
});

function asignar_modulos_sistema(id_contribuyente) {
	var light = $('#contenido_lista_contribuyentes');
	$(light).block({
		message: '<div class="loader"></div> <p><br />Procesando...</p>',
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
        url : '/facturacionv8/gestiondecontribuyentes/get_lista_modulos_sistema',
		method :  'POST',
		data: {id_contribuyente: id_contribuyente},
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			var html_modulo_option = '';
			$("#vm_asignar_modulos_sistema_razon_social_empresa").html(data.nombre_empresa)
			$.each(data.modulos, function(option_name, option_value) {
				var checked = option_value === "si" ? 'checked' : '';
				var title = data.modulos_title[option_name];
				var color = data.modulos_color[option_name];

				html_modulo_option += `
					<div class="col-md-12">
						<div class="checkbox">
							<label>
								<input onclick="cambiar_system_option('${option_name}', ${id_contribuyente}, this.checked)" type="checkbox" name="${option_name}" data-optionname="${option_name}" data-idcontribuyente="${id_contribuyente}" class="system_option_contribuyente ${color}" ${checked}>
								${title}
							</label>
						</div>
					</div>
				`;
			});

			$(light).unblock();
			$("#vm_asignar_modulos_sistema_lista").html(html_modulo_option);
			$("#vm_asignar_modulos_sistema").modal("show");
			$(".switch").bootstrapSwitch();

			$(".systemoption_control-primary").uniform({ wrapperClass: 'border-primary-600 text-primary-800' });
			$(".systemoption_control-danger").uniform({ wrapperClass: 'border-danger-600 text-danger-800' });
			$(".systemoption_control-success").uniform({ wrapperClass: 'border-success-600 text-success-800' });
			$(".systemoption_control-warning").uniform({ wrapperClass: 'border-warning-600 text-warning-800' });
			$(".systemoption_control-info").uniform({ wrapperClass: 'border-info-600 text-info-800' });
			$(".systemoption_control-custom").uniform({ wrapperClass: 'border-indigo-600 text-indigo-800' });

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

function cambiar_system_option(option_name, id_contribuyente, isChecked) {
	var newValue = isChecked ? 'si' : 'no'; // Convierte el booleano a 'si' o 'no'

	if(newValue == 'si') {
		var text_swal = "¿Realmente Deseas Activar Esta Opción?";
	} else {
		var text_swal = "Se Prohibirá el Acceso a esta opción a todos los usuarios de esta empresa, ¿Realmente Deseas Desactivar Esta Opción?";
	}

    swal({   
        title: 'Confirmación de Acción!',   
        text: text_swal,
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
                url : '/facturacionv8/gestiondecontribuyentes/guardar_system_option_modulo',
                data: {id_contribuyente: id_contribuyente, option_name: option_name, option_value: newValue},
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

function cambiar_tipo_empresa_sunat(id_contribuyente, tipo) {
    swal({   
        title: 'Tipo de Empresa',   
        text: '¿Realmente Deseas Cambiar el Tipo de Empresa?',
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
                url : '/facturacionv8/gestiondecontribuyentes/cambiar_tipo_empresa_sunat',
                data: {id_contribuyente: id_contribuyente, tipo_empresa_sunat: tipo},
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
                        dataTable_docs_contribuyentes.ajax.reload(null, false); 
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

function asignar_cert_pse(id_contribuyente) {
	var light = $('#contenido_lista_contribuyentes');
	$(light).block({
		message: '<div class="loader"></div> <p><br />Procesando...</p>',
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
        url : '/facturacionv8/gestiondecontribuyentes/asignar_p_s',
		method :  'POST',
		data: {id_contribuyente: id_contribuyente},
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
						url : '/facturacionv8/gestiondecontribuyentes/asignar_p_s',
						method :  'POST',
						data: {id_contribuyente: id_contribuyente, confirmacion: 'si'},
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
									cancelButtonText: "Cancelar",
									showCancelButton: true
								}, function(isconfirmed){  
									if(isconfirmed) {
										//get_lista_contribuyentes();
										dataTable_docs_contribuyentes.ajax.reload(null, false); 
									} else {
										//window.location.reload();
									}
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

function asignar_cert_pse_facturalaya(id_contribuyente) {
	var light = $('#contenido_lista_contribuyentes');
	$(light).block({
		message: '<div class="loader"></div> <p><br />Procesando...</p>',
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
        url : '/facturacionv8/gestiondecontribuyentes/asignar_pse_facturalaya',
		method :  'POST',
		data: {id_contribuyente: id_contribuyente},
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
						url : '/facturacionv8/gestiondecontribuyentes/asignar_pse_facturalaya',
						method :  'POST',
						data: {id_contribuyente: id_contribuyente, confirmacion: 'si'},
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
									cancelButtonText: "Cancelar",
									showCancelButton: true
								}, function(isconfirmed){  
									if(isconfirmed) {
										//get_lista_contribuyentes();
										dataTable_docs_contribuyentes.ajax.reload(null, false); 
									} else {
										//window.location.reload();
									}
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

function quitar_pse_facturalaya(id_contribuyente) {
	var light = $('#contenido_lista_contribuyentes');
	$(light).block({
		message: '<div class="loader"></div> <p><br />Procesando...</p>',
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
        url : '/facturacionv8/gestiondecontribuyentes/quitar_pse_facturalaya',
		method :  'POST',
		data: {id_contribuyente: id_contribuyente},
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
						url : '/facturacionv8/gestiondecontribuyentes/quitar_pse_facturalaya',
						method :  'POST',
						data: {id_contribuyente: id_contribuyente, confirmacion: 'si'},
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
									cancelButtonText: "Cancelar",
									showCancelButton: true
								}, function(isconfirmed){  
									if(isconfirmed) {
										//get_lista_contribuyentes();
										dataTable_docs_contribuyentes.ajax.reload(null, false); 
									} else {
										//window.location.reload();
									}
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

function inicializar_rango_gechas() {
	$('.daterange-ranges').daterangepicker(
        {
			autoUpdateInput: false,
			drops: "down",
			startDate: moment().subtract(360, 'days'),
			endDate: moment(),
            minDate: moment().subtract(965, 'days'),
            maxDate: moment(),
            dateLimit: { days: 960 },
            opens: $('html').attr('dir') == 'rtl' ? 'right' : 'left',
            applyClass: 'btn-success',
            cancelClass: 'btn-danger',
			locale: {
                format: 'DD/MMM/YYYY',
                daysOfWeek: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ],
                monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
                ],
                monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul', 'Ago','Sep','Oct','Nov','Dic'],
				firstDay: 1,
				direction: $('html').attr('dir') == 'rtl' ? 'rtl' : 'ltr'
            }
        },
        function(start, end) {
            $('.daterange-ranges span').html(start.format('MMMM D') + ' - ' + end.format('MMMM D'));
        }
    );

    $('.daterange-ranges span').html(moment().subtract(360, 'days').format('MMMM D') + ' - ' + moment().format('MMMM D'));
}

function formatDate(date) {
	var monthNames = [
	  "Enero", "Febrero", "Marzo",
	  "Abril", "Mayo", "Junio", "Julio",
	  "Agosto", "Septiembre", "Octubre",
	  "Noviembre", "Diciembre"
	];
  
	var day = date.getDate();
	var monthIndex = date.getMonth();
	var year = date.getFullYear();
  
	return day + ' de ' + monthNames[monthIndex] + ' del ' + year;
}

function get_lista_contribuyentes() {
	
	dataTable_docs_contribuyentes = $('#tbl_lista_contribuyentes').DataTable( {
		"processing": true,
		"serverSide": true,
		"ajax":{
			url :"/facturacionv8/gestiondecontribuyentes/get_lista_contribuyentes",
			type: "post",
			error: function(){
				$(".tbl_lista_contribuyentes-error").html("");
				$("#tbl_lista_contribuyentes").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
				$("#tbl_lista_contribuyentes_processing").css("display","none");
				
			}
		},
		"order": [[ 0, "desc" ]],
		buttons: {
			dom: {
				button: {
					className: 'btn btn-default'
				}
			},
			buttons: [
				'copyHtml5',
				'excelHtml5',
				'csvHtml5',
				'pdfHtml5'
			]
		},
		orderCellsTop: true,
		fixedHeader: true,
		"bDestroy": true,
		initComplete: function(){
			
		}
	} );
}

function get_lista_inactivos() {
	$.ajax({
		url : '/facturacionv8/gestiondecontribuyentes/get_lista_inactivos',
        method :  'POST',
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			$('#tbl_lista_suscripciones_vencidas').DataTable({
                data: data.lista,
                "bDestroy": true
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

function calcular_precio_sin_igv() {
    var igv_percent = parseFloat((18/100) + 1);
    if($('#valor_con_igv').val() == '' || $('#valor_con_igv').val() <= 0 || isNaN($('#valor_con_igv').val())) {
        precioarticulo = 0;
    } else {
        precioarticulo = parseFloat($('#valor_con_igv').val());
    }

    var precio_sin_igv = round_math(parseFloat(precioarticulo) / parseFloat(igv_percent), 2);
    $("#valor_sin_igv").val(precio_sin_igv);

}

function calcular_precio_con_igv() {
    var igv_percent = 1.18;
    if($('#valor_sin_igv').val() == '' || $('#valor_sin_igv').val() <= 0 || isNaN($('#valor_sin_igv').val())) {
        precioarticulo = 0;
    } else {
        precioarticulo = parseFloat($('#valor_sin_igv').val());
    }

    var precio_sin_igv = round_math(parseFloat(precioarticulo) * parseFloat(igv_percent), 2);
    $("#valor_con_igv").val(precio_sin_igv);

}

function vm_cambiar_fechafin_suscripcion(id_suscripcion, fecha_expira_actual = '') {
	$("#fechaexpira_idsuscripcion").val(id_suscripcion);
	if(fecha_expira_actual != '') {
		$("#fecha_expira_suscripcion").val(fecha_expira_actual);
	}
	$("#vm_cambiar_fecha_expira").modal({
		backdrop: 'static',
		keyboard: false
	});
}

function guardar_fecha_expira() {
	var light = $('#vm_cambiar_fecha_expira');
	$(light).block({
		message: '<div class="loader"></div> <p><br />Guardando...</p>',
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
        url : '/facturacionv8/gestiondecontribuyentes/guardar_fecha_suscripcion',
		method :  'POST',
		data: {id_contribuyente: $("#fechaexpira_idcontribuyente").val(), fecha_expiracion: $("#fecha_expira_suscripcion").val()},
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
						url : '/facturacionv8/gestiondecontribuyentes/guardar_fecha_suscripcion',
						method :  'POST',
						data: {id_contribuyente: $("#fechaexpira_idcontribuyente").val(), fecha_expiracion: $("#fecha_expira_suscripcion").val(), confirmacion: 'si'},
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
									dataTable_docs_contribuyentes.ajax.reload(null, false);
									$("#vm_cambiar_fecha_expira").modal('hide');
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

function cambiar_tipo_certificado(id_contribuyente) {
	$("#tipo_cert_idcontribuyente").val(id_contribuyente);
	$("#vm_cambiar_tipo_certificado").modal({
		backdrop: 'static',
		keyboard: false
	});
}

function guardar_expira_cert() {
	var light = $('#vm_cambiar_tipo_certificado');
	$(light).block({
		message: '<div class="loader"></div> <p><br />Guardando...</p>',
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
	
	//id: f8sjHDGDSAjdh
	$.ajax({
        url : '/facturacionv8/gestiondecontribuyentes/guardar_expira_certificado',
		method :  'POST',
		data: {id_contribuyente: $("#tipo_cert_idcontribuyente").val(), fecha_expiracion: $("#fecha_expira_certificado").val()},
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
						url : '/facturacionv8/gestiondecontribuyentes/guardar_expira_certificado',
						method :  'POST',
						data: {id_contribuyente: $("#tipo_cert_idcontribuyente").val(), fecha_expiracion: $("#fecha_expira_certificado").val(), confirmacion: 'si'},
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
									dataTable_docs_contribuyentes.ajax.reload(null, false);
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

function ver_estadisticas_contribuyente(id_contribuyente) {
	var light = $('#contenido_lista_contribuyentes');
	$(light).block({
		message: '<div class="loader"></div> <p><br />Buscando Datos...</p>',
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
        url : '/facturacionv8/gestiondecontribuyentes/ver_estadisticas_contribuyente',
		method :  'POST',
		data: {id_contribuyente: id_contribuyente},
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			$("#vm_ver_estadisticas").modal("show");
			$("#cantidad_clientes").html('<i class="icon-users4 mr-2"></i>' + data.cantidad_clientes);
			$("#cantidad_productos").html('<i class="icon-cart position-left"></i>' + data.cantidad_productos);
			$("#cantidad_sucursales").html('<i class="icon-store2 position-left"></i>' + data.cantidad_sucursales);
			$("#cantidad_usuarios").html('<i class="icon-users mr-2"></i>' +  data.cantidad_usuarios);
			$("#nombre_plan2").html(data.ultimo_plan_suscripcion.nombre_plan);
			$("#limite_docs").html(data.ultimo_plan_suscripcion.limite_docs);
			$("#fecha_expiracion").html(data.ultimo_plan_suscripcion.fecha_expiracion);
			$("#planes_basexreseller").html(data.lista_planesbase_html);
			//Suma general de docs
			var total_cpe  = 0;
			var total_otros = 0;
			data.numdocs_emitidos.forEach(function(item, key){
		
				total_cpe += item.total_cpe;
				total_otros += item.total_otros;

			});
			//Cargar Select de fechas
			$("#fechas_rango").empty(); //Limpiar cada vez que cargue un nuevo set de fechas y no se acumulen
			$.each(data.numdocs_emitidos, function(id, item) {
				$('#fechas_rango').append('<option data-id=' + id + ' value=' + id + '>' + item.fecha_inicio +' - ' + item.fecha_fin + '</option>');
            });

			//Tomar la Fecha actual seleccionada y mostrarla. 
			var fecha_actual = $("#fechas_rango").val();
			data.numdocs_emitidos.forEach(function(item, key){
				if(key == fecha_actual){
					$("#total_factura").html(item.total_facturas);
					$("#total_boleta").html(item.total_boletas);
					$("#total_nota_credito").html(item.total_notas_credito);
					$("#total_nota_debito").html(item.total_notas_debito);
					$("#total_nota_venta").html(item.total_notas_venta);
					$("#total_docs_enviados_sunat").html(item.total_cpe);
				}
			});
			//Mostrar valores de Cambio de fechas
			$("#fechas_rango").on('change', function (e) {
				var idfecha = this.value;
				data.numdocs_emitidos.forEach(function(item, key){
					if(key == idfecha){
						$("#total_factura").html(item.total_facturas);
						$("#total_boleta").html(item.total_boletas);
						$("#total_nota_credito").html(item.total_notas_credito);
						$("#total_nota_debito").html(item.total_notas_debito);
						$("#total_nota_venta").html(item.total_notas_venta);
						$("#total_cotizacion").html(item.total_cotizaciones);
						$("#total_docs_enviados_sunat").html(item.total_cpe);
					}
				});
			
			});
			
			$("#number_cpe").html(total_cpe);
			$("#number_otros").html(total_otros);
			console.log(data);

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


function editar_fecha_expira(id_contribuyente, fecha_expira = '') {
	$("#fechaexpira_idcontribuyente").val(id_contribuyente);
	$("#vm_cambiar_fecha_expira").modal({
		backdrop: 'static',
		keyboard: false
	});

	if(fecha_expira != '') {
		$("#fecha_expira_suscripcion").val(fecha_expira);
	}
}