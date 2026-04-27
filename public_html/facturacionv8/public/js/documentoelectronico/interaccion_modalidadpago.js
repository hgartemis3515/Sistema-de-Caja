$(function(){
	validar_formadepago_tipoventa();

    $('#opcion_tipo_venta').on('switchChange.bootstrapSwitch', function(event, state) {
        validar_formadepago_tipoventa();
    });

    $("#condicionpago_comprobante").on('change', function(){
        var valor_tipo_venta = $("#opcion_tipo_venta").bootstrapSwitch('state');
        if(!valor_tipo_venta) {
            validar_formadepago_tipoventa();
        } else {
            validar_monto_pagado_adeudado();
        }
        
    });

    $("#txt_monto_adeudado").on('change', function() {
        var total_adeudado = parseFloat($("#txt_monto_adeudado").val());
        var total_comprobante = parseFloat($("#txt_total_comprobante").val());
        var tipo_operacion = $("#tipo_operacion_docelectronico").val();
        var aplica_retencion = $("#select_aplica_retencion").val();

        if(tipo_operacion == '1001' || tipo_operacion == '1002' || tipo_operacion == '1003' || tipo_operacion == '1004') {
            var tipo_moneda = $("#codmoneda_comprobante").val();
            if(tipo_moneda == 'USD') {
                total_comprobante = total_comprobante - parseFloat($("#monto_dolares_detraccion").val());
            } else {
                total_comprobante = total_comprobante - parseFloat($("#monto_detraccion").val());
            }
        }
        
        if(aplica_retencion == 'si') {
            if(tipo_moneda == 'USD') {
                total_comprobante = total_comprobante - parseFloat($("#monto_dolares_retencion").val());
            } else {
                total_comprobante = total_comprobante - parseFloat($("#monto_retencion").val());
            }
        }

        var total_pagado = round_math(total_comprobante - total_adeudado, 2);
        
        if(total_adeudado >= total_comprobante) {
            total_pagado = 0;
        }

        $("#pago_parcial").val(total_pagado);

        $("#total_recibido").trigger('change');

        $("#tbl_body_lista_cuotas").html('');
        $("#opt_agregar_cuotas_credito").html('(Click Aquí para Agregar 2 o Más Cuotas)');
        $("#monto_total_cuotas").html('0');

        validar_monto_pagado_adeudado();
	});
	
	$("#txt_monto_adeudado").on('input', function() {
        $("#txt_monto_adeudado").trigger('change');
	});
	
	$("#total_recibido").on('input', function() {
        $("#txt_monto_adeudado").trigger('change');
    });

    $("#pago_parcial").on('change', function() {
        var total_pagado = parseFloat($("#pago_parcial").val());
        var total_comprobante = parseFloat($("#txt_total_comprobante").val());
        var tipo_operacion = $("#tipo_operacion_docelectronico").val();
        if(tipo_operacion == '1001' || tipo_operacion == '1002' || tipo_operacion == '1003' || tipo_operacion == '1004') {
            var tipo_moneda = $("#codmoneda_comprobante").val();
            if(tipo_moneda == 'USD') {
                total_comprobante = total_comprobante - parseFloat($("#monto_dolares_detraccion").val());
            } else {
                total_comprobante = total_comprobante - parseFloat($("#monto_detraccion").val());
            }
        }

        var aplica_retencion = $("#select_aplica_retencion").val();
        if(aplica_retencion == 'si') {
            if(tipo_moneda == 'USD') {
                total_comprobante = total_comprobante - parseFloat($("#monto_dolares_retencion").val());
            } else {
                total_comprobante = total_comprobante - parseFloat($("#monto_retencion").val());
            }
        }

        var total_pendiente = round_math(total_comprobante - total_pagado, 2);

        if(total_pagado >= total_comprobante) {
            total_pendiente = 0;
        }
        
        $("#txt_monto_adeudado").val(total_pendiente);
        $("#txt_monto_adeudado").trigger('change');
    });
});

function validar_monto_pagado_adeudado() {
    var control_condicionpago_comprobante = $("#content_condicionpago_comprobante");
    var estado_condicionpago_comprobante = control_condicionpago_comprobante.is(':visible')?true:false;
    
    var control_numero_operacion = $("#content_numero_operacion");
    var estado_numero_operacion = control_numero_operacion.is(':visible')?true:false;
    
    var control_fecha_deposito = $("#content_fecha_deposito");
    var estado_fecha_deposito = control_fecha_deposito.is(':visible')?true:false;
    
    var control_cuenta_banco_deposito = $("#content_cuenta_banco_deposito");
    var estado_cuenta_banco_deposito = control_cuenta_banco_deposito.is(':visible')?true:false;

    var valor_pago_parcial = $("#pago_parcial").val();
    var valor_condicionpago = $("#condicionpago_comprobante").find(':selected').data('tipocondicion');

    if(valor_pago_parcial > 0) {
        validar_formadepago_tipoventa(false);
        control_condicionpago_comprobante.show();
        if(valor_condicionpago == 'tarjeta_credito') {
            if(!control_condicionpago_comprobante.is('.col-sm-6, .col-md-6')) {
                control_condicionpago_comprobante.removeClass();
                control_condicionpago_comprobante.addClass("col-sm-6 col-md-6");
            }

            if(!control_numero_operacion.is('.col-sm-6, .col-md-6')) {
                control_numero_operacion.removeClass();
                control_numero_operacion.addClass("col-sm-6 col-md-6");
            }
            control_numero_operacion.show();
            control_fecha_deposito.hide();
            control_cuenta_banco_deposito.hide();
            return false;
        } else if(valor_condicionpago == 'transferencia') {
            if(!control_condicionpago_comprobante.is('.col-sm-3, .col-md-3')) {
                control_condicionpago_comprobante.removeClass();
                control_condicionpago_comprobante.addClass("col-sm-3 col-md-3");
            }

            if(!control_numero_operacion.is('.col-sm-3, .col-md-3')) {
                control_numero_operacion.removeClass();
                control_numero_operacion.addClass("col-sm-3 col-md-3");
            }
            control_numero_operacion.show();

            if(!control_fecha_deposito.is('.col-sm-3, .col-md-3')) {
                control_fecha_deposito.removeClass();
                control_fecha_deposito.addClass("col-sm-3 col-md-3");
            }
            control_fecha_deposito.show();

            if(!control_cuenta_banco_deposito.is('.col-sm-3, .col-md-3')) {
                control_cuenta_banco_deposito.removeClass();
                control_cuenta_banco_deposito.addClass("col-sm-3 col-md-3");
            }
            control_cuenta_banco_deposito.show();

            return false;
        } else {
            if(!control_condicionpago_comprobante.is('.col-sm-12, .col-md-12')) {
                control_condicionpago_comprobante.removeClass();
                control_condicionpago_comprobante.addClass("col-sm-12 col-md-12");
            }
            
            control_numero_operacion.hide();
            control_fecha_deposito.hide();
            control_cuenta_banco_deposito.hide();
            return false;
        }
    } else {
        //si es una venta al crédito ocultamos los demás controles
        var valor_tipo_venta = $("#opcion_tipo_venta").bootstrapSwitch('state');
        if(!valor_tipo_venta) {
        
        } else {
            control_condicionpago_comprobante.hide();
            control_numero_operacion.hide();
            control_fecha_deposito.hide();
            control_cuenta_banco_deposito.hide();   
        }
    }
}

function validar_formadepago_tipoventa(inicializar = true) {
    var resp = get_opciones_formas_pago();
    var total_condiciones = resp.total_condiciones;
    var tiene_pago_credito = resp.tiene_pago_credito;
	var tiene_otras_formas_de_pago = resp.tiene_otras_formas_de_pago;
	
    var control_fecha_pago = $("#content_fecha_pago");
    var estado_control_fecha_pago = control_fecha_pago.is(':visible')?true:false;

    var control_monto_adeudado = $("#content_monto_adeudado");
    var estado_monto_adeudado = control_monto_adeudado.is(':visible')?true:false;

    var control_opt_agregar_cuotas = $("#opt_agregar_cuotas_credito");
    var estado_control_optagregar_cuotas = control_opt_agregar_cuotas.is(':visible')?true:false;
    
    var control_pago_parcial = $("#content_pago_parcial");
    var estado_pago_parcial = control_pago_parcial.is(':visible')?true:false;
    
    var control_condicionpago_comprobante = $("#content_condicionpago_comprobante");
    var estado_condicionpago_comprobante = control_condicionpago_comprobante.is(':visible')?true:false;
    
    var control_numero_operacion = $("#content_numero_operacion");
    var estado_numero_operacion = control_numero_operacion.is(':visible')?true:false;
    
    var control_fecha_deposito = $("#content_fecha_deposito");
    var estado_fecha_deposito = control_fecha_deposito.is(':visible')?true:false;
    
    var control_cuenta_banco_deposito = $("#content_cuenta_banco_deposito");
    var estado_cuenta_banco_deposito = control_cuenta_banco_deposito.is(':visible')?true:false;

    var valor_tipo_venta = $("#opcion_tipo_venta").bootstrapSwitch('state');
    var valor_monto_adeudado = $("#txt_monto_adeudado").val();
    var valor_pago_parcial = $("#pago_parcial").val();
    var valor_condicionpago = $("#condicionpago_comprobante").find(':selected').data('tipocondicion');
    var valor_numero_operacion = $("#txt_numero_operacion").val();
    var valor_fecha_deposito = $("#fecha_deposito").val();
    var valor_select_cuentabanco = $("#select_cuenta_banco_deposito").val();

    if(total_condiciones <= 0) {
        $("#contenido_total_condicionespago").hide();
        return false;
    }
    
    $("#contenido_total_condicionespago").show();
    if(tiene_pago_credito == 'si' && tiene_otras_formas_de_pago == 'si') {
        $("#content_tipo_venta").show();
    }
    
    if(valor_tipo_venta) { //es una venta al crédito

        var resp_json =  get_json_cuotas();
        if(resp_json.num_cuotas >= 2) {
            control_fecha_pago.hide();
            //content_monto_adeudado
            //content_pago_parcial
            if(!control_monto_adeudado.is('.col-sm-5, .col-md-5')) {
                control_monto_adeudado.removeClass();
                control_monto_adeudado.addClass("col-sm-5 col-md-5");
            }

            if(!control_pago_parcial.is('.col-sm-4, .col-md-4')) {
                control_pago_parcial.removeClass();
                control_pago_parcial.addClass("col-sm-4 col-md-4");
            }
        } else {
            if(!estado_control_fecha_pago) {
                control_fecha_pago.show();
            }

            if(!control_monto_adeudado.is('.col-sm-3, .col-md-3')) {
                control_monto_adeudado.removeClass();
                control_monto_adeudado.addClass("col-sm-3 col-md-3");
            }

            if(!control_pago_parcial.is('.col-sm-3, .col-md-3')) {
                control_pago_parcial.removeClass();
                control_pago_parcial.addClass("col-sm-3 col-md-3");
            }
        }
        
        if(!estado_control_optagregar_cuotas) {
            control_opt_agregar_cuotas.show();
        }

        if(!estado_monto_adeudado) {
            control_monto_adeudado.show();
        }

        if(!estado_pago_parcial) {
            control_pago_parcial.show();
        }

        if(inicializar) {
            var tipo_operacion = $("#tipo_operacion_docelectronico").val();
            var monto_retencion = 0;
            if($("#select_aplica_retencion").val() == 'si') {
                if(tipo_moneda == 'USD') {
                    var monto_retencion = parseFloat($("#monto_dolares_retencion").val());
                } else {
                    var monto_retencion = parseFloat($("#monto_retencion").val());
                }
            }

            if(tipo_operacion == '1001' || tipo_operacion == '1002' || tipo_operacion == '1003' || tipo_operacion == '1004') {
                var tipo_moneda = $("#codmoneda_comprobante").val();
                var total_adeudado = 0;
                if(tipo_moneda == 'USD') {
                    $("#txt_monto_adeudado").val(round_math(parseFloat($("#txt_total_comprobante").val()) - parseFloat($("#monto_dolares_detraccion").val()), 2));
                } else {
                    $("#txt_monto_adeudado").val(round_math(parseFloat($("#txt_total_comprobante").val()) - parseFloat($("#monto_detraccion").val()), 2));
                }
            } else {
                if($("#select_aplica_retencion").val() == 'si') {
                    $("#txt_monto_adeudado").val(round_math(parseFloat($("#txt_total_comprobante").val()) - monto_retencion, 2));
                } else {
                    $("#txt_monto_adeudado").val($("#txt_total_comprobante").val());
                }
            }
            
            $("#pago_parcial").val(0);

            //si es una venta al crédito ocultamos los demás controles
            control_condicionpago_comprobante.hide();
            control_numero_operacion.hide();
            control_fecha_deposito.hide();
            control_cuenta_banco_deposito.hide();
        }
    } else { //ES UNA VENTA AL CONTADO
        control_fecha_pago.hide();
        control_monto_adeudado.hide();
        control_pago_parcial.hide();
        control_opt_agregar_cuotas.hide();

        if(tiene_otras_formas_de_pago == 'no') {
            control_condicionpago_comprobante.hide();
            control_numero_operacion.hide();
            control_fecha_deposito.hide();
            control_cuenta_banco_deposito.hide();
            return false;
        }

        control_condicionpago_comprobante.show();
        if(valor_condicionpago == 'tarjeta_credito') {
            if(!control_condicionpago_comprobante.is('.col-sm-5, .col-md-5')) {
                control_condicionpago_comprobante.removeClass();
                control_condicionpago_comprobante.addClass("col-sm-5 col-md-5");
            }

            if(!control_numero_operacion.is('.col-sm-4, .col-md-4')) {
                control_numero_operacion.removeClass();
                control_numero_operacion.addClass("col-sm-4 col-md-4");
            }
            control_numero_operacion.show();
            control_fecha_deposito.hide();
            control_cuenta_banco_deposito.hide();
            return false;
        } else if(valor_condicionpago == 'transferencia') {
            if(!control_condicionpago_comprobante.is('.col-sm-9, .col-md-9')) {
                control_condicionpago_comprobante.removeClass();
                control_condicionpago_comprobante.addClass("col-sm-9 col-md-9");
            }

            if(!control_numero_operacion.is('.col-sm-4, .col-md-4')) {
                control_numero_operacion.removeClass();
                control_numero_operacion.addClass("col-sm-4 col-md-4");
            }
            control_numero_operacion.show();

            if(!control_fecha_deposito.is('.col-sm-4, .col-md-4')) {
                control_fecha_deposito.removeClass();
                control_fecha_deposito.addClass("col-sm-4 col-md-4");
            }
            control_fecha_deposito.show();

            if(!control_cuenta_banco_deposito.is('.col-sm-4, .col-md-4')) {
                control_cuenta_banco_deposito.removeClass();
                control_cuenta_banco_deposito.addClass("col-sm-4 col-md-4");
            }
            control_cuenta_banco_deposito.show();

            return false;
        } else {
            if(!control_condicionpago_comprobante.is('.col-sm-9, .col-md-9')) {
                control_condicionpago_comprobante.removeClass();
                control_condicionpago_comprobante.addClass("col-sm-9 col-md-9");
            }
            
            control_numero_operacion.hide();
            control_fecha_deposito.hide();
            control_cuenta_banco_deposito.hide();
            return false;
        }
    }

    return false;
}

function get_opciones_formas_pago() {
    var total_condiciones = 0;
    var tiene_pago_credito = 'no';
    var tiene_otras_formas_de_pago = 'no';

    $("#condicionpago_comprobante > option").each(function() {
        total_condiciones = total_condiciones + 1;
        if($(this).data('tipocondicion') == 'credito') {
            tiene_pago_credito = 'si';
            $(this).remove();
        } else {
            tiene_otras_formas_de_pago = 'si';
        }
    });

    var resp = new Object();
        resp['total_condiciones'] = total_condiciones;
        resp['tiene_pago_credito'] = tiene_pago_credito;
        resp['tiene_otras_formas_de_pago'] = tiene_otras_formas_de_pago;
        
    return resp;
}