$(function(){
	inicializar_tabla_cuotas();
});

function inicializar_tabla_cuotas() {
    $("#opt_agregar_cuotas_credito").click(function() {
        var total_adeudado = parseFloat($("#txt_monto_adeudado").val());
        if(total_adeudado <= 0) {
            swal({   
                title: 'Cuidado!',   
                text: 'El monto total adeudado es cero, no se puede agregar cuotas',
                html: true,
                type: "warning",   
                showCancelButton: false,   
                confirmButtonColor: "#3f51b5",    //confirmButtonColor: "#3f51b5",   
                cancelButtonColor: "#DD6B55",
                confirmButtonText: "Ok!",   
                closeOnConfirm: true,
                showLoaderOnConfirm: true
            }, function(isconfirmed){  

            });

            return false;
        }
        $("#vm_lista_cuota").modal({
            backdrop: 'static',
            keyboard: false
        });
        $("#vm_lista_cuotas_total_adeudado").html($("#txt_monto_adeudado").val());
    });

    $("#btn_eliminar_lista_cuotas").click(function() {
        $("#tbl_body_lista_cuotas").html('');
        $("#opt_agregar_cuotas_credito").html('(Click Aquí para Agregar 2 o Más Cuotas)');
        validar_formadepago_tipoventa(false);
    });
    
	$(".agregar_cuota_item").click(function(){
        var markup = '\
        <tr counter-id="1">\
            <td type_control="html" key_array="id_cuota" style="display:none;"></td>\
            <td type_control="html" key_array="nombre_cuota"><i class="icon-file-text2 position-left"></i> Cuota 1</td>\
            <td type_control="input" key_array="monto_cuota"><input type="text" class="form-control form-control-sm input_monto_cuota" name="monto_cuota" id="monto_cuota" placeholder="Monto Cuota" value="0"></td>\
            <td type_control="input" key_array="vencimiento_cuota"><input type="text" value="" name="fecha_vence_cuota" id="fecha_vence_cuota" placeholder="" class="form-control control_fecha_vence_cuota"></td>\
            <td type_control="html" key_array="">\
                <ul class="icons-list">\
                    <li class="text-danger-600"><a onclick="eliminar_cuota_item(this)" href="javascript:void(0);"><i class="icon-trash"></i></a></li>\
                </ul>\
            </td>\
        </tr>';
        $("#tbl_body_lista_cuotas").append(markup);

        $(".control_fecha_vence_cuota").daterangepicker({singleDatePicker: true, locale: { format: 'DD/MM/YYYY' }});
        var resp_json = get_json_cuotas();
        $(".input_monto_cuota").on('input', function() {
            var resp_json = get_json_cuotas();
            if(resp_json.monto_total_cuotas > 0) {
                $("#footer_tabla_cuotas").show("slide");
                $("#monto_total_cuotas").html(resp_json.monto_total_cuotas);
            } else {
                $("#footer_tabla_cuotas").hide("slide");
            }
        }).on('change', function() {
            var resp_json = get_json_cuotas();
            if(resp_json.monto_total_cuotas > 0) {
                $("#footer_tabla_cuotas").show("slide");
                $("#monto_total_cuotas").html(resp_json.monto_total_cuotas);
            } else {
                $("#footer_tabla_cuotas").hide("slide");
            }
        });
    });

    $("#btn_guardar_lista_cuotas").click(function() {
        var resp_json =  get_json_cuotas();
        var resp_validacion = validar_data_cuotas(resp_json);
        if(!resp_validacion.respuesta) {
            swal({   
                title: 'Cuidado!',   
                text: resp_validacion.mensaje,
                html: true,
                type: "warning",   
                showCancelButton: false,   
                confirmButtonColor: "#3f51b5",    //confirmButtonColor: "#3f51b5",   
                cancelButtonColor: "#DD6B55",
                confirmButtonText: "Ok!",   
                closeOnConfirm: true,
                showLoaderOnConfirm: true
            }, function(isconfirmed){  

            });

            return false;
        }

        if(resp_json.num_cuotas <= 1) {
            swal({   
                title: 'Cuidado!',   
                text: 'Para Utilizar Esta Opción Al menos Debería Agregar 2 o más cuotas',
                html: true,
                type: "warning",   
                showCancelButton: false,   
                confirmButtonColor: "#3f51b5",    //confirmButtonColor: "#3f51b5",   
                cancelButtonColor: "#DD6B55",
                confirmButtonText: "Ok!",   
                closeOnConfirm: true,
                showLoaderOnConfirm: true
            }, function(isconfirmed){  

            });

            return false;
        }

        var total_adeudado = parseFloat($("#txt_monto_adeudado").val());
        var total_en_cuotas = parseFloat(resp_json.monto_total_cuotas);

        if(total_adeudado != total_en_cuotas) {
            swal({   
                title: 'Cuidado!',   
                text: 'La Suma Total de las Cuotas debe Coincidir con el Monto Total Adeudado',
                html: true,
                type: "warning",   
                showCancelButton: false,   
                confirmButtonColor: "#3f51b5",    //confirmButtonColor: "#3f51b5",   
                cancelButtonColor: "#DD6B55",
                confirmButtonText: "Ok!",   
                closeOnConfirm: true,
                showLoaderOnConfirm: true
            }, function(isconfirmed){  

            });

            return false;
        }

        $("#opt_agregar_cuotas_credito").html('Se registraron ' + resp_json.num_cuotas + ' cuotas. (Click para Editar o Agregar Más Cuotas)');
        $("#monto_total_cuotas").html('0');
        $("#vm_lista_cuota").modal('hide');
        validar_formadepago_tipoventa(false);

    });
}

function eliminar_cuota_item(oButton) {
    swal({   
        title: 'Cuidado!',   
        text: '¿Realmente Deseas esta Cuota?',
        html: true,
        type: "warning",   
        showCancelButton: true,   
        confirmButtonColor: "#3f51b5",    //confirmButtonColor: "#3f51b5",   
        cancelButtonColor: "#DD6B55",
        confirmButtonText: "Si, Adelante!",   
        closeOnConfirm: true,
        showLoaderOnConfirm: true
    }, function(isconfirmed){  
        if(isconfirmed) {
            oButton.parentNode.parentNode.parentNode.parentNode.remove();
            $(".input_monto_cuota").trigger("input");
        } else {
            return;
        }
    });
}

//renombra las cuotas y extrae un array de cuotas
function get_json_cuotas() {

    var data = [];
    var n = 0;
    var monto_total = 0;
    $('#tbl_lista_cuotas tbody tr').map(function (idxRow, ele) {
        var obj = {};
        $(ele).find('td').map(function (idxCell, ele) {
            var type_control = $(ele).attr('type_control');
            var key_array = $(ele).attr('key_array');
            
            if(type_control == 'input') {
                var input = $(ele).find(':input');
                obj[key_array] = input.val().replace(/\s/g, "");
                input.val(input.val().replace(/\s/g, "")); //eliminamos espacios

                if(key_array == 'monto_cuota') {
                    monto_total = monto_total + parseFloat(input.val());
                }
            }

            if(key_array == 'nombre_cuota') {
                $(ele).html('<i class="icon-file-text2 position-left"></i> Cuota ' + (n + 1));
            }

            if(key_array == 'id_cuota') {
                obj[key_array] = (n + 1);
            }
        });

        if(!isEmpty_obj(obj)) {
            n++;
            data.push(obj);
        }
    });
    
    var resp = {};
    resp['cuotas'] = data;
    resp['num_cuotas'] = n;
    resp['monto_total_cuotas'] = monto_total;

    return resp;
}

function isEmpty_obj(obj) {
    for(var prop in obj) {
      if(Object.prototype.hasOwnProperty.call(obj, prop)) {
        return false;
      }
    }
    return JSON.stringify(obj) === JSON.stringify({});
}

function validar_data_cuotas(data_cuotas) {
    var fecha_anterior = '';

    for (let indice = 0; indice < data_cuotas.cuotas.length; indice ++) { 
        var cuota = data_cuotas.cuotas[indice];
        console.log(cuota.monto_cuota);
        if(!es_numero(cuota.monto_cuota)) {
            return {
                respuesta: false,
                mensaje: 'La cuota N° ' + (indice + 1) + ' tiene un monto inválido...'
            };
        }

        if(!validatedate(cuota.vencimiento_cuota, 'dd/mm/yyyy')) {
            return {
                respuesta: false,
                mensaje: 'La fecha de vencimiento para la cuota N° ' + (indice + 1) + ' tiene una fecha inválida...'
            };
        }

        if(cuota.monto_cuota <= 0) {
            return {
                respuesta: false,
                mensaje: 'La cuota N° ' + (indice + 1) + ' debe tener un valor mayor a cero...'
            };
        }

        var array_fecha_vencimiento = cuota.vencimiento_cuota.split('/');
        var fecha_vencimiento = new Date(array_fecha_vencimiento[2], array_fecha_vencimiento[1] - 1, array_fecha_vencimiento[0]).setHours(0, 0, 0, 0);
        var fecha_actual = new Date().setHours(0, 0, 0, 0);

        if(indice == 0) {
            if(fecha_vencimiento <= fecha_actual){
                return {
                    respuesta: false,
                    mensaje: 'La fecha de vencimiento de la cuota N° ' + (indice + 1) + ' debe ser mayor a la fecha actual.'
                };
            }

            fecha_anterior = fecha_vencimiento;
        } else {
            if(fecha_vencimiento <= fecha_anterior) {
                return {
                    respuesta: false,
                    mensaje: 'La fecha de vencimiento de la cuota N° ' + (indice + 1) + ' es menor o igual a la fecha de vencimiento de la cuota N° ' + (indice) + '. ¡La Cuota N° ' + (indice + 1) + ' debería tener una fecha de vencimiento mayor a la anterior.'
                };
            }
        }
    }

    return {
        respuesta: true
    };
}

function es_numero(value) {
    return !isNaN(+value);
}

function validatedate(inputText, DateFormat) {
    // format dd/mm/yyyy or in any order of (dd or mm or yyyy) you can write dd or mm or yyyy in first or second or third position ... or can be slash"/" or dot"." or dash"-" in the dates formats
    var invalid = "";
    var dt = "";
    var mn = "";
    var yr = "";
    var k;
    var delm = DateFormat.includes("/") ? "/" : (DateFormat.includes("-") ? "-" : (DateFormat.includes(".") ? "." : ""));
    var f1 = inputText.split(delm);
    var f2 = DateFormat.split(delm);
    for (k = 0; k <= 2; k++) {
        dt = dt + (f2[parseInt(k)] == "dd" ? f1[parseInt(k)] : "");
        mn = mn + (f2[parseInt(k)] == "mm" ? f1[parseInt(k)] : "");
        yr = yr + (f2[parseInt(k)] == "yyyy" ? f1[parseInt(k)] : "");
    }
    var mn_days = "0-31-" + (yr % 4 == 0 ? 29 : 28) + "-31-30-31-30-31-31-30-31-30-31";
    var days = mn_days.split("-");
    if (f1.length != 3 || mn.length > 2 || dt.length > 2 || yr.length != 4 || !(parseInt(mn) >= 1 && parseInt(mn) <= 12) || !(parseInt(yr) >= parseInt(1900) && parseInt(yr) <= parseInt(2100)) || !(parseInt(dt) >= 1 && parseInt(dt) <= parseInt(days[parseInt(mn)]))) {
        invalid = "true";
    }
    var resp = (invalid == "true" ? false : true);
    return resp;
}