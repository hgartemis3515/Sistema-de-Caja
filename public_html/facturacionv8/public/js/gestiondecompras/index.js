var dataTable_listacompras;
var dataTable_lista_otros_docs;
var dataTable_lista_orden_compra;
$(function() {
	var switches = Array.prototype.slice.call(document.querySelectorAll('.switch2'));
    switches.forEach(function(html) {
        var switchery = new Switchery(html, {color: '#4CAF50'});
	});
	$('.multiselect').multiselect();
	
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
	
	$('.select_criterios').select2({ minimumResultsForSearch: -1 });
	
	get_lista_sucursales($("#select_sucursal"));
	get_lista_vendedores($("#select_vendedores"));

	$(".control_fecha_inicio").daterangepicker({
		singleDatePicker: true, 
		locale: { format: 'DD/MM/YYYY' },
		timePicker: false
	}, function(start, end) {
		$("#fecha_inicio_valor").val(start.format('YYYY-MM-DD'));
	});

	$(".control_fecha_fin").daterangepicker({
		singleDatePicker: true, 
		locale: { format: 'DD/MM/YYYY' },
		timePicker: false
	}, function(start, end) {
		$("#fecha_fin_valor").val(end.format('YYYY-MM-DD'));
	});

	$("#btn_busqueda_compra").click(function(e){
		let fecha_inicio = $("#fecha_inicio_valor").val();
		let fecha_fin = $("#fecha_fin_valor").val();
		let id_sucursal = $("#select_sucursal").val();
		let tipo_comprobantes = $("#select_tipo_comprobante").val();
		let id_vendedor = $("#select_vendedores").val();
		let select_tipofecha_busqueda = $("#select_tipofecha_busqueda").val();
		$("#tbl_titulo_fecha").html($("#select_tipofecha_busqueda option:selected").text());
		get_lista_comprobantes(fecha_inicio, fecha_fin, id_sucursal, id_vendedor, tipo_comprobantes, select_tipofecha_busqueda);
	});

	$("#btn_busqueda_compra").trigger('click');
});

function get_detalle_compra(idcompra){
	$.ajax({
		url : '/facturacionv8/gestiondecompras/get_detalle_compra',
		data: {idcompra: idcompra },
        method :  'POST',
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {

			var num_doc =  data.encabezado.num_doc;
			var total_gravadas =  data.encabezado.total_gravadas;
			var total_exonerado =  data.encabezado.total_exoneradas;
			var total_inafecto =  data.encabezado.total_inafecta;
			var total_gratuitas =  data.encabezado.total_gratuitas;
			var total_exportacion =  data.encabezado.total_exportacion;
			var total_icbper =  data.encabezado.total_icbper;

			if(num_doc == null){
				$(".encabezado").hide("slow");
			}else{
				$(".encabezado").show("slow");
			}
			if(total_exportacion > 0) {
				$("#row_exportacion_documento").show("slow");
			} else {
				$("#row_exportacion_documento").hide("slow");
			}
		
			if(total_exonerado > 0) {
				$("#row_exonerada_documento").show("slow");
			} else {
				$("#row_exonerada_documento").hide("slow");
			}
			
			if(total_inafecto > 0) {
				$("#row_inafecta_documento").show("slow");
			} else {
				$("#row_inafecta_documento").hide("slow");
			}
		
			if(total_gravadas > 0) {
				$("#row_gravada_documento").show("slow");
			} else {
				$("#row_gravada_documento").hide("slow");
			}
		
			if(total_gratuitas > 0) {
				$("#row_gratuita_documento").show("slow");
			} else {
				$("#row_gratuita_documento").hide("slow");
			}
		
			if(total_icbper > 0) {
				$("#row_icbper_documento").show("slow");
			} else {
				$("#row_icbper_documento").hide("slow");
			}
			$("#ruc_empresa").html("R.U.C " + data.encabezado.num_doc);
			$("#factura_empresa").html(data.encabezado.descripcion);
			$("#compra_serie").html(data.encabezado.serie_comprobante);
			$("#compra_num").html(data.encabezado.numero_comprobante);
			$("#razon_social").html("<i class='fa fa-user color-indigo'></i> " + data.encabezado.razon_social);
			$("#direccion_fiscal").html("<i class='fa fa-map-marker color-indigo'></i> " + data.encabezado.direccion_fiscal);
			$("#fecha_comprobante").html("<i class='fa fa-calendar color-indigo'></i> " + data.encabezado.fecha_comprobante);
			//Totales
			$("#gravada_documento").html(data.encabezado.total_gravadas);
			$("#exonerada_documento").html(data.encabezado.total_exoneradas);
			$("#inafecta_documento").html(data.encabezado.total_inafecta);
			$("#exportacion_documento").html(data.encabezado.total_exportacion);
			$("#igv_documento").html(data.encabezado.total_igv);
			$("#gratuita_documento").html(data.encabezado.total_gratuitas);
			$("#icbper_documento").html(data.encabezado.total_icbper);
			$("#total_documento").html(data.encabezado.total);

			if(data.encabezado.id_codigomoneda == 'USD') {
				$(".simbolo_moneda").html('$ ');
			} else {
				$(".simbolo_moneda").html('S/ ');
			}
			
			$('#tbl_lista_detalle_compra').DataTable({
				data: data.lista,
				"bDestroy": true
			});
			$( '.dt-buttons' ).hide();
		} else {
			swal({
				title: 'Error',
				text: data.mensaje,
				html: true,
				type: "error",
				confirmButtonText: "Ok",
				confirmButtonColor: "#2196F3"
			}, function(){
				
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
     
		})
  });
}

function anular_compra(id_compra) {
	var light = $('#cuerpo_comprobante');
	$(light).block({
		message: '<div class="loading"> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
					</div> <p> <br />Guardando ...</p>',
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
        url : '/facturacionv8/gestiondecompras/anular_compra',
        method :  'POST',
		dataType : "json",
		data: {id_compra: id_compra}
    }).then(function(data){

		if(data.respuesta == 'ok') {
			swal({   
				title: data.titulo,   
				text: data.mensaje,
				html: true,
				type: "warning",   
				showCancelButton: true,   
				confirmButtonColor: "#f44336",    //confirmButtonColor: "#3f51b5",   
				cancelButtonColor: "#DD6B55",
				confirmButtonText: "Si, Adelante!",   
				closeOnConfirm: false,
  				showLoaderOnConfirm: true
			}, function(isconfirmed){  
				if(isconfirmed) {
					
					$.ajax({
						url : '/facturacionv8/gestiondecompras/anular_compra',
						method :  'POST',
						dataType : "json",
						data: {id_compra: id_compra, confirmacion: 'si'}
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
								dataTable_listacompras.ajax.reload(null, false);
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

function anular_orden_compra(id_tipodoc_electronico, serie_comprobante, numero_comprobante, modalidad) {

	var light = $('#cuerpo_comprobante');
	$(light).block({
		message: '<div class="loading"> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
					</div> <p> <br />Guardando ...</p>',
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
        url : '/facturacionv8/ordendecompra/anular_orden_compra',
        method :  'POST',
		dataType : "json",
		data: {id_tipodoc_electronico: id_tipodoc_electronico, serie_comprobante: serie_comprobante, numero_comprobante: numero_comprobante, modalidad: modalidad}
    }).then(function(data){

		if(data.respuesta == 'ok') {
			swal({   
				title: data.titulo,   
				text: data.mensaje,
				html: true,
				type: "warning",   
				showCancelButton: true,   
				confirmButtonColor: "#f44336",    //confirmButtonColor: "#3f51b5",   
				cancelButtonColor: "#DD6B55",
				confirmButtonText: "Si, Adelante!",   
				closeOnConfirm: false,
  				showLoaderOnConfirm: true
			}, function(isconfirmed){  
				if(isconfirmed) {
					
					$.ajax({
						url : '/facturacionv8/ordendecompra/anular_orden_compra',
						method :  'POST',
						dataType : "json",
						data: {id_tipodoc_electronico: id_tipodoc_electronico, serie_comprobante: serie_comprobante, numero_comprobante: numero_comprobante, modalidad: modalidad, confirmacion: 'si'}
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
								dataTable_lista_orden_compra.ajax.reload(null, false);
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

function get_lista_comprobantes(fecha_inicio, fecha_fin, id_sucursal, id_vendedor, tipo_comprobantes, select_tipofecha_busqueda) {
	dataTable_listacompras = $('#tbl_lista_compras').DataTable( {
		"processing": true,
		"serverSide": true,
		"ajax":{
			url :"/facturacionv8/gestiondecompras/get_lista_compras", // json datasource
			data: {
				fecha_inicio		: fecha_inicio, 
				fecha_fin			: fecha_fin, 
				id_sucursal			: id_sucursal, 
				id_vendedor			: id_vendedor, 
				tipo_comprobantes	: tipo_comprobantes,
				tipo_compras		: 'docs_compra',
				select_tipofecha_busqueda		: select_tipofecha_busqueda
			},
			type: "post",
			error: function(){
				$(".tbl_lista_compras-error").html("");
				$("#tbl_lista_compras").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
				$("#tbl_lista_compras_processing").css("display","none");
				
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
		orderCellsTop: true,
		fixedHeader: true,
		"bDestroy": true,
		initComplete: function(){
			$("#tbl_lista_compras_wrapper").children("div.datatable-header").children("div.dt-buttons").html("<a class='btn bg-indigo legitRipple' href='/facturacionv8/gestiondecompras/registrarcompra'><i class='fa fa-plus'></i> Registrar Compra </a>");
		}
	} );

	dataTable_lista_otros_docs = $('#tbl_lista_otros_docs').DataTable( {
		"processing": true,
		"serverSide": true,
		"ajax":{
			url :"/facturacionv8/gestiondecompras/get_lista_compras", // json datasource
			data: {
				fecha_inicio		: fecha_inicio, 
				fecha_fin			: fecha_fin, 
				id_sucursal			: id_sucursal, 
				id_vendedor			: id_vendedor, 
				tipo_comprobantes	: tipo_comprobantes,
				tipo_compras		: 'otros_docs',
				select_tipofecha_busqueda		: select_tipofecha_busqueda
			},
			type: "post",
			error: function(){
				$(".tbl_lista_otros_docs-error").html("");
				$("#tbl_lista_otros_docs").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
				$("#tbl_lista_otros_docs_processing").css("display","none");
				
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
		orderCellsTop: true,
		fixedHeader: true,
		"bDestroy": true,
		initComplete: function(){
			$("#tbl_lista_otros_docs_wrapper").children("div.datatable-header").children("div.dt-buttons").html("<a class='btn bg-indigo legitRipple' href='/facturacionv8/gestiondecompras/registrarcompra'><i class='fa fa-plus'></i> Crear Documento de Compra </a>");
		}
	});

	dataTable_lista_orden_compra = $('#tbl_lista_orden_compra').DataTable( {
		"processing": true,
		"serverSide": true,
		"ajax":{
			url :"/facturacionv8/gestiondecompras/get_lista_compras", // json datasource
			data: {
				fecha_inicio		: fecha_inicio, 
				fecha_fin			: fecha_fin, 
				id_sucursal			: id_sucursal, 
				id_vendedor			: id_vendedor, 
				tipo_comprobantes	: tipo_comprobantes,
				tipo_compras		: 'ordenes_compra',
				select_tipofecha_busqueda		: select_tipofecha_busqueda
			},
			type: "post",
			error: function(){
				$(".tbl_lista_orden_compra-error").html("");
				$("#tbl_lista_orden_compra").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
				$("#tbl_lista_orden_compra_processing").css("display","none");
				
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
		orderCellsTop: true,
		fixedHeader: true,
		"bDestroy": true,
		initComplete: function(){
			$("#tbl_lista_orden_compra_wrapper").children("div.datatable-header").children("div.dt-buttons").html("<a class='btn bg-indigo legitRipple' href='/facturacionv8/gestiondecompras/registrarcompra'><i class='fa fa-plus'></i> Crear Orden de Compra </a>");
		}
	});
}

function get_data_estadistica(fecha_inicio, fecha_fin, id_sucursal = 0, id_vendedor = 0, periodo = 'dia') {
	var light = $('#cuerpo_comprobante');
	$(light).block({
		message: '<div class="loading"> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
					</div> <p> <br />Guardando ...</p>',
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
        url : '/facturacionv8/gestiondecompras/get_datos_estadisticos',
		method :  'POST',
		data: {fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, id_sucursal: id_sucursal, id_vendedor: id_vendedor, periodo: periodo},
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			generar_grafico_compras(data.series, data.periodo_array);
			generar_grafico_totales_docs(data.totales_compra);
			$(light).unblock(); 
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

function generar_grafico_totales_docs(totales_compras) {
	
	var pie_basic_element = document.getElementById('grafico_totales_compras');
	var pie_basic = echarts.init(pie_basic_element);
	var series = [];
	var legend = [];

	$.each(totales_compras, function(key, item){
		let serie_item = {value: item.value, name: item.name};
		series.push(serie_item);
		legend.push(item.name);
	});
	
	pie_basic.setOption({

		// Colors
		color: [
			'#2ec7c9','#b6a2de','#5ab1ef','#ffb980','#d87a80',
			'#8d98b3','#e5cf0d','#97b552','#95706d','#dc69aa',
			'#07a2a4','#9a7fd1','#588dd5','#f5994e','#c05050',
			'#59678c','#c9ab00','#7eb00a','#6f5553','#c14089'
		],

		// Global text styles
		textStyle: {
			fontFamily: 'Roboto, Arial, Verdana, sans-serif',
			fontSize: 13
		},

		// Add title
		title: {
			text: 'Condición de Pago',
			subtext: 'Tipos de Condiciones de Pago',
			left: 'center',
			textStyle: {
				fontSize: 17,
				fontWeight: 500
			},
			subtextStyle: {
				fontSize: 12
			}
		},

		// Add tooltip
		tooltip: {
			trigger: 'item',
			backgroundColor: 'rgba(0,0,0,0.75)',
			padding: [10, 15],
			textStyle: {
				fontSize: 13,
				fontFamily: 'Roboto, sans-serif'
			},
			formatter: "{a} <br/>{b}: S/ {c} ({d}%)"
		},

		// Add legend
		/*legend: {
			orient: 'vertical',
			top: 'center',
			left: 0,
			data: legend,
			itemHeight: 8,
			itemWidth: 8
		},*/

		// Add series
		series: [{
			name: 'Total en Soles',
			type: 'pie',
			radius: '70%',
			center: ['50%', '57.5%'],
			itemStyle: {
				normal: {
					borderWidth: 1,
					borderColor: '#fff'
				}
			},
			data: series
		}]
	});

	// Resize function
    var triggerChartResize = function() {
        pie_basic_element && pie_basic.resize();
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

function generar_grafico_compras(lista_series, periodo_array) {
	
	var area_basic_element = document.getElementById('grafico_compras_detalle');
	var area_basic = echarts.init(area_basic_element);
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
			}
		}
		
		series_array.push(serie);
		series_legend.push(key);
	});

	area_basic.setOption({

		// Define colors
		color: ['#2ec7c9','#b6a2de','#5ab1ef','#ffb980','#d87a80'],

		// Global text styles
		textStyle: {
			fontFamily: 'Roboto, Arial, Verdana, sans-serif',
			fontSize: 13
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
			data: series_legend
		},

		// Add tooltip
		tooltip: {
			trigger: 'axis',
			backgroundColor: 'rgba(0,0,0,0.75)',
			padding: [10, 15],
			textStyle: {
				fontSize: 13,
				fontFamily: 'Roboto, sans-serif'
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
        area_basic_element && area_basic.resize();
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