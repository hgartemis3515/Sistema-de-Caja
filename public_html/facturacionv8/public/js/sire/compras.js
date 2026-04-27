var datosPeriodos = null;
var tbl_propuesta_sire;

$(function(){
    $(".select2_minimo").select2({
        minimumResultsForSearch: -1
	});

    lista_periodos();

    $("#btn_extraer_ticket_sire").click(get_num_ticket);

    $("#btn_consultar_estado_y_descargar_propuesta_sire").click(consultar_estado_y_descargar_propuesta_sire);

    
      $(document).on('click', '.next_step_btn', function() {
        var dataStepValue = $(this).data('step');
       
        if(dataStepValue == 1){
            $('.step-1').addClass('disabled-items');
            $('.step-3').addClass('disabled-items');
            $('.step-2').removeClass('disabled-items');
            $('.step-2').addClass('active');
            $('.step-1 :input').prop('disabled', true);
            $('.step-2 :input').prop('disabled', false);
            $('.step-2 :button').prop('disabled', false);
            $('.step-3 :button').prop('disabled', true);
            $('.next_step_btn').remove();
            $('.btn-next-2').append('<button class="btn bg-indigo font-weight-bold text-uppercase next_step_btn" data-step="2">Siguiente</button>');
       }if(dataStepValue == 2){
            $('.step-1').addClass('disabled-items');
            $('.step-2').addClass('disabled-items');
            $('.step-3').removeClass('disabled-items');
            $('.step-3').addClass('active');
            $('.step-1 :input').prop('disabled', true);
            $('.step-2 :input').prop('disabled', true);
            $('.next_step_btn').remove();
            $('#btn_consultar_estado_y_descargar_propuesta_sire').prop('disabled', false);
            console.log('llego');
       }
     
    });

    var switches = Array.prototype.slice.call(document.querySelectorAll('.switch2'));
    switches.forEach(function(html) {
        var switchery = new Switchery(html, {color: '#4CAF50'});
	});
	
	var btn_opciones_accesos_sunat = document.querySelector('.btn_opciones_accesos_sunat');
    btn_opciones_accesos_sunat.onchange = function() {
        if(btn_opciones_accesos_sunat.checked) {
            $(".opc_datos_operaciones_sunat").show("slide");
        } else {
            $(".opc_datos_operaciones_sunat").hide("slide");
        }
	};
    
    $("#btn_guardar_datos_acceso").click(guardar_datos_acceso_sunat);
});

function guardar_datos_acceso_sunat(){
	var light = $("#configuracion_data_sol");

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
        url : "/facturacionv8/sire/guardar_data_acceso_sunat",
		method :  'POST',
		data: {
            usuario_sol_principal: $("#usuario_sol_principal").val(),
            password_usuario_sol: $("#password_usuario_sol").val(),
            cliente_id: $("#cliente_id").val(),
            client_secret: $("#client_secret").val(),
        },
        dataType : "json"
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
				$(light).unblock();
				
            });
        } else {
            swal({   
                title:'Error',   
                text: data.mensaje,
                html: true,
                type: "error", 
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Ok",
            }, function() {
				$(light).unblock();
            });
        }
    }, function(reason){
    	swal({   
			title: 'Error',   
			text: reason,
			html: true,
			type: "error",  
			confirmButtonColor: "#DD6B55",   
			confirmButtonText: "Ok"
		}, function() {
			$(light).unblock();
		});
    });
}

function consultar_estado_y_descargar_propuesta_sire() {
    var light = $("#content_sire_propuesta");

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

    $.ajax({
        data : {
            num_ticket: $('#num_ticket').val()
        },
        url : '/facturacionv8/sirecompras/sire_compras_consultar_y_descargar_propuesta',
        method :  'POST',
        dataType : "json"
    }).then(function(data){
        if(data.respuesta == 'ok') {
            tbl_propuesta_sire = $('#tbl_propuesta_sire').DataTable({
                data: data.registros,
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
						text: '<span></span><i class="icon-file-excel position-left"></i> Descargar TXT</span>',
                        action: function ( e, dt, node, config ) {
                            var data_url_string = 'num_ticket' + '=' + encodeURIComponent($("#num_ticket").val());
                            window.open("/facturacionv8/sirecompras/download_txt_rce?" + data_url_string, "_blank");
                        },
                        className: 'btn btn-info'
					},
					{
						extend: 'colvis',
						text: '<i class="icon-three-bars"></i> <span class="caret"></span>',
						className: 'btn bg-blue btn-icon',
						collectionLayout: 'fixed four-column'
					}
				],
                stateSave: true,
				"columns": [
                    { "data": "url_search_sunat", "title": "Preview", "visible": true, "className": "text-left" },
                    { "data": "ruc", "title": "RUC", "visible": false, "className": "text-left" },
                    { "data": "razon_social", "title": "Razón Social", "visible": false, "className": "text-left" },
                    { "data": "periodo", "title": "Periodo", "visible": true, "className": "text-left" },
                    { "data": "car_sunat", "title": "CAR SUNAT", "visible": false, "className": "text-left" },
                    { "data": "fecha_emision", "title": "Fecha Emisión", "visible": true, "className": "text-left" },
                    { "data": "fecha_vcto_pago", "title": "Fecha Vcto Pago", "visible": false, "className": "text-left" },
                    { "data": "tipo_cp_doc", "title": "Tipo CP Doc", "visible": true, "className": "text-left" },
                    { "data": "serie_cdp", "title": "Serie CDP", "visible": true, "className": "text-left" },
                    { "data": "anio", "title": "Año", "visible": false, "className": "text-left" },
                    { "data": "nro_cp_doc_inicial", "title": "Nro CP Doc Inicial", "visible": true, "className": "text-left" },
                    { "data": "nro_final_rango", "title": "Nro Final Rango", "visible": false, "className": "text-left" },
                    { "data": "tipo_doc_identidad", "title": "Tipo Doc Identidad", "visible": true, "className": "text-left" },
                    { "data": "nro_doc_identidad", "title": "Nro Doc Identidad", "visible": true, "className": "text-left" },
                    { "data": "apellidos_nombres_razon_social", "title": "Apellidos Nombres Razón Social", "visible": true, "className": "text-left" },
                    { "data": "bi_gravado_dg", "title": "BI Gravado DG", "visible": true, "className": "text-left" },
                    { "data": "igv_ipm_dg", "title": "IGV IPM DG", "visible": true, "className": "text-left" },
                    { "data": "bi_gravado_dgng", "title": "BI Gravado DGNG", "visible": true, "className": "text-left" },
                    { "data": "igv_ipm_dgng", "title": "IGV IPM DGNG", "visible": true, "className": "text-left" },
                    { "data": "bi_gravado_dng", "title": "BI Gravado DNG", "visible": true, "className": "text-left" },
                    { "data": "igv_ipm_dng", "title": "IGV IPM DNG", "visible": true, "className": "text-left" },
                    { "data": "valor_adq_ng", "title": "Valor Adq NG", "visible": true, "className": "text-left" },
                    { "data": "isc", "title": "ISC", "visible": false, "className": "text-left" },
                    { "data": "icbper", "title": "ICBPER", "visible": true, "className": "text-left" },
                    { "data": "otros_trib_cargos", "title": "Otros Trib Cargos", "visible": true, "className": "text-left" },
                    { "data": "total_cp", "title": "Total CP", "visible": true, "className": "text-left" },
                    { "data": "moneda", "title": "Moneda", "visible": true, "className": "text-left" },
                    { "data": "tipo_cambio", "title": "Tipo Cambio", "visible": true, "className": "text-left" },
                    { "data": "fecha_emision_doc_modificado", "title": "Fecha Emisión Doc Modificado", "visible": false, "className": "text-left" },
                    { "data": "tipo_cp_modificado", "title": "Tipo CP Modificado", "visible": false, "className": "text-left" },
                    { "data": "serie_cp_modificado", "title": "Serie CP Modificado", "visible": false, "className": "text-left" },
                    { "data": "cod_dam_dsi", "title": "Cod DAM DSI", "visible": false, "className": "text-left" },
                    { "data": "nro_cp_modificado", "title": "Nro CP Modificado", "visible": false, "className": "text-left" },
                    { "data": "clasif_bss_sss", "title": "Clasif BSS SSS", "visible": false, "className": "text-left" },
                    { "data": "id_proyecto_operadores", "title": "ID Proyecto Operadores", "visible": false, "className": "text-left" },
                    { "data": "porcpart", "title": "Porcpart", "visible": false, "className": "text-left" },
                    { "data": "imb", "title": "IMB", "visible": false, "className": "text-left" },
                    { "data": "car_orig_ind_e_o_i", "title": "Car Orig Ind E O I", "visible": false, "className": "text-left" },
                    { "data": "detraccion", "title": "Detracción", "visible": false, "className": "text-left" },
                    { "data": "tipo_de_nota", "title": "Tipo de Nota", "visible": false, "className": "text-left" },
                    { "data": "est_comp", "title": "Est Comp", "visible": true, "className": "text-left" },
                    { "data": "clu1", "title": "Clu1", "visible": false, "className": "text-left" },
                    { "data": "clu2", "title": "Clu2", "visible": false, "className": "text-left" },
                    { "data": "clu3", "title": "Clu3", "visible": false, "className": "text-left" },
                    { "data": "clu4", "title": "Clu4", "visible": false, "className": "text-left" },
                    { "data": "clu5", "title": "Clu5", "visible": false, "className": "text-left" },
                    { "data": "clu6", "title": "Clu6", "visible": false, "className": "text-left" },
                    { "data": "clu7", "title": "Clu7", "visible": false, "className": "text-left" },
                    { "data": "clu8", "title": "Clu8", "visible": false, "className": "text-left" },
                    { "data": "clu9", "title": "Clu9", "visible": false, "className": "text-left" },
                    { "data": "clu10", "title": "Clu10", "visible": false, "className": "text-left" },
                    { "data": "clu11", "title": "Clu11", "visible": false, "className": "text-left" },
                    { "data": "clu12", "title": "Clu12", "visible": false, "className": "text-left" },
                    { "data": "clu13", "title": "Clu13", "visible": false, "className": "text-left" },
                    { "data": "clu14", "title": "Clu14", "visible": false, "className": "text-left" },
                    { "data": "clu15", "title": "Clu15", "visible": false, "className": "text-left" },
                    { "data": "clu16", "title": "Clu16", "visible": false, "className": "text-left" },
                    { "data": "clu17", "title": "Clu17", "visible": false, "className": "text-left" },
                    { "data": "clu18", "title": "Clu18", "visible": false, "className": "text-left" },
                    { "data": "clu19", "title": "Clu19", "visible": false, "className": "text-left" },
                    { "data": "clu20", "title": "Clu20", "visible": false, "className": "text-left" },
                    { "data": "clu21", "title": "Clu21", "visible": false, "className": "text-left" },
                    { "data": "clu22", "title": "Clu22", "visible": false, "className": "text-left" },
                    { "data": "clu23", "title": "Clu23", "visible": false, "className": "text-left" },
                    { "data": "clu24", "title": "Clu10", "visible": false, "className": "text-left" },
                    { "data": "clu25", "title": "Clu11", "visible": false, "className": "text-left" },
                    { "data": "clu26", "title": "Clu12", "visible": false, "className": "text-left" },
                    { "data": "clu27", "title": "Clu13", "visible": false, "className": "text-left" },
                    { "data": "clu28", "title": "Clu14", "visible": false, "className": "text-left" },
                    { "data": "clu29", "title": "Clu15", "visible": false, "className": "text-left" },
                    { "data": "clu30", "title": "Clu16", "visible": false, "className": "text-left" },
                    { "data": "clu31", "title": "Clu17", "visible": false, "className": "text-left" },
                    { "data": "clu32", "title": "Clu18", "visible": false, "className": "text-left" },
                    { "data": "clu33", "title": "Clu19", "visible": false, "className": "text-left" },
                    { "data": "clu34", "title": "Clu20", "visible": false, "className": "text-left" },
                    { "data": "clu35", "title": "Clu21", "visible": false, "className": "text-left" },
                    { "data": "clu36", "title": "Clu22", "visible": false, "className": "text-left" },
                    { "data": "clu37", "title": "Clu23", "visible": false, "className": "text-left" },
                    { "data": "clu38", "title": "Clu23", "visible": false, "className": "text-left" },
                    { "data": "clu39", "title": "Clu23", "visible": false, "className": "text-left" },
                ],
				initComplete: function(){
                    $(light).unblock();
				}
			});

            console.log(data);

            $("#html_total_facturas_soles").html(data.total_facturas.toLocaleString('es-PE', { style: 'currency', currency: 'PEN' }));
            $("#html_total_boletas_soles").html(data.total_boletas.toLocaleString('es-PE', { style: 'currency', currency: 'PEN' }));
            $("#html_total_notas_credito_soles").html(data.total_notas_credito.toLocaleString('es-PE', { style: 'currency', currency: 'PEN' }));
            $("#html_total_notas_debito_soles").html(data.total_notas_debito.toLocaleString('es-PE', { style: 'currency', currency: 'PEN' }));
            $("#html_total_neto_soles").html(data.total_cp.toLocaleString('es-PE', { style: 'currency', currency: 'PEN' }));

            $("#totales_sire").show('slide');
            $("#content_reportes").show('slide');
        } else {
            // Manejo del error
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
        // Manejo del error AJAX
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
    });
}

function get_num_ticket() {
    var light = $("#content_sire_propuesta");

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

    $.ajax({
        data : {num_ejercicio: $('#num_ejercicio').val(), periodo_tributario: $('#periodo_tributario').val()},
        url : '/facturacionv8/sirecompras/sire_compras_get_ticket',
        method :  'POST',
        dataType : "json"
    }).then(function(data){
        if(data.respuesta == 'ok') {
            $('#num_ticket').val(data.num_ticket);
            $(light).unblock();
        } else {
            // Manejo del error
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
        // Manejo del error AJAX
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
    });
}

function lista_periodos() {
    var light = $("#content_sire_propuesta");

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

    $.ajax({
        url : '/facturacionv8/sirecompras/sire_compras_lista_periodos',
        method :  'POST',
        dataType : "json"
    }).then(function(data){
        if(data.respuesta == 'ok') {
            datosPeriodos = data.lista;
            llenarSelectEjercicios(datosPeriodos);
            $(light).unblock();
        } else {
            // Manejo del error
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
        // Manejo del error AJAX
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
    });
}

function llenarSelectEjercicios(datos) {
    var selectEjercicio = $('#num_ejercicio'); // Asumiendo que tienes un <select> con este ID
    datos.forEach(function(item) {
        var option = $('<option></option>').attr('value', item.numEjercicio).text(item.numEjercicio);
        selectEjercicio.append(option);
    });

    selectEjercicio.on('change', function() {
        $("#num_ticket").val('');
        actualizarSelectPeriodos($(this).val());
    });
}

function actualizarSelectPeriodos(numEjercicio) {
    $("#num_ticket").val('');
    var selectPeriodos = $('#periodo_tributario'); // Asumiendo que tienes un <select> con este ID
    selectPeriodos.empty();

    var periodos = datosPeriodos.find(function(item) {
        return item.numEjercicio === numEjercicio;
    });

    if(periodos) {
        periodos.lisPeriodos.forEach(function(per) {
            var mes = getNombreMes(per.perTributario.substring(4, 6));
            var option = $('<option></option>').attr('value', per.perTributario).text(mes);
            selectPeriodos.append(option);
        });
    }

    selectPeriodos.on('change', function() {
        $("#num_ticket").val('');
    });
}

function getNombreMes(numeroMes) {
    var nombresMeses = {
        '01': 'Enero', '02': 'Febrero', '03': 'Marzo', '04': 'Abril',
        '05': 'Mayo', '06': 'Junio', '07': 'Julio', '08': 'Agosto',
        '09': 'Septiembre', '10': 'Octubre', '11': 'Noviembre', '12': 'Diciembre'
    };
    return nombresMeses[numeroMes] || 'Mes Desconocido';
}



