var dataTable_docs_sunat;
var dataTable_notas_de_venta;
var dataTable_cotizaciones;
var dataTable_guiasremision;
var grafico_ventas_soles;
var grafico_ventas_dolares;
var grafico_tipoventa_soles;
var grafico_tipoventa_dolares;
var dataTable_guiastransportista;

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

	$('.select2').select2({ minimumResultsForSearch: -1 });

	$('.select_criterios').select2({ minimumResultsForSearch: -1 });

	$(".btn_crear_comunicaciondebaja").click(btn_crear_comunicaciondebaja);

	var switches = Array.prototype.slice.call(document.querySelectorAll('.switch2'));
    switches.forEach(function(html) {
        var switchery = new Switchery(html, {color: '#4CAF50'});
	});
	
	var btn_opciones_avanzadas = document.querySelector('.btn_opciones_avanzadas');

	$(".opc_controles_avanzados").hide("slide");
    btn_opciones_avanzadas.onchange = function() {
        if(btn_opciones_avanzadas.checked) {
            $(".opc_controles_avanzados").show("slide");
        } else {
            $(".opc_controles_avanzados").hide("slide");
        }
	};
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
	
	/* phone modal */
	$("#codigo_pais").text('+51');
	$("#codigo_pais_select").change(function () {
		var codigo_pais = $(this).data('codigo');
		$("#codigo_pais").text(codigo_pais);
	});

	$("#celular_cliente_whatsapp").keyup(function () {
		var number_phone = $(this).val();
		$("#number_phone_2").text(number_phone);
		$("#mensaje_whatsapp").trigger('keyup');
	});

	$("#mensaje_whatsapp").keyup(function () {
		var mensaje_whatsapp = $(this).val();
		if (mensaje_whatsapp == ''){
			$(".body-text-message").text('');
		} else {
			var url_a4 = '';
			var url_ticket = '';
			var url_xml = '';
			var url_whatsapp = '';
			var numero_celular = $("#celular_cliente_whatsapp").val().replace(/ /g,'');
			
			if($("#txt_url_a4_whatsapp").val() != '') {
				url_a4 = "https://" + $("#txt_dominio_whatsapp").val() + $("#txt_url_a4_whatsapp").val();
			}

			if($("#txt_url_ticket_whatsapp").val() != '') {
				url_ticket = "https://" + $("#txt_dominio_whatsapp").val() + $("#txt_url_ticket_whatsapp").val();
			}

			if($("#txt_url_xml_whatsapp").val() != '') {
				url_xml = "https://" + $("#txt_dominio_whatsapp").val() + $("#txt_url_xml_whatsapp").val();
			}
			
			mensaje_whatsapp = mensaje_whatsapp.replaceAll("{url_pdf_a4}", url_a4);
			mensaje_whatsapp = mensaje_whatsapp.replaceAll("{url_pdf_ticket}", url_ticket);
			mensaje_whatsapp = mensaje_whatsapp.replaceAll("{url_xml}", url_xml);
			
			$(".body-text-message").html('<div class="text-item-single"><p id="text-send">' + mensaje_whatsapp +'</p></div>');
			
			if(is_mobil()) {
				url_whatsapp = "https://api.whatsapp.com/send?phone=51" + numero_celular + "&text=" + encodeURIComponent(mensaje_whatsapp);
			} else {
				url_whatsapp = "https://web.whatsapp.com/send?phone=51" + numero_celular + "&text=" + encodeURIComponent(mensaje_whatsapp);
			}

			$("#btn_enviar_whatsapp").attr(
				'href', url_whatsapp
			);
		}
	});

	//CODIGO PARA BANDERAS
	$("#codigo_pais_select").select2({
		templateResult: function (data) {
			var span = '';
			if(data.id) {
				
				span = $("<span><img class='mr-2' src='https://facturalaya.com/facturacionv8/public/img/flag_peru_16.png'/> " + data.text + "</span>");
				//span = $("<span><img class='mr-2' src='https://www.free-country-flags.com/countries/"+data.id+"/1/tiny/" + data.id + ".png'/> " + data.text + "</span>");
			}
			
			return span;
		},
		templateSelection: function (data) {
			var span = '';
			if(data.id) {
				//span = $("<span><img class='mr-2' src='https://www.free-country-flags.com/countries/"+data.id+"/1/tiny/" + data.id + ".png'/> " + data.text + "</span>");
				span = $("<span><img class='mr-2' src='https://facturalaya.com/facturacionv8/public/img/flag_peru_16.png'/> " + data.text + "</span>");
			}
			return span;
		}
	});
	//FIN CODIGO PARA BANDERAS

	//CODIGO AGREGAR EMOJIS AL SELECT
	var mySelect = document.getElementById('select_emoji')
	var newOption;
	var emojRange = [
	[128513, 128591], [9986, 10160], [128640, 128704]
	];
	for (var i = 0; i < emojRange.length; i++) {
		var range = emojRange[i];
		for (var x = range[0]; x < range[1]; x++) {

			newOption = document.createElement('option');
			newOption.value = x;
			newOption.innerHTML = "&#" + x + ";";
			mySelect.appendChild(newOption);
		}
	}
	//FIN CODIGO AGREGAR EMOTICOS

	$("#btn_xml_whatsapp").on("click", function() {
		insertar_texto_mensaje_whatsapp('mensaje_whatsapp', '{url_xml}');
		$("#mensaje_whatsapp").trigger('keyup');
	});

	$("#btn_a4_whatsapp").on("click", function() {
		insertar_texto_mensaje_whatsapp('mensaje_whatsapp', '{url_pdf_a4}');
		$("#mensaje_whatsapp").trigger('keyup');
	});

	$("#btn_ticket_whatsapp").on("click", function() {
		insertar_texto_mensaje_whatsapp('mensaje_whatsapp', '{url_pdf_ticket}');
		$("#mensaje_whatsapp").trigger('keyup');
	});

	$("#btn_add_emoji_whatsapp").on("click", function() {
		insertar_texto_mensaje_whatsapp('mensaje_whatsapp', $( "#select_emoji option:selected" ).text());
		$("#mensaje_whatsapp").trigger('keyup');
	});

	$(".file-styled").uniform({
        fileButtonClass: 'action btn btn-primary',
		fileButtonHtml: 'Seleccionar Archivo'
    }); 
	
	//NUEVO FILTRO PARA EL DASHBOARD
	$(".select_minimizado").select2({
        minimumResultsForSearch: -1
	});
	$('.multiselect').multiselect();
	$(".control_fecha_inicio").daterangepicker({
		singleDatePicker: true, 
		locale: { format: 'DD-MM-YYYY H:mm:ss' },
		timePicker: true
	}, function(start, end) {
		$("#fecha_inicio_valor").val(start.format('YYYY-MM-DD H:mm:ss'));
		get_datos_estadisticos();
		get_lista_comprobantes();
	});

	$(".control_fecha_fin").daterangepicker({
		singleDatePicker: true, 
		locale: { format: 'DD-MM-YYYY H:mm:ss' },
		timePicker: true
	}, function(start, end) {
		$("#fecha_fin_valor").val(end.format('YYYY-MM-DD H:mm:ss'));
        get_datos_estadisticos();
		get_lista_comprobantes();
	});

	$(".select_filtros_avanzados").change(function() {
		get_datos_estadisticos();
		get_lista_comprobantes();
	});

	$("#select_periodo").change(function() {
		get_datos_estadisticos();
	});

	window.addEventListener('resize',function(){
		grafico_ventas_soles.resize();
		//grafico_ventas_dolares.resize();
		grafico_tipoventa_soles.resize();
		//grafico_tipoventa_dolares.resize();
	});

	get_datos_estadisticos();
	get_lista_comprobantes();
	
	jQuery(document).on( 'shown.bs.tab', 'a[data-toggle="tab"]', function (e) { // on tab selection event
		jQuery( "#grafico_ventas_detalle_soles, #grafico_tipos_venta_soles, #grafico_ventas_detalle_dolares, #grafico_tipos_venta_dolares").each(function() {
			grafico_ventas_soles.resize();
			//grafico_ventas_dolares.resize();
			grafico_tipoventa_soles.resize();
			//grafico_tipoventa_dolares.resize();
		});
	})
	
});

function enviar_mensaje_whatsapp(txt_tipo_cpe_whatsapp, txt_serie_cpe_whatsapp, txt_numero_cpe_whatsapp, txt_dominio_whatsapp, txt_url_a4_whatsapp, txt_url_ticket_whatsapp, txt_url_xml_whatsapp, txt_phone_cliente_whatsapp, txt_nombre_cliente_whatsapp) {
	$("#txt_tipo_cpe_whatsapp").val(txt_tipo_cpe_whatsapp);
	$("#txt_serie_cpe_whatsapp").val(txt_serie_cpe_whatsapp);
	$("#txt_numero_cpe_whatsapp").val(txt_numero_cpe_whatsapp);
	$("#txt_dominio_whatsapp").val(txt_dominio_whatsapp);
	$("#txt_url_a4_whatsapp").val(txt_url_a4_whatsapp);
	$("#txt_url_ticket_whatsapp").val(txt_url_ticket_whatsapp);
	$("#txt_url_xml_whatsapp").val(txt_url_xml_whatsapp);
	$("#txt_phone_cliente_whatsapp").val(txt_phone_cliente_whatsapp);
	$("#celular_cliente_whatsapp").val(txt_phone_cliente_whatsapp).trigger('keyup');
	$("#txt_nombre_cliente_whatsapp").val(txt_nombre_cliente_whatsapp);

	$("#modal_whatsapp").modal('show');
	$("#mensaje_whatsapp").trigger('keyup');
}

function insertar_texto_mensaje_whatsapp(areaId,text) {
	var txtarea = document.getElementById(areaId);
	var scrollPos = txtarea.scrollTop;
	var strPos = 0;
	var br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ? 
		"ff" : (document.selection ? "ie" : false ) );
	if (br == "ie") { 
		txtarea.focus();
		var range = document.selection.createRange();
		range.moveStart ('character', -txtarea.value.length);
		strPos = range.text.length;
	}
	else if (br == "ff") strPos = txtarea.selectionStart;

	var front = (txtarea.value).substring(0,strPos);  
	var back = (txtarea.value).substring(strPos,txtarea.value.length); 
	txtarea.value=front+text+back;
	strPos = strPos + text.length;
	if (br == "ie") { 
		txtarea.focus();
		var range = document.selection.createRange();
		range.moveStart ('character', -txtarea.value.length);
		range.moveStart ('character', strPos);
		range.moveEnd ('character', 0);
		range.select();
	}
	else if (br == "ff") {
		txtarea.selectionStart = strPos;
		txtarea.selectionEnd = strPos;
		txtarea.focus();
	}
	txtarea.scrollTop = scrollPos;
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

function guardar_condicion_pago() {
	var light = $('#vm_modificar_condicionpago_content');
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
        url : '/facturacionv8/dashboard/guardar_condicion_pago',
		method :  'POST',
		data: {idcontribuyente: idcontribuyente, tipo_doc: tipo_doc, serie_doc: serie_doc, numero_comprobante: numero_comprobante, monto_pagado: monto_pagado, fecha_proximo_pago: fecha_proximo_pago, condicionpago_abono: condicionpago_abono, txt_numero_operacion: txt_numero_operacion, txt_observacion_abono: txt_observacion_abono, fecha_deposito: fecha_deposito, cuenta_banco_deposito: cuenta_banco_deposito},
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
						url : '/facturacionv8/dashboard/guardar_condicion_pago',
						method :  'POST',
						data: {idcontribuyente: idcontribuyente, tipo_doc: tipo_doc, serie_doc: serie_doc, numero_comprobante: numero_comprobante, monto_pagado: monto_pagado, fecha_proximo_pago: fecha_proximo_pago, condicionpago_abono: condicionpago_abono, txt_numero_operacion: txt_numero_operacion, txt_observacion_abono: txt_observacion_abono, fecha_deposito: fecha_deposito, cuenta_banco_deposito: cuenta_banco_deposito, confirmacion: 'si'},
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
									dataTable_docs_sunat.ajax.reload(null, false);
									dataTable_notas_de_venta.ajax.reload(null, false);
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

//-----FUNCIONALIDAD PARA MOSTRAR Y OCULTAR INFORMACIÓN EXTRA TABLA CPE------
function tbl_informacion_extra_cpe ( row ) {
	var nota_tag = '';
	if(row.nota != ''){
		nota_tag = '<p><span class="font-weight-bold title-tag">Nota:</span> '+ row.nota +'</p>';
	}

	var logs_tag = '';
	if(row.logs_html != ''){
		logs_tag = '<p class="tag-content"><span class="font-weight-bold title-tag">Logs</span>: '+ row.logs_html +'</p>';
	}

    return '<div class="icon-box-content"><i class="icon-play4 mr-3 icon-2x"></i></div>\
	<div class="box-cpe-tag">\
	<p><span class="font-weight-bold title-tag">Colaborador:</span> '+ row.nombre_user_creador_doc +'</p>\
	<p><span class="font-weight-bold title-tag">Tipo Operacion:</span> '+ row.tipo_operacion +'</p>\
	<p><span class="font-weight-bold title-tag">Aplica Retención</span>: '+ row.retencion +'</p>'+ logs_tag + '\
	<p class="tag-content"><span class="font-weight-bold title-tag">Etiquetas</span>: '+ row.etiquetas +'</p>\
	 '+ nota_tag +' \
	</div>\
	';
}

$('#show_all_iformation_cpe').on('click', function(){
	dataTable_docs_sunat.rows().every(function(){
		if(!this.child.isShown()){
			this.child(tbl_informacion_extra_cpe(this.data())).show();
			$(this.node()).addClass('shown');
		}
	});
});

$('#hide_all_iformation_cpe').on('click', function(){
	dataTable_docs_sunat.rows().every(function(){
		if(this.child.isShown()){
			this.child.hide();
			$(this.node()).removeClass('shown');
		}
	});
});
//----------------------------------------------------------------------------

//-----FUNCIONALIDAD PARA MOSTRAR Y OCULTAR INFORMACIÓN EXTRA TABLA NOTA DE VENTAS------
function tbl_informacion_extra_nota_venta ( row ) {
	var nota_tag = '';
	if(row.nota != ''){
		nota_tag = '<p><span class="font-weight-bold title-tag">Nota:</span> '+ row.nota +'</p>';
	}

	var logs_tag = '';
	if(row.logs_html != ''){
		logs_tag = '<p class="tag-content"><span class="font-weight-bold title-tag">Logs</span>: '+ row.logs_html +'</p>';
	}

    return '<div class="icon-box-content"><i class="icon-play4 mr-3 icon-2x"></i></div>\
	<div class="box-cpe-tag">\
		<p><span class="font-weight-bold title-tag">Colaborador:</span> '+ row.nombre_user_creador_doc +'</p>\
		<p class="tag-content"><span class="font-weight-bold title-tag">Etiquetas:</span> '+ row.etiquetas +'</p>'+ logs_tag + '\
	 	'+ nota_tag +' \
	</div>\
	';
}

$('#show_all_iformation_notaventa').on('click', function(){
	dataTable_notas_de_venta.rows().every(function(){
		if(!this.child.isShown()){
			this.child(tbl_informacion_extra_nota_venta(this.data())).show();
			$(this.node()).addClass('shown');
		}
	});
});

$('#hide_all_iformation_notaventa').on('click', function(){
	dataTable_notas_de_venta.rows().every(function(){
		if(this.child.isShown()){
			this.child.hide();
			$(this.node()).removeClass('shown');
		}
	});
});
//----------------------------------------------------------------------------

//-----FUNCIONALIDAD PARA MOSTRAR Y OCULTAR INFORMACIÓN EXTRA TABLA COTIZACIONES------
function tbl_informacion_extra_cotizacion ( row ) {
	var nota_tag = '';
	if(row.nota != ''){
		nota_tag = '<p><span class="font-weight-bold title-tag">Nota:</span> '+ row.nota +'</p>';
	}

	var logs_tag = '';
	if(row.logs_html != ''){
		logs_tag = '<p class="tag-content"><span class="font-weight-bold title-tag">Logs</span>: '+ row.logs_html +'</p>';
	}

    return '<div class="icon-box-content"><i class="icon-play4 mr-3 icon-2x"></i></div>\
	<div class="box-cpe-tag">\
		<p><span class="font-weight-bold title-tag">Colaborador:</span> '+ row.nombre_user_creador_doc +'</p>\
		<p class="tag-content"><span class="font-weight-bold title-tag">Etiquetas:</span> '+ row.etiquetas +'</p>'+ logs_tag + '\
	 	'+ nota_tag +'\
	</div>\
	';
}

$('#show_all_iformation_cotizacion').on('click', function(){
	dataTable_cotizaciones.rows().every(function(){
		if(!this.child.isShown()){
			this.child(tbl_informacion_extra_cotizacion(this.data())).show();
			$(this.node()).addClass('shown');
		}
	});
});

$('#hide_all_iformation_cotizacion').on('click', function(){
	dataTable_cotizaciones.rows().every(function(){
		if(this.child.isShown()){
			this.child.hide();
			$(this.node()).removeClass('shown');
		}
	});
});
//----------------------------------------------------------------------------

//-----FUNCIONALIDAD PARA MOSTRAR Y OCULTAR INFORMACIÓN EXTRA TABLA GUÍAS REMISIÓN------
function tbl_informacion_extra_guia_remision ( row ) {
	var nota_tag = '';
	if(row.nota != ''){
		nota_tag = '<p><span class="font-weight-bold title-tag">Nota:</span> '+ row.nota +'</p>';
	}

    return '<div class="icon-box-content"><i class="icon-play4 mr-3 icon-2x"></i></div>\
	<div class="box-cpe-tag">\
		<p><span class="font-weight-bold title-tag">Colaborador:</span> '+ row.nombre_user_creador_doc +'</p>\
		<p class="tag-content"><span class="font-weight-bold title-tag">Etiquetas:</span> '+ row.etiquetas +'</p>\
		'+ nota_tag +'\
	</div>\
	';
}

$('#show_all_iformation_guias').on('click', function(){
	dataTable_guiasremision.rows().every(function(){
		if(!this.child.isShown()){
			this.child(tbl_informacion_extra_guia_remision(this.data())).show();
			$(this.node()).addClass('shown');
		}
	});
});

$('#hide_all_iformation_guias').on('click', function(){
	dataTable_guiasremision.rows().every(function(){
		if(this.child.isShown()){
			this.child.hide();
			$(this.node()).removeClass('shown');
		}
	});
});
//----------------------------------------------------------------------------

//-----FUNCIONALIDAD PARA MOSTRAR Y OCULTAR INFORMACIÓN EXTRA TABLA GUÍAS TRANSPORTISTA------
function tbl_informacion_extra_guia_transportista ( row ) {
	var nota_tag = '';
	if(row.nota != ''){
		nota_tag = '<p><span class="font-weight-bold title-tag">Nota:</span> '+ row.nota +'</p>';
	}

    return '<div class="icon-box-content"><i class="icon-play4 mr-3 icon-2x"></i></div>\
	<div class="box-cpe-tag">\
		<p><span class="font-weight-bold title-tag">Colaborador:</span> '+ row.nombre_user_creador_doc +'</p>\
		<p class="tag-content"><span class="font-weight-bold title-tag">Etiquetas:</span> '+ row.etiquetas +'</p>\
		'+ nota_tag +'\
	</div>\
	';
}

$('#show_all_iformation_guiastransportista').on('click', function(){
	dataTable_guiastransportista.rows().every(function(){
		if(!this.child.isShown()){
			this.child(tbl_informacion_extra_guia_transportista(this.data())).show();
			$(this.node()).addClass('shown');
		}
	});
});

$('#hide_all_iformation_guiastransportista').on('click', function(){
	dataTable_guiastransportista.rows().every(function(){
		if(this.child.isShown()){
			this.child.hide();
			$(this.node()).removeClass('shown');
		}
	});
});
//----------------------------------------------------------------------------

function get_lista_comprobantes() {
	
	dataTable_docs_sunat = $('#tbl_lista_documentos').DataTable( {
		"processing": true,
		"serverSide": true,
		"ajax":{
			url :"/facturacionv8/reportedocumentos/get_lista_documentos", // json datasource
			data: {vendedores: $("#select_vendedor_filtro").val(),sucursales: $("#select_sucursal_filtro").val(), fecha_inicio_valor: $("#fecha_inicio_valor").val(), fecha_fin_valor: $("#fecha_fin_valor").val()},
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
			{ 
				'className': 'details-control', 
				'orderable': false, 
				'data': null, 
				'defaultContent': ''
			},
            { "data": "fecha_cpe", 		"visible": true},
			{ "data": "documento", 		"visible": true},
			{ "data": "cliente", 		"visible": true},
			{ "data": "total", 			"visible": true},
			{ "data": "pdf", 			"visible": true},
			{ "data": "xml", 			"visible": true},
			{ "data": "cdr", 			"visible": true},
			{ "data": "sunat", 			"visible": true},
			{ "data": "opciones", 		"visible": true}
        ],
		orderCellsTop: true,
		fixedHeader: true,
		"bDestroy": true,
		initComplete: function(){
			$("#tbl_lista_documentos_wrapper").children("div.datatable-header").children("div.dt-buttons").html($("#button_actions_crear_comprobantes").html());

			$('#tbl_lista_documentos tbody').on('click', 'td.details-control', function(){
				var tr = $(this).closest('tr');
				var row = dataTable_docs_sunat.row( tr );

				if(row.child.isShown()){
					row.child.hide();
					tr.removeClass('shown');
				} else {
					row.child(tbl_informacion_extra_cpe(row.data())).show();
					tr.addClass('shown');
				}
			});
		}
	} );

	dataTable_notas_de_venta = $('#tbl_lista_notas_venta').DataTable( {
		"processing": true,
		"serverSide": true,
		"ajax":{
			url :"/facturacionv8/reportedocumentos/get_notas_de_venta", // json datasource
			data: {vendedores: $("#select_vendedor_filtro").val(),sucursales: $("#select_sucursal_filtro").val(), fecha_inicio_valor: $("#fecha_inicio_valor").val(), fecha_fin_valor: $("#fecha_fin_valor").val()},
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
		"columns": [
			{ 
				'className': 'details-control', 
				'orderable': false, 
				'data': null, 
				'defaultContent': ''
			},
            { "data": "documento", 		"visible": true},
			{ "data": "fecha_cpe", 		"visible": true},
			{ "data": "cliente", 		"visible": true},
			{ "data": "total", 			"visible": true},
			{ "data": "pdf_a4", 		"visible": true},
			{ "data": "pdf_ticket", 	"visible": true},
			{ "data": "opciones", 		"visible": true}
        ],
		orderCellsTop: true,
		fixedHeader: true,
		"bDestroy": true,
		initComplete: function(){
			$("#tbl_lista_notas_venta_wrapper").children("div.datatable-header").children("div.dt-buttons").html("<a class='btn bg-indigo legitRipple' href='/facturacionv8/documentoelectronico/index/77/nuevo'><i class='fa fa-plus'></i> Crear Nota de Venta </a>");

			$('#tbl_lista_notas_venta tbody').on('click', 'td.details-control', function(){
				var tr = $(this).closest('tr');
				var row = dataTable_notas_de_venta.row( tr );

				if(row.child.isShown()){
					row.child.hide();
					tr.removeClass('shown');
				} else {
					row.child(tbl_informacion_extra_nota_venta(row.data())).show();
					tr.addClass('shown');
				}
			});
		}
	} );

	dataTable_cotizaciones = $('#tbl_lista_cotizaciones').DataTable( {
		"processing": true,
		"serverSide": true,
		"ajax":{
			url :"/facturacionv8/reportedocumentos/get_cotizaciones", // json datasource
			data: {vendedores: $("#select_vendedor_filtro").val(),sucursales: $("#select_sucursal_filtro").val(), fecha_inicio_valor: $("#fecha_inicio_valor").val(), fecha_fin_valor: $("#fecha_fin_valor").val()},
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
		"columns": [
			{ 
				'className': 'details-control', 
				'orderable': false, 
				'data': null, 
				'defaultContent': ''
			},
            { "data": "documento", 		"visible": true},
			{ "data": "fecha_cpe", 		"visible": true},
			{ "data": "cliente", 		"visible": true},
			{ "data": "total", 			"visible": true},
			{ "data": "pdf_a4", 		"visible": true},
			{ "data": "pdf_ticket", 	"visible": true},
			{ "data": "opciones", 		"visible": true}
        ],
		orderCellsTop: true,
		fixedHeader: true,
		"bDestroy": true,
		initComplete: function(){
			$("#tbl_lista_cotizaciones_wrapper").children("div.datatable-header").children("div.dt-buttons").html("<a class='btn bg-indigo legitRipple' href='/facturacionv8/documentoelectronico/index/88/nuevo'><i class='fa fa-plus'></i> Crear Cotización </a>");

			$('#tbl_lista_cotizaciones tbody').on('click', 'td.details-control', function(){
				var tr = $(this).closest('tr');
				var row = dataTable_cotizaciones.row( tr );

				if(row.child.isShown()){
					row.child.hide();
					tr.removeClass('shown');
				} else {
					row.child(tbl_informacion_extra_cotizacion(row.data())).show();
					tr.addClass('shown');
				}
			});
		}
	} );

	dataTable_guiasremision = $('#tbl_lista_guiasremision').DataTable( {
		"processing": true,
		"serverSide": true,
		"ajax":{
			url :"/facturacionv8/reportedocumentos/get_lista_guias_remision", // json datasource
			data: {vendedores: $("#select_vendedor_filtro").val(),sucursales: $("#select_sucursal_filtro").val(), fecha_inicio_valor: $("#fecha_inicio_valor").val(), fecha_fin_valor: $("#fecha_fin_valor").val()},
			type: "post",
			error: function(){
				$(".tbl_lista_guiasremision-error").html("");
				$("#tbl_lista_guiasremision").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
				$("#tbl_lista_guiasremision_processing").css("display","none");
			}
		},
		"order": [[ 0, "desc" ]],
		buttons: [
			{
                text: '<i class="icon-file-excel position-left"></i> Exp. Sucursal</span>',
                className: 'btn btn-info',
                action: function ( e, dt, node, config ) {
                    window.open("/facturacionv8/producto/export_to_excel/" + $("#select_almacen_mostrar").val(),"_self");
                }
            },
			{
                text: '<i class="icon-file-excel position-left"></i> Crear G.R.E.</span>',
                className: 'btn btn-primary',
                action: function ( e, dt, node, config ) {
					window.open("/facturacionv8/guiaderemision","_self");
                }
            },
			{
				extend: 'collection',
				text: '<i class="icon-plus-circle2 position-left"></i> Más Opciones</span>',
				className: 'btn btn-success',
				buttons: [
					{ 
						text: '<i class="icon-file-excel position-left"></i> Exp. Lista de G.R.E.</span>',
						action: function ( e, dt, node, config ) {
							let fecha_inicio = convertirFecha($("#fecha_inicio_valor").val());
							let fecha_fin = convertirFecha($("#fecha_fin_valor").val());
							let id_sucursal = get_options_multiselelect($("#select_sucursal_filtro"));
							let id_vendedor = get_options_multiselelect($("#select_vendedor_filtro"));
							

							var datastring = [];

							datastring.push({ name: "fecha_inicio", value: fecha_inicio });
							datastring.push({ name: "fecha_fin", value: fecha_fin });
							datastring.push({ name: "id_sucursal", value: id_sucursal });
							datastring.push({ name: "id_vendedor", value: id_vendedor });
							datastring.push({ name: 'tipo_reporte', value: 'lista' });

							var data_url_string = '';
							var n = 0;

							$(datastring).each(function(i, field){
								n++;
								if(n == 1) {
									data_url_string = field.name + '=' + encodeURIComponent(field.value);
								} else {
									data_url_string = data_url_string + '&' + field.name + '=' + encodeURIComponent(field.value);
								}
							});
							
							window.open("/facturacionv8/reportes/get_reporte_guias?" + data_url_string, "_blank");

						}
					},
					{ 
						text: '<i class="icon-file-excel position-left"></i> Exp. detalle de G.R.E.</span>',
						action: function ( e, dt, node, config ) {
							let fecha_inicio = convertirFecha($("#fecha_inicio_valor").val());
							let fecha_fin = convertirFecha($("#fecha_fin_valor").val());
							let id_sucursal = get_options_multiselelect($("#select_sucursal_filtro"));
							let id_vendedor = get_options_multiselelect($("#select_vendedor_filtro"));


							var datastring = [];

							datastring.push({ name: "fecha_inicio", value: fecha_inicio });
							datastring.push({ name: "fecha_fin", value: fecha_fin });
							datastring.push({ name: "id_sucursal", value: id_sucursal });
							datastring.push({ name: "id_vendedor", value: id_vendedor });
							datastring.push({ name: 'tipo_reporte', value: 'lista_detalle' });

							var data_url_string = '';
							var n = 0;

							$(datastring).each(function(i, field){
								n++;
								if(n == 1) {
									data_url_string = field.name + '=' + encodeURIComponent(field.value);
								} else {
									data_url_string = data_url_string + '&' + field.name + '=' + encodeURIComponent(field.value);
								}
							});
							
							window.open("/facturacionv8/reportes/get_reporte_guias?" + data_url_string, "_blank");
						}
					},
					{ 
						text: '<i class="icon-file-excel position-left"></i> Crear G.R.E. Masiva</span>',
						action: function ( e, dt, node, config ) {
							$("#vm_crear_gre_masivo").modal({
								backdrop: 'static',
								keyboard: false
							});
						}
					},
				],
				fade: true
			}
		],
		"columns": [
			{ 
				'className': 'details-control', 
				'orderable': false, 
				'data': null, 
				'defaultContent': ''
			},
            { "data": "fecha_cpe", 		"visible": true},
			{ "data": "documento", 		"visible": true},
			{ "data": "cliente", 		"visible": true},
			{ "data": "peso", 			"visible": true},
			{ "data": "pdf", 			"visible": true},
			{ "data": "xml", 			"visible": true},
			{ "data": "cdr", 			"visible": true},
			{ "data": "sunat", 			"visible": true},
			{ "data": "opciones", 		"visible": true}
        ],
		orderCellsTop: true,
		fixedHeader: true,
		"bDestroy": true,
		initComplete: function(){

			$('#tbl_lista_guiasremision tbody').on('click', 'td.details-control', function(){
				var tr = $(this).closest('tr');
				var row = dataTable_guiasremision.row( tr );

				if(row.child.isShown()){
					row.child.hide();
					tr.removeClass('shown');
				} else {
					row.child(tbl_informacion_extra_guia_remision(row.data())).show();
					tr.addClass('shown');
				}
			});
		}
	} );

	dataTable_guiastransportista = $('#tbl_lista_guiastransportista').DataTable( {
		"processing": true,
		"serverSide": true,
		"ajax":{
			url :"/facturacionv8/reportedocumentos/get_lista_guias_transportista", // json datasource
			data: {vendedores: $("#select_vendedor_filtro").val(),sucursales: $("#select_sucursal_filtro").val(), fecha_inicio_valor: $("#fecha_inicio_valor").val(), fecha_fin_valor: $("#fecha_fin_valor").val()},
			type: "post",
			error: function(){
				$(".tbl_lista_guiastransportista-error").html("");
				$("#tbl_lista_guiastransportista").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
				$("#tbl_lista_guiastransportista_processing").css("display","none");
			}
		},
		"order": [[ 0, "desc" ]],
		buttons: [
			{
                text: '<i class="icon-file-excel position-left"></i> Crear Guía Transportista</span>',
                className: 'btn btn-primary',
                action: function ( e, dt, node, config ) {
					window.open("/facturacionv8/guiatransportista","_self");
                }
            }
		],
		"columns": [
			{ 
				'className': 'details-control', 
				'orderable': false, 
				'data': null, 
				'defaultContent': ''
			},
            { "data": "fecha_cpe", 		"visible": true},
			{ "data": "documento", 		"visible": true},
			{ "data": "destinatario",	"visible": true},
			{ "data": "peso", 			"visible": true},
			{ "data": "pdf", 			"visible": true},
			{ "data": "xml", 			"visible": true},
			{ "data": "cdr", 			"visible": true},
			{ "data": "sunat", 			"visible": true},
			{ "data": "opciones", 		"visible": true}
        ],
		orderCellsTop: true,
		fixedHeader: true,
		"bDestroy": true,
		initComplete: function(){

			$('#tbl_lista_guiastransportista tbody').on('click', 'td.details-control', function(){
				var tr = $(this).closest('tr');
				var row = dataTable_guiastransportista.row( tr );

				if(row.child.isShown()){
					row.child.hide();
					tr.removeClass('shown');
				} else {
					row.child(tbl_informacion_extra_guia_transportista(row.data())).show();
					tr.addClass('shown');
				}
			});
		}
	});
}

function convertirFecha(fechaString) {
    if(fechaString) {
        // Si la fecha es proporcionada, la formateamos
        return fechaString.split(" ")[0];
    } else {
        // Si no se proporciona la fecha, obtenemos la fecha actual
        var fechaActual = new Date();
        var dd = String(fechaActual.getDate()).padStart(2, '0');
        var mm = String(fechaActual.getMonth() + 1).padStart(2, '0'); // Los meses en JavaScript empiezan desde 0
        var yyyy = fechaActual.getFullYear();

        fechaActual = yyyy + '-' + mm + '-' + dd;
        return fechaActual;
    }
}

function get_options_multiselelect($select) {
	// Verificamos si el elemento select existe
	if($select.length > 0) {
	  var valoresSeleccionados = $select.val();
  
	  // Verificamos si hay algún valor seleccionado
	  if(valoresSeleccionados !== null) {
		var cadenaValores = valoresSeleccionados.join(",");
		return cadenaValores;
	  } else {
		// No se seleccionó ningún valor
		return "";
	  }
	} else {
	  // El elemento select no existe
	  return "";
	}
  }

function anular_doc_no_oficial(id_tipodocumento, numero_comprobante, idsucursal) {
	var light = $('#vm_content_comunicacionbaja');
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
								//window.location.reload();
								dataTable_notas_de_venta.ajax.reload(null, false);
								dataTable_cotizaciones.ajax.reload(null, false);
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

function get_datos_estadisticos() {
	var light = $('#panel_rangofechas');
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
	
	var datastring = $("#frm_filtros").serializeArray();
	datastring.push({ name: "sucursales", value: $("#select_sucursal_filtro").val() });
	datastring.push({ name: "vendedores", value: $("#select_vendedor_filtro").val() });
	datastring.push({ name: "periodo", value: $("#select_periodo").val() });
	$.ajax({
        url :  "/facturacionv8/dashboard/get_datos_estadisticos",
        method :  'POST',
		dataType : "json",
		data: datastring
    }).then(function(data){
		if(data.respuesta == 'ok') {
			
			//MOSTRANDO DATOS EN SOLES
			$("#html_total_facturas_soles").html(data.total_facturas);
			$("#html_total_boletas_soles").html(data.total_boletas);
			$("#html_total_notas_credito_soles").html( data.total_notas_credito);
			$("#html_total_notas_debito_soles").html(data.total_notas_debito);
			$("#html_total_notas_venta_soles").html(data.total_notas_venta);
			$("#html_total_neto_soles").html(data.total_neto);
			
			dibujar_grafico_tipo_pie(document.getElementById('grafico_tipos_venta_soles'), data.totales, 'PEN');
			generar_grafico_ventas_areas(document.getElementById('grafico_ventas_detalle_soles'), data.series, data.periodo_array, 'PEN');

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

function btn_crear_comunicaciondebaja() {
	var light = $('#vm_content_comunicacionbaja');
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
								//window.location.reload();
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
								//window.location.reload();
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
									dataTable_notas_de_venta.ajax.reload(null, false);
									dataTable_cotizaciones.ajax.reload(null, false);
									dataTable_guiasremision.ajax.reload(null, false);
									dataTable_guiastransportista.ajax.reload(null, false);
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

function get_lista_doc_pendientes(){
	var light = $("#content_doc_pendientes");

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
		url : '/facturacionv8/notificacion/get_lista_doc_pendientes',
		method :  'POST',
		dataType : "json"
	}).then(function(data){
			if(data.respuesta == 'ok') {
				$('#tbl_lista_doc_pendientes').DataTable({
						data: data.lista,
						"bDestroy": true,
						"pageLength": 5
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
        url : '/facturacionv8/dashboard/modificar_docelectronico',
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
						url : '/facturacionv8/dashboard/modificar_docelectronico',
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
		url : '/facturacionv8/dashboard/verificar_estado_sunat',
		method :  'POST',
		dataType : "json",
		data: {id_contribuyente: id_contribuyente, id_tipodoc_electronico: id_tipodoc_electronico, serie_comprobante: serie_comprobante, numero_comprobante: numero_comprobante}
	}).then(function(data){
			if(data.respuesta == 'ok') {
				/*
				if(data.estado_documento == 'ticket') {
					$("#btn_resetear_documento").show();
					$("#btn_aprobar_manualmente").show();
					$("#contenedor_numero_ticket").show();
				} else if (data.estado_documento == 'aprobado') {
					$("#btn_resetear_documento").hide();
					$("#btn_aprobar_manualmente").hide();
					$("#contenedor_numero_ticket").hide();
					$("#btn_recuperar_cdr").show();
				} else if (data.estado_documento == 'pendiente') {
					$("#btn_resetear_documento").show();
				} else if (data.estado_documento == 'sin_cdr') {
					$("#btn_resetear_documento").hide();
					$("#btn_aprobar_manualmente").hide();
					$("#contenedor_numero_ticket").hide();
					$("#btn_recuperar_cdr").show();
				} else {
					$("#btn_resetear_documento").hide();
					$("#btn_aprobar_manualmente").hide();
					$("#contenedor_numero_ticket").hide();
					$("#btn_recuperar_cdr").hide();
				} */

				$("#btn_resetear_documento").show();
				$("#btn_aprobar_manualmente").show();
				$("#contenedor_numero_ticket").show();
				$("#btn_recuperar_cdr").show();

				$("#contenido_respuesta_sunat").html(data.html);

				if(data.log_documento != '') {
					$("#contenedor_logs_documento").show();
					$("#info_logs_documento").html(data.log_documento);
				} else {
					$("#contenedor_logs_documento").hide();
					$("#info_logs_documento").html("");
				}

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

function modal_importar_cpe() {
	$("#vm_importar_cpe").modal('show');
}


























function generar_grafico_ventas_areas(area_basic_element, lista_series, periodo_array, moneda) {
	
	var area_basic = echarts.init(area_basic_element);
	if(moneda == 'PEN') {
		grafico_ventas_soles = area_basic;
	} else {
		grafico_ventas_dolares = area_basic;
	}

	var series_array = [];
	var series_legend = [];
	
	$.each(lista_series, function(key, item_serie){
		serie = {
			name: key,
			type: 'line',
			data: item_serie,
			areaStyle: {
				normal: {
					opacity: 0.25
				}
			},
			smooth: true,
			symbolSize: 7,
			itemStyle: {
				normal: {
					borderWidth: 2
				}
			},
		}
		
		series_array.push(serie);
		series_legend.push(key);
	});

	area_basic.setOption({

		// Define colors
		color: ['#2ec7c9','#b6a2de','#5ab1ef','#ffb980','#d87a80', '#3f51b5'],

		// Global text styles
		textStyle: {
			fontFamily: 'var(--body-font-family)',
			color: 'var(--body-color)',
			fontSize: 14,
			lineHeight: 22,
			textBorderColor: 'transparent'
		},

		// Chart animation duration
		animationDuration: 750,

		// Setup grid
		grid: {
			left: 0,
			right: 40,
			top: 35,
			bottom: 0,
			containLabel: true
		},

		// Add legend
		legend: {
			data: series_legend,
			itemWidth:25,
			itemHeight:18,
			itemGap: 30
		},

		// Display toolbox
		toolbox: {
			show: true,
			feature: {
				magicType: {
					show: true,
					title: {
						line: 'Cambiar a Lineas',
						bar: 'Cambiar a Barras',
					},
					type: ['line', 'bar']
				},
				/*saveAsImage: {
					show: true,
					title: 'Same as image',
					lang: ['Save']
				}*/
			}
		},

		// Enable drag recalculate
		calculable: true,

		// Add tooltip
		tooltip: {
			trigger: 'axis',
			className: 'shadow-sm rounded',
			backgroundColor: '#fff',
			borderColor: '#eeeded',
			padding: 15,
			textStyle: {
				color: '#000'
			}
		},

		// Horizontal axis
		xAxis: [{
			type: 'category',
			boundaryGap: false,
			data: periodo_array,
			axisLabel: {
				color: '#333'
			},
			axisLine: {
				lineStyle: {
					color: '#999'
				}
			},
			splitLine: {
				show: true,
				lineStyle: {
					color: '#eee',
					type: 'dashed'
				}
			}
		}],

		// Vertical axis
		yAxis: [{
			type: 'value',
			axisLabel: {
				color: '#333'
			},
			axisLine: {
				lineStyle: {
					color: '#999'
				}
			},
			splitLine: {
				lineStyle: {
					color: '#eee'
				}
			},
			splitArea: {
				show: true,
				areaStyle: {
					color: ['rgba(250,250,250,0.1)', 'rgba(0,0,0,0.01)']
				}
			}
		}],

		// Add series
		series: series_array
	});

    // Resize function
    var triggerChartResize = function() {
        area_basic.resize();
    };

    // On sidebar width change
    $(document).on('click', '.sidebar-control', function() {
        setTimeout(function () {
            triggerChartResize();
        }, 0);
    });

    // On window resize
    var resizeCharts;
    window.onresize = function () {
        clearTimeout(resizeCharts);
        resizeCharts = setTimeout(function () {
            triggerChartResize();
        }, 200);
    };
}

function dibujar_grafico_tipo_pie(pie_nested_element, cajachica, moneda) {

	if (typeof echarts == 'undefined') {
		console.warn('Warning - echarts.min.js is not loaded.');
		return;
	}
	
	if(moneda == 'PEN') {
		var total_credito = round_math(cajachica.subtotales_soles.monto_adeudado, 2);
		var total_pagado = round_math(cajachica.subtotales_soles.tarjeta_credito + cajachica.subtotales_soles.transferencia + cajachica.subtotales_soles.contado, 2);
		var total_tarjeta_credito = round_math(cajachica.subtotales_soles.tarjeta_credito, 2);
		var total_transferencia = round_math(cajachica.subtotales_soles.transferencia, 2);
		var total_contado = round_math(cajachica.subtotales_soles.contado, 2);
	} else {
		var total_credito = round_math(cajachica.subtotales_dolares.monto_adeudado, 2);
		var total_pagado = round_math(cajachica.subtotales_dolares.tarjeta_credito + cajachica.subtotales_dolares.transferencia + cajachica.subtotales_dolares.contado, 2);
		var total_tarjeta_credito = round_math(cajachica.subtotales_dolares.tarjeta_credito, 2);
		var total_transferencia = round_math(cajachica.subtotales_dolares.transferencia, 2);
		var total_contado = round_math(cajachica.subtotales_dolares.contado, 2);
	}

	//
	// Charts configuration
	//

	if (pie_nested_element) {

		// Initialize chart
		var pie_nested = echarts.init(pie_nested_element, null, { renderer: 'svg' });

		if(moneda == 'PEN') {
			grafico_tipoventa_soles = pie_nested;
		} else {
			grafico_tipoventa_dolares = pie_nested;
		}

		//
		// Chart config
		//

		// Options
		pie_nested.setOption({

			// Colors
			color: [
				'#2ec7c9', '#4caf50', '#b6a2de','#5ab1ef','#dc69aa',
				'#07a2a4','#9a7fd1','#588dd5','#7eb00a','#c14089'
			],

			// Global text styles
			textStyle: {
				fontFamily: 'var(--body-font-family)',
				color: 'var(--body-color)',
				fontSize: 14,
				lineHeight: 22,
				textBorderColor: 'transparent'
			},

			calculable: true,

			// Display toolbox
			toolbox: {
				show: true,
				orient: 'vertical',
				feature: {
					magicType: {
						show: true,
						title: {
							pie: 'Switch to pies',
							funnel: 'Switch to funnel',
						},
						type: ['pie', 'funnel']
					},
					saveAsImage: {
						show: true,
						title: 'Same as image',
						lang: ['Save']
					}
				}
			},

			// Add tooltip
			tooltip: {
				trigger: 'item',
				className: 'shadow-sm rounded',
				backgroundColor: '#fff',
				borderColor: '#eeeded',
				padding: 15,
				textStyle: {
					color: '#000'
				},
				formatter: '{a} <br/>{b}: {c} ({d}%)'
			},

			// Add legend
			legend: {
				show: false,
				orient: 'vertical',
				top: 'center',
				left: 0,
				data: ['Italy','Spain','Austria','Germany','Poland','Denmark','Hungary','Portugal','France','Netherlands'],
				itemHeight: 8,
				itemWidth: 8,
				textStyle: {
					color: 'var(--body-color)'
				},
				itemStyle: {
					borderColor: 'transparent'
				}
			},

			// Add series
			series: [

				// Inner
				{
					name: 'Tipo de Venta',
					type: 'pie',
					selectedMode: 'single',
					radius: [0, '50%'],
					itemStyle: {
						borderColor: 'var(--card-bg)'
					},
					label: {
						position: 'inner'
					},
					data: [
                        {value: total_credito, name: 'Al Crédito'},
                        {value: total_pagado, name: 'Pagado'}
                    ]
				},

				// Outer
				{
					name: 'Condición de Pago',
					type: 'pie',
					radius: ['60%', '85%'],
					itemStyle: {
						borderColor: 'var(--card-bg)'
					},
					label: {
						color: 'var(--body-color)'
					},
					data: [
                        {value: total_tarjeta_credito, name: 'Tarjeta Crédito/Débito'},
                        {value: total_transferencia, name: 'Transferencia'},
                        {value: total_contado, name: 'En Efectivo'}
                    ]
				}
			]
		});
	}


	//
	// Resize charts
	//

	// Resize function
	var triggerChartResize = function() {
		pie_nested_element && pie_nested.resize();
	};

	// On sidebar width change
	var sidebarToggle = document.querySelectorAll('.sidebar-control');
	if (sidebarToggle) {
		sidebarToggle.forEach(function(togglers) {
			togglers.addEventListener('click', triggerChartResize);
		});
	}

	// On window resize
	var resizeCharts;
	window.addEventListener('resize', function() {
		clearTimeout(resizeCharts);
		resizeCharts = setTimeout(function () {
			triggerChartResize();
		}, 200);
	});
};