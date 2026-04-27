var tbl_lista_reporte;
var tbl_lista_general_ventas;

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
	
	$(".select").select2();
	$(".select_minimizado").select2({
        minimumResultsForSearch: -1
	});
	$('.multiselect').multiselect();
    //get_lista_vendedores($("#select_vendedor"));
    //get_lista_sucursales($("#select_sucursal"));
	$(".control_fecha").daterangepicker({singleDatePicker: true, locale: { format: 'DD/MM/YYYY' }});
    $(".btn_generar_reporte_detallado").click(function() {
		get_reporte_general_ventas();
		reporte_detallado();
	});
	
});

function get_reporte_general_ventas() {
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
		url: '/facturacionv8/reportes/get_reporte_general_ventas',
		data: datastring,
		method: 'POST',
		dataType: 'json'
	}).then(function(data){
		if(data.respuesta == 'ok'){
			tbl_lista_general_ventas = $('#tbl_lista_reporte_general').DataTable({
                data: data.lista,
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
						extend: 'collection',
						text: '<i class="icon-plus-circle2 position-left"></i> Opciones</span>',
						className: 'btn btn-info',
						buttons: [
							{ 
								text: '<i class="icon-file-excel position-left"></i> Formato C.M.</span>',
								action: function ( e, dt, node, config ) {
									var datastring = $("#frm_reporte_detallado").serializeArray();
									datastring.push({ name: "sucursales", value: $("#select_sucursal").val() });
									datastring.push({ name: "vendedores", value: $("#select_vendedor").val() });
									datastring.push({ name: "tipos_documentos", value: $("#select_tipo_comprobante").val() });
									datastring.push({ name: "estados_documentos", value: $("#select_estado").val() });
									datastring.push({ name: "tipos_monedas", value: $("#id_cod_moneda").val() });

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
									
									window.open("/facturacionv8/reportes/get_formato_cm?" + data_url_string, "_self");

								}
							}
						],
						fade: true
					},
					{
						extend: 'colvis',
						text: '<i class="icon-three-bars"></i> <span class="caret"></span>',
						className: 'btn bg-blue btn-icon',
						collectionLayout: 'fixed four-column'
					}
				],
				"columns": [
					{ "data": "nombre_sucursal", 				"visible": true }, //0
					{ "data": "nombre_vendedor", 				"visible": true }, //1
					{ "data": "nombre_tipo_documento", 			"visible": true }, //2
					{ "data": "fecha_comprobante", 				"visible": true }, //3

					{ "data": "id_codigomoneda", 				"visible": false }, //3
					{ "data": "tipo_cambio", 					"visible": false }, //3
					
					{ "data": "condicion_pago_tipo", 			"visible": false }, //4
					{ "data": "condicion_pago_nombre", 			"visible": false }, //5
					{ "data": "cpago_nrooperacion", 			"visible": false }, //6
					{ "data": "cpago_fechadeposito", 			"visible": false }, //7
					{ "data": "cpago_idbanco", 					"visible": false }, //8
					{ "data": "nombre_banco_deposito", 			"visible": false }, //9
					{ "data": "transporte_nro_placa", 			"visible": true }, //10
					
					{ "data": "serie_correlativo", 				"visible": true }, //11
					{ "data": "estado_envio_sunat", 			"visible": true }, //12

					{ "data": "nro_orden", 						"visible": true }, //11
					{ "data": "docs_relacionados", 				"visible": true }, //12
					
					{ "data": "id_tipodoc_cliente", 				"visible": true }, //13
					{ "data": "numero_doc_cliente", 				"visible": true }, //14
					{ "data": "nombre_cliente_text", 				"visible": true }, //15

					{ "data": "direccion_cliente", 				"visible": false }, //16
					{ "data": "id_ubigeo_cliente", 				"visible": false }, //17
					{ "data": "ubigeo_dir_cliente", 			"visible": false }, //18
					
					{ "data": "total_exportacion", 				"visible": false }, //19
					{ "data": "total_gravadas", 				"visible": true }, //20
					{ "data": "total_exoneradas", 				"visible": true }, //21
					{ "data": "total_inafecta", 				"visible": false }, //22
					{ "data": "total_gratuitas", 				"visible": false }, //23
					{ "data": "total_icbper", 					"visible": true }, //24
					{ "data": "total_descuento", 				"visible": true }, //25
					{ "data": "sub_total", 						"visible": true }, //26
					{ "data": "porcentaje_igv", 				"visible": false }, //27 
					{ "data": "total_igv", 						"visible": true }, //28
					{ "data": "total_isc", 						"visible": false }, //29
					{ "data": "total_otr_imp", 					"visible": false }, //30
					{ "data": "total", 							"visible": true }, //31

					{ "data": "id_tipo_comprobante_modifica", 	"visible": false }, //32
					{ "data": "serie_documento_modifica", 		"visible": false }, //33
					{ "data": "nro_documento_modifica", 		"visible": false }, //34
					{ "data": "nota", 							"visible": true }, //35
				  ],
				stateSave: false,
				"columnDefs": [],
				initComplete: function(){
					$(light).unblock();
				}
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

function reporte_detallado() {
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
		url: '/facturacionv8/reportes/get_reportedetallado',
		data: datastring,
		method: 'POST',
		dataType: 'json'
	}).then(function(data){
		if(data.respuesta == 'ok'){
			tbl_lista_reporte = $('#tbl_lista_reporte').DataTable({
                data: data.lista,
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
						extend: 'collection',
						text: '<i class="icon-plus-circle2 position-left"></i> Opciones</span>',
						className: 'btn btn-info',
						buttons: [
							{ 
								text: '<i class="icon-file-excel position-left"></i> Formato C.M.</span>',
								action: function ( e, dt, node, config ) {
									var datastring = $("#frm_reporte_detallado").serializeArray();
									datastring.push({ name: "sucursales", value: $("#select_sucursal").val() });
									datastring.push({ name: "vendedores", value: $("#select_vendedor").val() });
									datastring.push({ name: "tipos_documentos", value: $("#select_tipo_comprobante").val() });
									datastring.push({ name: "estados_documentos", value: $("#select_estado").val() });
									datastring.push({ name: "tipos_monedas", value: $("#id_cod_moneda").val() });

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
									
									window.open("/facturacionv8/reportes/get_formato_cm?" + data_url_string, "_self");

								}
							}
						],
						fade: true
					},
					{
						extend: 'colvis',
						text: '<i class="icon-three-bars"></i> <span class="caret"></span>',
						className: 'btn bg-blue btn-icon',
						collectionLayout: 'fixed four-column'
					}
				],
				"columns": [
					{ "data": "nombre_sucursal", 				"visible": true }, //0
					{ "data": "nombre_vendedor", 				"visible": true }, //1
					{ "data": "nombre_tipo_documento", 			"visible": true }, //2
					{ "data": "fecha_comprobante", 				"visible": true }, //3

					{ "data": "id_codigomoneda", 				"visible": false }, //4
					{ "data": "tipo_cambio", 					"visible": false }, //5
					
					{ "data": "condicion_pago_tipo", 			"visible": false }, //6
					{ "data": "condicion_pago_nombre", 			"visible": false }, //7
					{ "data": "cpago_nrooperacion", 			"visible": false }, //8
					{ "data": "cpago_fechadeposito", 			"visible": false }, //9
					{ "data": "cpago_idbanco", 					"visible": false }, //10
					{ "data": "nombre_banco_deposito", 			"visible": false }, //11
					{ "data": "transporte_nro_placa", 			"visible": true }, //12
					
					{ "data": "serie_correlativo", 				"visible": true }, //13
					{ "data": "estado_envio_sunat", 			"visible": true }, //14
					
					{ "data": "id_tipodoc_cliente", 				"visible": true }, //15
					{ "data": "numero_doc_cliente", 				"visible": true }, //14
					{ "data": "nombre_cliente_text", 				"visible": true }, //16

					{ "data": "direccion_cliente", 				"visible": false }, //17
					{ "data": "id_ubigeo_cliente", 				"visible": false }, //18
					{ "data": "ubigeo_dir_cliente", 			"visible": false }, //19
					
					{ "data": "total_exportacion", 				"visible": false }, //20
					{ "data": "total_gravadas", 				"visible": true }, //21
					{ "data": "total_exoneradas", 				"visible": true }, //22
					{ "data": "total_inafecta", 				"visible": false }, //23
					{ "data": "total_gratuitas", 				"visible": false }, //24
					{ "data": "total_icbper", 					"visible": true }, //25
					{ "data": "total_descuento", 				"visible": true }, //26
					{ "data": "sub_total", 						"visible": true }, //27
					{ "data": "porcentaje_igv", 				"visible": false }, //28 
					{ "data": "total_igv", 						"visible": true }, //29
					{ "data": "total_isc", 						"visible": false }, //30
					{ "data": "total_otr_imp", 					"visible": false }, //31
					{ "data": "total", 							"visible": true }, //32

					{ "data": "id_tipo_comprobante_modifica", 	"visible": false }, //33
					{ "data": "serie_documento_modifica", 		"visible": false }, //34
					{ "data": "nro_documento_modifica", 		"visible": false }, //35
					{ "data": "nota", 							"visible": true }, //36

					{ "data": "item", 							"visible": true }, //37
					{ "data": "idproducto", 					"visible": false }, //38
					{ "data": "codigo", 						"visible": true }, //38
					{ "data": "nombre_producto_original", 		"visible": false }, //39
					{ "data": "producto_servicio", 				"visible": true }, //39
					{ "data": "marca", 							"visible": true }, //40
					{ "data": "nombre_categoria",				"visible": true }, //41
					{ "data": "cantidad", 						"visible": true }, //42
					{ "data": "nombre_unidad", 					"visible": true }, //43
					{ "data": "precio_sin_igv", 				"visible": true }, //44
					{ "data": "igv", 							"visible": false }, //45
					{ "data": "isc", 							"visible": true }, //46
					{ "data": "icbper", 						"visible": true }, //47
					{ "data": "subtotal", 						"visible": true }, //48

					{ "data": "costo_unitario", 				"visible": true }, //49
					{ "data": "costoxcantidad", 				"visible": true }, //50
					{ "data": "utilidad", 						"visible": true }, //51
					{ "data": "stock", 							"visible": true }, //52
				  ],
				stateSave: false,
				"columnDefs": [
					{ className: "bg-success-600", "targets": [ 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52 ] },
					{ className: "bg-primary-600", "targets": [ 53, 54, 55 ] },
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

function verificar_columnas_seleccionadas() {
	var array_columnas_seleccionadas = [0, 1, 2, 3];
	var array_columnas_totales = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30];
	$.each(array_columnas_totales, function( index, key_column ){
		if(checkValue(key_column, array_columnas_seleccionadas) == 'existe') {
			tbl_lista_reporte.column(key_column).visible(true);
		} else {
			tbl_lista_reporte.column(key_column).visible(false);
		}
	});
}

function checkValue(value,arr){
	var status = 'no_existe';
   
	for(var i=0; i<arr.length; i++){
	  var name = arr[i];
	  if(name == value){
		status = 'existe';
		break;
	  }
	}
  
	return status;
}