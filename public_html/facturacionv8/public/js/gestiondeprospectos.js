$(function() {
    $.extend( $.fn.dataTable.defaults, {
        autoWidth: false,
        dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        language: {
            search: '<span>Filter:</span> _INPUT_',
            searchPlaceholder: 'Escribe para Filtrar...',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
        }
    });

    $(".control_fecha").daterangepicker({singleDatePicker: true, locale: { format: 'DD/MM/YYYY' }});
    
    $('#add_tag').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) { 
          e.preventDefault();
          return false;
        }
    });

    $('#nombre_etiqueta').keyup(function(e){
        if(e.keyCode == 13) {
            $('.btn_save_tag').trigger("click");
        }
    });

    $('#tbl_lista_prospectos').DataTable();
    $(".select2").select2();
    inicializar_controles();
    $(".search_document").click(function() {
        var num_doc = $("#cliente_numerodocumento").val();
		if(num_doc != '') {
            var tipo_doc = 6;
            consultar_numero_doc($("#idcliente"), num_doc, $("#razon_social"), $("#direccionfiscal"), $("#ubigeo"), tipo_doc, $("#email"));
        }
    });
    get_lista_prospectos();
    $('#add_modal_token').hide();
    $('#btn_new_prospecto').click(function(){
        $("#frm_agregar_prospecto")[0].reset();
        $("#id_prospecto").val('');
        $("#box-telefono-prospecto .tokenfield .token").remove();
        $("#box-correo-prospecto .tokenfield .token").remove();
        $("#box-etiqueta-prospecto .tokenfield .token").remove();
        $('#content-tag .tagg-item').remove();
        $('#add_modal_token').hide();
        $('#bg-primary').prop("checked", true).uniform('regresh');
        $('#bg-success').prop("checked", false).uniform('regresh');
        $('#bg-warning').prop("checked", false).uniform('regresh');
        $('#bg-danger').prop("checked", false).uniform('regresh');
        $(".radio").trigger('change');
    });
    $('#telefonos').on('tokenfield:createtoken', function (event) {
        var existingTokens = $(this).tokenfield('getTokens');
        $.each(existingTokens, function(index, token) {
            if (token.value === event.attrs.value)
            {
                 event.preventDefault();
                swal({   
                    title: 'Error',   
                    text: 'Ya has agregado ese número de teléfono',
                    html: true,
                    type: "error",  
                    confirmButtonColor: "#DD6B55",   
                    confirmButtonText: "Ok"
                }, function() {
                    $('#telefonos').tokenfield('removetoken', token.value);
                });
            }
               
        });
    });
    $('#email').on('tokenfield:createtoken', function (event) {
        var existingTokens = $(this).tokenfield('getTokens');
        $.each(existingTokens, function(index, token) {
             if (token.value === event.attrs.value)
            {
                 event.preventDefault();
                swal({   
                    title: 'Error',   
                    text: 'Ya has agregado ese correo electrónico',
                    html: true,
                    type: "error",  
                    confirmButtonColor: "#DD6B55",   
                    confirmButtonText: "Ok"
                }, function() {
                    $('#email').tokenfield('removetoken', token.value);
                });
            }
               
            });
    });
    $('#addtoken').click(add_token);
    $('.btn_save_tag').click(save_tag);    
    $('.btn_guardar_telefono').click(guardar_telefono);
    $('.btn_guardar_correo').click(guardar_correo);
    $('.btn_save_prospectos').click(save_prospectos);
    $(".btn_guardar_nota").click(guardar_nota);
});

function add_token(){
    var nombre_etiqueta = $("#nombre_etiqueta_modal").val();
    var  color_etiqueta =  $("input[name='color-etiqueta-modal']:checked").val();
    var tag_initial = $("#etiquetas").val();
   
    if(nombre_etiqueta != '' && color_etiqueta){
        if(tag_initial == ''){
            var auxiliar = $("#etiquetas").val(nombre_etiqueta+'__'+color_etiqueta);  
        }else{
            var auxiliar = $("#etiquetas").val(tag_initial+','+ nombre_etiqueta+'__'+color_etiqueta);
            var arr_tag = tag_initial.split(",");
            var tag_color = nombre_etiqueta+'__'+color_etiqueta;
            arr_tag.forEach(function(etiqueta, index) {
                var arr_tag_nombre = etiqueta.split('__');
                if(arr_tag_nombre[0] == nombre_etiqueta){
                    // Obtenemos el indice de la posición de la etiqueta
                    var indice = arr_tag.indexOf(arr_tag_nombre[0]+'__'+arr_tag_nombre[1]); 
                    //Se elimina elemento repetido
                    arr_tag.splice(indice, 1); 
                    //Se añade el nuevo tag de diferente color
                    arr_tag.push(tag_color); 
                    //Se transforma el array a cadena de string
                    var nueva_cadena = arr_tag.join(',');
                    //Se reemplaza el nuevo valor en el input de tag
                    var tag_actual = $("#etiquetas").val(nueva_cadena);
                    //se elimina el tag visual del div
                    $('#item'+arr_tag_nombre[0]+'__'+arr_tag_nombre[1]).remove(); 
                  
                }
            });
        }
        $('#add_modal_token').show();
        $('#content-tag').append('<div class="tagg-item" id="item'+nombre_etiqueta +'__'+ color_etiqueta+'"><span class="'+ color_etiqueta +'">'+nombre_etiqueta+'</span><a href="javascript:void(0)" onClick="delete_token_temporaly(\'' +nombre_etiqueta +'__'+ color_etiqueta+'\')"  data-value="(\'' +nombre_etiqueta + '\')" class="close">×</a></div>');
        $("#nombre_etiqueta_modal").val(''); 
    }else{
      swal({   
        title: 'Error',   
        text: 'Para agregar una etiqueta, debes seleccionar un color y escribir una palabra',
        html: true,
        type: "error",  
        confirmButtonColor: "#DD6B55",   
        confirmButtonText: "Ok"
    });  
    }
    
}

function delete_token_temporaly(string){
    //declaración de variables
    var token_etiqueta = $("#etiquetas").val();
    //Separar cadenas en array
    var arr_tag = token_etiqueta.split(",");
    // Obtenemos el indice de la posición de la etiqueta
    var indice = arr_tag.indexOf(string); 
    arr_tag.splice(indice, 1); //se elimina elemento
    //Reemplazar etiquetas restantes
    $("#etiquetas").val(arr_tag);
    $('#item'+string).remove();
     //Para ocultar el div que almacena los tag cuando el array esté en 0
    var size_tag = arr_tag.length;
    if(size_tag == 0){
       $('#add_modal_token').hide();  
    }
  
}
function delete_token(idetiqueta){
    var light = $('#content-tag');
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
        url : "/facturacionv8/gestiondeprospectos/eliminar_etiqueta",
        method :  'POST',
        data: {idetiqueta: idetiqueta},
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			$(light).unblock();
            get_lista_prospectos();
            $('#item'+data.etiqueta_remove).remove();
            $("#etiquetas").val(data.etiqueta_input);
            //Separar cadenas en array
            var arr_tag = data.etiqueta_input.split(",");
            console.log(arr_tag);
                //Para ocultar el div que almacena los tag cuando el array esté en 0
            var size_tag = arr_tag.length;
            if(size_tag == 0){
                $('#add_modal_token').hide();  
            }
		
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
function  get_lista_prospectos(){
    var light = $('#tbl_lista_prospectos');
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

    dataTable_lista_prospectos = $('#tbl_lista_prospectos').DataTable( {
        "processing": true,
        "dom": 'Blfrtip',
		"serverSide": true,
		"ajax":{
			url :"/facturacionv8/gestiondeprospectos/get_lista_prospectos", // json datasource
			type: "post",
			error: function(){
				$(".tbl_lista_prospectos-error").html("");
				$("#tbl_lista_prospectos").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
				$("#tbl_lista_prospectos_processing").css("display","none");
			}
        },
        "bDestroy": true,
        "lengthMenu": [ [10, 25, 50, 100, 300, 600], [10, 25, 50, 100, 300, 600] ],
		buttons: [
            {
                text: '<i class="icon-plus-circle2 position-left"></i>	Nuevo Prospecto</span>',
                className: 'btn bg-indigo',
                action: function ( e, dt, node, config ) {
                    crear_nuevo_prospecto();
                }
            },
            {
                text: '<i class="icon-file-excel position-left"></i> Exportar GMAIL</span>',
                className: 'btn btn-success',
                action: function ( e, dt, node, config ) {
                    window.open("/facturacionv8/gestiondeprospectos/export_to_gmail/","_self");
                }
            },
            {
                extend: 'colvis',
                text: '<i class="icon-three-bars"></i> <span class="caret"></span>',
                className: 'btn bg-indigo btn-icon',
                collectionLayout: 'fixed two-column'
            }
        ],
        "columns": [
            { "data": "id_prospecto", "visible": true},
            { "data": "fecha_registro", "visible": true},
            { "data": "nombre_contacto", "visible": true},
            { "data": "etiquetas", "visible": true},
            { "data": "telefonos", "visible": true},
            { "data": "correos", "visible": true},
            { "data": "razon_social", "visible": true},
            { "data": "nota", "visible": true}
          ],
        stateSave: false,
        initComplete: function(){
            $(light).unblock();
            $('[data-popup="popover"]').popover();
        }
    });
}

function crear_nuevo_prospecto() {
    $("#nuevo_prospecto").modal("show");
}

function save_prospectos(){
    var light = $('#modal-prospecto');
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
    var datastring = $("#frm_agregar_prospecto").serializeArray();
	$.ajax({
        url : "/facturacionv8/gestiondeprospectos/save",
		method :  'POST',
		data: datastring,
        dataType : "json"
    }).then(function(data){
        if(data.respuesta == 'ok') {
			swal({   
                title:data.titulo,   
                text: data.mensaje,
                html: true,
                type: "success", 
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Ok",
            }, function() {
                $(light).unblock();
				$("#frm_agregar_prospecto")[0].reset();
				$("#id_prospecto").val('');
                get_lista_prospectos();
                $('#nuevo_prospecto').modal('hide');
                $("#box-telefono-prospecto .tokenfield .token").remove();
                $("#box-correo-prospecto .tokenfield .token").remove();
                $("#box-etiqueta-prospecto .tokenfield .token").remove();
                $('#content-tag .tagg-item').remove();

            });
        } else {
            swal({   
                title:'Error',   
                text: data.mensaje,
                html: true,
                type: "error", 
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Ok",
            }, function() {
				$(light).unblock();
            });
        }
    }, function(reason){
    	swal({   
			title: 'Error',   
			text: reason,
			html: true,
			type: "error",  
			confirmButtonColor: "#DD6B55",   
			confirmButtonText: "Ok"
		}, function() {
			$(light).unblock();
		});
    });
}
function editar_prospecto(id_prospecto) {
    $('#nuevo_prospecto').modal('show');
    $("#frm_agregar_prospecto")[0].reset();
    $('#bg-primary').prop("checked", true).uniform('regresh');
    $('#bg-success').prop("checked", false).uniform('regresh');
    $('#bg-warning').prop("checked", false).uniform('regresh');
    $('#bg-danger').prop("checked", false).uniform('regresh');
    $(".radio").trigger('change');
    //eliminar tag anteriores
    $("#box-telefono-prospecto .tokenfield .token").remove();
    $("#box-correo-prospecto .tokenfield .token").remove();
    $("#box-etiqueta-prospecto .tokenfield .token").remove();
    $('#add_modal_token').show();
    $('#content-tag .tagg-item').remove();
	$.ajax({
        url : '/facturacionv8/gestiondeprospectos/get_data_prospecto',
		method :  'POST',
		data: {id_prospecto: id_prospecto},
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
            var cadena_etiquetas = data.etiqueta;
            cadena_etiquetas.forEach(function(etiqueta, index) {
             $('#content-tag').append('<div class="tagg-item" id="item'+etiqueta.nombre+'__'+etiqueta.color+'"><span class="'+ etiqueta.color +'">'+etiqueta.nombre+'</span><a href="javascript:void(0)" onclick="delete_token('+etiqueta.id_etiqueta+')" data-idetiqueta="'+etiqueta.id_etiqueta+'"  class="close">×</a></div>');
            });
            $("#id_prospecto").val(data.prospecto.id_prospecto);
			$("#nombre_contacto").val(data.prospecto.nombre_contacto);
            $("#select_sucursal").val(data.prospecto.id_sucursal).trigger("change").trigger("select2:select");
            $("#telefonos").tokenfield('setTokens', data.prospecto.telefonos);
            $('#email').tokenfield('setTokens', data.prospecto.correos);
            $("#etiquetas").val(data.prospecto.etiquetas);
            $("#cliente_numerodocumento").val(data.prospecto.ruc);
            $("#razon_social").val(data.prospecto.razon_social);
            $("#nota_prospecto").val(data.prospecto.notas);
            $("#fecha_expira_oferta").val(data.fecha_expira);
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
function eliminar_prospecto(id_prospecto) {
	var light = $('#content_lista_prospectos');
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
	swal({
        title: "Confirmación!",
        text: "¿Estás seguro que deseas eliminar este prospecto?",
        type: "warning",
        showCancelButton: true,   
        confirmButtonColor: "#3f51b5",       
        cancelButtonColor: "#aaaaaa",
        confirmButtonText: "Sí, Adelante!",
        closeOnConfirm: false
    },function(isconfirmed){
        if(isconfirmed) {
            var confirmado = 'si';
			$.ajax({
				url : "/facturacionv8/gestiondeprospectos/eliminar_prospecto",
				method :  'POST',
				data: {id_prospecto: id_prospecto},
				dataType : "json"
			}).then(function(data){
				if(data.respuesta == 'ok') {
					swal({   
						title:'Ok',   
						text: data.mensaje,
						html: true,
						type: "success", 
						confirmButtonColor: "#DD6B55",   
						confirmButtonText: "Ok",
					}, function() {
						$(light).unblock();
						get_lista_prospectos();
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
        }else{
            $(light).unblock();
            return;
        }
      
    });
}
/* Items independientes */
function agregar_tag(id_prospecto){
    $('#agregar_tagg_modal').modal('show');
    $('#id_prospecto_tag').val(id_prospecto);   
    $("#nombre_etiqueta").val(''); 
}
function data_telefono(id_prospecto){
    $('#agregar_telefono_modal').modal('show');
    $('#id_prospecto_telefono').val(id_prospecto); 
}
function data_correo(id_prospecto){
    $('#agregar_correo_modal').modal('show');
    $('#id_prospecto_correo').val(id_prospecto); 
}
function get_data_nota(id_prospecto){
    var light = $('#form_nota_prospecto');
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
       url : "/facturacionv8/gestiondeprospectos/get_data_nota",
       method :  'POST',
       data:  {id_prospecto: id_prospecto},
       dataType : "json"
   }).then(function(data){
       if(data.respuesta == 'ok') {
            $(light).unblock();
            $('#editar_nota_modal').modal('show');
            $("#id_prospecto_nota").val(data.prospecto.id_prospecto);
            $("#nombre_empresa_nota").html(data.prospecto.razon_social);
            $("#nombre_nota").html('<label class="label-form"><i class="icon-user mr-2"></i></label>' + data.prospecto.nombre_contacto);
            $("#telefono_nota").html('<label class="label-form"><i class="fa fa-phone position-left"></i></label>' + data.prospecto.telefonos);
            $("#nota_prospecto_new").val(data.prospecto.nota);
            if(data.prospecto.nota == ''){
                $("#tipo_nota").html('Agregar Nota de Prospecto');
            }else{
                $("#tipo_nota").html('Editar Nota de Prospecto');
            }
           
       } else {
           swal({   
               title:'Error',   
               text: data.mensaje,
               html: true,
               type: "error", 
               confirmButtonColor: "#DD6B55",   
               confirmButtonText: "Ok",
           }, function() {
               $(light).unblock();
           });
       }
   }, function(reason){
       swal({   
           title: 'Error',   
           text: reason,
           html: true,
           type: "error",  
           confirmButtonColor: "#DD6B55",   
           confirmButtonText: "Ok"
       }, function() {
           $(light).unblock();
       });
   });

}
function save_tag(){
    var light = $('#add_tag');
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
    
    var datastring = $("#add_tag").serializeArray();
    $.ajax({
        url : "/facturacionv8/gestiondeprospectos/agregar_etiqueta",
		method :  'POST',
		data: datastring,
        dataType : "json"
    }).then(function(data){
        if(data.respuesta == 'ok') {
			$(light).unblock();
            get_lista_prospectos();
            $('#agregar_tagg_modal').modal('hide');
            $("#nombre_etiqueta").val('');
            $("#id_prospecto_tag").val('');
        } else {
            swal({   
                title:'Error',   
                text: data.mensaje,
                html: true,
                type: "error", 
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Ok",
            }, function() {
				$(light).unblock();
            });
        }
    }, function(reason){
    	swal({   
			title: 'Error',   
			text: reason,
			html: true,
			type: "error",  
			confirmButtonColor: "#DD6B55",   
			confirmButtonText: "Ok"
		}, function() {
			$(light).unblock();
		});
    }); 
  
}
function guardar_telefono(){
     var light = $('#add_phone');
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
     var datastring = $("#add_phone").serializeArray();
	$.ajax({
        url : "/facturacionv8/gestiondeprospectos/agregar_telefono",
		method :  'POST',
		data: datastring,
        dataType : "json"
    }).then(function(data){
        if(data.respuesta == 'ok') {
			swal({   
                title:'Ok',   
                text: data.mensaje,
                html: true,
                type: "success", 
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Ok",
            }, function() {
                $(light).unblock();
                get_lista_prospectos();
                $('#agregar_telefono_modal').modal('hide');
                $("#telefono").val('');

            });
        } else {
            swal({   
                title:'Error',   
                text: data.mensaje,
                html: true,
                type: "error", 
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Ok",
            }, function() {
				$(light).unblock();
            });
        }
    }, function(reason){
    	swal({   
			title: 'Error',   
			text: reason,
			html: true,
			type: "error",  
			confirmButtonColor: "#DD6B55",   
			confirmButtonText: "Ok"
		}, function() {
			$(light).unblock();
		});
    });
}
function guardar_correo(){
    /*var correo = $("#correo").val();
    $('.content-correo').append('<p>'+correo+'</p>');
     $('#agregar_correo_modal').modal('hide');
     $("#add_correo")[0].reset();*/
     var light = $('#add_correo');
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
     var datastring = $("#add_correo").serializeArray();
	$.ajax({
        url : "/facturacionv8/gestiondeprospectos/agregar_correo",
		method :  'POST',
		data: datastring,
        dataType : "json"
    }).then(function(data){
        if(data.respuesta == 'ok') {
			swal({   
                title:'Ok',   
                text: data.mensaje,
                html: true,
                type: "success", 
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Ok",
            }, function() {
                $(light).unblock();
                get_lista_prospectos();
                $('#agregar_correo_modal').modal('hide');
                $("#correo").val('');

            });
        } else {
            swal({   
                title:'Error',   
                text: data.mensaje,
                html: true,
                type: "error", 
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Ok",
            }, function() {
				$(light).unblock();
            });
        }
    }, function(reason){
    	swal({   
			title: 'Error',   
			text: reason,
			html: true,
			type: "error",  
			confirmButtonColor: "#DD6B55",   
			confirmButtonText: "Ok"
		}, function() {
			$(light).unblock();
		});
    });
}
function guardar_nota(){
   
     var light = $('#form_nota_prospecto');
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
     var datastring = $("#form_nota_prospecto").serializeArray();
	$.ajax({
        url : "/facturacionv8/gestiondeprospectos/guardar_nota",
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
                confirmButtonColor: "#7880f0",   
                confirmButtonText: "Aceptar",
            }, function() {
                $(light).unblock();
                get_lista_prospectos();
                $('#editar_nota_modal').modal('hide');
                $("#form_nota_prospecto")[0].reset();
            });
        } else {
            swal({   
                title:'Error',   
                text: data.mensaje,
                html: true,
                type: "error", 
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Ok",
            }, function() {
				$(light).unblock();
            });
        }
    }, function(reason){
    	swal({   
			title: 'Error',   
			text: reason,
			html: true,
			type: "error",  
			confirmButtonColor: "#DD6B55",   
			confirmButtonText: "Ok"
		}, function() {
			$(light).unblock();
		});
    });
}
function inicializar_controles() {
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