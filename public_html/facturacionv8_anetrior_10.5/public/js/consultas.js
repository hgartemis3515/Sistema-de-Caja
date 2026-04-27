$(function(){
	$('.result').hide();
	$('#btn_consulta_documento').click(enviarparametros);
});
function enviarparametros(){
    var light = $("#frm_consulta");

    $(light).block({
		message: '<div class="loading"> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
					</div> <p> <br />Espere un momento, por favor...</p>',
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
    
    var datastring = $("#frm_consulta").serializeArray();

	$.ajax({
        url : '/facturacionv8/consultas/consultar',
        data: datastring,
		method :  'POST',
        dataType : "json"
    }).then(function(data){
        if(data.respuesta == 'ok') {
            $("#msg_respuesta").html(data.mensaje);
            $('.result').fadeIn( "slow" );
            $(light).unblock();
        } else {
            swal({   
                title:'Error',   
                text: data.mensaje,
                html: true,
                type: "error", 
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Ok",
            });
            $(light).unblock();
        }
    });
}