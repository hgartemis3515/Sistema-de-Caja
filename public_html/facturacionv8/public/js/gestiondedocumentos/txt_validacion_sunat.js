var array_txt_cpe = [];
var array_data_cpe = [];
var array_cpe_informe_sunat = [];
function get_txt_validacion_sunat() {

    $("#content_validación_cpe").hide();
    $("#content_tabla_informe_sunat").html("");

    array_txt_cpe = [];
    array_data_cpe = [];
    array_cpe_informe_sunat = [];

    $("#barra_progreso_div").show("slide");
    $("#content_data_reporte_sunat").html("");

    const rows = document.querySelectorAll('#tbl_lista_documentos tbody tr');

    $('#content_barra_progreso .progress-bar').attr('data-transitiongoal', 0).progressbar({display_text: 'center', use_percentage: false});
	var $pb = $('#content_barra_progreso .progress-bar');
	$pb.attr('aria-valuemax', rows.length);

    function makeAjaxCall(url, data) {
		return $.ajax({
			url: url,
			type: 'POST',
			dataType : "json",
			data: data
		});
	}

    function processRow(index) {
		// si se ha procesado todas las filas, detiene la recursión
		if (index >= rows.length) {
			var recarga_tabla = $('#opcion_recarga_tabla').bootstrapSwitch('state');
			if(recarga_tabla) {
				//dataTable_docs_sunat.ajax.reload(null, false);
			}

            /*
            array_data_cpe.forEach(function(value, index) {
                $("#content_data_reporte_sunat").append(value + "<br /><br />");
            });
            */

            $("#content_validación_cpe").show();

            var html_tabla = '\
            <table class="tabla_informe_sunat">\
                <tr>\
                    <th scope="row">RUC Contribuyente</th>\
                    <th>Tipo</th>\
                    <th>Serie</th>\
                    <th>Correlativo</th>\
                    <th>Fecha Emisión</th>\
                    <th>Importe Total</th>\
                </tr>\
            ';

            var filas_tabla = '';
            array_cpe_informe_sunat.forEach(function(cpe, index) {
                filas_tabla = filas_tabla + "\
                <tr>\
                    <th>" + cpe.ruc_emisor + "</th>\
                    <th>" + cpe.nombre_cpe + "</th>\
                    <td>" + cpe.serie_comprobante + "</td>\
                    <td>" + cpe.numero_comprobante + "</td>\
                    <td>" + cpe.fecha_comprobante + "</td>\
                    <td>" + cpe.total + "</td>\
                </tr>\
                ";
            });

            html_tabla = html_tabla + filas_tabla + '</table>';

            $("#content_tabla_informe_sunat").html(html_tabla);

            $("#content_data_reporte_sunat").show("slide");
		  	return;
		}
        
        $pb.attr('data-transitiongoal', (index + 1));
        $pb.progressbar({display_text: 'center', use_percentage: false});
		
		let id_row = rows[index].getAttribute('id');
		if(id_row != '') {
			let codigo	 = id_row.split('||');
			let documento = {
				"id_row"					: id_row,
				"id_contribuyente"			: codigo[0],
				"id_tipodoc_electronico"	: codigo[1],
				"serie_comprobante"			: codigo[2],
				"numero_comprobante"		: codigo[3],
				"tipo_envio_sunat"			: codigo[4],
				"estado_envio_sunat"		: codigo[5],
				"numero_ticket"				: codigo[6]
			};

            var id_campo_cliente = "#clie_" + id_row.replace(/\|\|/g, '_');

            if(codigo[5] == 'aceptado') {
                makeAjaxCall('/facturacionv8/gestiondedocumentos/proceso_validacion_cpe', documento)
				    .then(function(response) {

                        if(response.respuesta == 'ok') {
                            $(id_campo_cliente).html('\
                            <div class="alert alert-success alert-bordered" style="max-width: 670px; white-space: normal; word-wrap: break-word;">\
                                '+ response.mensaje +'\
                            </div>');
                        } else if(response.respuesta == 'informe_sunat') {
                            $(id_campo_cliente).html('\
                            <div class="alert alert-success alert-bordered" style="max-width: 670px; white-space: normal; word-wrap: break-word;">\
                                El Documento no se encuentra en SUNAT:<br />\
                                Ruc: ' + response.data_cpe + ' \
                                \
                            </div>');

                            array_txt_cpe.push(response.txt_cpe);
                            array_data_cpe.push(response.data_cpe);
                            array_cpe_informe_sunat.push(response.cpe);

                        } else {
                            $(id_campo_cliente).html('\
                            <div class="alert alert-danger alert-bordered" style="max-width: 670px; white-space: normal; word-wrap: break-word;">\
                                '+ response.mensaje +'\
                            </div>');
                        }

                        processRow(index + 1);
                    }).catch(function(error) {
                        processRow(index + 1);
				    });
            } else {
                processRow(index + 1);
            }			
		} else {
			processRow(index + 1);
		}
	}

    processRow(0);
}