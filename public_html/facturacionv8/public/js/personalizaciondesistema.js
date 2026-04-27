$(function () {
    get_data_personalizacion();
    inicializar_controles();
    //get_color();
    //Apenas cargue y no hay nada guardado en la bd
    let tipo_color = $(".tipo_color ").val();
    if (tipo_color == 'color_solido') {
        $(".content-solid").append('<div class="box_solido"><div class="row"> <div class="col-lg-3"><input type="color" class="form-control" name="color_solido" id="color_solido"></div><button class="btn bg-indigo legitRipple btn_vista_color mxy-20 float-right mt-5 mr-2" type="button"><i class="icon-floppy-disk mr-2"></i>Vista previa </button></div></div>');
        $(".box_degradado").remove();
        $(".btn_vista_color").click(function () {
            $("#navbar-indigo").addClass('new_color');
            $(".bg-indigo").removeClass('btn');
            $(".navigation li a i").addClass('new_style_icon');
            $("legend i").addClass('new_style_icon');
            let value_color = $("#color_solido").val();
            $(".new_color").css('background', value_color);
            $(".bg-indigo").css('background', value_color);
            $("i").css("cssText", $("i").attr("style") + ";color: " + value_color);
        });
    }

    //Cuando hay cambio de boton local
    $(".tipo_color ").on("change", function () {
        let tipo_color = $(this).val();
        if (tipo_color == 'color_solido') {
            $(".content-solid").html('<div class="box_solido"><div class="row"> <div class="col-lg-3"><input type="color" class="form-control" name="color_solido" id="color_solido"></div><button class="btn bg-indigo legitRipple btn_vista_color mxy-20 float-right mt-5 mr-2" type="button"><i class="icon-floppy-disk mr-2"></i>Vista previa </button></div></div>');
            $(".box_degradado").remove();
            $(".btn_vista_color").click(function () {
                $("#navbar-indigo").addClass('new_color');
                $(".bg-indigo").removeClass('btn');
                $(".navigation li a i").addClass('new_style_icon');
                $("legend i").addClass('new_style_icon');
                let value_color = $("#color_solido").val();
                $(".new_color").css('background', value_color);
                $(".bg-indigo").css('background', value_color);
                $("i").css("cssText", $("i").attr("style") + ";color: " + value_color);

            });

        } else {
            $(".content-degradado").html('<div class="box_degradado"><div class="row"> <div class="col-lg-3"><input type="color" class="form-control" name="color_degradado_1" id="color_degradado_1"></div> <div class="col-lg-3"><input type="color" class="form-control" name="color_degradado_2" id="color_degradado_2"> </div><button class="btn bg-indigo legitRipple btn_vista_degradado mxy-20 float-right mt-5 mr-2" type="button"> <i class="icon-floppy-disk mr-2"></i>Vista previa</button></div></div>');
            $(".box_solido").remove();
            $("#navbar-indigo").removeAttr('style');
            $("#navbar-indigo").removeClass('new_color');
            $(".btn_vista_degradado").click(function () {
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

            });

        }
    });

    $(".btn_deshacer").click(function () {
        $("#navbar-indigo").removeAttr('style');
        $(".bg-indigo").removeAttr('style');
        $("i").removeAttr('style');
        $(".navigation li a i").removeClass('new_style_icon');
        $("#navbar-indigo").removeClass('new_degradado');
        $("#navbar-indigo").removeClass('new_color');

    });
    $(".tipo_color ").on("change", function () {
        $("#navbar-indigo").removeAttr('style');
        $(".bg-indigo").removeAttr('style');
        $("i").removeAttr('style');
        $(".navigation li a i").removeClass('new_style_icon');
        $("#navbar-indigo").removeClass('new_degradado');
        $("#navbar-indigo").removeClass('new_color');
    });

    $(".btn_save_color").click(save_color);
  //  $(".btn_deshacer_cambios").click(btn_deshacer_cambios);
    $(".btn_save_login").click(save_login);
    $(".btn_save_register").click(save_register);
    $(".btn_save_mensaje").click(save_mensaje_sub);
    $('#btn_subirimagen_login').click(function () {
        ratio = 1280 / 720;
        ancho_corte_width = 1280;
        alto_corte_height = 720;
        imagetipo = 'img_login_template';
        $('.fileinput-remove').trigger('click');
        $('.previewrecorteimg').html('');
        $('#vm_cargar_imagen').modal('show');
    });

    $('#btn_subirimagen_register').click(function () {
        ratio = 1280 / 720;
        ancho_corte_width = 1280;
        alto_corte_height = 720;
        imagetipo = 'img_registro_template';
        $('.fileinput-remove').trigger('click');
        $('.previewrecorteimg').html('');
        $('#vm_cargar_imagen').modal('show');
    });

    inicializacion_plugin_para_recortar_imagenes($('.previewrecorteimg'), '.kv-file-content .file-preview-image', $('.file-input'), $("#btn_guardarimagen"));

    $("#vm_cargar_imagen").on("hidden.bs.modal", function () {
        $('.modal:visible').css("overflow-y","auto");
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
function save_mensaje_sub() {
    var datastring = $("#frm_mensaje_sub").serializeArray();
  
    swal({
        title: '¡Confirmación!',
        text: '¡Atención! Estás a punto de cambiar el mensaje de aviso de caducidad de suscripción del sistema, ¿estás seguro que quieres hacerlo?',
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
                url: '/facturacionv8/personalizaciondesistema/save_mensaje_sub',
                data: datastring,
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


}
function save_login() {
    var datastring = $("#frm_bg_login").serializeArray();
  
    swal({
        title: '¡Confirmación!',
        text: '¡Atención! Estás a punto de cambiar el estilo del inicio de sesión del sistema, ¿estás seguro que quieres hacerlo?',
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
                url: '/facturacionv8/personalizaciondesistema/save_template_login',
                data: datastring,
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


}

function save_register() {
    var datastring = $("#frm_bg_registro").serializeArray();
    swal({
        title: '¡Confirmación!',
        text: '¡Atención! Estás a punto de cambiar el estilo del registro del sistema, ¿estás seguro que quieres hacerlo?',
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
                url: '/facturacionv8/personalizaciondesistema/save_template_registro',
                data: datastring,
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


}

function save_color() {
    var datastring = $("#frm_color").serializeArray();
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
                url: '/facturacionv8/personalizaciondesistema/save_color',
                data: datastring,
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
}

function btn_deshacer_cambios(idopcion) {
   
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
                url: '/facturacionv8/personalizaciondesistema/deshacer_diseno',
                data: {idopcion: idopcion},
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
}

function get_data_personalizacion() {
    $.ajax({
        url: '/facturacionv8/personalizaciondesistema/get_data_personalizacion',
        method: 'POST',
        dataType: "json"
    }).then(function (data) {
        if (data.respuesta == 'ok') {
            $("input[name=id_plantilla_login][value='" + data.opciones.id_plantilla_login + "']").prop("checked", true).uniform('regresh');
            $("input[name=id_plantilla_login][value='" + data.opciones.id_plantilla_login + "']").trigger('change');
            $("input[name=id_plantilla_registro][value='" + data.opciones.id_plantilla_registro + "']").prop("checked", true).uniform('regresh');
            $("input[name=id_plantilla_registro][value='" + data.opciones.id_plantilla_registro + "']").trigger('change');

            if (data.opciones.color_fondo_tipo == 'color_solido') {
                $("#radio_solido").prop("checked", true).uniform('regresh');
                $("#radio_solido").trigger('change');

                $("#radio_degradado").prop("checked", false).uniform('regresh');
                $("#radio_degradado").trigger('change');

                $(".content-solid").html('<div class="box_solido"><div class="row"> <div class="col-lg-2"><input type="color" class="form-control" name="color_solido" id="color_solido" value="' + data.opciones.color_fondo_1_rgb + '"></div><button class="btn bg-indigo legitRipple btn_vista_color mxy-20 float-right mt-5 mr-2" type="button"><i class="icon-floppy-disk mr-2"></i>Vista previa </button></div></div>');
                $(".box_degradado").remove();
                $(".btn_vista_color").click(function () {
                    $("#navbar-indigo").addClass('new_color');
                    $(".bg-indigo").removeClass('btn');
                    $(".navigation li a i").addClass('new_style_icon');
                    $("legend i").addClass('new_style_icon');
                    let value_color = $("#color_solido").val();
                    $(".new_color").css('background', value_color);
                    $(".bg-indigo").css('background', value_color);
                    $("i").css("cssText", $("i").attr("style") + ";color: " + value_color);

                });

            } else {
                $("#radio_degradado").prop("checked", true).uniform('regresh');
                $("#radio_degradado").trigger('change');

                $("#radio_solido").prop("checked", false).uniform('regresh');
                $("#radio_solido").trigger('change');

                $(".content-degradado").html('<div class="box_degradado"><div class="row"> <div class="col-lg-2"><input type="color" class="form-control" name="color_degradado_1" id="color_degradado_1" value="' + data.opciones.color_fondo_1_rgb + '"></div> <div class="col-lg-2"><input type="color" class="form-control" name="color_degradado_2" id="color_degradado_2" value="' + data.opciones.color_fondo_2_rgb + '"> </div></div></div>');
                $(".box_solido").remove();
                $(".btn_vista_degradado").click(function () {
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

                    $("i").css("cssText", $("i").attr("style") + ";background: linear-gradient(45deg, " + value_color_1 + " 0%, " + value_color_2 + " 100%); -webkit-background-clip: text;-webkit-text-fill-color: transparent;");

                });
            }

            $("#msj_expira_suscripcion").val(data.opciones.msj_expira_suscripcion);
            $("#img_upload_preview_login").attr("src",data.opciones.img_background_login);
            $("#img_upload_preview_register").attr("src",data.opciones.img_background_register);
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

