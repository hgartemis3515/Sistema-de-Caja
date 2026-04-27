$(function() {
    $("#menu_opt_factura").css({
        'color': '#fff',
        'background': 'linear-gradient(45deg, rgba(63,81,181,1) 0%, rgba(120,128,240,1) 100%)',
        'border-radius': '7px',
        'transition': 'all 0.5s ease', // Agrega una transición suave
        'transform': 'scale(1.1)', // Aumenta la escala del elemento en un 10%
    });
    
    $('.menu_superior_crear_doc_compra').on('click', function(e) {
        e.preventDefault(); // Evita que el enlace siga el href

        // Quita el estilo a todos los elementos de menú
        $('.menu_superior_crear_doc_compra').css({
            'color': '#555',
            'background': '',
            'border-radius': '',
            'transition': 'all 0.5s ease', // Agrega una transición suave
            'transform': 'scale(1)', // Restablece la escala del elemento
        });

        // Aplica el nuevo estilo solo al enlace clickeado
        $(this).css({
            'color': '#fff',
            'background': 'linear-gradient(45deg, rgba(63,81,181,1) 0%, rgba(120,128,240,1) 100%)',
            'border-radius': '7px',
            'transition': 'all 0.5s ease', // Agrega una transición suave
            'transform': 'scale(1.1)', // Aumenta la escala del elemento en un 10%
        });

        var tipo_doc = $(this).data('tipodocumento');

        if(tipo_doc == '01') {
            $('#tipo_factura').trigger('click').uniform('refresh');
        } else if(tipo_doc == '03') {
            $('#tipo_boleta').trigger('click').uniform('refresh');
        } else if(tipo_doc == '07') {
            $('#tipo_notacredito').trigger('click').uniform('refresh');
        } else if(tipo_doc == '08') {
            $('#tipo_notadebito').trigger('click').uniform('refresh');
        } else if(tipo_doc == '99') {
            $('#tipo_ordencompra').trigger('click').uniform('refresh');
        } else if(tipo_doc == '00') {
            $('#tipo_otro').trigger('click').uniform('refresh');
        }
    });
});