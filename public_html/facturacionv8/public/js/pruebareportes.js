$(function(){
    // $( "body" ).addClass( "sidebar-xs" );
    $('.select2').select2();
    get_lista_sucursales($("#select_sucursal"), 'si');
    get_lista_vendedores($("#select_vendedores"));

    $("#rangofechas").daterangepicker(
        {
            singleDatePicker: false,
            startDate: moment().subtract(30, 'days'),
            endDate: moment(),
            minDate: moment().subtract(365, 'days'),
            maxDate: moment(),
            locale: {
                format: 'DD/MM/YYYY',
                daysOfWeek: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ],
                monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
                ],
                monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul', 'Ago','Sep','Oct','Nov','Dic'],
                firstDay: 1
            }
        }, function(start, end) {
                $("#fechainicio").val(start.format('YYYY-MM-DD'));
                $("#fechafinal").val(end.format('YYYY-MM-DD'));
        }
    );
    
    $("#fechainicio").val(moment().subtract(30, 'days').format('YYYY-MM-DD'));
    $("#fechafinal").val(moment().format('YYYY-MM-DD'));
    $("#btn_generar_reporte").click(generar_reporte);
    
}); 

function generar_reporte(){
	var light = $("#contenido_datos_clientes");

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
	
	var datastring = $("#frm_generar_repote").serializeArray();
	
	$.ajax({
        url : "/facturacionv8/pruebareportes/generar_reporte",
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