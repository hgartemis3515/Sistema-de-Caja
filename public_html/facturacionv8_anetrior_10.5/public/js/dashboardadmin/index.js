var dataTable_docs_sunat;
var dataTable_guiasremision;

$(function() {
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

	$(".control_fecha").daterangepicker({singleDatePicker: true, locale: { format: 'DD/MM/YYYY' }});

	$("#rangofechas").daterangepicker({
		singleDatePicker: false,
		startDate: moment().subtract(60, 'days'),
		endDate: moment().subtract(5, 'days'),
		minDate: moment().subtract(365, 'days'),
		maxDate: moment(),
		locale: {
			format: 'DD/MM/YYYY',
			daysOfWeek: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ],
			monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
			],
			monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul', 'Ago','Sep','Oct','Nov','Dic'],
			firstDay: 1
		}
	}, function(start, end) {
			$("#fechainicio").val(start.format('YYYY-MM-DD'));
			$("#fechafinal").val(end.format('YYYY-MM-DD'));
			let fecha_inicio = $("#fechainicio").val();
			let fecha_fin = $("#fechafinal").val();
			let id_contribuyente = $("#select_contribuyente").val();
			let estado_documento = $("#select_estado_comprobante").val();
			let tipo_documento = $("#select_tipo_comprobante").val();
			get_lista_comprobantes(fecha_inicio, fecha_fin, id_contribuyente, estado_documento, tipo_documento);
		}
	);
	
	var switches = Array.prototype.slice.call(document.querySelectorAll('.switch2'));
    switches.forEach(function(html) {
        var switchery = new Switchery(html, {color: '#4CAF50'});
	});
	
	$(".opc_controles_avanzados").show("slide");

	$("#btn_enviar_email").click(enviar_cpe_cliente);

	$(".btn_guardar_nuevo_ticket").click(function(){
		let id_contribuyente = $("#vestado_id_contribuyente").val();
		let id_tipodoc_electronico = $("#vestado_tipo_doc_electronico").val();
		let serie_comprobante = $("#vestado_serie_comprobante").val();
		let numero_comprobante = $("#vestado_numero_comprobante").val();
		let num_ticket = $("#txt_numero_ticket").val();
		let accion = 'actualizar_ticket';

		modificar_comprobante(id_contribuyente, id_tipodoc_electronico, serie_comprobante, numero_comprobante, accion, num_ticket);
	});

	$("#btn_resetear_documento").click(function(){
		let id_contribuyente = $("#vestado_id_contribuyente").val();
		let id_tipodoc_electronico = $("#vestado_tipo_doc_electronico").val();
		let serie_comprobante = $("#vestado_serie_comprobante").val();
		let numero_comprobante = $("#vestado_numero_comprobante").val();
		let accion = 'resetear';
		
		modificar_comprobante(id_contribuyente, id_tipodoc_electronico, serie_comprobante, numero_comprobante, accion);
	});

	$("#btn_recuperar_cdr").click(function(){
		let id_contribuyente = $("#vestado_id_contribuyente").val();
		let id_tipodoc_electronico = $("#vestado_tipo_doc_electronico").val();
		let serie_comprobante = $("#vestado_serie_comprobante").val();
		let numero_comprobante = $("#vestado_numero_comprobante").val();
		let accion = 'recover_cdr';
		
		modificar_comprobante(id_contribuyente, id_tipodoc_electronico, serie_comprobante, numero_comprobante, accion);
	});
	
	$("#btn_aprobar_manualmente").click(function(){
		let id_contribuyente = $("#vestado_id_contribuyente").val();
		let id_tipodoc_electronico = $("#vestado_tipo_doc_electronico").val();
		let serie_comprobante = $("#vestado_serie_comprobante").val();
		let numero_comprobante = $("#vestado_numero_comprobante").val();
		let accion = 'aprobar_manualmente';
		
		modificar_comprobante(id_contribuyente, id_tipodoc_electronico, serie_comprobante, numero_comprobante, accion);
	});

	$("#select_contribuyente").select2();
	$('#select_estado_comprobante').select2({ minimumResultsForSearch: -1 });
	$('#select_tipo_comprobante').select2({ minimumResultsForSearch: -1 });

	$(".select_criterios").on('change', function() {
		let fecha_inicio = $("#fechainicio").val();
		let fecha_fin = $("#fechafinal").val();
		let id_contribuyente = $("#select_contribuyente").val();
		let estado_documento = $("#select_estado_comprobante").val();
		let tipo_documento = $("#select_tipo_comprobante").val();
		get_lista_comprobantes(fecha_inicio, fecha_fin, id_contribuyente, estado_documento, tipo_documento);
	});
	
	$("#fechainicio").val(moment().subtract(60, 'days').format('YYYY-MM-DD'));
	$("#fechafinal").val(moment().subtract(5, 'days').format('YYYY-MM-DD'));
	let fecha_inicio = $("#fechainicio").val();
	let fecha_fin = $("#fechafinal").val();

	get_lista_comprobantes(fecha_inicio, fecha_fin, '0', 'pendiente', '01');
});

function get_lista_comprobantes(fecha_inicio, fecha_fin, id_contribuyente = '0', estado_documento = 'todos', tipo_documento = 'todos') {
	
	dataTable_docs_sunat = $('#tbl_lista_documentos').DataTable( {
		"processing": true,
		"serverSide": true,
		"ajax":{
			url :"/facturacionv8/dashboardadmin/get_lista_docs_sunat", // json datasource
			data: {fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, id_contribuyente: id_contribuyente, estado_documento: estado_documento, tipo_documento: tipo_documento},
			type: "post",
			error: function(){
				$(".tbl_lista_documentos-error").html("");
				$("#tbl_lista_documentos").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
				$("#tbl_lista_documentos_processing").css("display","none");
				
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
		"columns": [
			{ "data": "fecha_registro" },
			{ "data": "comprobante" },
			{ "data": "cliente" },
			{ "data": "total" },
			{ "data": "pdf" },
			{ "data": "xml" },
			{ "data": "cdr" },
			{ "data": "sunat" },
			{ "data": "opciones" },
		],
		"lengthMenu": [ 10, 5, 1, 25, 50, 75, 100, 200, 300, 500 ],
		orderCellsTop: true,
		fixedHeader: true,
		"bDestroy": true,
		initComplete: function(){
			$("#tbl_lista_documentos_wrapper").children("div.datatable-header").children("div.dt-buttons").html($("#button_actions_crear_comprobantes").html());
		}
	} );
}

function descargar_cdr_resumen(ticket, idcontribuyente = 0) {
	var light = $('#content_lista_docs_general');
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
		url : '/facturacionv8/resumendeboletas/consultar_ticket',
		data: {numero_ticket: ticket, idcontribuyente: idcontribuyente},
        method :  'POST',
        dataType : "json"
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
				//window.location.reload();
				dataTable_docs_sunat.ajax.reload(null, false);
				$(light).unblock();
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

function enviar_resumen_individual_sunat(id_contribuyente, tipodoc, serie, numero, accion) {
    var light = $('#content_listado_documentos');
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
        url : '/facturacionv8/resumendeboletas/crear_resumen_individual',
        method :  'POST',
		dataType : "json",
		data: {id_contribuyente: id_contribuyente, tipodoc: tipodoc, serie: serie, numero: numero, accion: accion}
    }).then(function(data){

		if(data.respuesta == 'ok') {
			swal({   
				title: data.titulo,   
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
						url : '/facturacionv8/resumendeboletas/crear_resumen_individual',
						method :  'POST',
						dataType : "json",
						data: {id_contribuyente: id_contribuyente, tipodoc: tipodoc, serie: serie, numero: numero, accion: accion, confirmacion: 'si'}
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
								//window.location.reload();
								dataTable_docs_sunat.ajax.reload(null, false);
								$(light).unblock();
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

function enviar_documento_sunat(id_contribuyente, id_tipodoc_electronico, serie_comprobante, numero_comprobante, modo) {
	var light = $('#content_listado_documentos');
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
        url : '/facturacionv8/reportedocumentos/enviar_documento_sunat',
		method :  'POST',
		data: {id_contribuyente: id_contribuyente, id_tipodoc_electronico: id_tipodoc_electronico, serie_comprobante: serie_comprobante, numero_comprobante: numero_comprobante, modo: modo},
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			swal({   
				title: data.titulo,   
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
						url : '/facturacionv8/reportedocumentos/enviar_documento_sunat',
						method :  'POST',
						data: {id_contribuyente: id_contribuyente, id_tipodoc_electronico: id_tipodoc_electronico, serie_comprobante: serie_comprobante, numero_comprobante: numero_comprobante, modo: modo, confirmacion: 'si'},
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
									confirmButtonText: "OK"
								}, function(){
									//window.location.reload();
									dataTable_docs_sunat.ajax.reload(null, false);
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

function enviar_email_cpe(id_contribuyente, tipo_doc, serie_doc, numero_comprobante, email) {
	$("#email_idcontribuyente").val(id_contribuyente);
	$("#email_tipo_doc").val(tipo_doc);
	$("#email_serie_doc").val(serie_doc);
	$("#email_numero_comprobante").val(numero_comprobante);
	$("#email_cliente").val(email);

	$("#vm_enviar_email").modal('show');
}

function enviar_cpe_cliente() {
	var light = $("#content_enviar_email_vm");

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

	let id_contribuyente = $("#email_idcontribuyente").val();
	let tipo_doc = $("#email_tipo_doc").val();
	let serie_doc = $("#email_serie_doc").val();
	let numero_comprobante = $("#email_numero_comprobante").val();
	let email= $("#email_cliente").val();

	$.ajax({
		url : '/facturacionv8/herramientas/enviar_cpe_email',
		method :  'POST',
		dataType : "json",
		data: {id_contribuyente: id_contribuyente, tipo_doc: tipo_doc, serie_doc: serie_doc, numero_comprobante: numero_comprobante, email: email}
	}).then(function(data){
			if(data.respuesta == 'ok') {
				swal({
					title: data.titulo,
					text: data.mensaje,
					html: true,
					type: "success",
					confirmButtonText: "Ok",
					confirmButtonColor: "#2196F3"
				}, function(){
					$("#email_idcontribuyente").val('');
					$("#email_tipo_doc").val('');
					$("#email_serie_doc").val('');
					$("#email_numero_comprobante").val('');
					$("#email_cliente").val('');

					$("#vm_enviar_email").modal('hide');
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

function modificar_comprobante(id_contribuyente, id_tipodoc_electronico, serie_comprobante, numero_comprobante, accion, num_ticket) {
	var light = $("#vm_verificar_estado_sunat");

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
        url : '/facturacionv8/dashboardadmin/modificar_docelectronico',
        method :  'POST',
		dataType : "json",
		data: {id_contribuyente: id_contribuyente, id_tipodoc_electronico: id_tipodoc_electronico, serie_comprobante: serie_comprobante, numero_comprobante: numero_comprobante, accion: accion, num_ticket: num_ticket}
    }).then(function(data){

		if(data.respuesta == 'ok') {
			swal({   
				title: data.titulo,   
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
						url : '/facturacionv8/dashboardadmin/modificar_docelectronico',
						method :  'POST',
						dataType : "json",
						data: {id_contribuyente: id_contribuyente, id_tipodoc_electronico: id_tipodoc_electronico, serie_comprobante: serie_comprobante, numero_comprobante: numero_comprobante, accion: accion, num_ticket: num_ticket, confirmacion: 'si'}
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
								dataTable_docs_sunat.ajax.reload(null, false);
								$("#vm_verificar_estado_sunat").modal("hide");
								$(light).unblock();
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

function ver_estado_documento_sunat(id_contribuyente, id_tipodoc_electronico, serie_comprobante, numero_comprobante) {
	$("#vestado_id_contribuyente").val(id_contribuyente);
	$("#vestado_tipo_doc_electronico").val(id_tipodoc_electronico);
	$("#vestado_serie_comprobante").val(serie_comprobante);
	$("#vestado_numero_comprobante").val(numero_comprobante);

	$("#estado_num_doc_22").html(serie_comprobante + '-' + numero_comprobante);

	var light = $("#contenido_documentos_totales");

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
		url : '/facturacionv8/dashboardadmin/verificar_estado_sunat',
		method :  'POST',
		dataType : "json",
		data: {id_contribuyente: id_contribuyente, id_tipodoc_electronico: id_tipodoc_electronico, serie_comprobante: serie_comprobante, numero_comprobante: numero_comprobante}
	}).then(function(data){
			if(data.respuesta == 'ok') {
				
				$("#btn_resetear_documento").show();
				$("#btn_aprobar_manualmente").show();
				$("#contenedor_numero_ticket").show();
				$("#btn_recuperar_cdr").show();

				$("#contenido_respuesta_sunat").html(data.html);
				$("#vm_verificar_estado_sunat").modal("show");
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

function modificar_condicion_pago(id_contribuyente, id_tipodoc_electronico, serie_comprobante, numero_comprobante, fecha_pagopendiente, tipo_condicion, monto_pendiente, moneda) {
	var simbolo_moneda = 'S/';
	if(moneda == 'USD') {
		simbolo_moneda = 'USD $';
	}

	$(".simbolo_moneda").html(simbolo_moneda);

	if(id_tipodoc_electronico == '77') {
		var mensaje = 'La Nota de Venta N°: ' + numero_comprobante + ' tiene un pago pendiente de ' + simbolo_moneda + ' ' + monto_pendiente + ', el cuál se debe realizar como máximo el día: '+ fecha_pagopendiente +'. A continuación Ingresa el total de dinero abonado y luego haz click en Guardar.';
	} else {
		var mensaje = 'El Documento electrónico: ' + serie_comprobante + '-' + numero_comprobante + ' tiene un pago pendiente de ' + simbolo_moneda + ' ' + monto_pendiente + ', el cuál se debe realizar como máximo el día: '+ fecha_pagopendiente +'. A continuación Ingresa el total de dinero abonado y luego haz click en Guardar.';
	}

	$("#vm_condicionpago_idcontribuyente").val(id_contribuyente);
	$("#vm_condicionpago_tipo_doc").val(id_tipodoc_electronico);
	$("#vm_condicionpago_serie_doc").val(serie_comprobante);
	$("#vm_condicionpago_numero_comprobante").val(numero_comprobante);
	$("#monto_adeudado").val(monto_pendiente);
	$("#vm_condicionpago_id_moneda").val(moneda);
	$("#monto_a_pagar").val(0);
	
	$("#mensaje_condicionpago").html(mensaje);
	$("#vm_modificar_condicionpago").modal('show');
}

function procesar_comprobantes() {
	
	$("#content_lista_procesados").show('slide');
	var arr = [];
	var i = 0;
	$("#tbl_lista_documentos tr").each(function() {
		let id_row = this.id;
		let opcion_estado_doc = $("#select_estado_comprobante").val();
		setTimeout(function() {
			if(id_row != '') {
				arr.push(id_row);
				let codigo	 = id_row.split('||');
				if(opcion_estado_doc == 'xml') {
					if(codigo[5] != 'pendiente') {
						$.ajax({
							url : '/facturacionv8/reportedocumentos/enviar_documento_sunat',
							method :  'POST',
							data: {id_contribuyente: codigo[0], id_tipodoc_electronico: codigo[1], serie_comprobante: codigo[2], numero_comprobante: codigo[3], modo: 'solo_firma', confirmacion: 'si'},
							dataType : "json",
							success: function(data3) {
								let total_procesados = parseInt($("#total_procesados").html()) + 1;
								$("#total_procesados").html(total_procesados);
								$("#lista_procesados").html( $("#lista_procesados").html() + codigo[0] + ': ' + codigo[2] + '-' + codigo[3] + ': ' + data3.mensaje + '<br />');
							}
						});	
					}
				} else {
					if(codigo[5] != 'aprobado' && codigo[5] != 'anulado') {
						//facturas y sus notas de crédito y débito
						if(codigo[1] == '01' || (codigo[1] == '07' && codigo[2].charAt(0) == 'F') || (codigo[1] == '08' && codigo[2].charAt(0) == 'F')) {
							//Factura
							$.ajax({
								url : '/facturacionv8/dashboardadmin/verificar_estado_sunat',
								method :  'POST',
								cache:false,
								dataType : "json",
								data: {id_contribuyente: codigo[0], id_tipodoc_electronico: codigo[1], serie_comprobante: codigo[2], numero_comprobante: codigo[3]},
								success: function(data) {
									if(data.resp_api.data.estadoCp == '0') {
										//documentos con estado pendiente
										//enviar_documento_sunat(1735, "01", "F001", 51, "inmediato")
										$.ajax({
											url : '/facturacionv8/reportedocumentos/enviar_documento_sunat',
											method :  'POST',
											data: {id_contribuyente: codigo[0], id_tipodoc_electronico: codigo[1], serie_comprobante: codigo[2], numero_comprobante: codigo[3], modo: 'inmediato', confirmacion: 'si'},
											dataType : "json",
											success: function(data2) {
												let total_procesados = parseInt($("#total_procesados").html()) + 1;
												$("#total_procesados").html(total_procesados);
												$("#lista_procesados").html( $("#lista_procesados").html() + codigo[0] + ': ' + codigo[2] + '-' + codigo[3] + ': ' + data2.mensaje + '<br />');
											}
										});				
									} else if(data.resp_api.data.estadoCp == '1') {
										//Se muestra como enviado en SUNAT, entonces se debe extrar el CDR de la factura
										let accion = 'recover_cdr';
										//modificar_comprobante(id_contribuyente, id_tipodoc_electronico, serie_comprobante, numero_comprobante, accion);
										$.ajax({
											url : '/facturacionv8/dashboardadmin/modificar_docelectronico',
											method :  'POST',
											dataType : "json",
											data: {id_contribuyente: codigo[0], id_tipodoc_electronico: codigo[1], serie_comprobante: codigo[2], numero_comprobante: codigo[3], accion: accion, num_ticket: '', confirmacion: 'si'}
										}).then(function(data6){
											if(data6.titulo == 'error_sin_xml') {
												$.ajax({
													url : '/facturacionv8/reportedocumentos/enviar_documento_sunat',
													method :  'POST',
													data: {id_contribuyente: codigo[0], id_tipodoc_electronico: codigo[1], serie_comprobante: codigo[2], numero_comprobante: codigo[3], modo: 'solo_firma', confirmacion: 'si'},
													dataType : "json",
													success: function(data7) {
														let total_procesados = parseInt($("#total_procesados").html()) + 1;
														$("#total_procesados").html(total_procesados);
														$("#lista_procesados").html( $("#lista_procesados").html() + codigo[0] + ': ' + codigo[2] + '-' + codigo[3] + ': ' + data7.mensaje + '<br />');
													}
												});	
											} else {
												let total_procesados = parseInt($("#total_procesados").html()) + 1;
												$("#total_procesados").html(total_procesados);
												$("#lista_procesados").html( $("#lista_procesados").html() + codigo[0] + ': ' + codigo[2] + '-' + codigo[3] + ': ' + data6.mensaje + '<br />');
											}
										});
									}
										
								}, error: function() {
									swal({
										title: 'Error',
										text: 'No podemos continuar porque el ID: ' + id_row + ' ha dato un error! ',
										html: true,
										type: "error",
										confirmButtonText: "Ok",
										confirmButtonColor: "#2196F3"
									}, function(){
										
									})
								}
							});
						}
	 
						//boletas y sus notas de crédito o débito
						if(codigo[1] == '03' || (codigo[1] == '07' && codigo[2].charAt(0) == 'B') || (codigo[1] == '08' && codigo[2].charAt(0) == 'B')) {
							//BOLETA
							$.ajax({
								url : '/facturacionv8/dashboardadmin/verificar_estado_sunat',
								method :  'POST',
								cache:false,
								dataType : "json",
								data: {id_contribuyente: codigo[0], id_tipodoc_electronico: codigo[1], serie_comprobante: codigo[2], numero_comprobante: codigo[3]},
								success: function(data) {
									if(data.resp_api.data.estadoCp == '0') {
										if(codigo[5] == 'ticket') {
											let accion_2 = 'resetear';
											$.ajax({
												url : '/facturacionv8/dashboardadmin/modificar_docelectronico',
												method :  'POST',
												dataType : "json",
												data: {id_contribuyente: codigo[0], id_tipodoc_electronico: codigo[1], serie_comprobante: codigo[2], numero_comprobante: codigo[3], accion: accion_2, num_ticket: '', confirmacion: 'si'}
											}).then(function(data){
												$.ajax({
													url : '/facturacionv8/resumendeboletas/crear_resumen_individual',
													method :  'POST',
													dataType : "json",
													data: {id_contribuyente: codigo[0], tipodoc: codigo[1], serie: codigo[2], numero: codigo[3], accion: 1, confirmacion: 'si'}
												}).then(function(data4){
													let total_procesados = parseInt($("#total_procesados").html()) + 1;
													$("#total_procesados").html(total_procesados);
													$("#lista_procesados").html( $("#lista_procesados").html() + codigo[0] + ': ' + codigo[2] + '-' + codigo[3] + '    =>  ' + data4.mensaje + '<br />');
												});
											});
											
										} else if(codigo[5] == 'pendiente') {
											$.ajax({
												url : '/facturacionv8/resumendeboletas/crear_resumen_individual',
												method :  'POST',
												dataType : "json",
												data: {id_contribuyente: codigo[0], tipodoc: codigo[1], serie: codigo[2], numero: codigo[3], accion: 1, confirmacion: 'si'}
											}).then(function(data3){
												let total_procesados = parseInt($("#total_procesados").html()) + 1;
												$("#total_procesados").html(total_procesados);
												$("#lista_procesados").html( $("#lista_procesados").html() + codigo[0] + ': ' + codigo[2] + '-' + codigo[3] + '    =>  ' + data3.mensaje + '<br />');
											});
										}
									} else if(data.resp_api.data.estadoCp == '1') {
										if(codigo[5] == 'ticket') {
											$.ajax({
												url : '/facturacionv8/resumendeboletas/consultar_ticket',
												data: {numero_ticket: codigo[6], idcontribuyente: codigo[0]},
												method :  'POST',
												dataType : "json"
											}).then(function(data2){
												if(data2.resp_api_ws.cod_sunat == 'soap-env:Client.0127') {
													//El error de Ticket No Existe!
													$.ajax({
														url : '/facturacionv8/resumendeboletas/crear_resumen_individual',
														method :  'POST',
														dataType : "json",
														data: {id_contribuyente: codigo[0], tipodoc: codigo[1], serie: codigo[2], numero: codigo[3], accion: 2, confirmacion: 'si'}
													}).then(function(data3){
														let total_procesados = parseInt($("#total_procesados").html()) + 1;
														$("#total_procesados").html(total_procesados);
														$("#lista_procesados").html( $("#lista_procesados").html() + codigo[0] + ': ' + codigo[2] + '-' + codigo[3] + '    =>  ' + data3.mensaje + '<br />');
													});
												}
											});
										}
									}
	
								}, error: function() {
									swal({
										title: 'Error',
										text: 'No podemos continuar porque el ID: ' + id_row + ' ha dato un error! ',
										html: true,
										type: "error",
										confirmButtonText: "Ok",
										confirmButtonColor: "#2196F3"
									}, function(){
										
									})
								}
							})
						}
						
					}
				}
				
			}
		}, 6000 * i); 
		i++;
	});
	
}

function sleep(milliseconds) {
	var start = new Date().getTime();
	for (var i = 0; i < 1e7; i++) {
		if ((new Date().getTime() - start) > milliseconds) {
			break;
		}
	}
}

function refrescar_tabla(){
	dataTable_docs_sunat.ajax.reload(null, false);
}