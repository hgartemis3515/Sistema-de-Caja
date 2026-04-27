$(function(){
    inicializar_subida_imagen_qr();

    $("#btn_eliminar_qr_yape").click(function() {
        $('#show_img_qr_yape').attr('src', '');
        $('#img_qr_yape').val('');
        $("#btn_subir_qr_yape").show();
        $("#btn_eliminar_qr_yape").hide();
    });

    $("#btn_eliminar_qr_plin").click(function() {
        $('#show_img_qr_plin').attr('src', '');
        $('#img_qr_plin').val('');
        $("#btn_subir_qr_plin").show();
        $("#btn_eliminar_qr_plin").hide();
    });
});

function inicializar_subida_imagen_qr() {
	$('#btn_subir_qr_yape').click(function() {
        ratio = 1 / 1;
        ancho_corte_width = 300;
        alto_corte_height = 300;
        imagetipo = 'img_qr_yape';
        $('.fileinput-remove').trigger('click');
        $('.previewrecorteimg').html('');
        $('#vm_cargar_imagen').modal('show');
	});

    $('#btn_subir_qr_plin').click(function() {
        ratio = 1 / 1;
        ancho_corte_width = 300;
        alto_corte_height = 300;
        imagetipo = 'img_qr_plin';
        $('.fileinput-remove').trigger('click');
        $('.previewrecorteimg').html('');
        $('#vm_cargar_imagen').modal('show');
	});
	 
	inicializacion_plugin_para_recortar_imagenes($('.previewrecorteimg'), '.kv-file-content .file-preview-image', $('.file-input'), $("#btn_guardarimagen"));

	$("#vm_cargar_imagen").on("hidden.bs.modal", function () {
        $('.modal:visible').css("overflow-y","auto");
	});
	
}