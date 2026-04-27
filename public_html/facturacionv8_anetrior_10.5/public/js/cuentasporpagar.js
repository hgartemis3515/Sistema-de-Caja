var dataTable_cpe;
var dataTable_notasventa;

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

	$(".control_fecha_inicio").daterangepicker({
		singleDatePicker: true, 
		locale: { format: 'DD/MM/YYYY' }, 
		timePicker: false
	}, function(start, end) {
		$("#fecha_inicio_valor").val(start.format('YYYY-MM-DD'));
		get_lista_documentos();
	});
  
	$(".control_fecha_fin").daterangepicker({
		singleDatePicker: true, 
		locale: { format: 'DD/MM/YYYY' },
		timePicker: false
	}, function(start, end) {
		$("#fecha_fin_valor").val(end.format('YYYY-MM-DD'));
		get_lista_documentos();
	});
	
	$(".select_tipoventa").select2({
        minimumResultsForSearch: -1
	});

	$('.multiselect').multiselect();
	sugerencias_proveedores($("#select_id_proveedor"));

	$(".filtro_select").change(function() {
		get_lista_documentos();  
	});

	get_lista_documentos();  

	$(".control_fecha").daterangepicker({singleDatePicker: true, locale: { format: 'DD/MM/YYYY' }});

	$(".monto_a_pagar").on('input', function() {
        calcular_monto_pendiente_pago();
    }).on('change', function() {
        calcular_monto_pendiente_pago();
	});
	
	$("#btn_guardar_condicion").click(crear_abono_pago);

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
}); 

function get_lista_documentos() {
	var datastring = $("#frm_filtros").serializeArray();
	
	dataTable_cpe = $('#tbl_lista_cpe').DataTable({
		"processing": true,
        "dom": 'Blfrtip',
		"serverSide": true,
		"ajax":{
			url :"/facturacionv8/cuentasporpagar/get_lista_cpe", // json datasource
			data: {fecha_inicio_valor: $("#fecha_inicio_valor").val(), fecha_fin_valor: $("#fecha_fin_valor").val(), select_id_proveedor: $("#select_id_proveedor").val(), tipo_venta: $("#select_tipoventa").val(), tipos_documentos: ($("#select_tipo_comprobante").val()).join(',')},
			type: "post",
			error: function(){
				$(".tbl_lista_documentos-error").html("");
				$("#tbl_lista_documentos").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
				$("#tbl_lista_documentos_processing").css("display","none");
				
			}
		},
		"order": [[ 0, "desc" ]],
		buttons: [
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                },
                text: '<i class="icon-file-excel position-left"></i> Exp. Vista</span>',
                className: 'btn btn-success'
            }
        ],
		"columns": [
            { "data": "fecha_registro", "visible": true},
			{ "data": "doc_serie_numero", "visible": true},
			{ "data": "ruc_nom_prov", "visible": true},
			{ "data": "deuda_total", "visible": true},
			{ "data": "abonos_total", "visible": true},
			{ "data": "fecha_vencimiento", "visible": true},
			{ "data": "menu", "visible": true}
        ],
		orderCellsTop: true,
		fixedHeader: true,
		"bDestroy": true,
		stateSave: false,
		initComplete: function(){
			
		}
	});
}

function sugerencias_clientes(select) {
    select.select2({
        language: "es",
        ajax: {
          type: "post",
          url: "/facturacionv8/client/get_lista_sugerencias_clientes",
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
              q: params.term, // search term
              page: params.page
            };
          },
          processResults: function (data, params) {
            // parse the results into the format expected by Select2
            // since we are using custom formatting functions we do not need to
            // alter the remote JSON data, except to indicate that infinite
            // scrolling can be used
            params.page = params.page || 1;
  
            return {
              results: data.items,
              pagination: {
                more: (params.page * 30) < data.total_count
              }
            };
          },
          cache: true
        },
        escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
            minimumInputLength: 1
    });
}

function sugerencias_proveedores(select) {
    select.select2({
        language: "es",
        ajax: {
          type: "post",
          url: "/facturacionv8/gestiondeproveedores/get_lista_sugerencias_proveedor",
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
              q: params.term, // search term
              page: params.page
            };
          },
          processResults: function (data, params) {
            // parse the results into the format expected by Select2
            // since we are using custom formatting functions we do not need to
            // alter the remote JSON data, except to indicate that infinite
            // scrolling can be used
            params.page = params.page || 1;
  
            return {
              results: data.items,
              pagination: {
                more: (params.page * 30) < data.total_count
              }
            };
          },
          cache: true
        },
        escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
            minimumInputLength: 1
    });
}

function crear_abono_deuda(id_compra, id_contribuyente, id_tipodoc_electronico, serie_comprobante, numero_comprobante, fecha_pagopendiente, tipo_condicion, monto_pendiente, moneda) {
	var simbolo_moneda = 'S/';
	if(moneda == 'USD') {
		simbolo_moneda = 'USD $';
	}

	$(".simbolo_moneda").html(simbolo_moneda);

	if(id_tipodoc_electronico == '00') {
		var mensaje = 'Otr.Doc. N°: ' + numero_comprobante + ' tiene un pago pendiente de ' + simbolo_moneda + ' ' + monto_pendiente + ', el cuál se debe realizar como máximo el día: '+ fecha_pagopendiente +'. A continuación Ingresa el total de dinero abonado y luego haz click en Guardar.';
	} else {
		var mensaje = 'El Documento electrónico: ' + serie_comprobante + '-' + numero_comprobante + ' tiene un pago pendiente de ' + simbolo_moneda + ' ' + monto_pendiente + ', el cuál se debe realizar como máximo el día: '+ fecha_pagopendiente +'. A continuación Ingresa el total de dinero abonado y luego haz click en Guardar.';
	}

	$("#vm_id_compra").val(id_compra);
	$("#vm_condicionpago_idcontribuyente").val(id_contribuyente);
	$("#vm_condicionpago_tipo_doc").val(id_tipodoc_electronico);
	$("#vm_condicionpago_serie_doc").val(serie_comprobante);
	$("#vm_condicionpago_numero_comprobante").val(numero_comprobante);
	$("#monto_adeudado").val(monto_pendiente);
	$("#vm_condicionpago_id_moneda").val(moneda);
	$("#monto_a_pagar").val(0);
	
	$("#mensaje_condicionpago").html(mensaje);
	$("#vm_crear_abono_deuda").modal('show');
}

function crear_abono_pago() {
	var light = $('#vm_crear_abono_deuda_content');
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

	var id_compra = $("#vm_id_compra").val();
	var idcontribuyente = $("#vm_condicionpago_idcontribuyente").val();
	var tipo_doc = $("#vm_condicionpago_tipo_doc").val();
	var serie_doc = $("#vm_condicionpago_serie_doc").val();
	var numero_comprobante = $("#vm_condicionpago_numero_comprobante").val();
	var monto_pagado = $("#monto_a_pagar").val();
	var fecha_proximo_pago = $("#nueva_fecha_pago").val();
	var condicionpago_abono = $("#condicionpago_abono").val();
	var txt_numero_operacion = $("#txt_numero_operacion").val();
	var txt_observacion_abono = $("#txt_observacion_abono").val();
	var fecha_deposito = $("#fecha_deposito").val();
	var cuenta_banco_deposito = $("#select_cuenta_banco_deposito").val();
	
	$.ajax({
        url : '/facturacionv8/cuentasporpagar/crear_abono_pago',
		method :  'POST',
		data: {id_compra: id_compra, idcontribuyente: idcontribuyente, tipo_doc: tipo_doc, serie_doc: serie_doc, numero_comprobante: numero_comprobante, monto_pagado: monto_pagado, fecha_proximo_pago: fecha_proximo_pago, condicionpago_abono: condicionpago_abono, txt_numero_operacion: txt_numero_operacion, txt_observacion_abono: txt_observacion_abono, fecha_deposito: fecha_deposito, cuenta_banco_deposito: cuenta_banco_deposito},
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
						url : '/facturacionv8/cuentasporpagar/crear_abono_pago',
						method :  'POST',
						data: {id_compra: id_compra, idcontribuyente: idcontribuyente, tipo_doc: tipo_doc, serie_doc: serie_doc, numero_comprobante: numero_comprobante, monto_pagado: monto_pagado, fecha_proximo_pago: fecha_proximo_pago, condicionpago_abono: condicionpago_abono, txt_numero_operacion: txt_numero_operacion, txt_observacion_abono: txt_observacion_abono, fecha_deposito: fecha_deposito, cuenta_banco_deposito: cuenta_banco_deposito, confirmacion: 'si'},
						dataType : "json"
					}).then(
						function(data) {
							if(data.respuesta == 'ok') {

								swal({   
									title: data.titulo,   
									/*text: data.mensaje + '<br /><br />' + '<a title="Formato Ticket" target="_blank" href="/facturacionv8/download/ticket_abono/' + data.id_montocobrado +'"><img src="/facturacionv8/img/svg/ticket_cpe.svg" style="max-width: 40px;"></a>',*/
									text: data.mensaje,
									html: true,
									type: "success",   
									confirmButtonColor: "#563d7c",
									confirmButtonText: "Ok"
								}, function(){  
									$("#vm_modificar_condicionpago").modal('hide');
									dataTable_cpe.ajax.reload(null, false);

									$("#vm_id_compra").val('');
									$("#vm_condicionpago_idcontribuyente").val('');
									$("#vm_condicionpago_tipo_doc").val('');
									$("#vm_condicionpago_serie_doc").val('');
									$("#vm_condicionpago_numero_comprobante").val('');
									$("#monto_a_pagar").val('');
									$("#condicionpago_abono").val('');
									$("#txt_numero_operacion").val('');
									$("#txt_observacion_abono").val('');
									$("#select_cuenta_banco_deposito").val('');

									$("#vm_crear_abono_deuda").modal('hide');
									
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

function get_lista_abonos(id_compra, id_contribuyente, id_tipodoc_electronico, serie_comprobante, numero_comprobante) {
	var light = $("#tbl_lista_cpe");

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
        url : '/facturacionv8/cuentasporpagar/get_lista_abonos',
        method :  'POST',
		dataType : "json",
		data: {id_compra: id_compra, id_contribuyente: id_contribuyente, id_tipodoc_electronico: id_tipodoc_electronico, serie_comprobante: serie_comprobante, numero_comprobante: numero_comprobante}
    }).then(function(data){
		if(data.respuesta == 'ok') {
			$('#tbl_lista_abonos').DataTable({
                data: data.lista,
				"bDestroy": true,
                buttons: {
                    dom: {
                        button: {
                            className: 'btn btn-primary'
                        }
                    },
                    buttons: [
						'excelHtml5'
                    ]
				},
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