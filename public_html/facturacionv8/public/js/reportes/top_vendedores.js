$(function() {
	$(".select").select2();
	$(".select_minimizado").select2({
        minimumResultsForSearch: -1
	});
	$('.multiselect').multiselect();
	$(".control_fecha").daterangepicker({singleDatePicker: true, locale: { format: 'DD/MM/YYYY' }});

	$(".btn_generar_reporte_topvendedores").click(reporte_detallado);
});

function reporte_detallado(){
	var light = $("#content_list_clientes");

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
	
	var datastring = $("#frm_reporte_top_vendedores").serializeArray();
	datastring.push({ name: "sucursales", value: $("#select_sucursal").val() });
	datastring.push({ name: "vendedores", value: $("#select_vendedor").val() });
	datastring.push({ name: "tipos_documentos", value: $("#select_tipo_comprobante").val() });
	datastring.push({ name: "estados_documentos", value: $("#select_estado").val() });
	datastring.push({ name: "tipos_monedas", value: $("#id_cod_moneda").val() });

	$.ajax({
		url: '/facturacionv8/reportes/get_top_vendedores',
		data: datastring,
		method: 'POST',
		dataType: 'json'
	}).then(function(data){
		if(data.respuesta == 'ok'){
			tbl_lista_reporte = $('#tbl_list_vendedores').DataTable({
                data: data.lista,
                "bDestroy": true,
				buttons: [
					{
						extend: 'excelHtml5',
						exportOptions: {
							columns: ':visible'
						},
						className: 'btn btn-default btn-success'
					}
				],
				"columns": [
					{ "data": "vendedor", "visible": true, "className": 'text-left'}, //0
					{ "data": "total_factura", "visible": true, "className": 'text-success', 
						render: function ( data, type, row ) {
							var moneda = 'S/ ';
							if($("#id_cod_moneda").val() == 'USD') {
								moneda = '$ ';
							} else {
								moneda = 'S/ ';
							}
							return '<a href="javascript:void(0)" class="text-success" onclick="mostrar_popup_detalle(' + row.idvendedor + ',' + "'01'" + ' )">' + moneda + row.total_factura + '</a>';
						}, 
					},
					{ "data": "total_boleta", "visible": true, "className": 'text-success', 
						render: function ( data, type, row ) {
							var moneda = 'S/ ';
							if($("#id_cod_moneda").val() == 'USD') {
								moneda = '$ ';
							} else {
								moneda = 'S/ ';
							}
							return '<a href="javascript:void(0)" class="text-success" onclick="mostrar_popup_detalle(' + row.idvendedor + ',' + "'03'" + ' )">' + moneda + row.total_boleta + '</a>';
						}, 
					},
					{ "data": "total_nota_venta", "visible": true, "className": 'text-success', 
						render: function ( data, type, row ) {
							var moneda = 'S/ ';
							if($("#id_cod_moneda").val() == 'USD') {
								moneda = '$ ';
							} else {
								moneda = 'S/ ';
							}
							return '<a href="javascript:void(0)" class="text-success" onclick="mostrar_popup_detalle(' + row.idvendedor + ',' + "'77'" + ' )">' + moneda + row.total_nota_venta + '</a>';
						}, 
					},
					{ "data": "total_nota_debito", "visible": true, "className": 'text-success', 
						render: function ( data, type, row ) {
							var moneda = 'S/ ';
							if($("#id_cod_moneda").val() == 'USD') {
								moneda = '$ ';
							} else {
								moneda = 'S/ ';
							}
							return '<a href="javascript:void(0)" class="text-success" onclick="mostrar_popup_detalle(' + row.idvendedor + ',' + "'08'" + ' )">' + moneda + row.total_nota_debito + '</a>';
						}, 
					},
					{ "data": "total_nota_credito", "visible": true, "className": 'text-danger', 
						render: function ( data, type, row ) {
							var moneda = 'S/ ';
							if($("#id_cod_moneda").val() == 'USD') {
								moneda = '$ ';
							} else {
								moneda = 'S/ ';
							}
							return '<a href="javascript:void(0)" class="text-danger" onclick="mostrar_popup_detalle(' + row.idvendedor + ',' + "'07'" + ' )">' + moneda + row.total_nota_credito + '</a>';
						}, 
					},
					{ "data": "total", "visible": true, "className": 'text-success', 
						render: function ( data, type, row ) {
							var moneda = 'S/ ';
							if($("#id_cod_moneda").val() == 'USD') {
								moneda = '$ ';
							} else {
								moneda = 'S/ ';
							}
							return '<a href="javascript:void(0)" class="text-success" onclick="mostrar_popup_detalle(' + row.idvendedor + ',' + "'01,03,07,08,77'" + ' )">' + moneda + row.total + '</a>';
						}, 
					},
					{ "data": "total", "visible": false, "className": 'text-success', 
						render: function ( data, type, row ) {
							return row.total;
						}, 
					},
					{ "data": "total_clientes", "visible": true, "className": 'text-primary', 
						render: function ( data, type, row ) {
							return row.total_clientes;
						}, 
					},
					{ "data": "promedio_num_clie", "visible": true, "className": 'text-primary', 
						render: function ( data, type, row ) {
							return row.promedio_num_clie;
						}, 
					},
					{ "total": "promedio_total", "visible": true, "className": 'text-success', 
						render: function ( data, type, row ) {
							var moneda = 'S/ ';
							if($("#id_cod_moneda").val() == 'USD') {
								moneda = '$ ';
							} else {
								moneda = 'S/ ';
							}
							return '<a href="javascript:void(0)" class="text-success" onclick="mostrar_popup_detalle(' + row.idvendedor + ',' + "'01,03,07,08,77'" + ' )">' + moneda + row.promedio_total + '</a>';
						}, 
					}
				],
				initComplete: function(){
					$(light).unblock();
				},
				"order": [[ 7, "desc" ]]
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

function mostrar_popup_detalle(id_vendedor, tipos_documentos) {
	$("#info_top_client").modal("show");
	var light = $("#body_info_top_client");
	get_reporte_documentos(id_vendedor, tipos_documentos, 'documentos', light);
	get_reporte_detallado_by_cliente(id_vendedor, tipos_documentos, 'detallado', light)
}

function get_reporte_documentos(id_vendedor, tipos_documentos, tiporeporte, light) {
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

	var datastring = $("#frm_reporte_top_vendedores").serializeArray();
	datastring.push({ name: "sucursales", value: $("#select_sucursal").val() });
	datastring.push({ name: "vendedores", value: $("#select_vendedor").val() });
	//datastring.push({ name: "tipos_documentos", value: $("#select_tipo_comprobante").val() });
	datastring.push({ name: "estados_documentos", value: $("#select_estado").val() });
	datastring.push({ name: "tipos_monedas", value: $("#id_cod_moneda").val() });
	datastring.push({ name: "id_vendedor", value: id_vendedor });
	datastring.push({ name: "tipos_documentos", value: tipos_documentos });
	datastring.push({ name: "tiporeporte", value: tiporeporte });

	$.ajax({
		url: '/facturacionv8/reportes/get_detalle_docs_by_idvendedor',
		data: datastring,
		method: 'POST',
		dataType: 'json'
	}).then(function(data){
		if(data.respuesta == 'ok'){
			tbl_lista_reporte = $('#tbl_lista_doc').DataTable({
                data: data.lista,
                "bDestroy": true,
				buttons: [
					{
						extend: 'excelHtml5',
						exportOptions: {
							columns: ':visible'
						},
						className: 'btn btn-default btn-success'
					},
					{
						extend: 'colvis',
						text: '<i class="icon-three-bars"></i> <span class="caret"></span>',
						className: 'btn bg-blue btn-icon',
						collectionLayout: 'fixed four-column'
					}
				],
				"columns": [
					{ "data": "datos_sucursal", "visible": false, "className": '', 
						render: function ( data, type, row ) {
							return row.datos_sucursal;
						}
					},
					{ "data": "datos_vendedor", "visible": false, "className": '', 
						render: function ( data, type, row ) {
							return row.datos_vendedor;
						}
					},
					{ "data": "fecha_registro", "visible": false, "className": '', 
						render: function ( data, type, row ) {
							return row.fecha_registro;
						}
					},
					{ "data": "fecha_comprobante", "visible": true, "className": '', 
						render: function ( data, type, row ) {
							return row.fecha_comprobante;
						}
					},
					{ "data": "fecha_vto_comprobante", "visible": false, "className": '', 
						render: function ( data, type, row ) {
							return row.fecha_vto_comprobante;
						}
					},
					{ "data": "id_tipodoc_electronico", "visible": false, "className": '', 
						render: function ( data, type, row ) {
							return row.id_tipodoc_electronico;
						}
					},
					{ "data": "serie_comprobante", "visible": true, "className": '', 
						render: function ( data, type, row ) {
							return row.serie_comprobante;
						}
					},
					{ "data": "numero_comprobante", "visible": true, "className": '', 
						render: function ( data, type, row ) {
							return row.numero_comprobante;
						}
					},

					{ "data": "condicion_pago", "visible": true, "className": '', 
						render: function ( data, type, row ) {
							return row.condicion_pago;
						}
					},
					{ "data": "cpago_nrooperacion", "visible": false, "className": '', 
						render: function ( data, type, row ) {
							return row.cpago_nrooperacion;
						}
					},
					{ "data": "cpago_fechadeposito", "visible": false, "className": '', 
						render: function ( data, type, row ) {
							return row.cpago_fechadeposito;
						}
					},
					{ "data": "cpago_idbanco", "visible": false, "className": '', 
						render: function ( data, type, row ) {
							return row.cpago_idbanco;
						}
					},
					{ "data": "nombre_banco_deposito", "visible": false, "className": '', 
						render: function ( data, type, row ) {
							return row.nombre_banco_deposito;
						}
					},

					{ "data": "id_codigomoneda", "visible": true, "className": '', 
						render: function ( data, type, row ) {
							return row.id_codigomoneda;
						}
					},
					{ "data": "total_gravadas", "visible": false, "className": '', 
						render: function ( data, type, row ) {
							return row.total_gravadas;
						}
					},
					{ "data": "total_inafecta", "visible": false, "className": '', 
						render: function ( data, type, row ) {
							return row.total_inafecta;
						}
					},
					{ "data": "total_exoneradas", "visible": false, "className": '', 
						render: function ( data, type, row ) {
							return row.total_exoneradas;
						}
					},
					{ "data": "total_gratuitas", "visible": false, "className": '', 
						render: function ( data, type, row ) {
							return row.total_gratuitas;
						}
					},
					{ "data": "total_icbper", "visible": false, "className": '', 
						render: function ( data, type, row ) {
							return row.total_icbper;
						}
					},
					{ "data": "total_igv", "visible": true, "className": '', 
						render: function ( data, type, row ) {
							return row.total_igv;
						}
					},
					{ "data": "sub_total", "visible": true, "className": '', 
						render: function ( data, type, row ) {
							return row.sub_total;
						}
					},
					{ "data": "total", "visible": true, "className": '', 
						render: function ( data, type, row ) {
							return row.total;
						}
					}
				],
				stateSave: true,
				initComplete: function(){
					$(light).unblock();
				}
			});

			$("#info_nombre_vendedor").html(data.nombre_vendedor);
			$("#info_email_vendedor").html(data.email_vendedor);
			$("#info_celular_vendedor").html(data.celular_vendedor);
			
			var texto_documentos_sel = '';
			$("#select_tipo_comprobante option:selected").each(function () {
				var $this = $(this);
				if ($this.length) {
					texto_documentos_sel = texto_documentos_sel + ' ' + $this.text();
				}
			 });
			$("#info_docs_seleccionados").html(texto_documentos_sel);
			$("#info_fecha_inicio").html($("#fecha_inicio").val());
			$("#info_fecha_fin").html($("#fecha_fin").val());
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

function get_reporte_detallado_by_cliente(id_vendedor, tipos_documentos, tiporeporte, light) {
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

	var datastring = $("#frm_reporte_top_vendedores").serializeArray();
	datastring.push({ name: "sucursales", value: $("#select_sucursal").val() });
	datastring.push({ name: "vendedores", value: $("#select_vendedor").val() });
	//datastring.push({ name: "tipos_documentos", value: $("#select_tipo_comprobante").val() });
	datastring.push({ name: "estados_documentos", value: $("#select_estado").val() });
	datastring.push({ name: "tipos_monedas", value: $("#id_cod_moneda").val() });
	datastring.push({ name: "id_vendedor", value: id_vendedor });
	datastring.push({ name: "tipos_documentos", value: tipos_documentos });
	datastring.push({ name: "tiporeporte", value: tiporeporte });

	$.ajax({
		url: '/facturacionv8/reportes/get_detalle_docs_by_idvendedor',
		data: datastring,
		method: 'POST',
		dataType: 'json'
	}).then(function(data){
		if(data.respuesta == 'ok'){
			tbl_lista_reporte = $('#tbl_detalle_doc').DataTable({
                data: data.lista,
                "bDestroy": true,
				buttons: [
					{
						extend: 'excelHtml5',
						exportOptions: {
							columns: ':visible'
						},
						className: 'btn btn-default btn-success'
					},
					{
						extend: 'colvis',
						text: '<i class="icon-three-bars"></i> <span class="caret"></span>',
						className: 'btn bg-blue btn-icon',
						collectionLayout: 'fixed four-column'
					}
				],
				"columns": [
					{ "data": "datos_sucursal", "visible": false, "className": '', 
						render: function ( data, type, row ) {
							return row.datos_sucursal;
						}
					},
					{ "data": "datos_vendedor", "visible": false, "className": '', 
						render: function ( data, type, row ) {
							return row.datos_vendedor;
						}
					},
					{ "data": "fecha_registro", "visible": false, "className": '', 
						render: function ( data, type, row ) {
							return row.fecha_registro;
						}
					},
					{ "data": "fecha_comprobante", "visible": false, "className": '', 
						render: function ( data, type, row ) {
							return row.fecha_comprobante;
						}
					},
					{ "data": "fecha_vto_comprobante", "visible": false, "className": '', 
						render: function ( data, type, row ) {
							return row.fecha_vto_comprobante;
						}
					},
					{ "data": "id_tipodoc_electronico", "visible": false, "className": '', 
						render: function ( data, type, row ) {
							return row.id_tipodoc_electronico;
						}
					},
					{ "data": "serie_comprobante", "visible": true, "className": '', 
						render: function ( data, type, row ) {
							return row.serie_comprobante;
						}
					},
					{ "data": "numero_comprobante", "visible": true, "className": '', 
						render: function ( data, type, row ) {
							return row.numero_comprobante;
						}
					},

					{ "data": "condicion_pago", "visible": true, "className": '', 
						render: function ( data, type, row ) {
							return row.condicion_pago;
						}
					},
					{ "data": "cpago_nrooperacion", "visible": false, "className": '', 
						render: function ( data, type, row ) {
							return row.cpago_nrooperacion;
						}
					},
					{ "data": "cpago_fechadeposito", "visible": false, "className": '', 
						render: function ( data, type, row ) {
							return row.cpago_fechadeposito;
						}
					},
					{ "data": "cpago_idbanco", "visible": false, "className": '', 
						render: function ( data, type, row ) {
							return row.cpago_idbanco;
						}
					},
					{ "data": "nombre_banco_deposito", "visible": false, "className": '', 
						render: function ( data, type, row ) {
							return row.nombre_banco_deposito;
						}
					},
					
					{ "data": "id_codigomoneda", "visible": false, "className": '', 
						render: function ( data, type, row ) {
							return row.id_codigomoneda;
						}
					},
					{ "data": "total_gravadas", "visible": false, "className": '', 
						render: function ( data, type, row ) {
							return row.total_gravadas;
						}
					},
					{ "data": "total_inafecta", "visible": false, "className": '', 
						render: function ( data, type, row ) {
							return row.total_inafecta;
						}
					},
					{ "data": "total_exoneradas", "visible": false, "className": '', 
						render: function ( data, type, row ) {
							return row.total_exoneradas;
						}
					},
					{ "data": "total_gratuitas", "visible": false, "className": '', 
						render: function ( data, type, row ) {
							return row.total_gratuitas;
						}
					},
					{ "data": "total_icbper", "visible": false, "className": '', 
						render: function ( data, type, row ) {
							return row.total_icbper;
						}
					},
					{ "data": "total_igv", "visible": false, "className": '', 
						render: function ( data, type, row ) {
							return row.total_igv;
						}
					},
					{ "data": "sub_total", "visible": false, "className": '', 
						render: function ( data, type, row ) {
							return row.sub_total;
						}
					},
					{ "data": "total", "visible": false, "className": '', 
						render: function ( data, type, row ) {
							return row.total;
						}
					},
					{ "data": "descripcion", "visible": true, "className": '', 
						render: function ( data, type, row ) {
							return row.descripcion;
						}
					},
					{ "data": "id_unidad_medida", "visible": false, "className": '', 
						render: function ( data, type, row ) {
							return row.id_unidad_medida;
						}
					},
					{ "data": "unidad_medida", "visible": false, "className": '', 
						render: function ( data, type, row ) {
							return row.unidad_medida;
						}
					},
					{ "data": "id_tipoafectacionigv", "visible": false, "className": '', 
						render: function ( data, type, row ) {
							return row.id_tipoafectacionigv;
						}
					},
					{ "data": "cantidad", "visible": true, "className": '', 
						render: function ( data, type, row ) {
							return row.cantidad;
						}
					},
					{ "data": "precio", "visible": true, "className": '', 
						render: function ( data, type, row ) {
							return row.precio;
						}
					},
					{ "data": "sub_total", "visible": false, "className": '', 
						render: function ( data, type, row ) {
							return row.sub_total;
						}
					},
					{ "data": "igv", "visible": false, "className": '', 
						render: function ( data, type, row ) {
							return row.igv;
						}
					},
					{ "data": "icbper", "visible": false, "className": '', 
						render: function ( data, type, row ) {
							return row.icbper;
						}
					},
					{ "data": "importe", "visible": true, "className": '', 
						render: function ( data, type, row ) {
							return row.importe;
						}
					}
					
				],
				initComplete: function(){
					$(light).unblock();
				}
			});

			$("#info_nombre_vendedor").html(data.nombre_vendedor);
			$("#info_email_vendedor").html(data.email_vendedor);
			$("#info_celular_vendedor").html(data.celular_vendedor);
			
			var texto_documentos_sel = '';
			$("#select_tipo_comprobante option:selected").each(function () {
				var $this = $(this);
				if ($this.length) {
					texto_documentos_sel = texto_documentos_sel + ' ' + $this.text();
				}
			 });
			$("#info_docs_seleccionados").html(texto_documentos_sel);
			$("#info_fecha_inicio").html($("#fecha_inicio").val());
			$("#info_fecha_fin").html($("#fecha_fin").val());
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