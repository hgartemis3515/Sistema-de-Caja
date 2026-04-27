$(function () {

    //INICIO FUNCIONALIDAD MENU SUPERIOR
    $(':radio[name="opt_menu_p"]').change(function() {
        var opcion = $(this).filter(':checked').val();
        if(opcion == 'login') {
            var estado_tab = $("#modulo_personalizar_login").is(':visible')?true:false;
            if(!estado_tab) {
                $("#modulo_personalizar_login").show('slide');
            }

            $("#modulo_personalizar_registro").hide();
            $("#modulo_personalizar_dominio").hide();
            $("#modulo_personalizar_colores").hide();
        }

        if(opcion == 'registro') {
            var estado_tab = $("#modulo_personalizar_registro").is(':visible')?true:false;
            if(!estado_tab) {
                $("#modulo_personalizar_registro").show('slide');
            }

            $("#modulo_personalizar_login").hide();
            $("#modulo_personalizar_dominio").hide();
            $("#modulo_personalizar_colores").hide();
        }

        if(opcion == 'dominio') {
            var estado_tab = $("#modulo_personalizar_dominio").is(':visible')?true:false;
            if(!estado_tab) {
                $("#modulo_personalizar_dominio").show('slide');
            }

            $("#modulo_personalizar_registro").hide();
            $("#modulo_personalizar_login").hide();
            $("#modulo_personalizar_colores").hide();
        }

        if(opcion == 'colores') {
            var estado_tab = $("#modulo_personalizar_colores").is(':visible')?true:false;
            if(!estado_tab) {
                $("#modulo_personalizar_colores").show('slide');
            }

            $("#modulo_personalizar_registro").hide();
            $("#modulo_personalizar_dominio").hide();
            $("#modulo_personalizar_login").hide();
        }
    });
    //FIN: FUNCIONALIDAD MENU SUPERIOR

    $('#btn_upload_image_login').click(function() {
        var width = parseFloat($(this).data('width'));
        var height = parseFloat($(this).data('height'));

        ratio = width / height;
        ancho_corte_width = width;
        alto_corte_height = height;
        imagetipo = 'background_login';
        $(".tamanio_texto_img").html(width + 'x' + height + 'px');
        $('.fileinput-remove').trigger('click');
        $('.previewrecorteimg').html('');
        $('#vm_cargar_imagen').modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    $('#btn_upload_image_registro').click(function() {
        var width = parseFloat($(this).data('width'));
        var height = parseFloat($(this).data('height'));

        ratio = width / height;
        ancho_corte_width = width;
        alto_corte_height = height;
        imagetipo = 'background_registro';
        $(".tamanio_texto_img").html(width + 'x' + height + 'px');
        $('.fileinput-remove').trigger('click');
        $('.previewrecorteimg').html('');
        $('#vm_cargar_imagen').modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    $('.btn_logo461x95').click(function() {
        var width = parseFloat($(this).data('width'));
        var height = parseFloat($(this).data('height'));

        ratio = width / height;
        ancho_corte_width = width;
        alto_corte_height = height;
        imagetipo = 'logo461x95';
        $(".tamanio_texto_img").html(width + 'x' + height + 'px');
        $('.fileinput-remove').trigger('click');
        $('.previewrecorteimg').html('');
        $('#vm_cargar_imagen').modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    $('.btn_logo291x60').click(function() {
        var width = parseFloat($(this).data('width'));
        var height = parseFloat($(this).data('height'));

        ratio = width / height;
        ancho_corte_width = width;
        alto_corte_height = height;
        imagetipo = 'logo291x60';
        $(".tamanio_texto_img").html(width + 'x' + height + 'px');
        $('.fileinput-remove').trigger('click');
        $('.previewrecorteimg').html('');
        $('#vm_cargar_imagen').modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    $('.btn_logo56x56').click(function() {
        var width = parseFloat($(this).data('width'));
        var height = parseFloat($(this).data('height'));

        ratio = width / height;
        ancho_corte_width = width;
        alto_corte_height = height;
        imagetipo = 'logo56x56';
        $(".tamanio_texto_img").html(width + 'x' + height + 'px');
        $('.fileinput-remove').trigger('click');
        $('.previewrecorteimg').html('');
        $('#vm_cargar_imagen').modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    $("#vm_cargar_imagen").on("hidden.bs.modal", function () {
        $('.modal:visible').css("overflow-y","auto");
	});

    inicializacion_plugin_para_recortar_imagenes($('.previewrecorteimg'), '.kv-file-content .file-preview-image', $('.file-input'), $("#btn_guardarimagen"));
    inicializar_controles();

    $(".btn_vista_previa_colores").click(function() {
        let opcion_seleccionada = $('input[name=radio_color]:checked').val();
        
        if(opcion_seleccionada == 'color_solido') {
            $("#navbar-indigo").addClass('new_color');
            $(".bg-indigo").removeClass('btn');
            $(".navigation li a i").addClass('new_style_icon');
            $("legend i").addClass('new_style_icon');
            let value_color = $("#color_solido").val();
            $(".new_color").css('background', value_color);
            $(".bg-indigo").css('background', value_color);
            $("i").css("cssText", $("i").attr("style") + ";color: " + value_color);
        }

        if(opcion_seleccionada == 'color_degradado') {
            $("#navbar-indigo").addClass('new_degradado');
            $(".bg-indigo").removeClass('btn');
            $(".navigation li a i").addClass('new_style_icon');
            $("legend i").addClass('new_style_icon');
            let value_color_1 = $("#color_degradado_1").val();
            let value_color_2 = $("#color_degradado_2").val();
            $('.new_degradado').css({
                background: "linear-gradient(45deg, " + value_color_1 + " 0%, " + value_color_2 + " 100%)"
            });
            $('.bg-indigo').css({
                background: "linear-gradient(45deg, " + value_color_1 + " 0%, " + value_color_2 + " 100%)"
            });

            $("i").css("cssText", $("i").attr("style") + ";color: " + value_color_1);
        }
    });
    
    $(".tipo_color").on("change", function () {
        let tipo_color = $(this).val();
        if (tipo_color == 'color_solido') {
            $(".content-solid").show('slide');
            $(".content-degradado").hide();
        } else {
            $(".content-solid").hide();
            $(".content-degradado").show('slide');
            
            $("#navbar-indigo").removeAttr('style');
            $("#navbar-indigo").removeClass('new_color');
        }
    });

    $(".tipo_color").trigger('change');

    $(':radio[name="opt_id_plantilla_login"]').change(function() {
        var id_plantilla_login = $(this).filter(':checked').val();
        $.ajax({
            url: '/facturacionv8/personalizaciondesistema/save_template_login',
            data: {id_plantilla_login: id_plantilla_login},
            method: 'POST',
            dataType: 'json'
        }).then(function (data) {
            if (data.respuesta == 'ok') {
                new PNotify({
                    title: data.titulo,
                    text: data.mensaje,
                    addclass: 'bg-success'
                });
            } else {
                new PNotify({
                    title: data.titulo,
                    text: data.mensaje,
                    addclass: 'bg-danger'
                });
            }
        }, function (reason) {
            swal({
                title: 'Error',
                text: 'Es posible que se haya cerrado la sessión, <a style="color: #1c81d1;" href="/facturacionv8/login" target="_blank">haz click aquí para ingresar de nuevo</a>, luego de iniciar sessión, puedes regresar a esta pantalla y recargar la página y continuar...',
                html: true,
                type: "error",
                confirmButtonText: "Ok",
                confirmButtonColor: "#2196F3"
            });
        });
    });
    
    $(':radio[name="opt_id_plantilla_registro"]').change(function() {
        var id_plantilla_registro = $(this).filter(':checked').val();
        $.ajax({
            url: '/facturacionv8/personalizaciondesistema/save_template_registro',
            data: {id_plantilla_registro: id_plantilla_registro},
            method: 'POST',
            dataType: 'json'
        }).then(function (data) {
            if (data.respuesta == 'ok') {
                new PNotify({
                    title: data.titulo,
                    text: data.mensaje,
                    addclass: 'bg-success'
                });
            } else {
                new PNotify({
                    title: data.titulo,
                    text: data.mensaje,
                    addclass: 'bg-danger'
                });
            }
        }, function (reason) {
            swal({
                title: 'Error',
                text: 'Es posible que se haya cerrado la sessión, <a style="color: #1c81d1;" href="/facturacionv8/login" target="_blank">haz click aquí para ingresar de nuevo</a>, luego de iniciar sessión, puedes regresar a esta pantalla y recargar la página y continuar...',
                html: true,
                type: "error",
                confirmButtonText: "Ok",
                confirmButtonColor: "#2196F3"
            });
        });
    });

    $("#btn_guardar_img_login").on('click', function() {
        let img_background_login = $("#url_img_background_login").val();
        $.ajax({
            url: '/facturacionv8/personalizaciondesistema/save_background_login',
            data: {img_background_login: img_background_login},
            method: 'POST',
            dataType: 'json'
        }).then(function (data) {
            if (data.respuesta == 'ok') {
                swal({
                    title: data.titulo,
                    text: data.mensaje,
                    html: true,
                    type: "success",
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ok",
                }, function () {
                    
                });
            } else {
                swal({
                    title: data.titulo,
                    text: data.mensaje,
                    html: true,
                    type: "error",
                    confirmButtonText: "Ok",
                    confirmButtonColor: "#2196F3"
                });
            }
        }, function (reason) {
            swal({
                title: 'Error',
                text: 'Es posible que se haya cerrado la sessión, <a style="color: #1c81d1;" href="/facturacionv8/login" target="_blank">haz click aquí para ingresar de nuevo</a>, luego de iniciar sessión, puedes regresar a esta pantalla y recargar la página y continuar...',
                html: true,
                type: "error",
                confirmButtonText: "Ok",
                confirmButtonColor: "#2196F3"
            });
        });
    });

    $("#btn_guardar_img_registro").on('click', function() {
        let img_background_register = $("#url_img_background_registro").val();
        $.ajax({
            url: '/facturacionv8/personalizaciondesistema/save_background_registro',
            data: {img_background_register: img_background_register},
            method: 'POST',
            dataType: 'json'
        }).then(function (data) {
            if (data.respuesta == 'ok') {
                swal({
                    title: data.titulo,
                    text: data.mensaje,
                    html: true,
                    type: "success",
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ok",
                }, function () {
                    
                });
            } else {
                swal({
                    title: data.titulo,
                    text: data.mensaje,
                    html: true,
                    type: "error",
                    confirmButtonText: "Ok",
                    confirmButtonColor: "#2196F3"
                });
            }
        }, function (reason) {
            swal({
                title: 'Error',
                text: 'Es posible que se haya cerrado la sessión, <a style="color: #1c81d1;" href="/facturacionv8/login" target="_blank">haz click aquí para ingresar de nuevo</a>, luego de iniciar sessión, puedes regresar a esta pantalla y recargar la página y continuar...',
                html: true,
                type: "error",
                confirmButtonText: "Ok",
                confirmButtonColor: "#2196F3"
            });
        });
    });

    $("#btn_guardar_dominio_logos").on('click', function() {
        let dominio_personalizado = $("#dominio_personalizado").val();
        let img_logo_461 = $("#txt_logo_461").val();
        let img_logo_291 = $("#txt_logo_291").val();
        let img_logo_56 = $("#txt_logo_56").val();

        $.ajax({
            url: '/facturacionv8/personalizaciondesistema/guardar_dominio_logos',
            data: {dominio_personalizado: dominio_personalizado, img_logo_461: img_logo_461, img_logo_291:img_logo_291, img_logo_56:img_logo_56},
            method: 'POST',
            dataType: 'json'
        }).then(function (data) {
            if (data.respuesta == 'ok') {
                swal({
                    title: data.titulo,
                    text: data.mensaje,
                    html: true,
                    type: "success",
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ok",
                }, function () {
                    
                });
            } else {
                swal({
                    title: data.titulo,
                    text: data.mensaje,
                    html: true,
                    type: "error",
                    confirmButtonText: "Ok",
                    confirmButtonColor: "#2196F3"
                });
            }
        }, function (reason) {
            swal({
                title: 'Error',
                text: 'Es posible que se haya cerrado la sessión, <a style="color: #1c81d1;" href="/facturacionv8/login" target="_blank">haz click aquí para ingresar de nuevo</a>, luego de iniciar sessión, puedes regresar a esta pantalla y recargar la página y continuar...',
                html: true,
                type: "error",
                confirmButtonText: "Ok",
                confirmButtonColor: "#2196F3"
            });
        });
    });

    $(".btn_guardar_color_base_sistema").on('click', function() {
        let tipo_color = $('input[name=radio_color]:checked').val();
        let color_fondo_base_1_rgb = $("#color_degradado_1").val();
        let color_fondo_base_2_rgb = $("#color_degradado_2").val();
        let color_fondo_base_solido_rgb = $("#color_solido").val();

        swal({
            title: '¡Confirmación!',
            text: '¡Atención! Estás a punto de cambiar el color del sistema, ¿estás seguro que quieres hacerlo?',
            html: true,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3f51b5", //confirmButtonColor: "#3f51b5",   
            cancelButtonColor: "#DD6B55",
            confirmButtonText: "Si, Adelante!",
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        }, function (isconfirmed) {
            if (isconfirmed) {
                $.ajax({
                    url: '/facturacionv8/personalizaciondesistema/guardar_color_base_sistema',
                    data: {tipo_color: tipo_color, color_fondo_base_1_rgb: color_fondo_base_1_rgb, color_fondo_base_2_rgb: color_fondo_base_2_rgb, color_fondo_base_solido_rgb: color_fondo_base_solido_rgb},
                    method: 'POST',
                    dataType: 'json'
                }).then(function (data) {
                    if (data.respuesta == 'ok') {
                        swal({
                            title: data.titulo,
                            text: data.mensaje,
                            html: true,
                            type: "success",
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Ok",
                        }, function () {
                            
                        });
                    } else {
                        swal({
                            title: data.titulo,
                            text: data.mensaje,
                            html: true,
                            type: "error",
                            confirmButtonText: "Ok",
                            confirmButtonColor: "#2196F3"
                        });
                    }
                }, function (reason) {
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
        });
    });

    $(".btn_reiniciar_colores_base").on('click', function() {
        swal({
            title: '¡Confirmación!',
            text: '¡Atención! Estás a punto de deshacer un cambio personalizado, ¿estás seguro que quieres hacerlo?',
            html: true,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3f51b5", //confirmButtonColor: "#3f51b5",   
            cancelButtonColor: "#DD6B55",
            confirmButtonText: "Si, Adelante!",
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        }, function (isconfirmed) {
            if (isconfirmed) {
                $.ajax({
                    url: '/facturacionv8/personalizaciondesistema/reiniciar_colores_base',
                    method: 'POST',
                    dataType: 'json'
                }).then(function (data) {
                    if (data.respuesta == 'ok') {
                        swal({
                            title: data.titulo,
                            text: data.mensaje,
                            html: true,
                            type: "success",
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Ok",
                        }, function () {
                            location.reload();
                        });
                    } else {
                        swal({
                            title: data.titulo,
                            text: data.mensaje,
                            html: true,
                            type: "error",
                            confirmButtonText: "Ok",
                            confirmButtonColor: "#2196F3"
                        });
                    }
                }, function (reason) {
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
        });
    });
});

function inicializar_controles() {
    // Initialize multiple switches
    var elems = Array.prototype.slice.call(document.querySelectorAll('.switchery'));
    elems.forEach(function (html) {
        var switchery = new Switchery(html);
    });

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