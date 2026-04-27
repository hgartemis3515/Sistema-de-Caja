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
		
	$(".select2").select2();
	$(".select_minimizado").select2({
        minimumResultsForSearch: -1
	});
	$('.multiselect').multiselect();
	$(".control_fecha_inicio").daterangepicker({
		singleDatePicker: true, 
		locale: { format: 'DD/MM/YYYY H:mm:ss' },
		timePicker: true
	}, function(start, end) {
		$("#fecha_inicio_valor").val(start.format('YYYY-MM-DD H:mm:ss'));
		get_resumen_totales();
		get_lista_movimientos();
	});

	$(".control_fecha_movimiento").daterangepicker({
		singleDatePicker: true, 
		locale: { format: 'DD/MM/YYYY H:mm:ss' },
		timePicker: true
	}, function(start, end) {
		$("#control_fecha_movimiento_valor").val(start.format('YYYY-MM-DD H:mm:ss'));
	});

	$(".control_fecha_fin").daterangepicker({
		singleDatePicker: true, 
		locale: { format: 'DD/MM/YYYY H:mm:ss' },
		timePicker: true
	}, function(start, end) {
		$("#fecha_fin_valor").val(end.format('YYYY-MM-DD H:mm:ss'));
		get_resumen_totales();
		get_lista_movimientos();
	});

	$(".select_filtro_cajachica").change(function() {
		get_resumen_totales(); 
		get_lista_movimientos();
	});
	 
	$("#content_client_movimiento").hide();
	
	$( ".control-comprobante" ).change(function() {
		if(this.value == '1') { 
            $("#content_client_movimiento").hide();
        } else if (this.value == '2') { 
            $("#content_client_movimiento").show();
        }
	});

    $(".search_document").click(function() {
        var num_doc = $("#proveedor_numerodocumento").val();
		if(num_doc != '') {
            var tipo_doc = 6;
            consultar_numero_doc($("#idcliente"), num_doc, $("#razon_social"), $("#direccionfiscal"), $("#ubigeo"), tipo_doc, $("#email"));
		}
    });
    $(".btn_save_registro").click(save_registro_movimiviento);
    inicializar_controles();
	get_lista_movimientos();
	get_resumen_totales();
	
	$('[data-popup="popover"]').popover();

	$(".tab_opcion").click(function() {
		get_lista_movimientos();
		get_resumen_totales();
	});

	$('.column100').on('mouseover',function(){
		var table1 = $(this).parent().parent().parent(); //devuelve el elemento padre directo del elemento seleccionado
		var table2 = $(this).parent().parent();
		var verTable = $(table1).data('vertable')+"";
		var column = $(this).data('column') + ""; 

		$(table2).find("."+column).addClass('hov-column-'+ verTable);
		$(table1).find(".row100.head ."+column).addClass('hov-column-head-'+ verTable);
	});

	$('.column100').on('mouseout',function(){
		var table1 = $(this).parent().parent().parent();
		var table2 = $(this).parent().parent();
		var verTable = $(table1).data('vertable')+"";
		var column = $(this).data('column') + ""; 

		$(table2).find("."+column).removeClass('hov-column-'+ verTable);
		$(table1).find(".row100.head ."+column).removeClass('hov-column-head-'+ verTable);
	});

	$(".tool_tip").hover(function(){
		$(this).append('<div class="tooltip_small"><div class="tooltip-arrow" style="left: 50%;"></div><div class="tooltip-inner">Click para Ver Más!</div></div>');
		$(".tool_tip").mouseleave(function() {
			$(".tooltip_small").remove();
		  });
	});

	$("#proveedor_tipo_docidentidad").on('change', function() {
        if(this.value == '6') {
            $("#proveedor_numerodocumento").attr('inputmode', 'numeric');
            $("#proveedor_numerodocumento").attr('type', 'number');
            $("#proveedor_numerodocumento").attr('pattern', '[0-9]*');

            $("#proveedor_numerodocumento-flexdatalist").attr('inputmode', 'numeric');
            $("#proveedor_numerodocumento-flexdatalist").attr('type', 'number');
            $("#proveedor_numerodocumento-flexdatalist").attr('pattern', '[0-9]*');

			$("#titulo_numerodocumento").html('N° RUC');
			$("#proveedor_titulodocumento").html('Razón Social');

        } else if(this.value == '1') {
            $("#proveedor_numerodocumento").attr('inputmode', 'numeric');
            $("#proveedor_numerodocumento").attr('type', 'number');
            $("#proveedor_numerodocumento").attr('pattern', '[0-9]*');

            $("#proveedor_numerodocumento-flexdatalist").attr('inputmode', 'numeric');
            $("#proveedor_numerodocumento-flexdatalist").attr('type', 'number');
            $("#proveedor_numerodocumento-flexdatalist").attr('pattern', '[0-9]*');

			$("#titulo_numerodocumento").html('N° DNI');
			$("#proveedor_titulodocumento").html('Nombre Completo');

        } else {
            $("#proveedor_numerodocumento").attr('inputmode', 'text');
            $("#proveedor_numerodocumento").attr('type', 'text');
            $("#proveedor_numerodocumento").attr('pattern', '[A-Za-z0-9_]{1,15}');

            $("#proveedor_numerodocumento-flexdatalist").attr('inputmode', 'text');
            $("#proveedor_numerodocumento-flexdatalist").attr('type', 'text');
            $("#proveedor_numerodocumento-flexdatalist").attr('pattern', '[A-Za-z0-9_]{1,15}');

			$("#titulo_numerodocumento").html('N° DOCUMENTO');
			$("#proveedor_titulodocumento").html('Nombre');

        }
    });

	$("#proveedor_tipo_docidentidad").trigger("change").trigger("select2:select");

	$("#condicionpago_abono").on('change', function(){
        var tipo_condicionpago = $(this).find(':selected').data('tipocondicion');
        if(!$('#condicionpago_abono').val()) {
			$("#content_numero_operacion").hide();
			$("#content_fecha_deposito").hide();
			$("#content_cuenta_banco_deposito").hide();
        } else {
            if(tipo_condicionpago == 'contado') {
				$("#content_numero_operacion").hide();
				$("#content_fecha_deposito").hide();
				$("#content_cuenta_banco_deposito").hide();
            } else if(tipo_condicionpago == 'credito') {
				$("#content_fecha_deposito").hide();
				$("#content_cuenta_banco_deposito").hide();
				$("#content_numero_operacion").removeClass();
				$("#content_numero_operacion").addClass("col-md-12");
				$("#content_numero_operacion").show();
            } else {
				$("#content_numero_operacion").removeClass();
				$("#content_numero_operacion").addClass("col-md-4");
				$("#content_numero_operacion").show();
				$("#content_fecha_deposito").show();
				$("#content_cuenta_banco_deposito").show();
            }
        }
	});
	$("#condicionpago_abono").trigger('change');
	
	$(".buscar_proveedor").click(function() {
        if(typeof $("#proveedor_numerodocumento-flexdatalist").val() != "undefined" && $("#proveedor_numerodocumento-flexdatalist").val() != '') {
            var num_doc = $("#proveedor_numerodocumento-flexdatalist").val();
            $("#proveedor_numerodocumento").val($("#proveedor_numerodocumento-flexdatalist").val());
        } else {
            var num_doc = $("#proveedor_numerodocumento").val();
        }
         
		if(num_doc != '') {
            var tipo_doc = $("#proveedor_tipo_docidentidad").val();
            if(tipo_doc == 1 || tipo_doc == 6) { //1: DNI //6: RUC
				consultar_data_documento_identidad(tipo_doc, num_doc);
            }
		}
	});
});

function consultar_data_documento_identidad(tipo_doc, num_doc) {
	$("#icon_search_document").hide();
    $("#icon_searching_document").show();
    $(".buscar_proveedor").prop('disabled', true);

	$("#id_proveedor_documento").val('');

	$.ajax({
        url : '/facturacionv8/herramientas/get_data_proveedor',
        data: {tipo_doc: tipo_doc, num_doc: num_doc},
        method :  'POST',
        dataType : "json"
    }).then(function(data){
        if(data.respuesta == 'ok') {
            if(tipo_doc == 1) { //DNI
                if(data.encontrado == true) {
                    if(data.api == true) {
                        $("#proveedor_nombre").val(data.data.nombre);
                    } else {
                        $("#id_proveedor_documento").val(data.data.id_proveedor);
                        $("#proveedor_nombre").val(data.data.razon_social);
                    }
                }
            } else if(tipo_doc == 6) { //RUC
                if(data.encontrado == true) {
                    if(data.api == true) {
                        $("#proveedor_nombre").val(data.data.razon_social);
                    } else {
                        $("#id_proveedor_documento").val(data.data.id_proveedor);
                        $("#proveedor_nombre").val(data.data.razon_social);
                    }

                    if(typeof data.data.estado !== 'undefined' && data.data.estado != '') {
                        $("#estado_numerodocumento").removeClass();
                        $("#razonsocial_numerodocumento").removeClass();
                        $("#titulo_numerodocumento").html("N° de R.U.C.");
                        $("#titulo_nombrecliente").html("Razón Social");
                        
                        if(data.data.estado == 'ACTIVO' || data.data.estado == 'activo') {
                            $("#estado_numerodocumento").addClass("form-group col-md-4 text-success");
                            $("#razonsocial_numerodocumento").addClass("form-group col-md-5 text-success");
     
                            $("#titulo_numerodocumento").html($("#titulo_numerodocumento").html() + " (ESTADO: " + data.data.estado + ")");
                            $("#titulo_nombrecliente").html($("#titulo_nombrecliente").html() + " (ESTADO: " + data.data.estado + ")");
                        } else {
                            $("#estado_numerodocumento").addClass("form-group col-md-4 text-danger");
                            $("#razonsocial_numerodocumento").addClass("form-group col-md-5 text-danger");
    
                            $("#titulo_numerodocumento").html($("#titulo_numerodocumento").html() + " (ESTADO: " + data.data.estado + ")");
                            $("#titulo_nombrecliente").html($("#titulo_nombrecliente").html() + " (ESTADO: " + data.data.estado + ")");
                        }
                    } else {
                        $("#estado_numerodocumento").removeClass();
                        $("#razonsocial_numerodocumento").removeClass();
                        $("#estado_numerodocumento").addClass("form-group col-md-4");
                        $("#razonsocial_numerodocumento").addClass("form-group col-md-5");
                        $("#titulo_numerodocumento").html("N° de R.U.C.");
                        $("#titulo_nombrecliente").html("Razón Social");
                    }
                }
            } else {
                if(data.encontrado == true) {

                }
            }
            
            $("#icon_search_document").show();
            $("#icon_searching_document").hide();
            $(".buscar_proveedor").prop('disabled', false);
        } else {
            swal({
                title: 'ERROR',
                text: data.mensaje,
                html: true,
                type: "error",
                confirmButtonText: "Ok",
                confirmButtonColor: "#2196F3"
            }, function(){
                $("#icon_search_document").show();
                $("#icon_searching_document").hide();
                $(".buscar_proveedor").prop('disabled', false);
            });
        }
    }, function(reason){
        swal({
            title: 'ERROR',
            text: 'Error al conectarse a la SUNAT, recarga la página e inténtalo nuevamente!',
            html: true,
            type: "error",
            confirmButtonText: "Ok",
            confirmButtonColor: "#2196F3"
        }, function(){
            $("#icon_search_document").show();
            $("#icon_searching_document").hide();
            $(".buscar_proveedor").prop('disabled', false);
        });
    });
}

function get_resumen_totales() {
	var datastring = $("#frm_filtros").serializeArray();
	datastring.push({ name: "sucursales", value: $("#select_sucursal_filtro").val() });
	datastring.push({ name: "vendedores", value: $("#select_vendedor_filtro").val() });
	$.ajax({
        url :  "/facturacionv8/cajachica/get_totales_ventas",
        method :  'POST',
		dataType : "json",
		data: datastring
    }).then(function(data){
		if(data.respuesta == 'ok') {
			rellenar_tablas_resumen_ventas(data.totales);
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
			
		});
    });
}

function rellenar_tablas_resumen_ventas(totales) {
	
	$("#factura_cash_soles").html(totales.facturas_soles.contado); //
	$("#factura_cash_dolares").html(totales.facturas_dolares.contado); //
	$("#factura_credito_soles").html(totales.facturas_soles.credito); //
	$("#factura_credito_dolares").html(totales.facturas_dolares.credito); //
	$("#factura_tarjeta_soles").html(totales.facturas_soles.tarjeta_credito); // 
	$("#factura_tarjeta_dolares").html(totales.facturas_dolares.tarjeta_credito); //
	$("#factura_transferencia_soles").html(totales.facturas_soles.transferencia); //
	$("#factura_transferencia_dolares").html(totales.facturas_dolares.transferencia); //
	$("#factura_montoadeudado_soles").html(totales.facturas_soles.monto_adeudado); //
	$("#factura_montoadeudado_dolares").html(totales.facturas_dolares.monto_adeudado); //
	$("#factura_total_soles").html(totales.facturas_soles.total - totales.anticipos.facturas_soles);
	$("#factura_total_dolares").html(totales.facturas_dolares.total - totales.anticipos.facturas_dolares);

	$("#boleta_cash_soles").html(totales.boletas_soles.contado); //
	$("#boleta_cash_dolares").html(totales.boletas_dolares.contado); //
	$("#boleta_credito_soles").html(totales.boletas_soles.credito); //
	$("#boleta_credito_dolares").html(totales.boletas_dolares.credito); //
	$("#boleta_tarjeta_soles").html(totales.boletas_soles.tarjeta_credito); //
	$("#boleta_tarjeta_dolares").html(totales.boletas_dolares.tarjeta_credito); //
	$("#boleta_transferencia_soles").html(totales.boletas_soles.transferencia); //
	$("#boleta_transferencia_dolares").html(totales.boletas_dolares.transferencia); //
	$("#boleta_montoadeudado_soles").html(totales.boletas_soles.monto_adeudado); //
	$("#boleta_montoadeudado_dolares").html(totales.boletas_dolares.monto_adeudado); //
	$("#boleta_total_soles").html(totales.boletas_soles.total - totales.anticipos.boletas_soles);
	$("#boleta_total_dolares").html(totales.boletas_dolares.total - totales.anticipos.boletas_dolares); 

	$("#nota_credito_cash_soles").html(totales.notas_credito_soles.contado); //
	$("#nota_credito_cash_dolares").html(totales.notas_credito_dolares.contado); //
	$("#nota_credito_credito_soles").html(totales.notas_credito_soles.credito); //
	$("#nota_credito_credito_dolares").html(totales.notas_credito_dolares.credito); //
	$("#nota_credito_tarjeta_soles").html(totales.notas_credito_soles.tarjeta_credito); //
	$("#nota_credito_tarjeta_dolares").html(totales.notas_credito_dolares.tarjeta_credito); //
	$("#nota_credito_transferencia_soles").html(totales.notas_credito_soles.transferencia); //
	$("#nota_credito_transferencia_dolares").html(totales.notas_credito_dolares.transferencia); //
	$("#nota_credito_montoadeudado_soles").html(totales.notas_credito_soles.monto_adeudado); //
	$("#nota_credito_montoadeudado_dolares").html(totales.notas_credito_dolares.monto_adeudado); //
	$("#nota_credito_total_soles").html(totales.notas_credito_soles.total - totales.anticipos.notas_credito_soles);
	$("#nota_credito_total_dolares").html(totales.notas_credito_dolares.total - totales.anticipos.notas_credito_dolares);

	$("#nota_debito_cash_soles").html(totales.notas_debito_soles.contado); //
	$("#nota_debito_cash_dolares").html(totales.notas_debito_dolares.contado); //
	$("#nota_debito_credito_soles").html(totales.notas_debito_soles.credito); //
	$("#nota_debito_credito_dolares").html(totales.notas_debito_dolares.credito); //
	$("#nota_debito_tarjeta_soles").html(totales.notas_debito_soles.tarjeta_credito); //
	$("#nota_debito_tarjeta_dolares").html(totales.notas_debito_dolares.tarjeta_credito); //
	$("#nota_debito_transferencia_soles").html(totales.notas_debito_soles.transferencia); //
	$("#nota_debito_transferencia_dolares").html(totales.notas_debito_dolares.transferencia); //
	$("#nota_debito_montoadeudado_soles").html(totales.notas_debito_soles.monto_adeudado); //
	$("#nota_debito_montoadeudado_dolares").html(totales.notas_debito_dolares.monto_adeudado); //
	$("#nota_debito_total_soles").html(totales.notas_debito_soles.total - totales.anticipos.notas_debito_soles);
	$("#nota_debito_total_dolares").html(totales.notas_debito_dolares.total - totales.anticipos.notas_debito_dolares);

	$("#nota_venta_cash_soles").html(totales.notas_venta_soles.contado); //
	$("#nota_venta_cash_dolares").html(totales.notas_venta_dolares.contado); //
	$("#nota_venta_credito_soles").html(totales.notas_venta_soles.credito); //
	$("#nota_venta_credito_dolares").html(totales.notas_venta_dolares.credito); //
	$("#nota_venta_tarjeta_soles").html(totales.notas_venta_soles.tarjeta_credito); //
	$("#nota_venta_tarjeta_dolares").html(totales.notas_venta_dolares.tarjeta_credito); //
	$("#nota_venta_transferencia_soles").html(totales.notas_venta_soles.transferencia); //
	$("#nota_venta_transferencia_dolares").html(totales.notas_venta_dolares.transferencia); //
	$("#nota_venta_montoadeudado_soles").html(totales.notas_venta_soles.monto_adeudado); //
	$("#nota_venta_montoadeudado_dolares").html(totales.notas_venta_dolares.monto_adeudado); //
	$("#nota_venta_total_soles").html(totales.notas_venta_soles.total);
	$("#nota_venta_total_dolares").html(totales.notas_venta_dolares.total);

	$("#abono_cash_soles").html(totales.abono_soles.contado); //
	$("#abono_cash_dolares").html(totales.abono_dolares.contado); //
	$("#abono_credito_soles").html(totales.abono_soles.credito); //
	$("#abono_credito_dolares").html(totales.abono_dolares.credito); //
	$("#abono_tarjeta_soles").html(totales.abono_soles.tarjeta_credito); //
	$("#abono_tarjeta_dolares").html(totales.abono_dolares.tarjeta_credito); //
	$("#abono_transferencia_soles").html(totales.abono_soles.transferencia); //
	$("#abono_transferencia_dolares").html(totales.abono_dolares.transferencia); //
	$("#abono_montoadeudado_soles").html(totales.abono_soles.monto_adeudado); //
	$("#abono_montoadeudado_dolares").html(totales.abono_dolares.monto_adeudado); //
	$("#abono_total_soles").html(totales.abono_soles.total);
	$("#abono_total_dolares").html(totales.abono_dolares.total);

	var totales_cash_soles =  round_math(totales.facturas_soles.contado + totales.abono_soles.contado + totales.boletas_soles.contado - totales.notas_credito_soles.contado + totales.notas_debito_soles.contado + totales.notas_venta_soles.contado, 2);
	var totales_cash_dolares = round_math(totales.facturas_dolares.contado + totales.abono_dolares.contado + totales.boletas_dolares.contado - totales.notas_credito_dolares.contado + totales.notas_debito_dolares.contado + totales.notas_venta_dolares.contado, 2);
	var totales_tarjeta_soles = round_math(totales.facturas_soles.tarjeta_credito + totales.abono_soles.tarjeta_credito + totales.boletas_soles.tarjeta_credito - totales.notas_credito_soles.tarjeta_credito + totales.notas_debito_soles.tarjeta_credito + totales.notas_venta_soles.tarjeta_credito, 2);
	var totales_tarjeta_dolares = round_math(totales.facturas_dolares.tarjeta_credito + totales.abono_dolares.tarjeta_credito + totales.boletas_dolares.tarjeta_credito - totales.notas_credito_dolares.tarjeta_credito + totales.notas_debito_dolares.tarjeta_credito + totales.notas_venta_dolares.tarjeta_credito, 2);
	var totales_transferencia_soles = round_math(totales.facturas_soles.transferencia + totales.abono_soles.transferencia + totales.boletas_soles.transferencia - totales.notas_credito_soles.transferencia + totales.notas_debito_soles.transferencia + totales.notas_venta_soles.transferencia, 2);
	var totales_transferencia_dolares = round_math(totales.facturas_dolares.transferencia + totales.abono_dolares.transferencia + totales.boletas_dolares.transferencia - totales.notas_credito_dolares.transferencia + totales.notas_debito_dolares.transferencia + totales.notas_venta_dolares.transferencia, 2);
	var totales_credito_soles = round_math(totales.facturas_soles.credito + totales.abono_soles.credito + totales.boletas_soles.credito - totales.notas_credito_soles.credito + totales.notas_debito_soles.credito + totales.notas_venta_soles.credito, 2);
	var totales_credito_dolares = round_math(totales.facturas_dolares.credito + totales.abono_dolares.credito + totales.boletas_dolares.credito - totales.notas_credito_dolares.credito + totales.notas_debito_dolares.credito + totales.notas_venta_dolares.credito, 2);
	$("#totales_cash_soles").html(totales_cash_soles);
	$("#totales_cash_dolares").html(totales_cash_dolares);
	$("#totales_tarjeta_soles").html(totales_tarjeta_soles);
	$("#totales_tarjeta_dolares").html(totales_tarjeta_dolares);
	$("#totales_transferencia_soles").html(totales_transferencia_soles);
	$("#totales_transferencia_dolares").html(totales_transferencia_dolares);
	$("#totales_credito_soles").html(totales_credito_soles);
	$("#totales_credito_dolares").html(totales_credito_dolares);
	
	$("#totales_montoadeudado_soles").html(round_math(totales.facturas_soles.monto_adeudado + totales.abono_soles.monto_adeudado + totales.boletas_soles.monto_adeudado - totales.notas_credito_soles.monto_adeudado + totales.notas_debito_soles.monto_adeudado + totales.notas_venta_soles.monto_adeudado, 2));
	$("#totales_montoadeudado_dolares").html(round_math(totales.facturas_dolares.monto_adeudado + totales.abono_dolares.monto_adeudado + totales.boletas_dolares.monto_adeudado - totales.notas_credito_dolares.monto_adeudado + totales.notas_debito_dolares.monto_adeudado + totales.notas_venta_dolares.monto_adeudado, 2));
	var totales_total_soles = round_math(totales.facturas_soles.total + totales.abono_soles.total + totales.boletas_soles.total - totales.notas_credito_soles.total + totales.notas_debito_soles.total + totales.notas_venta_soles.total - totales.anticipos.total_soles, 2);
	var totales_total_dolares = round_math(totales.facturas_dolares.total + totales.abono_dolares.total + totales.boletas_dolares.total - totales.notas_credito_dolares.total + totales.notas_debito_dolares.total + totales.notas_venta_dolares.total - totales.anticipos.total_dolares, 2);
	$("#totales_total_soles").html(totales_total_soles);
	$("#totales_total_dolares").html(totales_total_dolares);

	var subtotal_soles = round_math(totales.facturas_soles.total + totales.boletas_soles.total - totales.notas_credito_soles.total + totales.notas_debito_soles.total + totales.notas_venta_soles.total, 2);
	var subtotal_dolares = round_math(totales.facturas_dolares.total + totales.boletas_dolares.total - totales.notas_credito_dolares.total + totales.notas_debito_dolares.total + totales.notas_venta_dolares.total, 2);
	//$("#totales_subtotal_soles").html(subtotal_soles);
	//$("#totales_subtotal_dolares").html(subtotal_dolares);

	if(parseFloat(totales.abono_soles.total_documentos + totales.abono_dolares.total_documentos) > 0) {
		$("#abono_total_documentos").html(parseFloat(totales.abono_soles.total_documentos + totales.abono_dolares.total_documentos));
		$("#abono_total_documentos").show();
	} else {
		$("#abono_total_documentos").html(0);
		$("#abono_total_documentos").hide();
	}

	if(parseFloat(totales.notas_venta_soles.total_documentos + totales.notas_venta_dolares.total_documentos) > 0) {
		$("#nota_venta_total_documentos").html(parseFloat(totales.notas_venta_soles.total_documentos + totales.notas_venta_dolares.total_documentos));
		$("#nota_venta_total_documentos").show();
	} else {
		$("#nota_venta_total_documentos").html(0);
		$("#nota_venta_total_documentos").hide();
	}

	if(parseFloat(totales.notas_debito_soles.total_documentos + totales.notas_debito_dolares.total_documentos) > 0) {
		$("#nota_debito_total_documentos").html(parseFloat(totales.notas_debito_soles.total_documentos + totales.notas_debito_dolares.total_documentos));
		$("#nota_debito_total_documentos").show();
	} else {
		$("#nota_debito_total_documentos").html(0);
		$("#nota_debito_total_documentos").hide();
	}

	if(parseFloat(totales.notas_credito_soles.total_documentos + totales.notas_credito_dolares.total_documentos) > 0) {
		$("#nota_credito_total_documentos").html(parseFloat(totales.notas_credito_soles.total_documentos + totales.notas_credito_dolares.total_documentos));
		$("#nota_credito_total_documentos").show();
	} else {
		$("#nota_credito_total_documentos").html(0);
		$("#nota_credito_total_documentos").hide();
	}

	if(parseFloat(totales.boletas_soles.total_documentos + totales.boletas_dolares.total_documentos) > 0) {
		$("#boleta_total_documentos").html(parseFloat(totales.boletas_soles.total_documentos + totales.boletas_dolares.total_documentos));
		$("#boleta_total_documentos").show();
	} else {
		$("#boleta_total_documentos").html(0);
		$("#boleta_total_documentos").hide();
	}

	if(parseFloat(totales.facturas_soles.total_documentos + totales.facturas_dolares.total_documentos) > 0) {
		$("#factura_total_documentos").html(parseFloat(totales.facturas_soles.total_documentos + totales.facturas_dolares.total_documentos));
		$("#factura_total_documentos").show();
	} else {
		$("#factura_total_documentos").html(0);
		$("#factura_total_documentos").hide();
	}

	$("#totales_cobros_soles").html(totales.monto_cobrado_soles);
	$("#totales_cobros_dolares").html(totales.monto_cobrado_dolares);

	$("#totales_ingresoscaja_soles").html(totales.total_ingreso_soles);
	$("#totales_ingresoscaja_dolares").html(totales.total_ingreso_dolares);

	$("#totales_egresoscaja_soles").html(totales.total_egreso_soles);
	$("#totales_egresoscaja_dolares").html(totales.total_egreso_dolares);
	
	$("#totales_totalcaja_soles").html(totales_total_soles + totales.total_ingreso_soles - totales.total_egreso_soles);
	$("#totales_totalcaja_dolares").html(totales_total_dolares + totales.total_ingreso_dolares - totales.total_egreso_dolares);
	
	if(totales.tiene_dolares == 'no') {
		$(".montos_en_dolares").hide();
		$("#cabecera_cash").attr('colspan',1);
		$("#cabecera_tarjeta").attr('colspan',1);
		$("#cabecera_transferencia").attr('colspan',1);
		//$("#cabecera_parcial").attr('colspan',1);
		$("#cabecera_porcobrar").attr('colspan',1);
		$("#cabecera_total").attr('colspan',1);

		$(".texto_subtotales").attr('colspan',5);
		
	} else {
		$(".montos_en_dolares").show();
		//$(".montos_en_dolares").hide();
		$("#cabecera_cash").attr('colspan',2);
		$("#cabecera_tarjeta").attr('colspan',2);
		$("#cabecera_transferencia").attr('colspan',2);
		//$("#cabecera_parcial").attr('colspan',1);
		$("#cabecera_porcobrar").attr('colspan',2);
		$("#cabecera_total").attr('colspan',2);

		$(".texto_subtotales").attr('colspan',9);
	}
}
 
function inicializar_controles() {
    // Primary
    $(".control-primary").uniform({
        radioClass: 'choice',
        wrapperClass: 'border-primary-600 text-primary-800'
    });

    // Danger
    $(".control-danger").uniform({
        radioClass: 'choice',
        wrapperClass: 'border-danger-600 text-danger-800'
    });

    // Success
    $(".control-success").uniform({
        radioClass: 'choice',
        wrapperClass: 'border-success-600 text-success-800'
    });

    // Warning
    $(".control-warning").uniform({
        radioClass: 'choice',
        wrapperClass: 'border-warning-600 text-warning-800'
    });

    // Info
    $(".control-info").uniform({
        radioClass: 'choice',
        wrapperClass: 'border-info-600 text-info-800'
    });

}

function save_registro_movimiviento(){
	var light = $("#contenido_registro_movimiento");

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
	
	var datastring = $("#frm_registrar_movimiento").serializeArray();
	datastring.push({ name: "condicionpago_abono", value: $("#condicionpago_abono").val() });
	datastring.push({ name: "txt_numero_operacion", value: $("#txt_numero_operacion").val() });
	datastring.push({ name: "fecha_deposito", value: $("#fecha_deposito").val() });
	datastring.push({ name: "cuenta_banco_deposito", value: $("#select_cuenta_banco_deposito").val() });
	
	$.ajax({
        url : "/facturacionv8/cajachica/save",
		method :  'POST',
		data: datastring,
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
				$("#frm_registrar_movimiento")[0].reset();
				$("#id_movimiento").val('');
                get_lista_movimientos();
                $('#modal_movimientos').modal('hide');

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
function get_lista_movimientos() {
	var light = $('#contenido_lista_movimientos');
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

	var datastring = $("#frm_filtros").serializeArray();
	datastring.push({ name: "sucursales", value: $("#select_sucursal_filtro").val() });
	datastring.push({ name: "vendedores", value: $("#select_vendedor_filtro").val() });
	
	$.ajax({
        url :  "/facturacionv8/cajachica/get_lista_movimientos",
        method :  'POST',
		dataType : "json",
		data: datastring
    }).then(function(data){
		if(data.respuesta == 'ok') {
		
			$(".btn-cajachica").html('<a href="" target="_blank" id="btn_hojaliquidacion_pen" class="btn btn-primary mb-2 mr-2"><i class="icon-file-pdf mr-2"></i>Hoja Liquidación Soles</a><a href="" target="_blank" id="btn_hojaliquidacion_usd" class="btn btn-success mb-2 mr-2"><i class="icon-file-pdf mr-2"></i>Hoja Liquidación Dólares</a>' + data.btn_pdf);
			$("#btn_hojaliquidacion_pen").attr('href', data.url_hliquidacion_soles);
			$("#btn_hojaliquidacion_usd").attr('href', data.url_hliquidacion_dolares);
			$('#tbl_lista_movimientos').DataTable({
                data: data.lista,
				"bDestroy": true,

				buttons: [
					{
						text: '<i class="icon-plus-circle2 position-left"></i> Crear Movimiento',
						className: 'btn btn-info',
						action: function ( e, dt, node, config ) {
							agregar_movimiento();
						}
					},
					{
						extend: 'collection',
						text: '<i class="icon-plus-circle2 position-left"></i> Reportes</span>',
						className: 'btn btn-success',
						buttons: [
							{ 
								text: '<i class="icon-file-excel position-left"></i> Reporte 1</span>',
								action: function ( e, dt, node, config ) {
									var datastring = $("#frm_filtros").serializeArray();
									datastring.push({ name: "sucursales", value: $("#select_sucursal_filtro").val() });
									datastring.push({ name: "vendedores", value: $("#select_vendedor_filtro").val() });
									datastring.push({ name: "num_reporte", value: 1 });
									

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
									
									window.open("/facturacionv8/cajachica/get_reporte_exel_ingresos_egresos?" + data_url_string, "_blank");
		
								}
							},
							{ 
								text: '<i class="icon-file-excel position-left"></i> Reporte 2</span>',
								action: function ( e, dt, node, config ) {
									var datastring = $("#frm_filtros").serializeArray();
									datastring.push({ name: "sucursales", value: $("#select_sucursal_filtro").val() });
									datastring.push({ name: "vendedores", value: $("#select_vendedor_filtro").val() });
									datastring.push({ name: "num_reporte", value: 2 });

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
									
									window.open("/facturacionv8/cajachica/get_reporte_exel_ingresos_egresos?" + data_url_string, "_blank");
		
								}
							}
						],
						fade: true
					}
				],
				initComplete: function(){
					//$("#tbl_lista_movimientos_wrapper").children("div.datatable-header").children("div.dt-buttons").html($("#tbl_lista_movimientos_wrapper").children("div.datatable-header").children("div.dt-buttons").html() + '<button class="btn btn-success" tabindex="0"  data-toggle="modal" data-target="#modal_movimientos" type="button"><span><i class="icon-plus-circle2 position-left"></i>	Crear Nuevo</span></button>');
					$('[data-popup="popover"]').popover();
				}
			});
			
            $(light).unblock();
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
function agregar_movimiento() {
	$("#id_movimiento").val('');
	$("#descripcion").val('');
	$("#monto").val(0);
	$("#detalle_movimiento").val('');
	$("#proveedor_numerodocumento").val('');
	$("#razon_social").val('');
	$("#num_comprobante").val('');
	$("#modal_movimientos").modal('show');
	$('#radio_ingreso').prop("checked", true).uniform('regresh');
	$('#radio_egreso').prop("checked", false).uniform('regresh');
	$(".radio").trigger('change');
}
function editar_movimiento(id_movimiento) {
	var light = $('#contenido_lista_clientes');
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
    $('#modal_movimientos').modal('show');
	$.ajax({
        url : '/facturacionv8/cajachica/get_data_movimiento',
		method :  'POST',
		data: {id_movimiento: id_movimiento},
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			$(light).unblock();
			var tipo_movimiento = data.movimientocaja.tipo_movimiento;
			if (tipo_movimiento  == 'ingreso'){
				$('#radio_egreso').prop("checked", false).uniform('regresh');
				$('#radio_ingreso').prop("checked", true).uniform('regresh');
				$(".radio").trigger('change');
			}else if (tipo_movimiento  == 'egreso'){
				$('#radio_egreso').prop("checked", true).uniform('regresh');
				$('#radio_ingreso').prop("checked", false).uniform('regresh');
				$(".radio").trigger('change');
			}
			$("#id_movimiento").val(data.movimientocaja.id_movimiento);
			$("#select_sucursal").val(data.movimientocaja.id_sucursal).trigger("change").trigger("select2:select");
			$("#select_vendedor").val(data.movimientocaja.id_vendedor).trigger("change").trigger("select2:select");
			$("#control_fecha_movimiento_valor").val(data.movimientocaja.fecha_movimiento);
			$("#control_fecha_movimiento").val(data.fecha_movimiento_show);
			$("#descripcion").val(data.movimientocaja.descripcion);
			$("#id_cod_moneda").val(data.movimientocaja.moneda).trigger("change").trigger("select2:select");
			$("#monto").val(data.movimientocaja.monto);
			$("#detalle_movimiento").val(data.movimientocaja.detalle);
			$("#proveedor_numerodocumento").val(data.movimientocaja.ruc_comprobante);
			$("#razon_social").val(data.movimientocaja.razon_social);
			$("#select_tipo_comprobante").val(data.movimientocaja.tipo_comprobante).trigger("change").trigger("select2:select");
			$("#num_comprobante").val(data.movimientocaja.serie_num_comprobante);
			
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
function eliminar_movimiento(id_movimiento) {
	var light = $('#contenido_lista_clientes');
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
	swal({
        title: "Confirmación!",
        text: "¿Estás seguro que deseas eliminar este movimiento de caja?",
        type: "warning",
        showCancelButton: true,   
        confirmButtonColor: "#3f51b5",       
        cancelButtonColor: "#aaaaaa",
        confirmButtonText: "Si, Adelante!",
        closeOnConfirm: false
    },function(isconfirmed){
        if(isconfirmed) {
            var confirmado = 'si';
			$.ajax({
				url : '/facturacionv8/cajachica/delete',
				method :  'POST',
				data: {id_movimiento: id_movimiento},
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
						get_lista_movimientos();
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
        }else{
            $(light).unblock();
            return;
        }
      
    });
	
}

function ver_detalle_caja(id_cpe, moneda, tipo_transaccion, tipo_venta = 'contado') {
	var light = $('#tabla_caja_chica');
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

	var datastring = $("#frm_filtros").serializeArray();
	datastring.push({ name: "sucursales", value: $("#select_sucursal_filtro").val() });
	datastring.push({ name: "vendedores", value: $("#select_vendedor_filtro").val() });
	datastring.push({ name: "id_tipocpe", value: id_cpe });
	datastring.push({ name: "id_codigomoneda", value: moneda });
	datastring.push({ name: "tipo_transaccion", value: tipo_transaccion });
	datastring.push({ name: "tipo_venta", value: tipo_venta });

	if(tipo_venta == 'contado') {
		ver_columnas_deuda = false;
	} else {
		ver_columnas_deuda = true;
	}
	
	$.ajax({
        url : '/facturacionv8/cajachica/detallecajachica',
		method :  'POST',
		data: datastring,
        dataType : "json"
    }).then(function(data){
		$(light).unblock();
		if(data.respuesta == 'ok') {
			tbl_lista_docs = $('#tbl_lista_doc').DataTable({
				data: data.lista_docs,
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
					{ "data": "serie_correlativo", "visible": true},
					{ "data": "cliente", "visible": true},
					{ "data": "sucursal", "visible": false},
					{ "data": "moneda", "visible": true},
					{ "data": "total_gravadas", "visible": false},
					{ "data": "total_inafecta", "visible": false},
					{ "data": "total_exoneradas", "visible": false},
					{ "data": "total_gratuitas", "visible": false},
					{ "data": "total_exportacion", "visible": false},
					{ "data": "total_icbper", "visible": false},
					{ "data": "total_descuento", "visible": false},
					{ "data": "sub_total", "visible": false},
					{ "data": "total_igv", "visible": false},
					{ "data": "total_isc", "visible": false},
					{ "data": "total_otr_imp", "visible": false},
					{ "data": "total", "visible": true},
					{ "data": "deuda", "visible": ver_columnas_deuda},
					{ "data": "pagado", "visible": ver_columnas_deuda},
					{ "data": "condicionpago", "visible": true}
				],
				"columnDefs": [
					{ className: "bg-primary-600", "targets": [ 16 ] }
				],
				stateSave: false,
				initComplete: function(){
					
				},
				"order": [[ 15, "desc" ]]
			});

			tbl_lista_docs_detalle = $('#tbl_detalle_doc').DataTable({
				data: data.lista_docs_detalle,
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
					{ "data": "serie_correlativo", "visible": true},
					{ "data": "cliente", "visible": false},
					{ "data": "sucursal", "visible": false},
					{ "data": "moneda", "visible": true},
					{ "data": "total_gravadas", "visible": false},
					{ "data": "total_inafecta", "visible": false},
					{ "data": "total_exoneradas", "visible": false},
					{ "data": "total_gratuitas", "visible": false},
					{ "data": "total_exportacion", "visible": false},
					{ "data": "total_icbper", "visible": false},
					{ "data": "total_descuento", "visible": false},
					{ "data": "sub_total", "visible": false},
					{ "data": "total_igv", "visible": false},
					{ "data": "total_isc", "visible": false},
					{ "data": "total_otr_imp", "visible": false},
					{ "data": "total", "visible": true},
					{ "data": "deuda", "visible": ver_columnas_deuda},
					{ "data": "pagado", "visible": ver_columnas_deuda},
					{ "data": "condicionpago", "visible": true},
					
					{ "data": "producto_codigo", "visible": false},
					{ "data": "producto_nombre", "visible": true},
					{ "data": "cantidad", "visible": true},
					{ "data": "unidad_medida", "visible": true},
					{ "data": "precio_sin_igv", "visible": false},
					{ "data": "item_igv", "visible": false},
					{ "data": "item_isc", "visible": false},
					{ "data": "item_icbper", "visible": false},
					{ "data": "item_importe", "visible": true}
				],
				"columnDefs": [
					{ className: "bg-primary-600", "targets": [ 16 ] },
					{ className: "bg-success-600", "targets": [ 18, 19, 20, 21, 22, 23, 24, 25, 26 ] },
				],
				stateSave: false,
				initComplete: function(){
					
				},
				"order": [[ 15, "desc" ]]
			});

			$(light).unblock();
			$("#info_detalle_caja").modal('show');
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

function ver_detalle_abonos(moneda, tipo_transaccion, tipo_reporte = 'abonos') {
	var light = $('#tabla_caja_chica');
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

	var datastring = $("#frm_filtros").serializeArray();
	datastring.push({ name: "sucursales", value: $("#select_sucursal_filtro").val() });
	datastring.push({ name: "vendedores", value: $("#select_vendedor_filtro").val() });
	datastring.push({ name: "id_codigomoneda", value: moneda });
	datastring.push({ name: "tipo_transaccion", value: tipo_transaccion });
	datastring.push({ name: "tipo_reporte", value: tipo_reporte });

	tipo_venta = 'credito';
	if(tipo_venta == 'contado') {
		ver_columnas_deuda = false;
	} else {
		ver_columnas_deuda = true;
	}
	
	$.ajax({
        url : '/facturacionv8/cajachica/detallecajachica',
		method :  'POST',
		data: datastring,
        dataType : "json"
    }).then(function(data){
		$(light).unblock();
		if(data.respuesta == 'ok') {

			tbl_detalle_abonos = $('#tbl_detalle_abonos').DataTable({
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
					{ "data": "serie_correlativo", "visible": true},
					{ "data": "cliente", "visible": false},
					{ "data": "sucursal", "visible": false},
					{ "data": "moneda", "visible": true},
					{ "data": "total_gravadas", "visible": false},
					{ "data": "total_inafecta", "visible": false},
					{ "data": "total_exoneradas", "visible": false},
					{ "data": "total_gratuitas", "visible": false},
					{ "data": "total_exportacion", "visible": false},
					{ "data": "total_icbper", "visible": false},
					{ "data": "total_descuento", "visible": false},
					{ "data": "sub_total", "visible": false},
					{ "data": "total_igv", "visible": false},
					{ "data": "total_isc", "visible": false},
					{ "data": "total_otr_imp", "visible": false},
					{ "data": "total", "visible": true},
					{ "data": "deuda", "visible": ver_columnas_deuda},
					{ "data": "pagado", "visible": ver_columnas_deuda},
					
					{ "data": "abono_fecha", "visible": false},
					{ "data": "abono_condicion_pago", "visible": true},
					{ "data": "abono_total", "visible": true},
					{ "data": "abono_nro_operacion", "visible": true},
					{ "data": "abono_banco", "visible": false},
					{ "data": "abono_detalle", "visible": false}
				],
				"columnDefs": [
					{ className: "bg-primary-600", "targets": [ 16 ] },
					{ className: "bg-success-600", "targets": [ 18, 19, 20, 21, 22, 23 ] },
				],
				stateSave: false,
				initComplete: function(){
					
				},
				"order": [[ 15, "desc" ]]
			});

			$(light).unblock();
			$("#info_detalle_abonos").modal('show');
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

function get_hoja_liquidacion(moneda, tipo_transaccion, tipo_reporte = 'hoja_liquidacion') {
	var light = $('#tabla_caja_chica');
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

	var datastring = $("#frm_filtros").serializeArray();
	datastring.push({ name: "sucursales", value: $("#select_sucursal_filtro").val() });
	datastring.push({ name: "vendedores", value: $("#select_vendedor_filtro").val() });
	datastring.push({ name: "id_codigomoneda", value: moneda });
	datastring.push({ name: "tipo_transaccion", value: tipo_transaccion });
	datastring.push({ name: "tipo_reporte", value: tipo_reporte });

	tipo_venta = 'credito';
	if(tipo_venta == 'contado') {
		ver_columnas_deuda = false;
	} else {
		ver_columnas_deuda = true;
	}
	
	$.ajax({
        url : '/facturacionv8/cajachica/detallecajachica',
		method :  'POST',
		data: datastring,
        dataType : "json"
    }).then(function(data){
		$(light).unblock();
		if(data.respuesta == 'ok') {
			console.log(data);
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

function consultar_numero_doc_proveedor(input_idusuario, num_doc, input_nombre, input_direccion, input_ubigeo, tipo_doc, input_email) {
    $("#icon_search_document").hide();
    $("#icon_searching_document").show();
    $(".search_document").prop('disabled', true);
    input_idusuario.val('');
    //id: f8sjHDGDSAjdh
$.ajax({
        url : '/facturacionv8/herramientas/get_data_proveedor',
        data: {tipo_doc: tipo_doc, num_doc: num_doc},
        method :  'POST',
        dataType : "json"
    }).then(function(data){
        if(data.respuesta == 'ok') {
            if(tipo_doc == 1) { //DNI
                if(data.encontrado == true) {
                    if(data.api == true) {
                        input_nombre.val(data.data.nombre);
                    } else {
                        input_idusuario.val(data.data.idcliente);
                        input_nombre.val(data.data.razon_social);
                        input_direccion.val(data.data.direccion_fiscal);
                        input_email.val(data.data.email);
                        input_ubigeo.val(data.data.id_cod_ubigeo).trigger("change").trigger("select2:select");
                    }
                }
            } else if(tipo_doc == 6) { //RUC
                if(data.encontrado == true) {
                    if(data.api == true) {
                        input_nombre.val(data.data.razon_social);
                        input_direccion.val(data.data.direccion);
                        if(typeof data.data.codigo_ubigeo !== 'undefined' && data.data.codigo_ubigeo != '') {
                            input_ubigeo.val(data.data.codigo_ubigeo).trigger("change").trigger("select2:select");
                        }
                    } else {
                        input_idusuario.val(data.data.idcliente);
                        input_nombre.val(data.data.razon_social);
                        input_direccion.val(data.data.direccion_fiscal);
                        input_email.val(data.data.email);
                        input_ubigeo.val(data.data.id_cod_ubigeo).trigger("change").trigger("select2:select");
                    }

                    if(typeof data.data.estado !== 'undefined' && data.data.estado != '') {
                        $("#estado_numerodocumento").removeClass();
                        $("#razonsocial_numerodocumento").removeClass();
                        $("#titulo_numerodocumento").html("N° de R.U.C.");
                        $("#titulo_nombrecliente").html("Razón Social");
                        
                        if(data.data.estado == 'ACTIVO' || data.data.estado == 'activo') {
                            $("#estado_numerodocumento").addClass("form-group col-md-4 text-success");
                            $("#razonsocial_numerodocumento").addClass("form-group col-md-5 text-success");
     
                            $("#titulo_numerodocumento").html($("#titulo_numerodocumento").html() + " (ESTADO: " + data.data.estado + ")");
                            $("#titulo_nombrecliente").html($("#titulo_nombrecliente").html() + " (ESTADO: " + data.data.estado + ")");
                        } else {
                            $("#estado_numerodocumento").addClass("form-group col-md-4 text-danger");
                            $("#razonsocial_numerodocumento").addClass("form-group col-md-5 text-danger");
    
                            $("#titulo_numerodocumento").html($("#titulo_numerodocumento").html() + " (ESTADO: " + data.data.estado + ")");
                            $("#titulo_nombrecliente").html($("#titulo_nombrecliente").html() + " (ESTADO: " + data.data.estado + ")");
                        }
                    } else {
                        $("#estado_numerodocumento").removeClass();
                        $("#razonsocial_numerodocumento").removeClass();
                        $("#estado_numerodocumento").addClass("form-group col-md-4");
                        $("#razonsocial_numerodocumento").addClass("form-group col-md-5");
                        $("#titulo_numerodocumento").html("N° de R.U.C.");
                        $("#titulo_nombrecliente").html("Razón Social");
                    }
                }
            } else {
                if(data.encontrado == true) {
                    input_nombre.val(data.data.razon_social);
                    input_direccion.val(data.data.direccion_fiscal);
                    input_ubigeo.val(data.data.id_cod_ubigeo).trigger("change").trigger("select2:select");
                }
            }
            
            $("#icon_search_document").show();
            $("#icon_searching_document").hide();
            $(".search_document").prop('disabled', false);
        } else {
            swal({
                title: 'ERROR',
                text: data.mensaje,
                html: true,
                type: "error",
                confirmButtonText: "Ok",
                confirmButtonColor: "#2196F3"
            }, function(){
                $("#icon_search_document").show();
                $("#icon_searching_document").hide();
                $(".search_document").prop('disabled', false);
            });
        }
    }, function(reason){
        swal({
            title: 'ERROR',
            text: 'Error al conectarse a la SUNAT, recarga la página e inténtalo nuevamente!',
            html: true,
            type: "error",
            confirmButtonText: "Ok",
            confirmButtonColor: "#2196F3"
        }, function(){
            $("#icon_search_document").show();
            $("#icon_searching_document").hide();
            $(".search_document").prop('disabled', false);
        });
    });
}