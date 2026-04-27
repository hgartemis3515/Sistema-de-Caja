$(function() {
    
});

function ver_stock_varias_sucursales(codigo_producto, idsucursal) {
    $("#vm_stock_varias_sucursales").modal("show");

    var light = $('#content_vm_stock_varias_sucursales');
    $(light).block({
        message: '<div class="loader"></div> <p><br />Espere un momento...</p>',
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
        url : '/facturacionv8/producto/get_stock_varias_sucursales',
        data : {codigo_producto: codigo_producto},
        method :  'POST',
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			$('#vm_stock_varias_sucursales_tbl_lista').DataTable({
                data: data.lista,
                "bDestroy": true,
                "columns": [
					{ "data": "idsucursal", "visible": false},
					{ "data": "nombre_sucursal", "visible": true},
					{ "data": "codigo", "visible": true},
                    { "data": "nombre_producto", "visible": true},
                    { "data": "stock", "visible": true}
				],
                "createdRow": function (row, data, dataIndex) {
                    if (data.idsucursal == idsucursal) {
                        $(row).css('color', 'green');
                    }
                },
				initComplete: function(){

				}
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
		});
    });
}