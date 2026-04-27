$(function() {
	$(".control-success").uniform({
        radioClass: 'choice',
        wrapperClass: 'border-success-600 text-success-800'
    });

	$(".control_fecha").daterangepicker({singleDatePicker: true, locale: { format: 'DD/MM/YYYY' }});

	if($("#contribuyente_mype").val() == 'no') {
		$('#opt_mype_tributario').prop('checked', false);
		$.uniform.update('#opt_mype_tributario');
		$("#renta_3ra_coeficiente").prop('disabled', false);
	} else {
		$('#opt_mype_tributario').prop('checked', true);
		$.uniform.update('#opt_mype_tributario');
		$("#renta_3ra_coeficiente").val("");
		$("#renta_3ra_porcentaje").val("1");
		$("#renta_3ra_coeficiente").prop('disabled', true);
	}

	$('.opt_mype_tributario').on('change', function() {
		var opt_mype_tributario = $('#opt_mype_tributario').is(":checked");
		if(opt_mype_tributario) {
			$("#renta_3ra_coeficiente").val("");
			$("#renta_3ra_porcentaje").val("1");
			$("#renta_3ra_coeficiente").prop('disabled', true);
		} else {
			$("#renta_3ra_coeficiente").val("0.015");
			$("#renta_3ra_porcentaje").val("1");
			$("#renta_3ra_coeficiente").prop('disabled', false);
		}
	});

	$(".btn_generar_reporte").on('click', function(){
		var light = $("#content_panel_liquidacion_mensual");

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

		var datastring = $("#frm_reporte_liquidacion_mensual").serializeArray();
		//datastring.push({ name: "sucursales", value: $("#select_sucursal").val() });

		$.ajax({
			url: '/facturacionv8/reportes/get_liquidacion_mensual',
			data: datastring,
			method: 'POST',
			dataType: 'json'
		}).then(function(data){
			if(data.respuesta == 'ok'){
				tbl_lista_reporte = $('#tbl_list_clientes').DataTable({
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
						{ "data": "cliente", "visible": true, "className": 'text-left'}, //0
						{ "data": "total_factura", "visible": true},
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
	});
});