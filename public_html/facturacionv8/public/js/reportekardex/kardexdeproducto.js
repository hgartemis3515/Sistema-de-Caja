var dataTable_kardex;

$(function () {
	$('.select').select2({ minimumResultsForSearch: -1 });
	//get_lista_sucursales($("#select_sucursal"), 'si');
	inicializar_buscar_productos_registrados();
	$('.select_producto_buscar').on('change', generar_kardex);
	$(".btn_generar_kardex").click(generar_kardex);

	// Setting datatable defaults
	$.extend($.fn.dataTable.defaults, {
		autoWidth: false,
		dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
		language: {
			search: '<span>Filter:</span> _INPUT_',
			searchPlaceholder: 'Type to filter...',
			lengthMenu: '<span>Show:</span> _MENU_',
			paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
		}
	});

	$("#select_sucursal").on('change', function() {
		$("#select_producto_buscar").empty();
		$("#codigo").val('');
		$("#tbl_kardex").empty();
	});

	$(".control_fecha_inicio").daterangepicker({
		singleDatePicker: true, 
		locale: { format: 'DD/MM/YYYY' },
		timePicker: true
	}, function(start, end) {
		$("#fecha_inicio_valor").val(start.format('YYYY-MM-DD'));
		//get_resumen_totales();
		//get_lista_movimientos();
	});


	$(".control_fecha_fin").daterangepicker({
		singleDatePicker: true, 
		locale: { format: 'DD/MM/YYYY' },
		timePicker: true
	}, function(start, end) {
		$("#fecha_fin_valor").val(start.format('YYYY-MM-DD'));
		//get_resumen_totales();
		//get_lista_movimientos();
	});
});

function generar_kardex() {
	var light = $("#content_lista_kardex");

    $(light).block({
		message: '<div class="loading"> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
					</div> <p> <br />Generando Reporte, Espera un Momento! ...</p>',
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

	var idproducto = $("#select_producto_buscar").val();
	var idsucursal = $("#select_sucursal").val();

	var fecha_inicio = $("#fecha_inicio_valor").val();
	var fecha_fin = $("#fecha_fin_valor").val();

	$.ajax({
		url: '/facturacionv8/reportekardex/get_kardex_producto',
		method: 'POST',
		data: { idproducto: idproducto, fecha_inicio: fecha_inicio, fecha_fin: fecha_fin },
		dataType: "json"
	}).then(function (data) {
		if (data.respuesta == 'ok') {
			$('#tbl_kardex').DataTable({
				data: data.lista,
				"bDestroy": true,
				buttons: [
					{
						extend: 'excelHtml5',
						exportOptions: {
							columns: ':visible'
						},
						text: '<i class="icon-file-excel position-left"></i> Exportar</span>',
						className: 'btn btn-success'
					},
					{
						text: '<img style="width: 16px;" src="/facturacionv8/img/sunat_logo.png" class="position-left"> Formato 13.1 SUNAT </span>',
						className: 'btn bg-info',
						action: function ( e, dt, node, config ) {
							window.open("/facturacionv8/reportekardex/get_inv_valorizado_sunat_prod/" + idproducto + "/" + idsucursal + "/" + fecha_inicio + "/" + fecha_fin, "_self");
						}
					},
					{
						extend: 'colvis',
						text: '<i class="icon-three-bars"></i> <span class="caret"></span>',
						className: 'btn bg-indigo btn-icon',
						collectionLayout: 'fixed two-column'
					}
				],
				"columns": [
					{ "data": "item", "visible": true},
					{ "data": "fecha_registro", "visible": true},
					{ "data": "html_detalle", "visible": true},

					{ "data": "detalle", "visible": false},

					{ "data": "cantidad_entrada", "visible": true, className: "bg-success-400" },
					{ "data": "costo_unitario_entrada", "visible": true, className: "bg-success-400" },
					{ "data": "costo_total_entrada", "visible": true, className: "bg-success-400" },

					{ "data": "cantidad_salida", "visible": true, className: "bg-primary-400" },
					{ "data": "costo_unitario_salida", "visible": true, className: "bg-primary-400" },
					{ "data": "costo_total_salida", "visible": true, className: "bg-primary-400" },

					{ "data": "stock_actual", "visible": true},
					{ "data": "costo_promedio_unitario", "visible": true},
					{ "data": "stock_valorizado", "visible": true}
				],
				initComplete: function(){
					$('[data-popup="popover"]').popover();
					$(light).unblock();
				}
			});
			$("#codigo").val(data.producto.codigo);
		} else {
			swal({
				title: 'Error',
				text: data.mensaje,
				html: true,
				type: "error",
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Ok",
			}, function () {
				$(light).unblock();
			});
		}
	}, function (reason) {
		swal({
			title: 'Error',
			text: reason,
			html: true,
			type: "error",
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Ok"
		}, function () {
			$(light).unblock();
		});
	});
}

function inicializar_buscar_productos_registrados() {
	$("#select_producto_buscar").select2({
		language: "es",
		minimumResultsForSearch: Infinity,
		ajax: {
			type: "post",
			url: "/facturacionv8/herramientas/get_sugerencias_producto",
			dataType: 'json',
			delay: 250,
			data: function (params) {
				return {
					q: params.term, // search term
					page: params.page,
					idsucursal: $("#select_sucursal").val()
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
		minimumInputLsength: 1
	});

	let idproducto = $("#idprod_buscar").val();
	let nombre_producto = $("#nombreprod_buscar").val();

	if (idproducto != '') {
		$('#select_producto_buscar').append('<option value="' + idproducto + '">' + nombre_producto + '</option>');
		$('#select_producto_buscar').val(idproducto);
		generar_kardex();
	}
}