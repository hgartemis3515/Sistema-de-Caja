$(function() {
	inicializar_subida_imagenes();
});

function inicializar_subida_imagenes() {
    $('#btn_agregarimagen_cuadrado').click(function() {
        ratio = 1 / 1;
        ancho_corte_width = 300;
        alto_corte_height = 300;
        imagetipo = 'img_cuadrada';
        $(".tamanio_texto_img").html('300x300px');
        $('.fileinput-remove').trigger('click');
        $('.previewrecorteimg').html('');
        $('#vm_cargar_imagen').modal('show');
    });

    $('#btn_agregarimagen_horizontal').click(function() {
        ratio = 350 / 167;
        ancho_corte_width = 350;
        alto_corte_height = 167;
        imagetipo = 'img_horizontal';
        $(".tamanio_texto_img").html('300x167px');
        $('.fileinput-remove').trigger('click');
        $('.previewrecorteimg').html('');
        $('#vm_cargar_imagen').modal('show');
    });

    $('#btn_agregarimagen_vertical').click(function() {
        ratio = 167 / 350;
        ancho_corte_width = 167;
        alto_corte_height = 350;
        imagetipo = 'img_vertical';
        $(".tamanio_texto_img").html('167x350px');
        $('.fileinput-remove').trigger('click');
        $('.previewrecorteimg').html('');
        $('#vm_cargar_imagen').modal('show');
    });

    $("#vm_cargar_imagen").on("hidden.bs.modal", function () {
        $('.modal:visible').css("overflow-y","auto");
	});

    inicializacion_plugin_para_recortar_imagenes_producto($('.previewrecorteimg'), '.kv-file-content .file-preview-image', $('.file-input'), $("#btn_guardarimagen"));
}

//**********  IMAGENES **************** */
function agregar_imagen_contenedor(url, div_contenedor, input_listaimagenes, clase_para_eliminar, clase_imagen) {
	var filename = url.substring(url.lastIndexOf('/')+1);
	var filename = filename.replace(".", "");

	var htmlimg = '\
	<div id="'+filename+'" class="' + clase_imagen + '">\
		<div class="thumbnail">\
			<div class="thumb">\
				<img src="'+url+'" alt="">\
				<div class="caption-overflow">\
					<span>\
						<a href="javascript:void(0)" data-img="'+url+'" class="btn bg-indigo btn-sm '+ clase_para_eliminar +'"><i class="icon-x" aria-hidden="true"></i></a>\
					</span>\
				</div>\
			</div>\
		</div>\
	</div>\
	';
	
	var htmlcontent = div_contenedor.html();
	div_contenedor.html(htmlimg + htmlcontent);

	var listaimagenes = input_listaimagenes.val();

	if(listaimagenes == '') {
		input_listaimagenes.val(url);
	} else {
		var arrayimgs = listaimagenes.split(",");
		arrayimgs.push(url);
		input_listaimagenes.val(arrayimgs.join());
	}

	$('.' + clase_para_eliminar).click({input_listaimagenes: input_listaimagenes}, eliminar_imagen_contenedor);
}

function eliminar_imagen_contenedor(event) {
	var img = $(this).data('img');
	var input_listaimagenes = event.data.input_listaimagenes;
	swal({
        title: 'Necesitamos de tu Confirmación',   
        text: '¿Realmente Deseas Eliminar la Imágen?',
        html: true,
        type: "warning",   
        showCancelButton: true,   
        confirmButtonColor: "#3f51b5",    //confirmButtonColor: "#3f51b5",   
        cancelButtonColor: "#DD6B55",
        confirmButtonText: "Si, Adelante!",   
        closeOnConfirm: true
    },
    function(confirm) {
        if(confirm) {
            var listaimagenes = input_listaimagenes.val();
            if(listaimagenes != '') {
                var arrayimgs = listaimagenes.split(",");
                if(arrayimgs.length > 1)
                {
                    arrayimgs.remove(img);
                    input_listaimagenes.val(arrayimgs.join());
                }
                else
                {
                    input_listaimagenes.val('');
                }
                var filename = img.substring(img.lastIndexOf('/')+1);
                var filename = filename.replace(".", "");
                $("#"+filename).remove();
            }
        }
    });
}

Array.prototype.remove = function() {
    var what, a = arguments, L = a.length, ax;
    while (L && this.length) {
        what = a[--L];
        while ((ax = this.indexOf(what)) !== -1) {
            this.splice(ax, 1);
        }
    }
    return this;
};

/***************** /IMAGENES ****************************** */