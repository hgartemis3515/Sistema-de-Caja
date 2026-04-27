$(function() {

	// Setting datatable defaults
    $.extend( $.fn.dataTable.defaults, {
        autoWidth: false,
        dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        language: {
            search: '<span>Filter:</span> _INPUT_',
            searchPlaceholder: 'Type to filter...',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
        }
	});
	
	$(".select_criterio_visualizacion").select2({
        minimumResultsForSearch: -1
	});
	
	$("#rangofechas").daterangepicker(
	{
		singleDatePicker: false,
		startDate: moment().subtract(1, 'month').startOf('month'),
		endDate: moment().subtract(1, 'month').endOf('month'),
		minDate: moment().subtract(700, 'days'),
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
			$(".botones_opciones_extra").hide('slide');
			$("#fechainicio").val(start.format('YYYY-MM-DD'));
			$("#fechafinal").val(end.format('YYYY-MM-DD'));
		}
	);

	$("#fechainicio").val(moment().subtract(1, 'month').startOf('month').format('YYYY-MM-DD'));
	$("#fechafinal").val(moment().subtract(1, 'month').endOf('month').format('YYYY-MM-DD'));

	$("#btn_generar_reporte").click(generar_reporte);
	get_lista_sucursales($("#idsucursal"));
	$(".select_criterio_visualizacion").on('change', function(){
		$(".botones_opciones_extra").hide('slide');
	});
});

function generar_reporte() {
	var light = $('#panel_rangofechas');
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

	var fecha_inicio = $("#fechainicio").val();
	var fecha_fin = $("#fechafinal").val();
	var idsucursal = $("#idsucursal").val();
	var tipo_documento = $("#tipo_documento").val();

	$.ajax({
        url : '/facturacionv8/reportes/crear_reporte',
        method :  'POST',
		dataType : "json",
		data: {fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, idsucursal: idsucursal, tipo_documento: tipo_documento}
    }).then(function(data){
		if(data.respuesta == 'ok') {
			$(light).unblock();

			tbl_detalle_documento = $('#tbl_detalle_documentos').DataTable({
                data: data.detalle_comprobantes,
                "bDestroy": true,
				buttons: [
					{
						extend: 'excelHtml5',
						exportOptions: {
							columns: ':visible'
						},
						className: 'btn btn-success'
					},
					{
						text: '<img src="/facturacionv8/public/img/tipo-de-cambio.png" style="width: 18px;"> Actualizar T.C.</span>',
						className: 'btn btn-primary',
						action: function ( e, dt, node, config ) {
							actualizar_tipo_cambio_ventas();
						}
					},
					{
						extend: 'colvis',
						text: '<i class="icon-three-bars"></i> <span class="caret"></span>',
						className: 'btn bg-blue btn-icon',
						collectionLayout: 'fixed four-column'
					}
				],
				"columns": [
					{ "data": "fecha", "visible": true},
					{ "data": "cod_unico", "visible": true},
					{ "data": "regimen", "visible": true},

					{ "data": "fecha_comprobante", "visible": true},
					{ "data": "fecha_vencimiento", "visible": true},
					{ "data": "id_tipodoc_electronico", "visible": true},
					{ "data": "serie_comprobante", "visible": true},
					{ "data": "numero_comprobante", "visible": true},
					{ "data": "num_maq_reg", "visible": true},

					{ "data": "cliente_id_tipodocidentidad", "visible": true},
					{ "data": "cliente_num_doc_identidad", "visible": true},
					{ "data": "cliente_razon_social", "visible": true},

					{ "data": "total_exportacion", "visible": true, "className": 'row_rigth_text'},
					{ "data": "total_gravadas", "visible": true, "className": 'row_rigth_text'},
					{ "data": "descuento", "visible": true, "className": 'row_rigth_text'},
					{ "data": "total_igv", "visible": true, "className": 'row_rigth_text'},
					{ "data": "descuento_igv", "visible": true, "className": 'row_rigth_text'},
					{ "data": "total_exoneradas", "visible": true, "className": 'row_rigth_text'},
					{ "data": "total_inafecta", "visible": true, "className": 'row_rigth_text'},
					{ "data": "total_isc", "visible": true, "className": 'row_rigth_text'},
					{ "data": "op_arroz_pilado", "visible": true},
					{ "data": "imp_arroz_pilado", "visible": true},
					{ "data": "icbper", "visible": true, "className": 'row_rigth_text'},
					{ "data": "otros_tributos", "visible": true, "className": 'row_rigth_text'},
					{ "data": "total", "visible": true, "className": 'row_rigth_text'},
					{ "data": "moneda", "visible": true},
					{ "data": "tipo_cambio", "visible": true},
					{ "data": "fecha_comp_modif", "visible": true},
					{ "data": "tipo_comp_modif", "visible": true},
					{ "data": "serie_comp_modif", "visible": true},
					{ "data": "numero_comp_modif", "visible": true},

					{ "data": "id_contr", "visible": true},
					{ "data": "error_tc", "visible": true},
					{ "data": "comp_m_p", "visible": true},
					{ "data": "estado", "visible": true},
					{ "data": "camp_libre_1", "visible": true},
					{ "data": "estado_comprobante", "visible": true}
				],
				initComplete: function(){

				}
			});

			$(".btn_generar_txt").attr('href', '/facturacionv8/reportes/generar_txt_libventas/'+ fecha_inicio + '/' + fecha_fin + '/' + idsucursal + '/' + tipo_documento);
			$(".btn_generar_excel").attr('href', '/facturacionv8/reportes/generar_excel_libventas/'+ fecha_inicio + '/' + fecha_fin + '/' + idsucursal + '/' + tipo_documento);
			$(".btn_generar_excel_ejb").attr('href', '/facturacionv8/reportes/generar_excel_ventas_ejb/'+ fecha_inicio + '/' + fecha_fin + '/' + idsucursal + '/' + tipo_documento);
			$(".btn_asiento_contable").attr('href', '/facturacionv8/reportes/get_asiento_contable/'+ fecha_inicio + '/' + fecha_fin + '/' + idsucursal + '/' + tipo_documento);
			$(".botones_opciones_extra").show('slide');
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

function actualizar_tipo_cambio_ventas() {
	var light = $('#panel_rangofechas');
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

	var fecha_inicio = $("#fechainicio").val();
	var fecha_fin = $("#fechafinal").val();
	var idsucursal = $("#idsucursal").val();
	var tipo_documento = $("#tipo_documento").val();
	
	$.ajax({
        url : '/facturacionv8/reportes/actualizar_tipo_cambio_ventas',
        method :  'POST',
		dataType : "json",
		data: {fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, idsucursal: idsucursal, tipo_documento: tipo_documento, confirmacion: 'no'}
    }).then(function(data){
		if(data.respuesta == 'ok') {

			swal({   
				title: data.titulo,   
				text: data.mensaje,
				html: true,
				type: "warning",   
				showCancelButton: true,   
				confirmButtonColor: "#3f51b5",
				cancelButtonColor: "#DD6B55",
				confirmButtonText: "Si, Adelante!",   
				closeOnConfirm: false,
  				showLoaderOnConfirm: true
			}, function(isconfirmed){  
				if(isconfirmed) {
					$.ajax({
						url : '/facturacionv8/reportes/actualizar_tipo_cambio_ventas',
						method :  'POST',
						dataType : "json",
						data: {fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, idsucursal: idsucursal, tipo_documento: tipo_documento, confirmacion: 'si'}
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
                                    generar_reporte();
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