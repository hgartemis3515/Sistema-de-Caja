var tbl_lista_documentos;
var tbl_lista_documentos_detalle;

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
	
    
	$(".select_minimizado").select2({
        minimumResultsForSearch: -1
	});
	$('.multiselect').multiselect();
    get_lista_vendedores($("#select_vendedor"));
    get_lista_sucursales($("#select_sucursal"));

	$(".control_fecha").daterangepicker({singleDatePicker: true, locale: { format: 'DD/MM/YYYY' }});
    
    $(".btn_generar_reporte_detallado").click(reporte_detallado);
	
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
		url: '/facturacionv8/reportes/get_reporte_detallado_compras',
		data: datastring,
		method: 'POST',
		dataType: 'json'
	}).then(function(data){
		if(data.respuesta == 'ok'){
			tbl_lista_documentos = $('#tbl_lista_documentos').DataTable({
                data: data.lista_documentos,
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
						extend: 'colvis',
						text: '<i class="icon-three-bars"></i> <span class="caret"></span>',
						className: 'btn bg-blue btn-icon',
						collectionLayout: 'fixed four-column'
					}
				],

                "columns": [
					{ "data": "fecha_registro", 	    "visible": true },
                    { "data": "fecha_comprobante", 	    "visible": true },
                    { "data": "id_tipodoc_electronico", "visible": true },
                    { "data": "nombre_doc_electronico", "visible": true },
                    { "data": "serie_comprobante", 	    "visible": true },
                    { "data": "numero_comprobante",     "visible": true },

                    { "data": "id_proveedor", 	        "visible": false },
                    { "data": "proveedor_num_ruc", 	    "visible": true },
                    { "data": "proveedor_razon_social", "visible": true },
                    { "data": "proveedor_email", 		"visible": false },
                    { "data": "proveedor_celular", 		"visible": false },
                    
                    { "data": "moneda_nombre", 			"visible": true },
                    { "data": "tipo_cambio_sunat", 		"visible": true },
                    
                    { "data": "total_gravadas", 		"visible": false },
                    { "data": "total_inafecta", 		"visible": false },
                    { "data": "total_exoneradas", 		"visible": false },
                    { "data": "total_gratuitas", 		"visible": false },
                    { "data": "total_exportacion", 		"visible": false },
                    { "data": "total_descuento", 		"visible": true },
                    { "data": "sub_total", 				"visible": true },
                    { "data": "total_igv", 				"visible": true },
                    { "data": "total_isc", 				"visible": false },
                    { "data": "total_icbper", 			"visible": true },
                    { "data": "total_otr_imp", 			"visible": false },
                    { "data": "total", 				    "visible": true },
                    
                    { "data": "nota", 				    "visible": false }
				  ],
				stateSave: true,
				"columnDefs": [
					{ className: "bg-success-600", "targets": [ 0, 1, 2, 3, 4, 5, 6 ] },
					{ className: "bg-primary-600", "targets": [ 7, 8, 9, 10, 11 ] }
				],
				initComplete: function(){
					$(light).unblock();
				}
			});
            
            tbl_lista_documentos_detalle = $('#tbl_lista_documentos_detalle').DataTable({
                data: data.detalle_documentos,
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
						extend: 'colvis',
						text: '<i class="icon-three-bars"></i> <span class="caret"></span>',
						className: 'btn bg-blue btn-icon',
						collectionLayout: 'fixed four-column'
					}
				],

                "columns": [
					{ "data": "fecha_registro", 	    "visible": true },  //1
                    { "data": "fecha_comprobante", 	    "visible": true },  //2
                    { "data": "id_tipodoc_electronico", "visible": true },  //3
                    { "data": "nombre_doc_electronico", "visible": true },  //4
                    { "data": "serie_comprobante", 	    "visible": true },
                    { "data": "numero_comprobante",     "visible": true },

                    { "data": "id_proveedor", 	        "visible": false },
                    { "data": "proveedor_num_ruc", 	    "visible": true },
                    { "data": "proveedor_razon_social", "visible": true },
                    { "data": "proveedor_email", 		"visible": false },
                    { "data": "proveedor_celular", 		"visible": false },
                    
                    { "data": "moneda_nombre", 			"visible": true },
                    { "data": "tipo_cambio_sunat", 		"visible": true },
                    
                    { "data": "total_gravadas", 		"visible": false },
                    { "data": "total_inafecta", 		"visible": false },
                    { "data": "total_exoneradas", 		"visible": false },
                    { "data": "total_gratuitas", 		"visible": false },
                    { "data": "total_exportacion", 		"visible": false },
                    { "data": "total_descuento", 		"visible": true },
                    { "data": "sub_total", 				"visible": true },
                    { "data": "total_igv", 				"visible": true },
                    { "data": "total_isc", 				"visible": false },
                    { "data": "total_icbper", 			"visible": true },
                    { "data": "total_otr_imp", 			"visible": false },
                    { "data": "total", 				    "visible": true },
                    { "data": "nota", 				    "visible": false },

                    { "data": "id_producto", 		    "visible": false },
                    { "data": "codigo_producto", 		"visible": true },
                    { "data": "producto_descripcion",   "visible": true },
                    { "data": "cantidad", 				"visible": true },
                    { "data": "unidad_medida", 			"visible": true },
                    { "data": "precio", 				"visible": true },
                    { "data": "precio_sin_igv", 		"visible": false },
                    { "data": "sub_total", 				"visible": true },
                    { "data": "igv", 				    "visible": true },
                    { "data": "importe", 				"visible": true },
                    { "data": "id_tipoafectacionigv",   "visible": false },
                    { "data": "afectacionigv_nombre", 	"visible": false },
                    { "data": "tipo_unidad", 		    "visible": false }
				  ],
				stateSave: true,
				"columnDefs": [
					{ className: "bg-success-600", "targets": [ 0, 1, 2, 3, 4, 5, 6 ] },
					{ className: "bg-primary-600", "targets": [ 7, 8, 9, 10, 11 ] }, 
                    { className: "bg-indigo-600", "targets": [ 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38 ] }, 
				],
				initComplete: function(){
					$(light).unblock();
				}
			});
		} else {
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