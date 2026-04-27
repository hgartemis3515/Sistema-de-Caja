var num_controls_anticipo = 0;
$(function() {
    $(".agregar_item_anticipo").click(agregar_item_tabla);


    $("#select_tiene_anticipos").on('change', valida_si_tiene_anticipos);
});

function valida_si_tiene_anticipos() {
    var tiene_anticipos = $("#select_tiene_anticipos").val();
    if(tiene_anticipos == 'si') {
        $("#row_total_anticipos").show();
        $("#row_total_a_pagar").show();
        $("#content_tiene_anticipos").show();
        var total_anticipos = calcularTotalAnticipos();
        var total_detraccion = 0;
        
        if($("#tipo_operacion_docelectronico").val() == '1001') { 
            var total_detraccion = parseFloat($("#txt_total_detraccion").val()) || 0;
        }

        var total_comprobante = parseFloat($("#txt_total_comprobante").val()) || 0;
        var total_a_pagar = round_math(total_comprobante - total_anticipos - total_detraccion, 2);
        $("#txt_total_anticipos").val(total_anticipos);
        $("#total_anticipos").html(total_anticipos);
        $("#txt_total_a_pagar").val(total_a_pagar);
        $("#total_a_pagar").html(total_a_pagar);
    } else {

        if($("#tipo_operacion_docelectronico").val() == '1001') { 
            
        } else {
            $("#row_total_a_pagar").hide();
        }

        $("#row_total_anticipos").hide();
        $("#content_tiene_anticipos").hide();
    }
}

function agregar_item_tabla() {
    agregar_item_anticipo();
}

function agregar_item_anticipo() {
    num_controls_anticipo++;
    //aquí podríamos agregar una validación de eliminar símbolos raros en la pregunta y respuesta
    var markup = `
    <tr counter-id="1">        
        <td style="width: 20% !important;" type_control="select" key_array="tipo_documento_anticipo">
            <select id="id_select_anticipo_` + num_controls_anticipo + `" class="select">
                <option value="01" selected>FACTURA</option>
            </select>
        </td>

        <td style="width: 25% !important;" type_control="input" key_array="serie_comprobante">
            <input type="text" id="id_serie_comprobante_` + num_controls_anticipo + `" value="" class="form-control">
        </td>

        <td style="width: 25% !important;" type_control="input" key_array="correlativo_comprobante">
            <input type="text" id="id_correlativo_comprobante_` + num_controls_anticipo + `" value="" class="form-control">
        </td>

        <td style="width: 20% !important;" type_control="input" key_array="monto_anticipo">
            <div class="input-group">
                <span class="input-group-addon font-weight-bold simbolo_moneda">S/.</span>
                <input class="form-control" type="text" value="" id="id_monto_anticipo_` + num_controls_anticipo + `">
            </div>
        </td>

        <td type_control="html" key_array="">
            <ul class="icons-list">
                <li class="text-danger-600"><a onclick="eliminar_anticipo(this)" href="javascript:void(0);"><i class="icon-trash"></i></a></li>\
            </ul>
        </td>
    </tr>`;

    $("#tbl_body_lista_anticipos").append(markup);
    $("#id_monto_anticipo_" + num_controls_anticipo).inputNumberFormat({ 'decimal': 2, 'decimalAuto': 2 });
    $("#id_correlativo_comprobante_" + num_controls_anticipo).inputNumberFormat({ 'decimal': 0, 'decimalAuto': 0 });

    $("#id_serie_comprobante_" + num_controls_anticipo).on('input', function() {
        var value = $(this).val();
        var newValue = value.replace(/[^a-zA-Z0-9]/g, ''); // Elimina caracteres no alfanuméricos
        if (newValue.charAt(0) !== 'F') { // Si el primer carácter no es F
            newValue = 'F' + newValue.substring(1); // Agrega F al principio
        }
        $(this).val(newValue.substring(0, 4)); // Limita a 4 caracteres
    });
    
    $("#id_select_anticipo_" + num_controls_anticipo).select2({ minimumResultsForSearch: -1 });

    $("#id_monto_anticipo_" + num_controls_anticipo).on('input', function() {
        valida_si_tiene_anticipos();
    });
}

function eliminar_anticipo(oButton) {
    swal({   
        title: 'Cuidado!',   
        text: '¿Realmente Deseas Eliminarlo?',
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
        } else {
            return;
        }
    });
}

function get_json_anticipos() {

    var data = [];
    var n = 0;
    $('#tbl_lista_anticipos tbody tr').map(function (idxRow, ele) {
        var obj = {};
        $(ele).find('td').map(function (idxCell, ele) {
            var type_control = $(ele).attr('type_control');
            var key_array = $(ele).attr('key_array');
            
            if(type_control == 'input') {
                var input = $(ele).find(':input');
                obj[key_array] = input.val();
            }
            
            if(type_control == 'textarea') {
                var textarea = $(ele).find('textarea');
                obj[key_array] = textarea.val();
            }

            if(type_control == 'select') {
                var select = $(ele).find('select');
                obj[key_array] = select.val();
            }

        });

        if(!isEmpty_obj(obj)) {
            n++;
            data.push(obj);
        }
    });
    
    var resp = {};
    resp['items'] = data;
    resp['num_items'] = n;

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

function calcularTotalAnticipos() {
    var jsonAnticipos = get_json_anticipos();
    var total = jsonAnticipos.items.reduce(function(acc, item) {
        var monto = parseFloat(item.monto_anticipo);
        if (!isNaN(monto) && item.monto_anticipo.trim() !== '') {
            return acc + monto;
        } else {
            return acc;
        }
    }, 0);
    
    return total;
}