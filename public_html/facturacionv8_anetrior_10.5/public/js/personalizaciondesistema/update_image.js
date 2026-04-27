
function inicializacion_plugin_para_recortar_imagenes($preview, contenedorimagen, $botoncargarimagen, $botonguardarenservidor) {
    $botonguardarenservidor.prop('disabled', true);
    // Modal template
    var modalTemplate = '<div class="modal-dialog modal-lg modalTemplate" role="document">\n' +
        '  <div class="modal-content">\n' +
        '    <div class="modal-header">\n' +
        '      <div class="kv-zoom-actions btn-group">{toggleheader}{fullscreen}{borderless}{close}</div>\n' +
        '      <h6 class="modal-title">{heading} <small><span class="kv-zoom-title"></span></small></h6>\n' +
        '    </div>\n' +
        '    <div class="modal-body">\n' +
        '      <div class="floating-buttons btn-group"></div>\n' +
        '      <div class="kv-zoom-body file-zoom-content"></div>\n' + '{prev} {next}\n' +
        '    </div>\n' +
        '  </div>\n' +
        '</div>\n';
    // Buttons inside zoom modal
    var previewZoomButtonClasses = {
        toggleheader: 'btn btn-default btn-icon btn-xs btn-header-toggle',
        fullscreen: 'btn btn-default btn-icon btn-xs',
        borderless: 'btn btn-default btn-icon btn-xs',
        close: 'btn btn-default btn-icon btn-xs'
    };
    // Icons inside zoom modal classes
    var previewZoomButtonIcons = {
        prev: '<i class="icon-arrow-left32"></i>',
        next: '<i class="icon-arrow-right32"></i>',
        toggleheader: '<i class="icon-menu-open"></i>',
        fullscreen: '<i class="icon-screen-full"></i>',
        borderless: '<i class="icon-alignment-unalign"></i>',
        close: '<i class="icon-cross3"></i>'
    };
    // File actions
    var fileActionSettings = {
        zoomClass: 'btn btn-link btn-xs btn-icon',
        showUpload: false,
        showZoom: false,
        showRemove: false,
        showDrag: false,
        zoomIcon: '<i class="icon-zoomin3"></i>',
        dragClass: 'btn btn-link btn-xs btn-icon',
        dragIcon: '<i class="icon-three-bars"></i>',
        removeClass: 'btn btn-link btn-icon btn-xs',
        removeIcon: '<i class="icon-trash"></i>',
        indicatorNew: '<i class="icon-file-plus text-slate"></i>',
        indicatorSuccess: '<i class="icon-checkmark3 file-icon-large text-success"></i>',
        indicatorError: '<i class="icon-cross2 text-danger"></i>',
        indicatorLoading: '<i class="icon-spinner2 spinner text-muted"></i>'
    };
    $botoncargarimagen.fileinput({
        language: 'es',
        browseLabel: 'Seleccionar Imágen...',
        previewSettings: {
            image: {
                width: "100%",
                height: "auto"
            },
        },
        allowedFileTypes: ['image'],
        browseIcon: '<i class="icon-file-plus"></i>',
        uploadIcon: '<i class="icon-file-upload2"></i>',
        removeIcon: '<i class="icon-cross3"></i>',
        layoutTemplates: {
            icon: '<i class="icon-file-check"></i>',
            modal: modalTemplate
        },
        initialCaption: "Haz Click en Cargar Imágen",
        previewZoomButtonClasses: previewZoomButtonClasses,
        previewZoomButtonIcons: previewZoomButtonIcons,
        fileActionSettings: fileActionSettings,
        showCaption: false,
        showRemove: false,
        showUpload: false
    });
    $botonguardarenservidor.click(plugin_guardar_imagen_recortada);
    $botoncargarimagen.on('fileimageloaded', function(event) {
        $imagenarecortar = $(contenedorimagen);
        $objeto = plugin_recortar_imagen($imagenarecortar, $preview);
        $botonguardarenservidor.prop('disabled', false);
    });
}

function plugin_recortar_imagen($objeto, $preview) {
    $objeto.cropper({
        viewMode: 1,
        dragMode: 'move',
        restore: false,
        cropBoxMovable: false,
        cropBoxResizable: false,
        aspectRatio: ratio,
        zoomTo: 1,
        build: function(e) {
            var $clone = $(this).clone();
            $clone.css({
                display: 'block',
                width: '100%',
                minWidth: 0,
                minHeight: 0,
                maxWidth: 'none',
                maxHeight: 'none'
            });
            $preview.css({
                width: '100%',
                'max-width': '200px',
                overflow: 'hidden'
            }).html($clone);
        },
        crop: function(e) {
            var imageData = $(this).cropper('getImageData');
            var previewAspectRatio = e.width / e.height;
            var previewWidth = $preview.width();
            var previewHeight = previewWidth / previewAspectRatio;
            var imageScaledRatio = e.width / previewWidth;
            $preview.height(previewHeight).find('img').css({
                width: imageData.naturalWidth / imageScaledRatio,
                height: imageData.naturalHeight / imageScaledRatio,
                marginLeft: -e.x / imageScaledRatio,
                marginTop: -e.y / imageScaledRatio
            });
        }
    });
}
function plugin_guardar_imagen_recortada() {
    var croppedCanvas = $imagenarecortar.cropper('getCroppedCanvas', {
        width: ancho_corte_width,
        height: alto_corte_height
    });
    var imagebase64 = croppedCanvas.toDataURL();
    var light = $('#vm_cargar_imagen .modal-content').parent();
    $(light).block({
        message: '<i class="icon-spinner spinner"></i> <strong>Ten Paciencia Por Favor... En unos segundos tu imágen estará lista :)</strong>',
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
    $('.btn_guardarimagen_loading').show();
    $('.btn_guardarimagen_icono').hide();
    $('#btn_guardarimagen').prop("disabled", true);
    $.ajax({
        type: "POST",
        data: {
            dataimage: imagebase64,
            imagetipo: imagetipo
        },
        url: "/facturacionv8/personalizaciondesistema/guardar_imagen_servidor",
        success: function(data) {
            $(light).unblock();
            $('#btn_guardarimagen').prop("disabled", false);
            if (data.respuesta == 'ok') {
                $('.btn_guardarimagen_loading').hide();
                $('.btn_guardarimagen_icono').show();
                swal({
                        title: "Excelente!",
                        text: "La imágen ha sido procesada correctamente!",
                        type: "success",
                        confirmButtonText: "Ok"
                    },
                    function() {
						if(imagetipo == 'background_login') {
                            $("#url_img_background_login").val(data.urlimagen);
                            $("#img_background_login").attr('src', '');
                            $("#img_background_login").attr('src', data.urlimagen);
                        }

                        if(imagetipo == 'background_registro') {
                            $("#url_img_background_registro").val(data.urlimagen);
                            $("#img_background_registro").attr('src', '');
                            $("#img_background_registro").attr('src', data.urlimagen);
                        }

                        if(imagetipo == 'logo461x95') {
                            $("#txt_logo_461").val(data.urlimagen);
                            $("#img_logo_461").attr('src', '');
                            $("#img_logo_461").attr('src', data.urlimagen);
                        }

                        if(imagetipo == 'logo291x60') {
                            $("#txt_logo_291").val(data.urlimagen);
                            $("#img_logo_291").attr('src', '');
                            $("#img_logo_291").attr('src', data.urlimagen);
                        }

                        if(imagetipo == 'logo56x56') {
                            $("#txt_logo_56").val(data.urlimagen);
                            $("#img_logo_56").attr('src', '');
                            $("#img_logo_56").attr('src', data.urlimagen);
                        }
                        

						$('#vm_cargar_imagen').modal('hide');
                    });
            } else {
                swal({
                    title: "Problemas!",
                    text: data.mensaje + ', Por Favor Inténtalo Nuevamente!...',
                    type: "error",
                    confirmButtonText: "Ok"
                },
                function() {
					window.location.reload();
                    /*$('#btn_guardarimagen').prop("disabled", false);
                    $('.btn_guardarimagen_loading').hide();
                    $('.btn_guardarimagen_icono').show();*/
                });
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            $(light).unblock();
            swal({
                title: "Problemas!",
                text: 'Tenemos Problemas con el Servidor, por favor inténtalo nuevamente...',
                type: "error",
                confirmButtonText: "Ok"
            },
            function() {
                $('#btn_guardarimagen').prop("disabled", false);
                $('.btn_guardarimagen_loading').hide();
                $('.btn_guardarimagen_icono').show();
            });
        },
        dataType: "json"
    });
}