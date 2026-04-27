$(function() {
    $(".btn_condicionpago").click(save);
    $('#tipo_pago').select2({
        minimumResultsForSearch: -1
    });  
    get_lista_condicionpago(); 
});


function save(){
    var datastring = $("#frm_condicionpago").serializeArray();
    $.ajax({
        url : '/facturacionv8/gestioncondiciondepago/save',
        method :  'POST',
        data: datastring,
        dataType : "json"
    }).then(function(data){
        if(data.respuesta == 'ok') {
            swal({   
                title: data.titulo,   
                text: data.mensaje,
                html: true,
                type: "success", 
                confirmButtonColor: "#00BCD4",   
                confirmButtonText: "Ok",
        }, function() {
                get_lista_condicionpago();
                $("#frm_condicionpago")[0].reset();
                $("#tipo_pago").select2("val", 0);
                $("#idcondicionpago").val('');
            });
        } else {
            swal({
                title: 'Error',
                text: data.mensaje,
                html: true,
                type: "error",
                confirmButtonText: "Ok",
                confirmButtonColor: "#2196F3"
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
        });
    });
}

function get_lista_condicionpago(){
    var light = $("#content_lista_condicionpago");

    $(light).block({
    message: '<div class="loading"> \
                    <div class="loading-bar"></div> \
                    <div class="loading-bar"></div> \
                    <div class="loading-bar"></div> \
                    <div class="loading-bar"></div> \
                </div> <p> <br />Un momento, estamos recuperando la data ...</p>',

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
        url : '/facturacionv8/gestioncondiciondepago/get_lista_condicionpago',
        method :  'POST',
        dataType : "json"
    }).then(function(data){
            if(data.respuesta == 'ok') {
                $('#tbl_lista_condicionpago').DataTable({
                    data: data.lista,
                    "bDestroy": true
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
        })
    });
}

function edit_condicionpago(idcondicionpago){
    var light = $("#content_lista_condicionpago");

    $(light).block({
    message: '<div class="loading"> \
                    <div class="loading-bar"></div> \
                    <div class="loading-bar"></div> \
                    <div class="loading-bar"></div> \
                    <div class="loading-bar"></div> \
                </div> <p> <br />Un momento, estamos recuperando la data ...</p>',

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
        url : '/facturacionv8/gestioncondiciondepago/get_data_condicionpago',
        method :  'POST',
        data: {idcondicionpago: idcondicionpago},
        dataType : "json"
    }).then(function(data){
        if(data.respuesta == 'ok') {
            $("#idcondicionpago").val(data.pago.id_condicionpago);
            $("#tipo_pago").val(data.pago.tipo).trigger("change").trigger("select2:select");
            $("#condicion_pago").val(data.pago.condicionpago);
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

function eliminar_condicionpago(idcondicionpago){
    var light = $("#content_lista_condicionpago");

    $(light).block({
    message: '<div class="loading"> \
                    <div class="loading-bar"></div> \
                    <div class="loading-bar"></div> \
                    <div class="loading-bar"></div> \
                    <div class="loading-bar"></div> \
                </div> <p> <br />Un momento ...</p>',

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
        url : '/facturacionv8/gestioncondiciondepago/eliminar_condicionpago',
        method :  'POST',
        data: {idcondicionpago: idcondicionpago},
        dataType : "json"
    }).then(function(data){
        if(data.respuesta == 'ok') {
            swal({   
                title: data.titulo,   
                text: data.mensaje,
                html: true,
                type: "success",   
                confirmButtonColor: "#00BCD4",   
                confirmButtonText: "OK"
        }, function() {
                $(light).unblock();
                get_lista_condicionpago();
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
}