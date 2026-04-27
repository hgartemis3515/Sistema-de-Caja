$(function() {
    $("#fecha_deposito").daterangepicker({singleDatePicker: true, locale: { format: 'DD/MM/YYYY' }});
    $("#nueva_fecha_pago").daterangepicker({singleDatePicker: true, locale: { format: 'DD/MM/YYYY' }});

    $(".monto_a_pagar").on('input', function() {
        calcular_monto_pendiente_pago();
    }).on('change', function() {
        calcular_monto_pendiente_pago();
	});

    $("#btn_guardar_abono").click(guardar_abono);

    $('#select_cuenta_banco_deposito').select2({ minimumResultsForSearch: -1 });
	$('#condicionpago_abono').select2({ minimumResultsForSearch: -1 });
	$("#condicionpago_abono").on('change', function(){
        var tipo_condicionpago = $(this).find(':selected').data('tipocondicion');
        if(!$('#condicionpago_abono').val()) {
			$("#content_numero_operacion").hide();
			$("#content_fecha_deposito").hide();
			$("#content_cuenta_banco_deposito").hide();
        } else {
            if(tipo_condicionpago == 'contado') {
				$("#content_numero_operacion").hide();
				$("#content_fecha_deposito").hide();
				$("#content_cuenta_banco_deposito").hide();
            } else if(tipo_condicionpago == 'credito') {
				$("#content_fecha_deposito").hide();
				$("#content_cuenta_banco_deposito").hide();
				$("#content_numero_operacion").removeClass();
				$("#content_numero_operacion").addClass("col-md-12");
				$("#content_numero_operacion").show();
            } else {
				$("#content_numero_operacion").removeClass();
				$("#content_numero_operacion").addClass("col-md-4");
				$("#content_numero_operacion").show();
				$("#content_fecha_deposito").show();
				$("#content_cuenta_banco_deposito").show();
            }
        }
	});
	$("#condicionpago_abono").trigger('change');

	$("#btn_cerrar_data_abono").click(function() {
		$("#content_data_abono").hide();
		$("#content_lista_abono").show('slide');

		limpiar_vm_abono();
	});

});

function ver_crear_abonos(id_contribuyente, id_tipodoc_electronico, serie_comprobante, numero_comprobante, fecha_pagopendiente, tipo_condicion, monto_pendiente, moneda, opt_agregar_abono = 'si') {
	var simbolo_moneda = 'S/';
	if(moneda == 'USD') {
		simbolo_moneda = 'USD $';
	}

	$(".simbolo_moneda").html(simbolo_moneda);
	
	if(id_tipodoc_electronico == '77') {
		var mensaje = 'La Nota de Venta N°: ' + numero_comprobante + ' tiene una deuda total de ' + simbolo_moneda + ' ' + monto_pendiente;
	} else {
		var mensaje; 
		mensaje = 'El Documento electrónico: ' + serie_comprobante + '-' + numero_comprobante + ' tiene una deuda total de ' + simbolo_moneda + ' ' + monto_pendiente;
		if(opt_agregar_abono == 'no') {
			mensaje = 'El Documento electrónico: ' + serie_comprobante + '-' + numero_comprobante + ' tiene una deuda total de ' + simbolo_moneda + ' ' + monto_pendiente + ', <strong class="text-primary"> y dado que el monto adeudado y las cuotas fueron informadas a SUNAT no se debe y tampoco se puede cambiar la fecha de las cuotas y/o montos adeudados.</strong>';
		}
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

	//limpiar_vm_abono();
	$("#content_data_abono").hide();
	$("#content_lista_abono").show();

	get_lista_abonos(id_contribuyente, id_tipodoc_electronico, serie_comprobante, numero_comprobante, opt_agregar_abono);
}

function guardar_abono() {
	var light = $('#content_data_abono');
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

	var idcontribuyente 		= $("#vm_condicionpago_idcontribuyente").val();
	var tipo_doc 				= $("#vm_condicionpago_tipo_doc").val();
	var serie_doc 				= $("#vm_condicionpago_serie_doc").val();
	var numero_comprobante 		= $("#vm_condicionpago_numero_comprobante").val();
	var monto_pagado 			= $("#monto_a_pagar").val();
	var condicionpago_abono 	= $("#condicionpago_abono").val();
	var txt_numero_operacion 	= $("#txt_numero_operacion").val();
	var txt_observacion_abono 	= $("#txt_observacion_abono").val();
	var fecha_deposito 			= $("#fecha_deposito").val();
	var cuenta_banco_deposito 	= $("#select_cuenta_banco_deposito").val();
	var id_abono 				= $("#vm_condicionpago_idabono").val();
	
	var fecha_proximo_pago 		= $("#nueva_fecha_pago").val(); //posible para eliminar
	
	$.ajax({
        url : '/facturacionv8/cuentasporcobrar/guardar_abono',
		method :  'POST',
		data: {id_abono: id_abono, idcontribuyente: idcontribuyente, tipo_doc: tipo_doc, serie_doc: serie_doc, numero_comprobante: numero_comprobante, monto_pagado: monto_pagado, fecha_proximo_pago: fecha_proximo_pago, condicionpago_abono: condicionpago_abono, txt_numero_operacion: txt_numero_operacion, txt_observacion_abono: txt_observacion_abono, fecha_deposito: fecha_deposito, cuenta_banco_deposito: cuenta_banco_deposito},
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
						url : '/facturacionv8/cuentasporcobrar/guardar_abono',
						method :  'POST',
						data: {id_abono: id_abono, idcontribuyente: idcontribuyente, tipo_doc: tipo_doc, serie_doc: serie_doc, numero_comprobante: numero_comprobante, monto_pagado: monto_pagado, fecha_proximo_pago: fecha_proximo_pago, condicionpago_abono: condicionpago_abono, txt_numero_operacion: txt_numero_operacion, txt_observacion_abono: txt_observacion_abono, fecha_deposito: fecha_deposito, cuenta_banco_deposito: cuenta_banco_deposito, confirmacion: 'si'},
						dataType : "json"
					}).then(
						function(data) {
							if(data.respuesta == 'ok') {

								swal({   
									title: data.titulo,   
									text: data.mensaje + '<br /><br />' + '<a title="Formato Ticket" target="_blank" href="/facturacionv8/download/ticket_abono/' + data.id_montocobrado +'"><img src="/facturacionv8/img/svg/ticket_cpe.svg" style="max-width: 40px;"></a>',
									html: true,
									type: "success",   
									confirmButtonColor: "#563d7c",
									confirmButtonText: "Ok"
								}, function(){  
									$("#vm_modificar_condicionpago").modal('hide');
									
									if (typeof dataTable_docs_sunat !== 'undefined') {
										dataTable_docs_sunat.ajax.reload(null, false);
									}
									
									if (typeof dataTable_docs_sunat !== 'undefined') {
										dataTable_notas_de_venta.ajax.reload(null, false);
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

function calcular_monto_pendiente_pago() {
	var monto_adeudado = parseFloat($("#monto_adeudado").val());
	var monto_a_pagar = parseFloat($('#monto_a_pagar').val());
	var moneda = $("#vm_condicionpago_id_moneda").val();
	var simbolo_moneda = 'S/';
	if(moneda == 'USD') {
		simbolo_moneda = 'USD $';
	}

	var mensaje = '';
	if($('#monto_a_pagar').val() == '' || $('#monto_a_pagar').val() < 0 || isNaN($('#monto_a_pagar').val())) {
		$("#content_resultado").hide('slide');
		$("#fecha_proximo_pago").hide();
    } else {
        if(monto_adeudado - monto_a_pagar < 0) {
			//Usted está pagando de más, mostrar un vuelto
			mensaje = '<div class="alert alert-primary alert-styled-left alert-bordered">\
			Usted debe entregar un Vuelto de '+ simbolo_moneda +' ' + Math.abs(monto_adeudado - monto_a_pagar) + ', haga click en GUARDAR para anular la deuda pendiente\
			</div>';
			$("#fecha_proximo_pago").hide();
		} else if (monto_adeudado - monto_a_pagar == 0) {
			//El documento pasará a un estado de pagado
			mensaje = '<div class="alert alert-success alert-styled-left alert-bordered">\
			Excelente, haga click en GUARDAR para anular el total de la deuda pendiente\
			</div>';
			$("#fecha_proximo_pago").hide();
		} else if (monto_adeudado - monto_a_pagar > 0) {
			//aún quedará un saldo pendiente
			mensaje = '<div class="alert alert-danger alert-styled-left alert-bordered">\
			Aún quedará un saldo pendiente de pago que asciende a: '+ simbolo_moneda +' ' + (monto_adeudado - monto_a_pagar) + ', debes seleccionar la fecha en la que se realizará el pago final, luego haz click en GUARDAR\
			</div>';
			$("#fecha_proximo_pago").show('slide');
		} else {
			mensaje = '';
			$("#fecha_proximo_pago").hide();
		}

		$("#content_resultado").html(mensaje);
		$("#content_resultado").show('slide');
    }
}

function get_lista_abonos(id_contribuyente, id_tipodoc_electronico, serie_comprobante, numero_comprobante, opt_agregar_abono = 'si') {
	var light = $("#tbl_lista_abonos");

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

	var class_opt_add_abono = (opt_agregar_abono == 'si')?'':'hide_opt';

	$("#vm_lista_abonos_idcontribuyente").val(id_contribuyente);
	$("#vm_lista_abonos_tipodoc").val(id_tipodoc_electronico);
	$("#vm_lista_abonos_serie_doc").val(serie_comprobante);
	$("#vm_lista_abonos_num_doc").val(numero_comprobante);
	$("#vm_lista_abonos_opt_cuotas").val(opt_agregar_abono);

	$.ajax({
        url : '/facturacionv8/cuentasporcobrar/get_lista_abonos',
        method :  'POST',
		dataType : "json",
		data: {id_contribuyente: id_contribuyente, id_tipodoc_electronico: id_tipodoc_electronico, serie_comprobante: serie_comprobante, numero_comprobante: numero_comprobante}
    }).then(function(data){
		if(data.respuesta == 'ok') {

			$('#tbl_lista_abonos').DataTable({
				data: data.lista,
				"bDestroy": true,
				"order": [[ 0, "desc" ]],
				buttons: [
					{
						text: '<i class="icon-plus-circle2 position-left"></i> Agregar Abonos</span>',
						className: 'btn bg-info ' + class_opt_add_abono,
						action: function ( e, dt, node, config ) {
							$("#content_lista_abono").hide();
							$("#content_data_abono").show('slide');
						}
					}
				],
				initComplete: function(){
					$('[data-popup="popover"]').popover();
				}
			});
			
			$("#mensaje_lista_abonos").html(data.html_nota_lista);
			$("#vm_lista_abonos").modal('show');
			$(light).unblock();
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

function eliminar_abono(id_contribuyente, id_tipodoc_electronico, serie_comprobante, numero_comprobante, id_abono, monto_cuota, fecha_pago_cuota, opt_informado_sunat) {
	var light = $('#content_lista_abono');
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
        url : '/facturacionv8/cuentasporcobrar/eliminar_abono',
		method :  'POST',
		data: {id_abono: id_abono, idcontribuyente: id_contribuyente, tipo_doc: id_tipodoc_electronico, serie_doc: serie_comprobante, numero_comprobante: numero_comprobante, monto_pagado: monto_cuota},
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
						url : '/facturacionv8/cuentasporcobrar/eliminar_abono',
						method :  'POST',
						data: {id_abono: id_abono, idcontribuyente: id_contribuyente, tipo_doc: id_tipodoc_electronico, serie_doc: serie_comprobante, numero_comprobante: numero_comprobante, monto_pagado: monto_cuota, confirmacion: 'si'},
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
									get_lista_abonos($("#vm_lista_abonos_idcontribuyente").val(), $("#vm_lista_abonos_tipodoc").val(), $("#vm_lista_abonos_serie_doc").val(), $("#vm_lista_abonos_num_doc").val(), $("#vm_lista_abonos_opt_cuotas").val());

                                    //los nombre de las variables deben coincidir con el dashboard
									if (typeof dataTable_docs_sunat !== 'undefined') {
										dataTable_docs_sunat.ajax.reload(null, false);
									}
									
									if (typeof dataTable_docs_sunat !== 'undefined') {
										dataTable_notas_de_venta.ajax.reload(null, false);
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

function resetear_abono(id_contribuyente, id_tipodoc_electronico, serie_comprobante, numero_comprobante, id_abono, monto_cuota, fecha_pago_cuota, opt_informado_sunat) {
	var light = $('#content_lista_abono');
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
        url : '/facturacionv8/cuentasporcobrar/resetear_cuota',
		method :  'POST',
		data: {id_abono: id_abono, idcontribuyente: id_contribuyente, tipo_doc: id_tipodoc_electronico, serie_doc: serie_comprobante, numero_comprobante: numero_comprobante, monto_pagado: monto_cuota},
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
						url : '/facturacionv8/cuentasporcobrar/resetear_cuota',
						method :  'POST',
						data: {id_abono: id_abono, idcontribuyente: id_contribuyente, tipo_doc: id_tipodoc_electronico, serie_doc: serie_comprobante, numero_comprobante: numero_comprobante, monto_pagado: monto_cuota, confirmacion: 'si'},
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

function editar_abono(id_contribuyente, id_tipodoc_electronico, serie_comprobante, numero_comprobante, id_abono, monto_cuota, fecha_pago_cuota, opt_informado_sunat) {
	$("#vm_condicionpago_idabono").val(id_abono);
	$("#vm_condicionpago_idcontribuyente").val(id_contribuyente);
	$("#vm_condicionpago_tipo_doc").val(id_tipodoc_electronico);
	$("#vm_condicionpago_serie_doc").val(serie_comprobante);
	$("#vm_condicionpago_numero_comprobante").val(numero_comprobante);

	$("#monto_a_pagar").val(monto_cuota);
	$("#monto_a_pagar").prop( "disabled", true );

	if(fecha_pago_cuota != '') {
		$("#fecha_pago_cuota").val(fecha_pago_cuota)
	}

	$("#content_lista_abono").hide();
	$("#content_data_abono").show('slide');

	$("#fecha_proximo_pago").hide();
	$("#content_resultado").hide();
}

function limpiar_vm_abono() {
	$("#vm_condicionpago_idabono").val('');
	$("#vm_condicionpago_idcontribuyente").val('');
	$("#vm_condicionpago_tipo_doc").val('');
	$("#vm_condicionpago_serie_doc").val('');
	$("#vm_condicionpago_numero_comprobante").val('');

	$("#monto_a_pagar").val('');
	$("#monto_a_pagar").prop( "disabled", false );

	//$("#fecha_pago_cuota").val('');

	$("#vm_condicionpago_id_moneda").val('');
}