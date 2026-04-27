function get_data_documento_electronico(tipo_doc, serie, numero, light, id_tipo_doc_modifica = '', serie_comprobante_modifica = '', numero_comprobante_modifica = ''){

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
        url : '/facturacionv8/documentoelectronico/get_documento_electronico',
		method :  'POST',
		data: {serie: serie, numero: numero, tipo_doc: tipo_doc, id_tipo_doc_modifica: id_tipo_doc_modifica, serie_comprobante_modifica: serie_comprobante_modifica, numero_comprobante_modifica: numero_comprobante_modifica},
        dataType : "json"
    }).then(function(data){
        if(data.respuesta == 'ok') {
			$(light).unblock();
			$("#preview_heading_comprobante").html(data.heading_elements);
			$("#preview_tipo_comprobante").html(data.titulo_comprobante);

			$("#cliente_tipo_docidentidad").val(data.cliente.id_tipodocidentidad).trigger("change").trigger("select2:select");
			$("#cliente_numerodocumento").val(data.cliente.num_doc);
			$("#cliente_nombre").val(data.cliente.razon_social);
			$("#cliente_direccion").val(data.cliente.direccion_fiscal);
			$("#select_codigoubigeo").val(data.cliente.id_cod_ubigeo).trigger("change").trigger("select2:select");
			$("#txt_descuento_porcentaje").val(data.comprobante.porcentaje_descuento_total);
			$("#codmoneda_comprobante").val(data.comprobante.id_codigomoneda);
			$("#content_data_doc_modificado").show('slide');
			limpiar_jq_grid(jQuery('#detalle_documento'));

			data.data_grid.forEach( function(data_item, indice, array) {
				jQuery('#detalle_documento').addRowData(indice + 1, data_item, 'last');
			});

			if(data.doc_modifica_id_tipodoc_electronico != '' && data.doc_modifica_serie_comprobante != '' && data.doc_modifica_numero_comprobante != '') {
				$("#tipo_docelectronico_modificar").val(data.doc_modifica_id_tipodoc_electronico).trigger("change").trigger("select2:select");
				var value_select = data.doc_modifica_serie_comprobante + ',' + data.doc_modifica_numero_comprobante;
				$("#serie_numero_doc_modificar").select2('data', {id: value_select, text: data.doc_modifica_serie_comprobante + ' - ' + data.doc_modifica_numero_comprobante});
				$("#serie_numero_doc_modificar").val(value_select).trigger("select2:select");
				if(tipo_doc == '07') {
					$("#id_motivo_nota_credito").val(data.cod_motivo_nota).trigger("change").trigger("select2:select");
				} else {
					$("#id_motivo_nota_debito").val(data.cod_motivo_nota).trigger("change").trigger("select2:select");
				}
			}

			$("#select_igv_sunat").val(parseInt(data.comprobante.porcentaje_igv)).trigger("change");
			calcular_totales_documento();
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

function limpiar_jq_grid(jq_grid) {
	var rowIds = jq_grid.jqGrid('getDataIDs');
	for(var i=0,len=rowIds.length;i<len;i++){
		var currRow = rowIds[i];
		jq_grid.jqGrid('delRowData', currRow);
	} 
}