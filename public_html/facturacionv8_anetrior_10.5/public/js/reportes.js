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
	
	$("#select_criterio_visualizacion").select2({
        minimumResultsForSearch: -1
	});
	
	$("#rangofechas").daterangepicker(
	{
		singleDatePicker: false,
		startDate: moment().subtract(30, 'days'),
		endDate: moment(),
		minDate: moment().subtract(365, 'days'),
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
			$("#fechainicio").val(start.format('YYYY-MM-DD'));
			$("#fechafinal").val(end.format('YYYY-MM-DD'));
		}
	);

	$("#fechainicio").val(moment().subtract(30, 'days').format('YYYY-MM-DD'));
	$("#fechafinal").val(moment().format('YYYY-MM-DD'));

	$("#btn_generar_reporte").click(generar_reporte);
	generar_reporte();

	
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

    var grafico_ventas;
    var grafico_ventas_options;

    require.config({
        paths: {
            echarts: '/facturacionv8/template/assets/js/plugins/visualization/echarts'
        }
    });

    require(
        [
            'echarts',
            'echarts/theme/limitless',
            'echarts/chart/line'
        ],

        function (ec, limitless) {
            grafico_ventas = ec.init(document.getElementById('grafico_ventas'), limitless);
			console.log(ec);
            grafico_ventas_options = {
                // Setup grid
                grid: { x: 60, x2: 60, y: 35, y2: 25 },
                // Add tooltip
                tooltip: {
                    trigger: 'axis'
                },
                // Add legend
                legend: {
                    data: []
                },
                // Enable drag recalculate
                calculable: true,
                // Horizontal axis
                xAxis: [{
                    type: 'category',
                    boundaryGap: false,
                    data: []
                }],
                // Add custom colors
                color: ['#29B6F6'],
                // Vertical axis
                yAxis: [{
                    type: 'value'
                }],
                // Add series
                series: []
            };
            
            window.onresize = function () {
                setTimeout(function () {                    
                    grafico_ventas.resize();
                }, 200);
            }
        }
	);
	
	var fecha_inicio = $("#fechainicio").val();
	var fecha_fin = $("#fechafinal").val();
	var intervalo = $("#select_criterio_visualizacion").val();

	$.ajax({
        url : '/facturacionv8/reportes/crear_reporte',
        method :  'POST',
		dataType : "json",
		data: {fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, intervalo: intervalo}
    }).then(function(data){
		if(data.respuesta == 'ok') {
			$(light).unblock();
			$("#total_facturas").html('S/ ' + data.totales.cpe_01.total);
			$("#total_boletas").html('S/ ' + data.totales.cpe_03.total);
			$("#total_notas_credito").html('S/ ' + data.totales.cpe_07.total);
			$("#total_notas_debito").html('S/ ' + data.totales.cpe_08.total);

			var series = [];
			var serie;
			var legend = [];  
			var colores = ['#4caf50', '#ff5722', '#5c6bc0', '#ec407a', '#03a9f4'];         
			var id_color = 0;
			$.each(data.data.series, function(key, val){
				serie = {
					color: colores[id_color],
					name: val.name,
					type: 'line',
					smooth: true,
					itemStyle: {normal: {areaStyle: {type: 'default'}}},
					data: val.data
				};
				series.push(serie);
				console.log(serie);
				legend.push(val.name);

				id_color++;
			});

			grafico_ventas_options.series = series;
			grafico_ventas_options.legend.data = legend;
			grafico_ventas_options.xAxis[0].data = data.data.items;
			grafico_ventas.setOption(grafico_ventas_options);

			$('#tbl_detalle_documentos').DataTable({
                data: data.detalle_comprobantes,
                "bDestroy": true,
                buttons: {
                    dom: {
                        button: {
                            className: 'btn btn-default'
                        }
                    },
                    buttons: [
                        'excelHtml5',
                        'csvHtml5'
                    ]
				},
				columnDefs: [
					{
						targets: [13, 14, 15, 16, 17, 18, 19],
						className: 'row_rigth_text'
					}
				  ]
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