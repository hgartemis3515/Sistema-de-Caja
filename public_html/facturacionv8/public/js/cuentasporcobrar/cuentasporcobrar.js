var dataTable_docs_sunat;
var dataTable_notas_de_venta;

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
		get_totales_monto_por_cobrar();
	});
  
	$(".control_fecha_fin").daterangepicker({
		singleDatePicker: true, 
		locale: { format: 'DD/MM/YYYY' },
		timePicker: false
	}, function(start, end) {
		$("#fecha_fin_valor").val(end.format('YYYY-MM-DD'));
		get_lista_documentos();
		get_totales_monto_por_cobrar();
	});
	
	$(".select_tipoventa").select2({
        minimumResultsForSearch: -1
	});

	$('.multiselect').multiselect();

	sugerencias_clientes($("#idcliente"));

	$(".filtro_select").change(function() {
		get_lista_documentos(); 
		get_totales_monto_por_cobrar(); 
	});

	get_lista_documentos();  
	get_totales_monto_por_cobrar();

	$(".control_fecha").daterangepicker({singleDatePicker: true, locale: { format: 'DD/MM/YYYY' }});
});

function get_totales_monto_por_cobrar() {
	var datastring = $("#frm_filtros").serializeArray();
	$.ajax({
		url: "/facturacionv8/cuentasporcobrar/get_totales_monto_por_cobrar",
		type: "POST",
		dataType : "json",
		data: datastring,
		success: function(data) {
			console.log(data);
			if(data.respuesta == 'ok') {
				console.log(data);
				$("#monto_deuda_facturas_soles").html(data.facturas_soles);
				$("#monto_deuda_boletas_soles").html(data.boletas_soles);
				$("#monto_deuda_nventas_soles").html(data.notas_venta_soles);
				$("#monto_deuda_total_soles").html(data.total_soles);

				$("#monto_deuda_facturas_dolares").html(data.boletas_dolares);
				$("#monto_deuda_boletas_dolares").html(data.facturas_dolares);
				$("#monto_deuda_nventas_dolares").html(data.notas_venta_dolares);
				$("#monto_deuda_total_dolares").html(data.total_dolares);
			}
		}
	});
}

function get_lista_documentos() {
	var datastring = $("#frm_filtros").serializeArray();
	
	dataTable_docs_sunat = $('#tbl_lista_cpe').DataTable({
		"processing": true,
        "dom": 'Blfrtip',
		"serverSide": true,
		"ajax":{
			url :"/facturacionv8/cuentasporcobrar/get_lista_cpe", // json datasource
			data: {fecha_inicio_valor: $("#fecha_inicio_valor").val(), fecha_fin_valor: $("#fecha_fin_valor").val(), idcliente: $("#idcliente").val(), tipo_venta: $("#select_tipoventa").val(), tipos_documentos: ($("#select_tipo_comprobante").val()).join(',')},
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
				text: '<i class="icon-file-excel position-left"></i> Descargar Reporte</span>',
				className: 'btn bg-blue btn-icon',
				action: function ( e, dt, node, config ) {
					var params = dt.ajax.params();
					var url = "/facturacionv8/cuentasporcobrar/get_reporte_excel_cpe";
					var data = {
						fecha_inicio_valor: $("#fecha_inicio_valor").val(),
						fecha_fin_valor: $("#fecha_fin_valor").val(),
						idcliente: $("#idcliente").val(),
						tipo_venta: $("#select_tipoventa").val(),
						tipos_documentos: ($("#select_tipo_comprobante").val()).join(','),
						draw: params.draw,
						start: params.start,
						length: params.length,
						search: params.search.value,
						order: params.order
					};
					
					axios.get(url, {
						params: data,
						responseType: 'blob' // Establecer el tipo de respuesta como blob
					}).then(function (response) {
						var blob = new Blob([response.data], {type: 'application/vnd.ms-excel'}); // Crear un blob con la respuesta
						var downloadUrl = window.URL.createObjectURL(blob); // Crear un URL de descarga para el blob
						var a = document.createElement("a"); // Crear un elemento <a> para el enlace de descarga
						a.href = downloadUrl; // Establecer el atributo href del enlace como el URL de descarga
						a.download = "reporte_cuentas_por_cobrar.xlsx"; // Establecer el atributo download del enlace como el nombre del archivo de descarga
						document.body.appendChild(a); // Agregar el enlace al cuerpo del documento
						a.click(); // Simular un clic en el enlace para descargar el archivo
						a.remove(); // Eliminar el enlace del cuerpo del documento después de la descarga
					}).catch(function (error) {
						// Manejar el error
					});
				}
			}
        ],
		"columns": [
            { "data": "fecha_registro", "visible": true},
			{ "data": "doc_serie_numero", "visible": true},
			{ "data": "doc_serie_numero_pdf", "visible": true},
			{ "data": "ruc_nom_clie", "visible": true},
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

	dataTable_notas_de_venta = $('#tbl_lista_notasventa').DataTable({
		"processing": true,
        "dom": 'Blfrtip',
		"serverSide": true,
		"ajax":{
			url :"/facturacionv8/cuentasporcobrar/get_lista_notaventa", // json datasource
			data: {fecha_inicio_valor: $("#fecha_inicio_valor").val(), fecha_fin_valor: $("#fecha_fin_valor").val(), idcliente: $("#idcliente").val(), tipo_venta: $("#select_tipoventa").val(), tipos_documentos: ($("#select_tipo_comprobante").val()).join(',')},
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
				text: '<i class="icon-file-excel position-left"></i> Descargar Reporte</span>',
				className: 'btn bg-blue btn-icon',
				action: function ( e, dt, node, config ) {
					var params = dt.ajax.params();
					var url = "/facturacionv8/cuentasporcobrar/get_reporte_excel_nventas";
					var data = {
						fecha_inicio_valor: $("#fecha_inicio_valor").val(),
						fecha_fin_valor: $("#fecha_fin_valor").val(),
						idcliente: $("#idcliente").val(),
						tipo_venta: $("#select_tipoventa").val(),
						tipos_documentos: ($("#select_tipo_comprobante").val()).join(','),
						draw: params.draw,
						start: params.start,
						length: params.length,
						search: params.search.value,
						order: params.order
					};
					
					axios.get(url, {
						params: data,
						responseType: 'blob' // Establecer el tipo de respuesta como blob
					}).then(function (response) {
						var blob = new Blob([response.data], {type: 'application/vnd.ms-excel'}); // Crear un blob con la respuesta
						var downloadUrl = window.URL.createObjectURL(blob); // Crear un URL de descarga para el blob
						var a = document.createElement("a"); // Crear un elemento <a> para el enlace de descarga
						a.href = downloadUrl; // Establecer el atributo href del enlace como el URL de descarga
						a.download = "reporte_cuentas_por_cobrar_nventas.xlsx"; // Establecer el atributo download del enlace como el nombre del archivo de descarga
						document.body.appendChild(a); // Agregar el enlace al cuerpo del documento
						a.click(); // Simular un clic en el enlace para descargar el archivo
						a.remove(); // Eliminar el enlace del cuerpo del documento después de la descarga
					}).catch(function (error) {
						// Manejar el error
					});
				}
			}
        ],
		"columns": [
            { "data": "fecha_registro", "visible": true},
			{ "data": "doc_serie_numero", "visible": true},
			{ "data": "doc_serie_numero_pdf", "visible": true},
			{ "data": "ruc_nom_clie", "visible": true},
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