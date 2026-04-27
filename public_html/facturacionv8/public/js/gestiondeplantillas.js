$(function() {
  inicializar_subida_imagen();
    $(".select_minimizado").select2({
        minimumResultsForSearch: -1
    }); 
    $(".btn_plantilla").click(save);
    inicializar_subida_imagen();
	get_list_template();
	
});
function inicializar_subida_imagen() {
	$('#btn_subirimagen').click(function() {
        ratio = 590 / 300;
		ancho_corte_width = 590;
        alto_corte_height = 300;
        imagetipo = 'img_preview_plantilla';
        $('.fileinput-remove').trigger('click');
        $('.previewrecorteimg').html('');
        $('#vm_cargar_imagen').modal('show');
	});
	
	inicializacion_plugin_para_recortar_imagenes($('.previewrecorteimg'), '.kv-file-content .file-preview-image', $('.file-input'), $("#btn_guardarimagen"));
    
    $("#vm_cargar_imagen").on("hidden.bs.modal", function () {
        $('.modal:visible').css("overflow-y","auto");
	});
	
}
function save(){
    var datastring = $("#frm_plantilla").serializeArray();
    $.ajax({
        url : '/facturacionv8/gestiondeplantillas/save',
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
          confirmButtonColor: "#00BCD4",   
          confirmButtonText: "Ok",
      }, function() {
        get_list_template();
        $("#frm_plantilla")[0].reset();
        $("#categoria_plantilla").select2("val", 0);
		$("#idplantilla").val('');
		$("#img_upload_preview").attr("src", "/facturacionv8/img/preview.jpg");
    });
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

function get_list_template(){
  var light = $("#content_lista_plantilla");

  $(light).block({
  message: '<div class="loading"> \
          <div class="loading-bar"></div> \
          <div class="loading-bar"></div> \
          <div class="loading-bar"></div> \
          <div class="loading-bar"></div> \
        </div> <p> <br />Un momento, estamos recuperando la data ...</p>',

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
      url : '/facturacionv8/gestiondeplantillas/get_list_plantilla',
      method :  'POST',
      dataType : "json"
  }).then(function(data){
    if(data.respuesta == 'ok') {
      $('#tbl_lista_plantilla').DataTable({
          data: data.lista,
          "bDestroy": true
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
  })
});
}
function edit_template(idplantilla){
	var light = $("#content_lista_plantilla");

	$(light).block({
	message: '<div class="loading"> \
					<div class="loading-bar"></div> \
					<div class="loading-bar"></div> \
					<div class="loading-bar"></div> \
					<div class="loading-bar"></div> \
				</div> <p> <br />Un momento, estamos recuperando la data ...</p>',

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
        url : '/facturacionv8/gestiondeplantillas/get_data_plantilla',
        method :  'POST',
        data: {idplantilla: idplantilla},
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			$("#idplantilla").val(data.plantilla.idtemplate);
			$("#nombre_plantilla").val(data.plantilla.nombre);
			$("#categoria_plantilla").val(data.plantilla.categoria).trigger("change").trigger("select2:select");
			$("#assets_plantilla").val(data.plantilla.ruta_assets);
			$("#html_plantilla").val(data.plantilla.html);
			$("#img_upload_preview").attr("src", data.plantilla.img_preview);
			$("#src_img_upload").val(data.plantilla.img_preview);
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

function eliminar_template(idplantilla){
	var light = $("#content_lista_plantilla");

	$(light).block({
	message: '<div class="loading"> \
					<div class="loading-bar"></div> \
					<div class="loading-bar"></div> \
					<div class="loading-bar"></div> \
					<div class="loading-bar"></div> \
				</div> <p> <br />Un momento ...</p>',

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
        url :  '/facturacionv8/gestiondeplantillas/eliminar_plantilla',
        method :  'POST',
        data: {idplantilla: idplantilla},
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			swal({   
				title: data.titulo,   
				text: data.mensaje,
				html: true,
				type: "success",   
				confirmButtonColor: "#00BCD4",   
				confirmButtonText: "OK"
		}, function() {
				$(light).unblock();
				get_list_template();
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
}