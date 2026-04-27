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
	$(".btn_generar_reporte").click(function() {
        tbl_lista_reporte.ajax.reload(null, false);
    });


    $(".control_fecha_inicio").daterangepicker({
		singleDatePicker: true, 
		locale: { format: 'DD/MM/YYYY H:mm:ss' },
		timePicker: true
	}, function(start, end) {
		$("#fecha_inicio_valor").val(start.format('YYYY-MM-DD H:mm:ss'));
	});

	$(".control_fecha_fin").daterangepicker({
		singleDatePicker: true, 
		locale: { format: 'DD/MM/YYYY H:mm:ss' },
		timePicker: true
	}, function(start, end) {
		$("#fecha_fin_valor").val(end.format('YYYY-MM-DD H:mm:ss'));
	});



	get_reporte_consolidado_productos();
});

function get_reporte_consolidado_productos() {
    tbl_lista_reporte = $('#tbl_lista_reporte').DataTable( {
		"processing": true,
		"serverSide": true,
		"ajax":{
			url :"/facturacionv8/reportes/get_reporte_consolidado_productos", // json datasource
			data: function(d) {
                return $.extend({}, d, serializeData());
            },
			type: "post",
			error: function(){
				$(".tbl_lista_reporte-error").html("");
				$("#tbl_lista_reporte").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
				$("#tbl_lista_reporte_processing").css("display","none");
				
			}
		},
		"order": [[ 0, "desc" ]],
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
                        text: '<i class="icon-file-excel position-left"></i> Formato Guía Remisión</span>',
                        action: function(e, dt, node, config) {
                            var data = serializeData();
                            var queryString = $.param(data);
                            window.open("/facturacionv8/reportes/get_formato_importacion_guiaremision?" + queryString, "_self");
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
            { "data": "fecha_registro", 	"visible": true },
            { "data": "nombre_documento", 	"visible": true },
            { "data": "serie_comprobante", 	"visible": true },
            { "data": "numero_comprobante", 	"visible": true },
            { "data": "id_producto", 	"visible": true },
            { "data": "producto_codigo", 	"visible": true },
            { "data": "producto_nombre", 	"visible": true },
            { "data": "cantidad_total", 	"visible": true },
            { "data": "tipo_unidad", 	"visible": false },
            { "data": "id_unidad_medida", 	"visible": false },
            { "data": "unidad_medida", 	"visible": true },
			{ "data": "id_presentacion", 	"visible": false },
          ],
        /*"columnDefs": [
            { className: "bg-success-600", "targets": [ 0, 1, 2, 3, 4 ] },
            { className: "bg-primary-600", "targets": [ 5, 6, 7, 8, 9, 10, 11, 12 ] }
        ],*/
		orderCellsTop: true,
		fixedHeader: true,
		"bDestroy": true,
		initComplete: function() {
            
		}
	} );
}

function serializeData() {
    var datastring = {
        vendedores: $("#select_vendedor").val(),
        sucursales: $("#select_sucursal").val(),
        fecha_inicio: $("#fecha_inicio_valor").val(),
        fecha_fin: $("#fecha_fin_valor").val(),
        tipos_monedas: $("#id_cod_moneda").val(),
        tipos_documentos: $("#select_tipo_comprobante").val(),
        estado_documento: $("#estado_documento").val()
    };
    return datastring;
}