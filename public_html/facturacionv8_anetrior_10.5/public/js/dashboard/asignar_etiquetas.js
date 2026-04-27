$(function() {
    extraer_lista_etiquetas();
    inicializar_radiobutton();

    $(".control_color_etiqueta ").on("change", function () {
        let color = $(this).val();
        $("#btn_agregar_etiqueta").removeClass();
        $("#btn_agregar_etiqueta").addClass('btn btn-'+ color +' legitRipple');
    });

    $("#btn_agregar_etiqueta").click(guardar_etiqueta);

    //$(".switch").bootstrapSwitch();
    // Switchery
    // ------------------------------

    // Initialize multiple switches
    if (Array.prototype.forEach) {
        var elems = Array.prototype.slice.call(document.querySelectorAll('.switchery'));
        elems.forEach(function(html) {
            var switchery = new Switchery(html);
        });
    } else {
        var elems = document.querySelectorAll('.switchery');
        for (var i = 0; i < elems.length; i++) {
            var switchery = new Switchery(elems[i]);
        }
    }
    
    $('#btn_show_hide_all_cpe').change(function() {
        if(this.checked) {
            dataTable_docs_sunat.rows().every(function(){
                // If row has details collapsed
                if(!this.child.isShown()){
                    // Open this row
                    this.child(tbl_informacion_extra_cpe(this.data())).show();
                    $(this.node()).addClass('shown');
                }
            });
        } else {
			dataTable_docs_sunat.rows().every(function(){
                // If row has details expanded
                if(this.child.isShown()){
                    // Collapse row details
                    this.child.hide();
                    $(this.node()).removeClass('shown');
                }
            });
		}
    });

    $('#btn_show_hide_all_cotizaciones').change(function() {
        if(this.checked) {
            dataTable_cotizaciones.rows().every(function(){
                // If row has details collapsed
                if(!this.child.isShown()){
                    // Open this row
                    this.child(tbl_informacion_extra_cotizacion(this.data())).show();
                    $(this.node()).addClass('shown');
                }
            });
        } else {
			dataTable_cotizaciones.rows().every(function(){
                // If row has details expanded
                if(this.child.isShown()){
                    // Collapse row details
                    this.child.hide();
                    $(this.node()).removeClass('shown');
                }
            });
		}
    });

    $('#btn_show_hide_all_guias').change(function() {
        if(this.checked) {
            dataTable_guiasremision.rows().every(function(){
                // If row has details collapsed
                if(!this.child.isShown()){
                    // Open this row
                    this.child(tbl_informacion_extra_guia_remision(this.data())).show();
                    $(this.node()).addClass('shown');
                }
            });
        } else {
			dataTable_guiasremision.rows().every(function(){
                // If row has details expanded
                if(this.child.isShown()){
                    // Collapse row details
                    this.child.hide();
                    $(this.node()).removeClass('shown');
                }
            });
		}
    });

    $('#btn_show_hide_all_guiastransportista').change(function() {
        if(this.checked) {
            dataTable_guiastransportista.rows().every(function(){
                // If row has details collapsed
                if(!this.child.isShown()){
                    // Open this row
                    this.child(tbl_informacion_extra_guia_transportista(this.data())).show();
                    $(this.node()).addClass('shown');
                }
            });
        } else {
			dataTable_guiastransportista.rows().every(function(){
                // If row has details expanded
                if(this.child.isShown()){
                    // Collapse row details
                    this.child.hide();
                    $(this.node()).removeClass('shown');
                }
            });
		}
    });

    $('#btn_show_hide_all_nventa').change(function() {
        if(this.checked) {
            dataTable_notas_de_venta.rows().every(function(){
                // If row has details collapsed
                if(!this.child.isShown()){
                    // Open this row
                    this.child(tbl_informacion_extra_nota_venta(this.data())).show();
                    $(this.node()).addClass('shown');
                }
            });
        } else {
			dataTable_notas_de_venta.rows().every(function(){
                // If row has details expanded
                if(this.child.isShown()){
                    // Collapse row details
                    this.child.hide();
                    $(this.node()).removeClass('shown');
                }
            });
		}
    });
});

function extraer_lista_etiquetas() {
    var light = $("#content_lista_de_etiquetas");

    $(light).block({
		message: '<div class="loading"> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
					</div> <p> <br />Guardando Etiqueta! ...</p>',
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

    $("#content_lista_de_etiquetas").html('');
    $("#content_etiquetas_elegir").html('');

    $.ajax({
        url : '/facturacionv8/gestiondeetiquetas/get_lista_etiquetas',
        method :  'POST',
        dataType : "json",
    }).then(function(data){
        if(data.respuesta == 'ok') {
            $.each(data.lista, function( index, etiqueta ) {
                var html_etiqueta = '\
                    <div id="etiqueta_id_'+etiqueta.id_etiqueta+'" class="tag_etiqueta_individual" data-coloretiqueta="'+etiqueta.color+'" data-nombreetiqueta="'+etiqueta.nombre+'" data-idetiqueta="'+etiqueta.id_etiqueta+'">\
                        <span class="bg-'+etiqueta.color+'">'+etiqueta.nombre+'</span>\
                        <a href="javascript:void(0)" onclick="eliminar_etiqueta('+etiqueta.id_etiqueta+')" class="close">×</a>\
                    </div>\
                ';

                var html_checkbox_etiquetas = '\
                    <div class="col-md-4" id="etiqueta_checkbox_'+etiqueta.id_etiqueta+'">\
                        <div class="checkbox">\
                            <label>\
                                <input onclick="asignar_etiquetaxdocumento('+etiqueta.id_etiqueta+')" data-nombre="'+etiqueta.nombre+'" data-color="'+etiqueta.color+'" id="input_etiqueta_check_'+etiqueta.id_etiqueta+'" type="checkbox" value="'+etiqueta.id_etiqueta+'" class="etiqueta_control-'+etiqueta.color+'">'+etiqueta.nombre+'\
                            </label>\
                        </div>\
                    </div>\
                ';
                //checked="checked"
                $("#content_lista_de_etiquetas").html($("#content_lista_de_etiquetas").html() + html_etiqueta);
                $("#content_etiquetas_elegir").html($("#content_etiquetas_elegir").html() + html_checkbox_etiquetas);
            });
            inicializar_controles_checkbox();
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

function asignar_etiquetaxdocumento(id_etiqueta) {
    var id_contribuyente = $("#vm_etiqueta_id_contribuyente").val();
    var tipo_documento = $("#vm_etiqueta_tipo_documento").val();
    var serie_documento = $("#vm_etiqueta_serie_documento").val();
    var correlativo_documento = $("#vm_etiqueta_correlativo").val();
    
    if(id_contribuyente != '' && tipo_documento != '' && serie_documento != '' && correlativo_documento != '') {
        if($('#input_etiqueta_check_'+id_etiqueta).length){
            let activo = $('#input_etiqueta_check_'+id_etiqueta).is(':checked');
            valor_activo = activo?'si':'no';
            $.ajax({
                url : '/facturacionv8/gestiondeetiquetas/asignar_etiquetaxdocumento',
                method :  'POST',
                dataType : "json",
                data: {id_contribuyente:id_contribuyente, tipo_documento:tipo_documento, serie_documento:serie_documento, correlativo_documento:correlativo_documento, id_etiqueta: id_etiqueta, activo: valor_activo},
            }).then(function(data){
                if(data.respuesta == 'ok') {
                    var id_etiqueta_doc = "tag_in_table-"+data.etiqueta.id_etiqueta+"-"+data.etiqueta.id_contribuyente+"-"+data.etiqueta.id_tipodoc_electronico+"-"+data.etiqueta.serie_comprobante+"-"+data.etiqueta.numero_comprobante+"";
                    var id_btn_addtag = "btn_addtag-"+data.etiqueta.id_contribuyente+"-"+data.etiqueta.id_tipodoc_electronico+"-"+data.etiqueta.serie_comprobante+"-"+data.etiqueta.numero_comprobante+"";
                    var class_array_etiquetas = "array_etiquetas-"+data.etiqueta.id_contribuyente+"-"+data.etiqueta.id_tipodoc_electronico+"-"+data.etiqueta.serie_comprobante+"-"+data.etiqueta.numero_comprobante+"";
                    var color = $('#input_etiqueta_check_'+id_etiqueta).data('color');
                    var nombre_etiqueta = $('#input_etiqueta_check_'+id_etiqueta).data('nombre');

                    if(valor_activo == 'si') {
                        if(tipo_documento == '77') {
                            //dataTable_notas_de_venta.ajax.reload(null, false);
                            $( '<span id="'+id_etiqueta_doc+'" data-idetiqueta="'+data.etiqueta.id_etiqueta+'" class="'+class_array_etiquetas+' label label-'+color+' label-roundless"><i class="icon-price-tag2" style="font-size:10px;"></i> '+nombre_etiqueta+'</span>' ).insertBefore( "#"+id_btn_addtag );
                            console.log(" => 77");
                        } else if(tipo_documento == '88') {
                            //dataTable_cotizaciones.ajax.reload(null, false);
                            $( '<span id="'+id_etiqueta_doc+'" data-idetiqueta="'+data.etiqueta.id_etiqueta+'" class="'+class_array_etiquetas+' label label-'+color+' label-roundless"><i class="icon-price-tag2" style="font-size:10px;"></i> '+nombre_etiqueta+'</span>' ).insertBefore( "#"+id_btn_addtag );
                            console.log(" => 88 + " + id_btn_addtag);
                        } else if(tipo_documento == '09') {
                            //dataTable_guiasremision.ajax.reload(null, false);
                            $( '<span id="'+id_etiqueta_doc+'" data-idetiqueta="'+data.etiqueta.id_etiqueta+'" class="'+class_array_etiquetas+' label label-'+color+' label-roundless"><i class="icon-price-tag2" style="font-size:10px;"></i> '+nombre_etiqueta+'</span>' ).insertBefore( "#"+id_btn_addtag );
                            console.log("=> 09");
                        } else {
                            //dataTable_docs_sunat.ajax.reload(null, false);
                            $( '<span id="'+id_etiqueta_doc+'" data-idetiqueta="'+data.etiqueta.id_etiqueta+'" class="'+class_array_etiquetas+' label label-'+color+' label-roundless"><i class="icon-price-tag2" style="font-size:10px;"></i> '+nombre_etiqueta+'</span>' ).insertBefore( "#"+id_btn_addtag );
                            console.log("=> " + tipo_documento);
                        }
                    } else {
                        $( "#"+id_etiqueta_doc ).remove();
                    }
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
    }
}

function inicializar_radiobutton() {
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

function inicializar_controles_checkbox() {
    // Primary
    $(".etiqueta_control-primary").uniform({
        radioClass: 'choice',
        wrapperClass: 'border-primary-600 text-primary-800'
    });

    // Danger
    $(".etiqueta_control-danger").uniform({
        radioClass: 'choice',
        wrapperClass: 'border-danger-600 text-danger-800'
    });

    // Success
    $(".etiqueta_control-success").uniform({
        radioClass: 'choice',
        wrapperClass: 'border-success-600 text-success-800'
    });

    // Warning
    $(".etiqueta_control-warning").uniform({
        radioClass: 'choice',
        wrapperClass: 'border-warning-600 text-warning-800'
    });

    // Info
    $(".etiqueta_control-info").uniform({
        radioClass: 'choice',
        wrapperClass: 'border-info-600 text-info-800'
    });
}

function eliminar_etiqueta(id_etiqueta) {
    var light = $("#content_vm_asignar_etiqueta");

    $(light).block({
		message: '<div class="loading"> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
					</div> <p> <br />Guardando Etiqueta! ...</p>',
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
		title: 'Necesitamos de tu Confirmación',   
		text: '¿Realmente Deseas Eliminar la Etiqueta? No se puede deshacer la eliminación.',
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
            $.ajax({
                url : '/facturacionv8/gestiondeetiquetas/eliminar_etiqueta',
                method :  'POST',
                dataType : "json",
                data: {id_etiqueta: id_etiqueta},
            }).then(function(data){
                if(data.respuesta == 'ok') {
                    $("#etiqueta_id_" + id_etiqueta).remove();
                    $("#etiqueta_checkbox_" + id_etiqueta).remove();
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
        } else {
			$(light).unblock();
			return;
		}
	});
    
}

function redibujar_checkbox() {
    var array_selected = [];
    $('#content_etiquetas_elegir input:checked').each(function() {
        array_selected.push(parseInt($(this).val()));
    });

    $("#content_etiquetas_elegir").html('');
    $('.tag_etiqueta_individual').each(function(i, obj) {
        var nombre = $(this).data('nombreetiqueta');
        var id_etiqueta = parseInt($(this).data('idetiqueta'));
        var color = $(this).data('coloretiqueta');
        var checked = '';
        if($.inArray(id_etiqueta, array_selected) !== -1) {
            var checked = ' checked';
        } else {
            
        }

        var html_checkbox_etiquetas = '\
                <div class="col-md-4" id="etiqueta_checkbox_'+id_etiqueta+'">\
                    <div class="checkbox">\
                        <label>\
                            <input onclick="asignar_etiquetaxdocumento('+id_etiqueta+')" data-nombre="'+nombre+'" data-color="'+color+'" id="input_etiqueta_check_'+id_etiqueta+'" type="checkbox" value="'+id_etiqueta+'" class="etiqueta_control-'+color+'" '+checked+'>'+nombre+'\
                        </label>\
                    </div>\
                </div>\
            ';
            
        $("#content_etiquetas_elegir").html($("#content_etiquetas_elegir").html() + html_checkbox_etiquetas);
    });

    inicializar_controles_checkbox();
}

function guardar_etiqueta() {
    var light = $("#content_vm_asignar_etiqueta");

    $(light).block({
		message: '<div class="loading"> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
					</div> <p> <br />Guardando Etiqueta! ...</p>',
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

    var nombre_etiqueta = $("#txt_nombre_etiqueta").val();
    var color_etiqueta = $('input[name=color_etiqueta]:checked').val();

    if(nombre_etiqueta == '') {
        swal({
            title: 'Error',
            text: 'Etiqueta Vacía',
            html: true,
            type: "info",
            confirmButtonText: "Ok",
            confirmButtonColor: "#2196F3"
        }, function(){
            return false;
            $(light).unblock();
        });
    }

    $('.tag_etiqueta_individual').each(function(i, obj) {
        var nombre_guardado = $(this).data('nombreetiqueta');
        if(nombre_etiqueta == nombre_guardado) {
            swal({
                title: 'Error',
                text: 'La Etiqueta con nombre: ' + nombre_etiqueta + ' ya existe.',
                html: true,
                type: "info",
                confirmButtonText: "Ok",
                confirmButtonColor: "#2196F3"
            }, function(){
                return false;
                $(light).unblock();
            });
        }
    });

    $.ajax({
        url : '/facturacionv8/gestiondeetiquetas/guardar_etiqueta',
        method :  'POST',
        dataType : "json",
        data: {nombre: nombre_etiqueta, color: color_etiqueta},
    }).then(function(data){
        if(data.respuesta == 'ok') {
            var html_etiqueta = '\
                <div id="etiqueta_id_'+data.etiqueta.id_etiqueta+'" class="tag_etiqueta_individual" data-coloretiqueta="'+data.etiqueta.color+'" data-nombreetiqueta="'+nombre_etiqueta+'" data-idetiqueta="'+data.etiqueta.id_etiqueta+'">\
                    <span class="bg-'+color_etiqueta+'">'+nombre_etiqueta+'</span>\
                    <a href="javascript:void(0)" onclick="eliminar_etiqueta('+data.etiqueta.id_etiqueta+')" class="close">×</a>\
                </div>\
            ';

            $("#txt_nombre_etiqueta").val('');
            $("#content_lista_de_etiquetas").html($("#content_lista_de_etiquetas").html() + html_etiqueta);
            redibujar_checkbox();
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

function etiquetar_documento(id_contribuyente, tipo_documento, serie_documento, correlativo, lista_etiquetas) {
    $("#vm_etiqueta_id_contribuyente").val(id_contribuyente);
    $("#vm_etiqueta_tipo_documento").val(tipo_documento);
    $("#vm_etiqueta_serie_documento").val(serie_documento);
    $("#vm_etiqueta_correlativo").val(correlativo);
    $('#content_etiquetas_elegir input:checkbox').prop('checked', false);
    $.uniform.update();

    var class_array_etiquetas = "array_etiquetas-"+id_contribuyente+"-"+tipo_documento+"-"+serie_documento+"-"+correlativo+"";
    var array_etiquetas = [];
    $( "."+class_array_etiquetas ).each(function( index ) {
        array_etiquetas.push($( this ).data('idetiqueta'));
    });

    //var array_etiquetas = lista_etiquetas.split(",");
    array_etiquetas.forEach( function(valor, indice, array) {
        if($('#input_etiqueta_check_'+valor).length){
            $('#input_etiqueta_check_'+valor).prop('checked',true);
            $.uniform.update();
        }
    });

    $("#vm_asignar_etiqueta").modal('show');
}