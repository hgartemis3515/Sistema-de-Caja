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
		minDate: moment().subtract(1100, 'days'),
		maxDate: moment(),
		maxSpan: {
			days: 365
		},
		locale: {
			format: 'DD/MM/YYYY',
			daysOfWeek: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ],
			monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
			],
			monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul', 'Ago','Sep','Oct','Nov','Dic'],
			firstDay: 1
		}
	}, function(start, end) {
			$(".btn_generar_txt").hide('slide');
			$("#fechainicio").val(start.format('YYYY-MM-DD'));
			$("#fechafinal").val(end.format('YYYY-MM-DD'));
		}
	);

	$("#fechainicio").val(moment().subtract(1, 'month').startOf('month').format('YYYY-MM-DD'));
	$("#fechafinal").val(moment().subtract(1, 'month').endOf('month').format('YYYY-MM-DD'));

	$("#btn_generar_reporte").click(generar_reporte);
	get_lista_sucursales($("#idsucursal"));
	$(".select_criterio_visualizacion").on('change', function(){
		$(".btn_generar_txt").hide('slide');
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
	var periodo = $("#select_periodo").val();
	var tipo_fecha = $("#filtro_tipo_fecha").val();

	$.ajax({
        url : '/facturacionv8/reportes/get_detallecompras',
        method :  'POST',
		dataType : "json",
		data: {fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, idsucursal: idsucursal, tipo_documento: tipo_documento, periodo: periodo, tipo_fecha: tipo_fecha}
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
							actualizar_tipo_cambio_compras();
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
					{ "data": "periodo", "visible": true},
					{ "data": "codigo_unico", "visible": true},
					{ "data": "regimen", "visible": true},

					{ "data": "fecha_comprobante", "visible": true},
					{ "data": "fecha_vencimiento", "visible": true},
					{ "data": "id_tipodoc_electronico", "visible": true},
					{ "data": "serie_comprobante", "visible": true},
					{ "data": "dua_dsi", "visible": true},
					{ "data": "numero_comprobante", "visible": true},
					{ "data": "odncf", "visible": true},

					{ "data": "id_tipodocidentidad", "visible": true},
					{ "data": "num_doc", "visible": true},
					{ "data": "razon_social", "visible": true},
					{ "data": "total_gravadas", "visible": true, "className": 'row_rigth_text'},
					{ "data": "total_igv", "visible": true, "className": 'row_rigth_text'},
					{ "data": "saldo_exportacion", "visible": true, "className": 'row_rigth_text'},
					{ "data": "imp_prom_mun", "visible": true, "className": 'row_rigth_text'},
					{ "data": "op_grav_no_cf", "visible": true, "className": 'row_rigth_text'},
					{ "data": "mont_imp_prom_mun", "visible": true, "className": 'row_rigth_text'},
					{ "data": "total_no_gravados", "visible": true, "className": 'row_rigth_text'},
					{ "data": "total_isc", "visible": true, "className": 'row_rigth_text'},

					{ "data": "total_icbper", "visible": true, "className": 'row_rigth_text'},
					{ "data": "total_otr_imp", "visible": true, "className": 'row_rigth_text'},
					{ "data": "total", "visible": true, "className": 'row_rigth_text'},
					{ "data": "moneda", "visible": true},
					{ "data": "tipo_cambio", "visible": true},

					{ "data": "fecha_comprobante_modifica", "visible": true},
					{ "data": "id_tipo_comprobante_modifica", "visible": true},
					{ "data": "serie_documento_modifica", "visible": true},
					{ "data": "dua_doc_modifica", "visible": true},
					{ "data": "nro_documento_modifica", "visible": true},

					{ "data": "fecha_detraccion", "visible": true},
					{ "data": "numero_detraccion", "visible": true},
					{ "data": "marc_comp_retenc", "visible": true},
					{ "data": "clasif_bienes_serv", "visible": true},

					{ "data": "id_contr", "visible": true},
					{ "data": "error_tc", "visible": true},
					{ "data": "error_prov_n_h", "visible": true},
					{ "data": "error_prov_ex", "visible": true},
					{ "data": "error_dni", "visible": true},
					{ "data": "comp_m_p", "visible": true},
					{ "data": "estado", "visible": true},
					{ "data": "campo_libre_1", "visible": true}

				],
				initComplete: function(){

				}
			});
			
			$(".btn_generar_txt").attr('href', '/facturacionv8/reportes/generar_txt_libcompras/'+ fecha_inicio + '/' + fecha_fin + '/' + idsucursal + '/' + tipo_documento + '/' + periodo);
			$(".btn_generar_txt").show('slide');
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

function actualizar_tipo_cambio_compras() {
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
	var periodo = $("#select_periodo").val();
	var tipo_fecha = $("#filtro_tipo_fecha").val();
	
	$.ajax({
        url : '/facturacionv8/reportes/actualizar_tipo_cambio_compras',
        method :  'POST',
		dataType : "json",
		data: {fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, idsucursal: idsucursal, tipo_documento: tipo_documento, periodo: periodo, tipo_fecha: tipo_fecha, confirmacion: 'no'}
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
						url : '/facturacionv8/reportes/actualizar_tipo_cambio_compras',
						method :  'POST',
						dataType : "json",
						data: {fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, idsucursal: idsucursal, tipo_documento: tipo_documento, periodo: periodo, tipo_fecha: tipo_fecha, confirmacion: 'si'}
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