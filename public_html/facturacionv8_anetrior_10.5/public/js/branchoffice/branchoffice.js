$(function(){
	$('.select_ubigeo').select2();
	$('.select_igv').select2({ minimumResultsForSearch: -1 });
	$('.select_glosa_amazonia').select2({ minimumResultsForSearch: -1 });
	$('.btn_branchoffice').click(save_branchoffice);
	/*$(".btn_generar_codigo").click(function(){
		generar_codigo($("#txt_codigo"), 6);
	});*/
	get_sucursal();
	inicializar_controles();
	
	$(".tab_diseno_pdf_a4_1").hide();
	$(".tab_diseno_pdf_a4_2").hide();
	$(".tab_diseno_pdf_a4_3").hide();
	$(".tab_diseno_pdf_a4_4").hide();
	$(".tab_diseno_pdf_a4_5").hide();
	$(".tab_diseno_pdf_a4_6").hide();
	$(".tab_diseno_pdf_a4_7").hide();
	$(".tab_diseno_pdf_a4_8").hide();

	$('input[type=radio][name=modelo_plantilla_pdf_a4]').change(function() {
		var modelo = $("input[name=modelo_plantilla_pdf_a4]:checked").val();
		if(modelo == '1'){
			$(".tab_diseno_pdf_a4_1").show();
			$(".tab_diseno_pdf_a4_2").hide();
			$(".tab_diseno_pdf_a4_3").hide();
			$(".tab_diseno_pdf_a4_4").hide();
			$(".tab_diseno_pdf_a4_5").hide();
			$(".tab_diseno_pdf_a4_6").hide();
			$(".tab_diseno_pdf_a4_7").hide();
			$(".tab_diseno_pdf_a4_8").hide();
		}else if(modelo == '2'){
			$(".tab_diseno_pdf_a4_1").hide(); 
			$(".tab_diseno_pdf_a4_2").show();
			$(".tab_diseno_pdf_a4_3").hide();
			$(".tab_diseno_pdf_a4_4").hide();
			$(".tab_diseno_pdf_a4_5").hide();
			$(".tab_diseno_pdf_a4_6").hide();
			$(".tab_diseno_pdf_a4_7").hide();
			$(".tab_diseno_pdf_a4_8").hide();
		} else if(modelo == '3'){
			$(".tab_diseno_pdf_a4_1").hide(); 
			$(".tab_diseno_pdf_a4_2").hide();
			$(".tab_diseno_pdf_a4_3").show();
			$(".tab_diseno_pdf_a4_4").hide();
			$(".tab_diseno_pdf_a4_5").hide();
			$(".tab_diseno_pdf_a4_6").hide();
			$(".tab_diseno_pdf_a4_7").hide();
			$(".tab_diseno_pdf_a4_8").hide();
		} 
		else if(modelo == '4'){
			$(".tab_diseno_pdf_a4_1").hide(); 
			$(".tab_diseno_pdf_a4_2").hide();
			$(".tab_diseno_pdf_a4_3").hide();
			$(".tab_diseno_pdf_a4_4").show();
			$(".tab_diseno_pdf_a4_5").hide();
			$(".tab_diseno_pdf_a4_6").hide();
			$(".tab_diseno_pdf_a4_7").hide();
			$(".tab_diseno_pdf_a4_8").hide();
		} 
		else if(modelo == '5'){
			$(".tab_diseno_pdf_a4_1").hide(); 
			$(".tab_diseno_pdf_a4_2").hide();
			$(".tab_diseno_pdf_a4_3").hide();
			$(".tab_diseno_pdf_a4_4").hide();
			$(".tab_diseno_pdf_a4_5").show();
			$(".tab_diseno_pdf_a4_6").hide();
			$(".tab_diseno_pdf_a4_7").hide();
			$(".tab_diseno_pdf_a4_8").hide();
		} 
		else if(modelo == '6'){
			$(".tab_diseno_pdf_a4_1").hide(); 
			$(".tab_diseno_pdf_a4_2").hide();
			$(".tab_diseno_pdf_a4_3").hide();
			$(".tab_diseno_pdf_a4_4").hide();
			$(".tab_diseno_pdf_a4_5").hide();
			$(".tab_diseno_pdf_a4_6").show();
			$(".tab_diseno_pdf_a4_7").hide();
			$(".tab_diseno_pdf_a4_8").hide();
		} 
		else if(modelo == '7'){
			$(".tab_diseno_pdf_a4_1").hide(); 
			$(".tab_diseno_pdf_a4_2").hide();
			$(".tab_diseno_pdf_a4_3").hide();
			$(".tab_diseno_pdf_a4_4").hide();
			$(".tab_diseno_pdf_a4_5").hide();
			$(".tab_diseno_pdf_a4_6").hide();
			$(".tab_diseno_pdf_a4_7").show();
			$(".tab_diseno_pdf_a4_8").hide();
			$(".tab_diseno_pdf_a4_8").hide();
		} 
		else if(modelo == '8'){
			$(".tab_diseno_pdf_a4_1").hide(); 
			$(".tab_diseno_pdf_a4_2").hide();
			$(".tab_diseno_pdf_a4_3").hide();
			$(".tab_diseno_pdf_a4_4").hide();
			$(".tab_diseno_pdf_a4_5").hide();
			$(".tab_diseno_pdf_a4_6").hide();
			$(".tab_diseno_pdf_a4_7").hide();
			$(".tab_diseno_pdf_a4_8").show();
		} else {
			$(".tab_diseno_pdf_a4_1").show();
			$(".tab_diseno_pdf_a4_2").hide();
			$(".tab_diseno_pdf_a4_3").hide();
			$(".tab_diseno_pdf_a4_4").hide();
			$(".tab_diseno_pdf_a4_5").hide();
			$(".tab_diseno_pdf_a4_6").hide();
			$(".tab_diseno_pdf_a4_7").hide();
			$(".tab_diseno_pdf_a4_8").hide();
		}
	});

	$(".modelo_plantilla_pdf_ticket").change(function() {
		var modelo = this.value;
		if(modelo == '1'){
			$(".tab_diseno_pdf_ticket_1").show();
			$(".tab_diseno_pdf_ticket_2").hide();
		}else{
			$(".tab_diseno_pdf_ticket_1").hide();
			$(".tab_diseno_pdf_ticket_2").show();
		} 
	});

	if($(".modelo_plantilla_pdf_ticket").val() == '1'){
		$(".tab_diseno_pdf_ticket_1").show();
		$(".tab_diseno_pdf_ticket_2").hide();
	}else{
		$(".tab_diseno_pdf_ticket_1").hide();
		$(".tab_diseno_pdf_ticket_2").show();
	} 

	$(".theme01_txt_pdf_a4_1").on('input', function() {
        $(".theme02_txt_pdf_a4_1").html($(this).html());
	});

	$(".theme02_txt_pdf_a4_1").on('input', function() {
        $(".theme01_txt_pdf_a4_1").html($(this).html());
	});

	$(".theme01_txt_pdf_a4_2").on('input', function() {
        $(".theme02_txt_pdf_a4_2").html($(this).html());
	});

	$(".theme02_txt_pdf_a4_2").on('input', function() {
        $(".theme01_txt_pdf_a4_2").html($(this).html());
	});

	$(".theme01_txt_pdf_a4_3").on('input', function() {
        $(".theme02_txt_pdf_a4_3").html($(this).html());
	});

	$(".theme02_txt_pdf_a4_3").on('input', function() {
        $(".theme01_txt_pdf_a4_3").html($(this).html());
	});
	
	$('input[type=radio][name=modelo_plantilla_pdf_a4]').trigger('change');

	$("#btn_guardar_plantilla").click(guardar_nueva_plantilla);

	$(".btn-preview-plantilla").click(function(){
		var type_preview = $(this).attr("data-id");
		if(type_preview == 'pdf_header_content'){
			$("#img_preview_plantilla").attr("src","/facturacionv8/img/template_preview_header.jpg");
		}else if(type_preview == 'pdf_debajo'){
			$("#img_preview_plantilla").attr("src","/facturacionv8/img/template_preview_centro.jpg");
		}else if(type_preview == 'pdf_final_content'){
			$("#img_preview_plantilla").attr("src","/facturacionv8/img/template_preview_final.jpg");
		}

	});
	
	$('.opcion_items_igv').change(function() {
		var valor_opcion = 'si';
		var idsucursal = $("#idsucursal").val();

        if(this.checked) {
            valor_opcion = 'si';
        } else {
			valor_opcion = 'no';
		}
		
		guardar_opcion_items_con_igv($(this).attr("id"), valor_opcion, idsucursal);
    });

	$('.opcion_switch_modifica_stock').change(function() {
		var valor_opcion = 'si';
		var idsucursal = $("#idsucursal").val();

        if(this.checked) {
            valor_opcion = 'si';
        } else {
			valor_opcion = 'no';
		}
		
		guardar_opcion_modifica_stock($(this).attr("id"), valor_opcion, idsucursal);
    });

	$("#factor_igv_sucursal").on('change', function() {
		var factor_igv = this.value;

		if(factor_igv == '10') {
			$("#explicacion_reduccion_igv").show('slide');
		} else {
			$("#explicacion_reduccion_igv").hide('slide');
		}
	});
	
	var switches = Array.prototype.slice.call(document.querySelectorAll('.opcion_switch_modifica_stock'));
    switches.forEach(function(html) {
        var switchery = new Switchery(html, {color: '#EF5350'});
    });

    $(".switch_opt_yape_plin").bootstrapSwitch();
});
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
function get_sucursal() {
	var light = $("#contenido_sucursal"); 
 
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
	
	var idsucursal = $("#idsucursal").val();
	if(idsucursal == '' || idsucursal <= 0) {
		$(light).unblock();
		return false;
	}
	
	$.ajax({
        url : "/facturacionv8/branchoffice/get_sucursal",
		method :  'POST',
		data: {idsucursal: idsucursal},
        dataType : "json"
    }).then(function(data){
        if(data.respuesta == 'ok') {
			$(light).unblock();
			$("#idsucursal").val(data.sucursal.idsucursal);
			$("#txt_codigo").val(data.sucursal.codigo);
			$("#nombre_sucursal").val(data.sucursal.nombre);
			$("#direccion").val(data.sucursal.direccion);
			$("#urbanizacion").val(data.sucursal.urbanizacion);
			$("#ubigeo").val(data.sucursal.id_ubigeo);
			$("#ubigeo").val(data.sucursal.id_ubigeo).trigger("change").trigger("select2:select");
			$("#telefono").val(data.sucursal.telefono);
			$("#email").val(data.sucursal.email);
			$("#sitioweb").val(data.sucursal.sitio_web);
			$("#info_adicional").val(data.sucursal.informacion_adicional);
			$("#leyenda_comprobantes").val(data.sucursal.leyenda_comprobantes);

			$("#factura_serie").val(data.sucursal.factura_serie);
			$("#factura_numero").val(data.sucursal.factura_numero);
			$("#factura_formato").val(data.sucursal.factura_formato).trigger("change").trigger("select2:select");

			$("#boleta_serie").val(data.sucursal.boleta_serie);
			$("#boleta_numero").val(data.sucursal.boleta_numero);
			$("#boleta_formato").val(data.sucursal.boleta_formato).trigger("change").trigger("select2:select");

			$("#notacredito_factura_serie").val(data.sucursal.notacredito_factura_serie);
			$("#notacredito_factura_numero").val(data.sucursal.notacredito_factura_numero);
			$("#notacredito_factura_formato").val(data.sucursal.notacredito_factura_formato).trigger("change").trigger("select2:select");

			$("#notadebito_factura_serie").val(data.sucursal.notadebito_factura_serie);
			$("#notadebito_factura_numero").val(data.sucursal.notadebito_factura_numero);
			$("#notadebito_factura_formato").val(data.sucursal.notadebito_factura_formato).trigger("change").trigger("select2:select");

			$("#notacredito_boleta_serie").val(data.sucursal.notacredito_boleta_serie);
			$("#notacredito_boleta_numero").val(data.sucursal.notacredito_boleta_numero);
			$("#notacredito_boleta_formato").val(data.sucursal.notacredito_boleta_formato).trigger("change").trigger("select2:select");

			$("#notadebito_boleta_serie").val(data.sucursal.notadebito_boleta_serie);
			$("#notadebito_boleta_numero").val(data.sucursal.notadebito_boleta_numero);
			$("#notadebito_boleta_formato").val(data.sucursal.notadebito_boleta_formato).trigger("change").trigger("select2:select");

			$("#guia_remision_serie").val(data.sucursal.guia_remision_serie);
			$("#guia_remision_numero").val(data.sucursal.guia_remision_numero);
			$("#guia_remision_formato").val(data.sucursal.guia_remision_formato).trigger("change").trigger("select2:select");

			$("#guia_transportista_serie").val(data.sucursal.guia_transportista_serie);
			$("#guia_transportista_numero").val(data.sucursal.guia_transportista_numero);
			$("#guia_transportista_formato").val(data.sucursal.guia_transportista_formato).trigger("change").trigger("select2:select");

			$("#orden_compra_serie").val(data.sucursal.orden_compra_serie);
			$("#orden_compra_numero").val(data.sucursal.orden_compra_numero);
			$("#orden_compra_formato").val(data.sucursal.orden_compra_formato).trigger("change").trigger("select2:select");

			$("#factor_igv_sucursal").val(parseInt(data.sucursal.factor_igv)).trigger("change").trigger("select2.select");

			$("#img_qr_yape").val(data.sucursal.img_qr_yape);
			if(data.sucursal.img_qr_yape !== '' &&  data.sucursal.img_qr_yape !== null){
				$("#btn_subir_qr_yape").hide();
				$("#btn_eliminar_qr_yape").css("display", "initial");
			}
			$("#nombre_titular_yape").val(data.sucursal.titular_yape);
			$("#num_celular_yape").val(data.sucursal.celular_yape);
			$("#img_qr_plin").val(data.sucursal.img_qr_plin);
			if (data.sucursal.img_qr_plin !== ''  && data.sucursal.img_qr_plin !== null){
				$("#btn_subir_qr_plin").hide();
				$("#btn_eliminar_qr_plin").css("display", "initial");
			}
			$("#nombre_titular_plin").val(data.sucursal.titular_plin);
			$("#num_celular_plin").val(data.sucursal.celular_plin);

			$(".textos_para_nueva_sucursal").hide();
			$(".textos_para_editar_sucursal").show();
			
			//$(".txt_propiedad_docelect").prop('disabled', true);
        } else {
            $(light).unblock(); 
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

function guardar_opcion_modifica_stock(nombre_opcion, valor_opcion, idsucursal) {
	if(idsucursal == '' || idsucursal <= 0) {
		return false;
	}

	$.ajax({
        url : "/facturacionv8/branchoffice/guardar_opcion_modifica_stock",
		method :  'POST',
		data: {nombre_opcion: nombre_opcion, valor_opcion: valor_opcion, idsucursal: idsucursal},
        dataType : "json"
    }).then(function(data){
        if(data.respuesta == 'ok') {
			
        } else {
            swal({   
                title:'Error',   
                text: data.mensaje,
                html: true,
                type: "error", 
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Ok",
            }, function() {
				
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
			
		});
    });
}

function guardar_opcion_items_con_igv(nombre_opcion, valor_opcion, idsucursal) {
	if(idsucursal == '' || idsucursal <= 0) {
		return false;
	}

	$.ajax({
        url : "/facturacionv8/branchoffice/guardar_opcion_items",
		method :  'POST',
		data: {nombre_opcion: nombre_opcion, valor_opcion: valor_opcion, idsucursal: idsucursal},
        dataType : "json"
    }).then(function(data){
        if(data.respuesta == 'ok') {
			
        } else {
            swal({   
                title:'Error',   
                text: data.mensaje,
                html: true,
                type: "error", 
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Ok",
            }, function() {
				
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
			
		});
    });
}

function save_branchoffice(){
	var light = $("#contenido_sucursal");

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
	
	var datastring = $("#frm_branchoffice").serializeArray();
	datastring.push({ name: "txt_pdf_a4_1", value: $("#txt_pdf_a4_1").val() });
	datastring.push({ name: "txt_pdf_a4_2", value: $("#txt_pdf_a4_2").val() });
	datastring.push({ name: "txt_pdf_a4_3", value: $("#txt_pdf_a4_3").val() });
	datastring.push({ name: "txt_pdf_ticket_1", value: $("#txt_pdf_a4_1").val() });
	datastring.push({ name: "txt_pdf_ticket_2", value: $("#txt_pdf_a4_2").val() });
	datastring.push({ name: "txt_pdf_ticket_3", value: $("#txt_pdf_a4_3").val() });;

	datastring.push({ name: "plantilla_pdf_a4", value: $(".modelo_plantilla_pdf_a4").val() });
	datastring.push({ name: "plantilla_pdf_ticket", value: $(".modelo_plantilla_pdf_ticket").val() });
	datastring.push({ name: "img_qr_yape", value: $("#img_qr_yape").val() });
	datastring.push({ name: "img_qr_plin", value: $("#img_qr_plin").val() });
	
	
	$.ajax({
        url : "/facturacionv8/branchoffice/insert",
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
				$("#idsucursal").val(data.idsucursal);
				get_sucursal();
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