$(function() {

    $('.select2').select2();

    get_lista_sucursales($("#idsucursal"));
    get_lista_contribuyentes($("#get_contribuyente"));
});

function get_lista_contribuyentes(select){
    
    $.ajax({
        url : '/facturacionv8/administracionproductos/get_list_contribuyente',
        method :  'POST',
        dataType : "json"
    }).then(function(data){
            if(data.respuesta == 'ok') {
                $.each(data.lista, function(key, item) {
                    select.append('<option value="' + item.idcontribuyente + '">' + item.razon_social + '</option>');
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
        })
    });
}
