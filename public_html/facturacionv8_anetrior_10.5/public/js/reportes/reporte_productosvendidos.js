var tbl_lista_reporte;
$(function() {
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
	
	$(".select").select2();
	$(".select_minimizado").select2({
        minimumResultsForSearch: -1
	});
	$('.multiselect').multiselect();
	$(".control_fecha").daterangepicker({singleDatePicker: true, locale: { format: 'DD/MM/YYYY' }});
	$(".btn_generar_reporte_detallado").click(reporte_detallado);
	reporte_detallado();
});

function reporte_detallado(){
	var light = $("#contenido_reporte_detallado");

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
	
	var datastring = $("#frm_reporte_detallado").serializeArray();
	datastring.push({ name: "sucursales", value: $("#select_sucursal").val() });
	datastring.push({ name: "vendedores", value: $("#select_vendedor").val() });
	datastring.push({ name: "tipos_documentos", value: $("#select_tipo_comprobante").val() });
	datastring.push({ name: "estados_documentos", value: $("#select_estado").val() });
	datastring.push({ name: "tipos_monedas", value: $("#id_cod_moneda").val() });

	$.ajax({
		url: '/facturacionv8/reportes/get_reporteproductosvendidos',
		data: datastring,
		method: 'POST',
		dataType: 'json'
	}).then(function(data){
		if(data.respuesta == 'ok'){
			$("#ventas_totales").html(data.venta_total);
			$("#costo_promedio_total").html(data.costo_total);
			$("#utilidad_total").html(data.utilidad_total);

			tbl_lista_reporte = $('#tbl_lista_reporte').DataTable({
                data: data.lista,
				"bDestroy": true,
				"order": [[ 9, "desc" ]],
				buttons: [
					{
						extend: 'excelHtml5',
						exportOptions: {
							columns: ':visible'
						},
						className: 'btn btn-default btn-success',
						footer: true
					},
					{
						extend: 'colvis',
						text: '<i class="icon-three-bars"></i> <span class="caret"></span>',
						className: 'btn bg-blue btn-icon',
						collectionLayout: 'fixed two-column'
					}
				],
				"columns": [
					{ "data": "idproducto", "visible": false }, //0
					{ "data": "codigo", "visible": true }, //0
					{ "data": "nombre", "visible": true }, //0
					{ "data": "nombre_unidad", "visible": true }, //0
					{ "data": "marca", "visible": false }, //0
					{ "data": "nombre_cageoria", "visible": false }, //0
					{ "data": "stock_actual", "visible": false }, //0
					{ "data": "cantidad_vendida", "visible": true }, //0
					{ "data": "id_cod_moneda", "visible": true }, //0
					{ "data": "precio_venta_promedio", "visible": true, className: "text-right" }, //9
					{ "data": "igv", "visible": false, className: "text-right" }, //10
					{ "data": "subtotal", "visible": false, className: "text-right" }, //11
					{ "data": "total", "visible": true, className: "bg-info text-right"}, //12
					{ "data": "precio_compra", "visible": false, className: "text-right" }, //13 { "visible": false },
					{ "data": "precio_compra", "visible": false, className: "text-right" }, //14 { "visible": false },
					{ "data": "costo_total_item", "visible": true, className: "bg-primary text-right"}, //15 { "visible": false },
					{ "data": "utilidad", "visible": true, className: "bg-success text-right"}, //16 { "visible": false },
				  ],
				stateSave: false,
				initComplete: function(){
					$(light).unblock();
				},
				"footerCallback": function ( row, data, start, end, display ) {
					var api = this.api(), data;
					
					var sum_igv = 0; //8
					var sum_subtotal = 0; //9
					var sum_total = 0; //10
					var sum_costo_promedio_unitario = 0; //11
					var sum_costo_promedio_total = 0; //12
					var sum_utilidad = 0; //13

                    // converting to interger to find total
                    var intVal = function ( i ) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '')*1 :
                            typeof i === 'number' ?
                                i : 0;
                    };
         
					// suma IGV
					if (api.column(10, {search:'applied'}).data().length > 0) {
                        sum_igv = api
									.column( 10, {search:'applied'} )
									.data()
									.reduce( function (a, b) {
											return intVal(a) + intVal(b);
									});
					}
					
					if (api.column(11, {search:'applied'}).data().length > 0) {
                        sum_subtotal = api
									.column( 11, {search:'applied'} )
									.data()
									.reduce( function (a, b) {
											return intVal(a) + intVal(b);
									});
					}
					
					if (api.column(12, {search:'applied'}).data().length > 0) {
                        sum_total = api
									.column( 12, {search:'applied'} )
									.data()
									.reduce( function (a, b) {
											return intVal(a) + intVal(b);
									});
					}

					if (api.column(13, {search:'applied'}).data().length > 0) {
                        sum_costo_unitario = api
									.column( 13, {search:'applied'} )
									.data()
									.reduce( function (a, b) {
											return intVal(a) + intVal(b);
									});
					}
					

					if (api.column(14, {search:'applied'}).data().length > 0) {
                        sum_costo_promedio_unitario = api
									.column( 14, {search:'applied'} )
									.data()
									.reduce( function (a, b) {
											return intVal(a) + intVal(b);
									});
					}
					
					if (api.column(15, {search:'applied'}).data().length > 0) {
                        sum_costo_promedio_total = api
									.column( 15, {search:'applied'} )
									.data()
									.reduce( function (a, b) {
											return intVal(a) + intVal(b);
									});
					}
					
					//suma de la utilidad
					if (api.column(16, {search:'applied'}).data().length > 0) {
                        sum_utilidad = api
									.column( 16, {search:'applied'} )
									.data()
									.reduce( function (a, b) {
										return intVal(a) + intVal(b);
									});
					}
                    
					// Update footer by showing the total with the reference of the column index 
					$( api.column( 1 ).footer() ).html('TOTALES');
                    $( api.column( 10 ).footer() ).html(round_math(sum_igv, 3));
                    $( api.column( 11 ).footer() ).html(round_math(sum_subtotal, 3));
                    $( api.column( 12 ).footer() ).html(round_math(sum_total, 3));
					$( api.column( 13 ).footer() ).html(round_math(sum_costo_unitario, 3));
                    $( api.column( 14 ).footer() ).html(round_math(sum_costo_promedio_unitario, 3));
                    $( api.column( 15 ).footer() ).html(round_math(sum_costo_promedio_total, 3));
                    $( api.column( 16 ).footer() ).html(round_math(sum_utilidad, 3));
                },
			});
		}
		else {
			swal({
				title: data.titulo,
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