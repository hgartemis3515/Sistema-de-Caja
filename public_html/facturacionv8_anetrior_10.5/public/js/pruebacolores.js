$(function(){
    inicializar_controles();
    get_color();
     //Apenas cargue y no hay nada guardado en la bd
    let tipo_color = $(".tipo_color ").val();
    if(tipo_color == 'color_solido') {
        $(".content-solid").append('<div class="box_solido"><div class="row"> <div class="col-lg-3"><input type="color" class="form-control" name="color_solido" id="color_solido"></div><button class="btn bg-indigo legitRipple btn_vista_color mxy-20 float-right mt-5 mr-2" type="button"><i class="icon-floppy-disk mr-2"></i>Vista previa </button></div></div>');
        $(".box_degradado").remove();
        $(".btn_vista_color").click(function(){
            $("#navbar-indigo").addClass('new_color');
            $(".bg-indigo").removeClass('btn');
            $(".navigation li a i").addClass('new_style_icon');
            $("legend i").addClass('new_style_icon');
            let value_color = $("#color_solido").val();
            $(".new_color").css('background', value_color);
            $(".bg-indigo").css('background', value_color);
            $("i").css("cssText", $("i").attr("style") + ";color: "+ value_color);
        });
    } 
   
    //Cuando hay cambio de boton local
   $(".tipo_color ").on("change", function () {
        let tipo_color = $(this).val();
        if(tipo_color == 'color_solido') {
            $(".content-solid").html('<div class="box_solido"><div class="row"> <div class="col-lg-3"><input type="color" class="form-control" name="color_solido" id="color_solido"></div><button class="btn bg-indigo legitRipple btn_vista_color mxy-20 float-right mt-5 mr-2" type="button"><i class="icon-floppy-disk mr-2"></i>Vista previa </button></div></div>');
            $(".box_degradado").remove();
            $(".btn_vista_color").click(function(){
                $("#navbar-indigo").addClass('new_color');
                $(".bg-indigo").removeClass('btn');
                $(".navigation li a i").addClass('new_style_icon');
                $("legend i").addClass('new_style_icon');
                let value_color = $("#color_solido").val();
                $(".new_color").css('background', value_color);
                $(".bg-indigo").css('background', value_color);
                $("i").css("cssText", $("i").attr("style") + ";color: "+ value_color);
               
            });
           
        }else{
            $(".content-degradado").html('<div class="box_degradado"><div class="row"> <div class="col-lg-3"><input type="color" class="form-control" name="color_degradado_1" id="color_degradado_1"></div> <div class="col-lg-3"><input type="color" class="form-control" name="color_degradado_2" id="color_degradado_2"> </div><button class="btn bg-indigo legitRipple btn_vista_degradado mxy-20 float-right mt-5 mr-2" type="button"> <i class="icon-floppy-disk mr-2"></i>Vista previa</button></div></div>');
            $(".box_solido").remove();
            $("#navbar-indigo").removeAttr( 'style' );
            $("#navbar-indigo").removeClass('new_color');
            $(".btn_vista_degradado").click(function(){
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
               
                $("i").css("cssText", $("i").attr("style") + ";color: "+ value_color_1);
              
            });
    
        }
    });

    $(".btn_deshacer").click(function(){
        $("#navbar-indigo").removeAttr( 'style' );
        $(".bg-indigo").removeAttr( 'style' );
        $("i").removeAttr( 'style' );
        $(".navigation li a i").removeClass('new_style_icon');
        $("#navbar-indigo").removeClass('new_degradado');
        $("#navbar-indigo").removeClass('new_color');
        console.log('llego');
       
    });
    $(".tipo_color ").on("change", function () {
        $("#navbar-indigo").removeAttr( 'style' );
        $(".bg-indigo").removeAttr( 'style' );
        $("i").removeAttr( 'style' );
        $(".navigation li a i").removeClass('new_style_icon');
        $("#navbar-indigo").removeClass('new_degradado');
        $("#navbar-indigo").removeClass('new_color');
    });

    $(".btn_save").click(save);
    $(".btn_deshacer_cambios").click(btn_deshacer_cambios);
});



function inicializar_controles() {

    // Initialize multiple switches
    var elems = Array.prototype.slice.call(document.querySelectorAll('.switchery'));
    elems.forEach(function(html) {
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

function save(){
    var datastring = $("#frm_color").serializeArray();
    swal({   
        title: '¡Confirmación!',   
        text: '¡Atención! Estás a punto de cambiar el color del sistema, ¿estás seguro que quieres hacerlo?',
        html: true,
        type: "warning",   
        showCancelButton: true,   
        confirmButtonColor: "#3f51b5",    //confirmButtonColor: "#3f51b5",   
        cancelButtonColor: "#DD6B55",
        confirmButtonText: "Si, Adelante!",   
        closeOnConfirm: false,
          showLoaderOnConfirm: true
    }, function(isconfirmed){  
        if(isconfirmed) {
            $.ajax({
                url: '/facturacionv8/pruebacolores/save',
                data: datastring,
                method: 'POST',
                dataType: 'json'
            }).then(function(data){
                if(data.respuesta == 'ok'){
                    swal({   
                        title: data.titulo,   
                        text: data.mensaje,
                        html: true,
                        type: "success", 
                        confirmButtonColor: "#DD6B55",   
                        confirmButtonText: "Ok",
                    }, function(){
                        location.reload();
                    });
                }
                else {
                    swal({
                        title: data.titulo,
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
    });
    

	
	
}
function btn_deshacer_cambios(){
    var datastring = $("#frm_color").serializeArray();
    swal({   
        title: '¡Confirmación!',   
        text: '¡Atención! Estás a punto de cambiar el color del sistema, ¿estás seguro que quieres hacerlo?',
        html: true,
        type: "warning",   
        showCancelButton: true,   
        confirmButtonColor: "#3f51b5",    //confirmButtonColor: "#3f51b5",   
        cancelButtonColor: "#DD6B55",
        confirmButtonText: "Si, Adelante!",   
        closeOnConfirm: false,
          showLoaderOnConfirm: true
    }, function(isconfirmed){  
        if(isconfirmed) {
            $.ajax({
                url: '/facturacionv8/pruebacolores/deshacer_diseno',
                data: datastring,
                method: 'POST',
                dataType: 'json'
            }).then(function(data){
                if(data.respuesta == 'ok'){
                    swal({   
                        title: data.titulo,   
                        text: data.mensaje,
                        html: true,
                        type: "success", 
                        confirmButtonColor: "#DD6B55",   
                        confirmButtonText: "Ok",
                    }, function(){
                        location.reload();
                    });
                }
                else {
                    swal({
                        title: data.titulo,
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
    });

	
}
function get_color(){
    $.ajax({
        url : '/facturacionv8/pruebacolores/get_color',
        method :  'POST',
        dataType : "json"
    }).then(function(data){
            if(data.respuesta == 'ok') {
              
                $("#idopcion").val(data.color.id_contribuyente);
              
                if(data.color.id_plantilla_login == '1'){
                    $("input[name=id_plantilla_login][value='1']").prop("checked",true).uniform('regresh');
                    $("input[name=id_plantilla_login][value='1']").trigger('change');
                }else if (data.color.id_plantilla_login == '2'){
                    $("input[name=id_plantilla_login][value='2']").prop("checked",true).uniform('regresh');
                    $("input[name=id_plantilla_login][value='2']").trigger('change');
                }else if (data.color.id_plantilla_login == '3'){
                    $("input[name=id_plantilla_login][value='3']").prop("checked",true).uniform('regresh');
                    $("input[name=id_plantilla_login][value='3']").trigger('change');
                }else if (data.color.id_plantilla_login == '4'){
                    $("input[name=id_plantilla_login][value='4']").prop("checked",true).uniform('regresh');
                    $("input[name=id_plantilla_login][value='4']").trigger('change');
                }else if (data.color.id_plantilla_login == '5'){
                    $("input[name=id_plantilla_login][value='5']").prop("checked",true).uniform('regresh');
                    $("input[name=id_plantilla_login][value='5']").trigger('change');
                }else if (data.color.id_plantilla_login == '12'){
                    $("input[name=id_plantilla_login][value='12']").prop("checked",true).uniform('regresh');
                    $("input[name=id_plantilla_login][value='12']").trigger('change');
                }else if (data.color.id_plantilla_login == '13'){
                    $("input[name=id_plantilla_login][value='13']").prop("checked",true).uniform('regresh');
                    $("input[name=id_plantilla_login][value='13']").trigger('change');
                }

                if(data.color.id_plantilla_registro == '6'){
                    $("input[name=id_plantilla_registro][value='6']").prop("checked",true).uniform('regresh');
                    $("input[name=id_plantilla_registro][value='6']").trigger('change');
                }else if (data.color.id_plantilla_registro == '7'){
                    $("input[name=id_plantilla_registro][value='7']").prop("checked",true).uniform('regresh');
                    $("input[name=id_plantilla_registro][value='7']").trigger('change');
                }else if (data.color.id_plantilla_registro == '8'){
                    $("input[name=id_plantilla_registro][value='8']").prop("checked",true).uniform('regresh');
                    $("input[name=id_plantilla_registro][value='8']").trigger('change');
                }else if (data.color.id_plantilla_registro == '9'){
                    $("input[name=id_plantilla_registro][value='9']").prop("checked",true).uniform('regresh');
                    $("input[name=id_plantilla_registro][value='9']").trigger('change');
                }else if (data.color.id_plantilla_registro == '10'){
                    $("input[name=id_plantilla_registro][value='10']").prop("checked",true).uniform('regresh');
                    $("input[name=id_plantilla_registro][value='10']").trigger('change');
                }else if (data.color.id_plantilla_registro == '11'){
                    $("input[name=id_plantilla_registro][value='11']").prop("checked",true).uniform('regresh');
                    $("input[name=id_plantilla_registro][value='11']").trigger('change');
                }else if (data.color.id_plantilla_registro == '14'){
                    $("input[name=id_plantilla_registro][value='14']").prop("checked",true).uniform('regresh');
                    $("input[name=id_plantilla_registro][value='14']").trigger('change');
                }

                if(data.color.color_fondo_tipo == 'color_solido'){
                    $("#radio_solido").prop("checked", true).uniform('regresh');
                    $("#radio_solido").trigger('change');

                    $("#radio_degradado").prop("checked", false).uniform('regresh');
                    $("#radio_degradado").trigger('change');

                    $(".content-solid").html('<div class="box_solido"><div class="row"> <div class="col-lg-2"><input type="color" class="form-control" name="color_solido" id="color_solido" value="'+ data.color.color_fondo_1_rgb +'"></div><button class="btn bg-indigo legitRipple btn_vista_color mxy-20 float-right mt-5 mr-2" type="button"><i class="icon-floppy-disk mr-2"></i>Vista previa </button></div></div>');
                    $(".box_degradado").remove();
                    $(".btn_vista_color").click(function(){
                        $("#navbar-indigo").addClass('new_color');
                        $(".bg-indigo").removeClass('btn');
                        $(".navigation li a i").addClass('new_style_icon');
                        $("legend i").addClass('new_style_icon');
                        let value_color = $("#color_solido").val();
                        $(".new_color").css('background', value_color);
                        $(".bg-indigo").css('background', value_color);
                        $("i").css("cssText", $("i").attr("style") + ";color: "+ value_color);
                       
                    });

                }else{
                    $("#radio_degradado").prop("checked", true).uniform('regresh');
                    $("#radio_degradado").trigger('change');

                    $("#radio_solido").prop("checked", false).uniform('regresh');
                    $("#radio_solido").trigger('change');

                    $(".content-degradado").html('<div class="box_degradado"><div class="row"> <div class="col-lg-2"><input type="color" class="form-control" name="color_degradado_1" id="color_degradado_1" value="'+ data.color.color_fondo_1_rgb +'"></div> <div class="col-lg-2"><input type="color" class="form-control" name="color_degradado_2" id="color_degradado_2" value="'+ data.color.color_fondo_2_rgb +'"> </div><button class="btn bg-indigo legitRipple btn_vista_degradado mxy-20 float-right mt-5 mr-2" type="button"> <i class="icon-floppy-disk mr-2"></i>Vista previa</button></div></div>');
                    $(".box_solido").remove();
                    $(".btn_vista_degradado").click(function(){
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
                       
                        $("i").css("cssText", $("i").attr("style") + ";background: linear-gradient(45deg, " + value_color_1 + " 0%, " + value_color_2 + " 100%); -webkit-background-clip: text;-webkit-text-fill-color: transparent;" );
                      
                    });
                }
               /* $.each(data.lista_disenios_login, function(index, login){

                    $(".content_item_login").append('<div class="col-lg-1"> <div class="thumbnail thumbnail-plantilla"><div class="thumb"><a  href="' + login.preview + '" data-popup="lightbox"> <img id="boleta_preview_a4" src="'+ login.preview +'" alt=""> <span class="zoom-image">  <i class="fa fa-eye"></i> </span></a> </div> </div> <div class="radio">  <label> <input type="radio" value="'+ login.id_diseno + '" name="id_plantilla_login" class="id_plantilla_login control-primary">  "'+ login.nombre + '" </label> </div>  </div>');
                     
                 });
                 $.each(data.lista_disenios_registro, function(index, login){
                     $(".content_item_registro").append('<div class="col-lg-1"> <div class="thumbnail thumbnail-plantilla"><div class="thumb"><a  href="' + login.preview + '" data-popup="lightbox"> <img id="boleta_preview_a4" src="'+ login.preview +'" alt=""> <span class="zoom-image">  <i class="fa fa-eye"></i> </span></a> </div> </div> <div class="radio">  <label> <input type="radio" value="'+ login.id_diseno + '" name="id_plantilla_registro"  class="id_plantilla_registro control-danger">  "'+ login.nombre + '" </label> </div>  </div>');
                      
                  });*/
                
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