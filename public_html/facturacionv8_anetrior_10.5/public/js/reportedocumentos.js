var dataTable_docs_sunat;
var dataTable_notas_de_venta;
var dataTable_cotizaciones;
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
	
	get_lista_comprobantes('', '');
	
	$.jGrowl.defaults.closer = false;
	$('#jgrowl-styled-arrow').on('click', function () {
		var text = $(this).data('html');
		var header = $(this).data('header');
        $.jGrowl(text, {
			position: 'center',
			life: 10000,
            header: header,
            theme: 'bg-success alert-styled-left alert-arrow-left alert-primary'
        });
    });
	
	$(".btn_crear_comunicaciondebaja").click(btn_crear_comunicaciondebaja);
	inicializar_rango_gechas();
});

function inicializar_rango_gechas() {
	$('.daterange-ranges').daterangepicker(
        {
			autoUpdateInput: false,
			drops: "down",
			startDate: moment().subtract(29, 'days'),
			endDate: moment(),
            minDate: moment().subtract(365, 'days'),
            maxDate: moment(),
            dateLimit: { days: 60 },
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
			get_lista_comprobantes(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
            $('.daterange-ranges span').html(start.format('MMMM D') + ' - ' + end.format('MMMM D'));
        }
    );

    $('.daterange-ranges span').html(moment().subtract(29, 'days').format('MMMM D') + ' - ' + moment().format('MMMM D'));
}

function anular_doc_no_oficial(id_tipodocumento, numero_comprobante, idsucursal) {
	var light = $('#vm_content_comunicacionbaja');
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
        url : '/facturacionv8/reportedocumentos/anular_doc_no_oficial',
        method :  'POST',
		dataType : "json",
		data: {id_tipodocumento: id_tipodocumento, numero_comprobante: numero_comprobante, idsucursal: idsucursal}
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
						url : '/facturacionv8/reportedocumentos/anular_doc_no_oficial',
						method :  'POST',
						dataType : "json",
						data: {id_tipodocumento: id_tipodocumento, numero_comprobante: numero_comprobante, idsucursal: idsucursal, confirmacion: 'si'}
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
								dataTable_notas_de_venta.ajax.reload(null, false);
								dataTable_cotizaciones.ajax.reload(null, false);
								dataTable_guiasremision.ajax.reload(null, false);
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

function btn_crear_comunicaciondebaja() {
	var light = $('#vm_content_comunicacionbaja');
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

	var tipodoc = $("#tipodoc").val();
	var serie = $("#serie").val();
	var numero = $("#numero").val();
	var motivo = $("#motivo").val();

	$.ajax({
        url : '/facturacionv8/comunicaciondebaja/crear_comunicacion_baja',
        method :  'POST',
		dataType : "json",
		data: {tipodoc: tipodoc, serie: serie, numero: numero, motivo: motivo}
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
						url : '/facturacionv8/comunicaciondebaja/crear_comunicacion_baja',
						method :  'POST',
						dataType : "json",
						data: {tipodoc: tipodoc, serie: serie, numero: numero, motivo: motivo, confirmacion: 'si'}
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
								dataTable_notas_de_venta.ajax.reload(null, false);
								dataTable_cotizaciones.ajax.reload(null, false);
								dataTable_guiasremision.ajax.reload(null, false);
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

function open_modal_comunicacionbaja(tipodoc, serie, numero) {
	var light = $('#content_listado_documentos');
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
        url : '/facturacionv8/comunicaciondebaja/get_info_documento',
        method :  'POST',
		dataType : "json",
		data: {tipodoc: tipodoc, serie: serie, numero: numero}
    }).then(function(data){

		if(data.respuesta == 'ok') {
			$(light).unblock();
			$("#tipodoc").val(tipodoc);
			$("#serie").val(serie);
			$("#numero").val(numero);
			$("#vm_titulo_comunicacionbaja").html(data.nombre + ': ' + data.documento.serie_comprobante + '-' + data.documento.numero_comprobante);
			$("#vm_comunicacionbaja").modal("show");
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

function descargar_cdr_resumen(ticket, idcontribuyente = 0) {
	var light = $('#content_panel');
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
				dataTable_docs_sunat.ajax.reload(null, false);
				dataTable_notas_de_venta.ajax.reload(null, false);
				dataTable_cotizaciones.ajax.reload(null, false);
				dataTable_guiasremision.ajax.reload(null, false);
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
        url : '/facturacionv8/resumendeboletas/crear_resumen_individual',
        method :  'POST',
		dataType : "json",
		data: {tipodoc: tipodoc, serie: serie, numero: numero, accion: accion}
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
						data: {tipodoc: tipodoc, serie: serie, numero: numero, accion: accion, confirmacion: 'si'}
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
								dataTable_notas_de_venta.ajax.reload(null, false);
								dataTable_cotizaciones.ajax.reload(null, false);
								dataTable_guiasremision.ajax.reload(null, false);
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
									dataTable_docs_sunat.ajax.reload(null, false);
									dataTable_notas_de_venta.ajax.reload(null, false);
									dataTable_cotizaciones.ajax.reload(null, false);
									dataTable_guiasremision.ajax.reload(null, false);
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

function get_lista_comprobantes(fecha_inicio, fecha_fin) {
	
	dataTable_docs_sunat = $('#tbl_lista_documentos').DataTable( {
		"processing": true,
		"serverSide": true,
		"ajax":{
			url :"/facturacionv8/reportedocumentos/get_lista_documentos", // json datasource
			data: {fecha_inicio: fecha_inicio, fecha_fin: fecha_fin},
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
		orderCellsTop: true,
		fixedHeader: true,
		"bDestroy": true,
		initComplete: function(){
			$("#tbl_lista_documentos_wrapper").children("div.datatable-header").children("div.dt-buttons").html($("#button_actions_crear_comprobantes").html());
		}
	} );

	dataTable_notas_de_venta = $('#tbl_lista_notas_venta').DataTable( {
		"processing": true,
		"serverSide": true,
		"ajax":{
			url :"/facturacionv8/reportedocumentos/get_notas_de_venta", // json datasource
			data: {fecha_inicio: fecha_inicio, fecha_fin: fecha_fin},
			type: "post",
			error: function(){
				$(".tbl_lista_notas_venta-error").html("");
				$("#tbl_lista_notas_venta").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
				$("#tbl_lista_notas_venta_processing").css("display","none");
				
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
			$("#tbl_lista_notas_venta_wrapper").children("div.datatable-header").children("div.dt-buttons").html("<a class='btn bg-indigo legitRipple' href='/facturacionv8/documentoelectronico/index/77/nuevo'><i class='fa fa-plus'></i> Crear Nota de Venta </a>");
		}
	} );

	dataTable_cotizaciones = $('#tbl_lista_cotizaciones').DataTable( {
		"processing": true,
		"serverSide": true,
		"ajax":{
			url :"/facturacionv8/reportedocumentos/get_cotizaciones", // json datasource
			data: {fecha_inicio: fecha_inicio, fecha_fin: fecha_fin},
			type: "post",
			error: function(){
				$(".tbl_lista_cotizaciones-error").html("");
				$("#tbl_lista_cotizaciones").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
				$("#tbl_lista_cotizaciones_processing").css("display","none");
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
			$("#tbl_lista_cotizaciones_wrapper").children("div.datatable-header").children("div.dt-buttons").html("<a class='btn bg-indigo legitRipple' href='/facturacionv8/documentoelectronico/index/88/nuevo'><i class='fa fa-plus'></i> Crear Cotización </a>");
		}
	} );

	dataTable_guiasremision = $('#tbl_lista_guiasremision').DataTable( {
		"processing": true,
		"serverSide": true,
		"ajax":{
			url :"/facturacionv8/reportedocumentos/get_lista_guias_remision", // json datasource
			data: {fecha_inicio: fecha_inicio, fecha_fin: fecha_fin},
			type: "post",
			error: function(){
				$(".tbl_lista_guiasremision-error").html("");
				$("#tbl_lista_guiasremision").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
				$("#tbl_lista_guiasremision_processing").css("display","none");
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
			$("#tbl_lista_guiasremision_wrapper").children("div.datatable-header").children("div.dt-buttons").html("<a class='btn bg-indigo legitRipple' href='/facturacionv8/guiaderemision'><i class='fa fa-plus'></i> Crear Guía Remisión </a>");
		}
	} );
}