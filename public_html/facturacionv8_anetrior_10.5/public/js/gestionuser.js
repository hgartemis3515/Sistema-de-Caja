$(function() {
	$.extend( $.fn.dataTable.defaults, {
        autoWidth: false,
        dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        language: {
            search: '<span>Filter:</span> _INPUT_',
            searchPlaceholder: 'Type to filter...',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
        }
    });

	$('.select_rol_usuario').select2({
        minimumResultsForSearch: -1
	});

	$('.select').select2({
        minimumResultsForSearch: -1
	});

	$('#opt_registro_documentos').multiselect({
        includeSelectAllOption: true,
        onSelectAll: function() {
            $.uniform.update();
        }
	});
	
	$('#opt_verreportes_reportes').multiselect({
        includeSelectAllOption: true,
        onSelectAll: function() {
            $.uniform.update();
        }
	});

	$('option', $('#opt_registro_documentos')).each(function(element) {
		$('#opt_registro_documentos').multiselect('select', $(this).val());
	});

	$('option', $('#opt_verreportes_reportes')).each(function(element) {
		$('#opt_verreportes_reportes').multiselect('select', $(this).val());
	});
	$.uniform.update();

	$('#idsucursal').select2({
        minimumResultsForSearch: -1
	});
	$(".btn_generar_codigo").click(function(){
		generar_codigo($("#txt_codigo"), 5);
	});
	generar_codigo($("#txt_codigo"), 5);
	$(".btn_generar_password").click(function(){
		generar_codigo($("#password"), 8);
	});
	$('.btn_saveuser').click(guardar_usuario);

	$('#show-passwd').on('click', function(e) {
		var current = $(this).attr('action');
		if (current == 'hide') {
			$('#password').attr('type', 'text');
			$('.icon-eye-blocked').attr('class', 'icon-eye');
			$('#show-passwd').attr('action','show');
		}
		if (current == 'show') {
			$('#password').attr('type', 'password');
			$('.icon-eye').attr('class', 'icon-eye-blocked');
			$('#show-passwd').attr('action','hide');
		}
	});

	$(".select_rol_usuario").on('change', function(){
		var id_rol = this.value;
		if(id_rol == 3) {
			$("#contenido_permisos_personalizados").hide();
		} else {
			$("#contenido_permisos_personalizados").show('slide');
		}
	});

	get_lista_sucursales($("#idsucursal"));

	get_lista_usuarios();
	inicializar_checkboxes();
});

function inicializar_checkboxes() {
	
    // Checkboxes/radios (Uniform)
    // ------------------------------

    // Default initialization
    $(".styled").uniform();

    // File input
    $(".file-styled").uniform({
        wrapperClass: 'bg-blue',
        fileButtonHtml: '<i class="icon-file-plus"></i>'
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

function editar_usuario(idusuario) {
	var light = $('#content_lista_usuarios');
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
        url : '/facturacionv8/gestionuser/get_data_usuario',
		method :  'POST',
		data: {idusuario: idusuario},
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			$(light).unblock();
			$("#idusuario").val(data.usuario.idusuario);
			$("#txt_codigo").val(data.usuario.codigo);
			$("#rol").val(data.usuario.id_rol).trigger("change").trigger("select2:select");
			if(!(data.usuario.idsucursal == null || data.usuario.idsucursal == '')) {
				$("#idsucursal").val(data.usuario.idsucursal).trigger("change").trigger("select2:select");
			}
			$("#nombre").val(data.usuario.nombre);
			$("#apellido").val(data.usuario.apellido);
			$("#password").val(data.usuario.password);
			$("#email").val(data.usuario.email);
			$("#celular").val(data.usuario.celular);
			$("#telefono").val(data.usuario.telefono);

			if(data.usuario.ver_ventas_totales == 'si') {
				$('#ver_ventas_totales').prop('checked',true);
				$.uniform.update('#ver_ventas_totales');
			} else {
				$('#ver_ventas_totales').prop('checked',false);
				$.uniform.update('#ver_ventas_totales');
			}

			if(data.usuario.modificacion_almacen == 'si') {
				$('#modificacion_almacen').prop('checked',true);
				$.uniform.update('#modificacion_almacen');
			} else {
				$('#modificacion_almacen').prop('checked',false);
				$.uniform.update('#modificacion_almacen');
			}

			if(data.usuario.ventas_multisucursal == 'si') {
				$('#ventas_multisucursal').prop('checked',true);
				$.uniform.update('#ventas_multisucursal');
			} else {
				$('#ventas_multisucursal').prop('checked',false);
				$.uniform.update('#ventas_multisucursal');
			}

			if(data.usuario.acceso_mod_compras == 'si') {
				$('#acceso_mod_compras').prop('checked',true);
				$.uniform.update('#acceso_mod_compras');
			} else {
				$('#acceso_mod_compras').prop('checked',false);
				$.uniform.update('#acceso_mod_compras');
			}

			if(data.usuario.modificacion_multi_almacen == 'si') {
				$('#modificacion_multi_almacen').prop('checked',true);
				$.uniform.update('#modificacion_multi_almacen');
			} else {
				$('#modificacion_multi_almacen').prop('checked',false);
				$.uniform.update('#modificacion_multi_almacen');
			}

			if((data.usuario.permisos === null) || (data.usuario.permisos === undefined) || (data.usuario.permisos == '') ) {
				//console.log($data.usuario.permisos);
			} else {
				var permisos = jQuery.parseJSON(data.usuario.permisos);
				//console.log(permisos.permisos_para_registros.opt_registro_ventas);

				$("#opt_registro_ventas").val(permisos.permisos_para_registros.opt_registro_ventas).trigger("change").trigger("select2:select");
				$("#opt_registro_compras").val(permisos.permisos_para_registros.opt_registro_compras).trigger("change").trigger("select2:select");
				$("#opt_registro_productos").val(permisos.permisos_para_registros.opt_registro_productos).trigger("change").trigger("select2:select");
				$("#opt_registro_cuentascobrar").val(permisos.permisos_para_registros.opt_registro_cuentascobrar).trigger("change").trigger("select2:select");
				$("#opt_cambio_fechaemision").val(permisos.permisos_para_registros.opt_cambio_fechaemision).trigger("change").trigger("select2:select");
				$('option', $("#opt_registro_documentos")).each(function(element) {
					$("#opt_registro_documentos").multiselect('deselect', $(this).val());
				});
				$.uniform.update();

				if(permisos.permisos_para_registros.opt_registro_documentos.boleta == true) {
					$('#opt_registro_documentos').multiselect('select', 'boleta'),
					$.uniform.update();
				}

				if(permisos.permisos_para_registros.opt_registro_documentos.factura == true) {
					$('#opt_registro_documentos').multiselect('select', 'factura'),
					$.uniform.update();
				}

				if(permisos.permisos_para_registros.opt_registro_documentos.nota_venta == true) {
					$('#opt_registro_documentos').multiselect('select', 'nota_venta'),
					$.uniform.update();
				}

				if(permisos.permisos_para_registros.opt_registro_documentos.cotizacion == true) {
					$('#opt_registro_documentos').multiselect('select', 'cotizacion'),
					$.uniform.update();
				}

				if(permisos.permisos_para_registros.opt_registro_documentos.guias_remision == true) {
					$('#opt_registro_documentos').multiselect('select', 'guias_remision'),
					$.uniform.update();
				}

				if(permisos.permisos_para_registros.opt_registro_documentos.notas_credito == true) {
					$('#opt_registro_documentos').multiselect('select', 'notas_credito'),
					$.uniform.update();
				}

				if(permisos.permisos_para_registros.opt_registro_documentos.notas_debito == true) {
					$('#opt_registro_documentos').multiselect('select', 'notas_debito'),
					$.uniform.update();
				}

				if(permisos.permisos_para_registros.opt_registro_documentos.anulacion_boleta == true) {
					$('#opt_registro_documentos').multiselect('select', 'anulacion_boleta'),
					$.uniform.update();
				}

				if(permisos.permisos_para_registros.opt_registro_documentos.anulacion_factura == true) {
					$('#opt_registro_documentos').multiselect('select', 'anulacion_factura'),
					$.uniform.update();
				}

				if(permisos.permisos_para_registros.opt_registro_documentos.anulacion_nota_venta == true) {
					$('#opt_registro_documentos').multiselect('select', 'anulacion_nota_venta'),
					$.uniform.update();
				}

				if(permisos.permisos_para_registros.opt_registro_documentos.anulacion_cotizacion == true) {
					$('#opt_registro_documentos').multiselect('select', 'anulacion_cotizacion'),
					$.uniform.update();
				}

				if(permisos.permisos_para_registros.opt_registro_documentos.anulacion_guias_remision == true) {
					$('#opt_registro_documentos').multiselect('select', 'anulacion_guias_remision'),
					$.uniform.update();
				}

				if(permisos.permisos_para_registros.opt_registro_documentos.anulacion_notas_credito == true) {
					$('#opt_registro_documentos').multiselect('select', 'anulacion_notas_credito'),
					$.uniform.update();
				}

				if(permisos.permisos_para_registros.opt_registro_documentos.anulacion_notas_debito == true) {
					$('#opt_registro_documentos').multiselect('select', 'anulacion_notas_debito'),
					$.uniform.update();
				}

				if(permisos.permisos_para_registros.opt_registro_documentos.editar_cotizacion == true) {
					$('#opt_registro_documentos').multiselect('select', 'editar_cotizacion'),
					$.uniform.update();
				}

				$("#opt_verreportes_ventas").val(permisos.permisos_para_reportes.opt_verreportes_ventas).trigger("change").trigger("select2:select");
				$("#opt_verreportes_contabilidad").val(permisos.permisos_para_reportes.opt_verreportes_contabilidad).trigger("change").trigger("select2:select");
				$("#opt_verreportes_cuentascobrar").val(permisos.permisos_para_reportes.opt_verreportes_cuentascobrar).trigger("change").trigger("select2:select");
				$('option', $("#opt_verreportes_reportes")).each(function(element) {
					$("#opt_verreportes_reportes").multiselect('deselect', $(this).val());
				});
				$.uniform.update();

				if(permisos.permisos_para_reportes.opt_verreportes_reportes.detalle_ventas == true) {
					$('#opt_verreportes_reportes').multiselect('select', 'detalle_ventas'),
					$.uniform.update();
				}

				if(permisos.permisos_para_reportes.opt_verreportes_reportes.productos_mas_vendidos == true) {
					$('#opt_verreportes_reportes').multiselect('select', 'productos_mas_vendidos'),
					$.uniform.update();
				}

				if(permisos.permisos_para_reportes.opt_verreportes_reportes.kardex == true) {
					$('#opt_verreportes_reportes').multiselect('select', 'kardex'),
					$.uniform.update();
				}

				if(permisos.permisos_para_reportes.opt_verreportes_reportes.top_clientes == true) {
					$('#opt_verreportes_reportes').multiselect('select', 'top_clientes'),
					$.uniform.update();
				}

				if(permisos.permisos_para_reportes.opt_verreportes_reportes.top_vendedores == true) {
					$('#opt_verreportes_reportes').multiselect('select', 'top_vendedores'),
					$.uniform.update();
				}

				if(permisos.permisos_para_reportes.opt_verreportes_reportes.top_estadisticas_dashboard == true) {
					$('#opt_verreportes_reportes').multiselect('select', 'top_estadisticas_dashboard'),
					$.uniform.update();
				}
				
				if(permisos.permisos_menus.opt_menu_cajachica == true) {
					$('#opt_menu_cajachica').prop('checked',true);
					$.uniform.update('#opt_menu_cajachica');
				} else {
					$('#opt_menu_cajachica').prop('checked',false);
					$.uniform.update('#opt_menu_cajachica');
				}

				if(permisos.permisos_menus.opt_menu_cuentasporcobrar == true) {
					$('#opt_menu_cuentasporcobrar').prop('checked',true);
					$.uniform.update('#opt_menu_cuentasporcobrar');
				} else {
					$('#opt_menu_cuentasporcobrar').prop('checked',false);
					$.uniform.update('#opt_menu_cuentasporcobrar');
				}

				if(permisos.permisos_menus.opt_menu_gestioncompras == true) {
					$('#opt_menu_gestioncompras').prop('checked',true);
					$.uniform.update('#opt_menu_gestioncompras');
				} else {
					$('#opt_menu_gestioncompras').prop('checked',false);
					$.uniform.update('#opt_menu_gestioncompras');
				}

				if(permisos.permisos_menus.opt_menu_proveedores == true) {
					$('#opt_menu_proveedores').prop('checked',true);
					$.uniform.update('#opt_menu_proveedores');
				} else {
					$('#opt_menu_proveedores').prop('checked',false);
					$.uniform.update('#opt_menu_proveedores');
				}

				if(permisos.permisos_menus.opt_menu_guiaremision == true) {
					$('#opt_menu_guiaremision').prop('checked',true);
					$.uniform.update('#opt_menu_guiaremision');
				} else {
					$('#opt_menu_guiaremision').prop('checked',false);
					$.uniform.update('#opt_menu_guiaremision');
				}

				if(permisos.permisos_menus.opt_menu_resumenboletas == true) {
					$('#opt_menu_resumenboletas').prop('checked',true);
					$.uniform.update('#opt_menu_resumenboletas');
				} else {
					$('#opt_menu_resumenboletas').prop('checked',false);
					$.uniform.update('#opt_menu_resumenboletas');
				}

				if(permisos.permisos_menus.opt_menu_clientes == true) {
					$('#opt_menu_clientes').prop('checked',true);
					$.uniform.update('#opt_menu_clientes');
				} else {
					$('#opt_menu_clientes').prop('checked',false);
					$.uniform.update('#opt_menu_clientes');
				}

				if(permisos.permisos_menus.opt_menu_inventario == true) {
					$('#opt_menu_inventario').prop('checked',true);
					$.uniform.update('#opt_menu_inventario');
				} else {
					$('#opt_menu_inventario').prop('checked',false);
					$.uniform.update('#opt_menu_inventario');
				}

				if(permisos.permisos_menus.opt_menu_contabilidad == true) {
					$('#opt_menu_contabilidad').prop('checked',true);
					$.uniform.update('#opt_menu_contabilidad');
				} else {
					$('#opt_menu_contabilidad').prop('checked',false);
					$.uniform.update('#opt_menu_contabilidad');
				}

				if(permisos.permisos_menus.opt_menu_contabilidad == true) {
					$('#opt_menu_top_clientes').prop('checked',true);
					$.uniform.update('#opt_menu_top_clientes');
				} else {
					$('#opt_menu_top_clientes').prop('checked',false);
					$.uniform.update('#opt_menu_top_clientes');
				}

				if(permisos.permisos_menus.opt_menu_contabilidad == true) {
					$('#opt_menu_top_vendedores').prop('checked',true);
					$.uniform.update('#opt_menu_top_vendedores');
				} else {
					$('#opt_menu_top_vendedores').prop('checked',false);
					$.uniform.update('#opt_menu_top_vendedores');
				}

				if(permisos.permisos_otros.opt_otros_costocompra == true) {
					$('#opt_otros_costocompra').prop('checked',true);
					$.uniform.update('#opt_otros_costocompra');
				} else {
					//se hace esto para que si en caso es indefinido entonces lo marque
					if(permisos.permisos_otros.opt_otros_costocompra == false) {
						$('#opt_otros_costocompra').prop('checked',false);
						$.uniform.update('#opt_otros_costocompra');
					} else {
						$('#opt_otros_costocompra').prop('checked',true);
						$.uniform.update('#opt_otros_costocompra');
					}
				}
			}

			if(data.usuario_opcion.modificar_precio_en_pantalla_venta == '') {
				$('#modificar_precio_en_pantalla_venta').prop('checked',true);
				$.uniform.update('#modificar_precio_en_pantalla_venta');
			} else {
				//se hace esto para que si en caso es indefinido entonces lo marque
				if(data.usuario_opcion.modificar_precio_en_pantalla_venta == 'si') {
					$('#modificar_precio_en_pantalla_venta').prop('checked',true);
					$.uniform.update('#modificar_precio_en_pantalla_venta');
				} else {
					$('#modificar_precio_en_pantalla_venta').prop('checked',false);
					$.uniform.update('#modificar_precio_en_pantalla_venta');
				}
			}

			if(data.usuario_opcion.venta_debajo_precio_minimo == '') {
				$('#venta_debajo_precio_minimo').prop('checked',true);
				$.uniform.update('#venta_debajo_precio_minimo');
			} else {
				//se hace esto para que si en caso es indefinido entonces lo marque
				if(data.usuario_opcion.venta_debajo_precio_minimo == 'si') {
					$('#venta_debajo_precio_minimo').prop('checked',true);
					$.uniform.update('#venta_debajo_precio_minimo');
				} else {
					$('#venta_debajo_precio_minimo').prop('checked',false);
					$.uniform.update('#venta_debajo_precio_minimo');
				}
			}
			
			if(data.usuario_opcion.ocultar_opciones_avanzadas_dashboard == '') {
				$('#ocultar_opciones_avanzadas_dashboard').prop('checked', false);
				$.uniform.update('#ocultar_opciones_avanzadas_dashboard');
			} else {
				//se hace esto para que si en caso es indefinido entonces lo marque
				if(data.usuario_opcion.venta_debajo_precio_minimo == 'si') {
					$('#ocultar_opciones_avanzadas_dashboard').prop('checked',true);
					$.uniform.update('#ocultar_opciones_avanzadas_dashboard');
				} else {
					$('#ocultar_opciones_avanzadas_dashboard').prop('checked',false);
					$.uniform.update('#ocultar_opciones_avanzadas_dashboard');
				}
			}

			if(data.usuario_opcion.opt_rango_mostrar_ventas == '') {
				$("#opt_rango_mostrar_ventas").val('ultimos_30_dias').trigger("change").trigger("select2:select");
			} else {
				$("#opt_rango_mostrar_ventas").val(data.usuario_opcion.opt_rango_mostrar_ventas).trigger("change").trigger("select2:select");
			}
			
		} else {
			swal({
				title: 'Error',
				text: data.mensaje,
				html: true,
				type: "error",
				confirmButtonText: "Ok",
				confirmButtonColor: "#3F51B5"
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
			confirmButtonColor: "#3F51B5"
		}, function(){
			$(light).unblock();
		});
    });
}

function eliminar_usuario(idusuario) {
	var light = $('#content_lista_usuarios');
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
        text: "¿Estás seguro que deseas eliminar este usuario?",
        type: "warning",
        showCancelButton: true,   
        confirmButtonColor: "#3f51b5",       
        cancelButtonColor: "#aaaaaa",
        confirmButtonText: "Si, Adelante!",
        closeOnConfirm: false
    },function(isconfirmed){
        if(isconfirmed) {
            var confirmado = 'si';
            $.ajax({
				url : '/facturacionv8/gestionuser/eliminar_usuario',
				method :  'POST',
				data: {idusuario: idusuario},
				dataType : "json"
			}).then(function(data){
				if(data.respuesta == 'ok') {
					swal({   
						title: data.titulo,   
						text: data.mensaje,
						html: true,
						type: "success", 
						confirmButtonColor: "#3F51B5",   
						confirmButtonText: "Ok",
					}, function() {
						$(light).unblock();
						get_lista_usuarios();
					});
				} else {
					swal({
						title: 'Error',
						text: data.mensaje,
						html: true,
						type: "error",
						confirmButtonText: "Ok",
						confirmButtonColor: "#3F51B5"
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
					confirmButtonColor: "#3F51B5"
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

function guardar_usuario(){
	var light = $("#content_panel_usuario");

    $(light).block({
		message: '<div class="loading"> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
					</div> <p> <br />Guardando ...</p>',
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
	
	var datastring = $("#frm_gestionuser").serializeArray();
	datastring.push({ name: "opt_registro_documentos2", value: $("#opt_registro_documentos").val() });
	datastring.push({ name: "opt_verreportes_reportes2", value: $("#opt_verreportes_reportes").val() });

	$.ajax({
        url : "/facturacionv8/gestionuser/insert",
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
                confirmButtonColor: "#3F51B5",   
                confirmButtonText: "Ok",
            }, function() {
				$(light).unblock();
				$("#frm_gestionuser")[0].reset();
				$("#idusuario").val('');
				get_lista_usuarios();
            });
        } else {
            swal({   
                title:'Error',   
                text: data.mensaje,
                html: true,
                type: "error", 
                confirmButtonColor: "#3F51B5",   
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
			confirmButtonColor: "#3F51B5",   
			confirmButtonText: "Ok"
		}, function() {
			$(light).unblock();
		});
    });
}

function get_lista_usuarios() {
	var light = $('#content_lista_usuarios');
	$(light).block({
		message: '<div class="loading"> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
					</div> <p> <br />Generando Reporte, Espera un Momento! ...</p>',
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

	dataTable_lista_usuarios = $('#tbl_lista_usuarios').DataTable( {
        "processing": true,
        "dom": 'Blfrtip',
		"serverSide": true,
		"ajax":{
			url :"/facturacionv8/gestionuser/get_lista_usuarios", // json datasource
			type: "post",
			error: function(){
				$(".tbl_lista_usuarios-error").html("");
				$("#tbl_lista_usuarios").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
				$("#tbl_lista_usuarios_processing").css("display","none");
			}
        },
        "bDestroy": true,
        "lengthMenu": [ [10, 25, 50, 100, 300], [10, 25, 50, 100, 300] ],
		buttons: [
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                },
                text: '<i class="icon-file-excel position-left"></i> Exp. Vista</span>',
                className: 'btn btn-success'
            },
            {
                extend: 'colvis',
                text: '<i class="icon-three-bars"></i> <span class="caret"></span>',
                className: 'btn bg-indigo btn-icon',
                collectionLayout: 'fixed two-column'
            }
        ],
        "columns": [
            { "data": "idusuario", 	"visible": true},
			{ "data": "codigo", 	"visible": false},
			{ "data": "rol", 		"visible": false},
			{ "data": "usuario", 	"visible": true},
			{ "data": "celular", 	"visible": false},
			{ "data": "telefono", 	"visible": false},
			{ "data": "email", 		"visible": false},
			{ "data": "fecha_registro", "visible": true},
			{ "data": "estado", 	"visible": true},
			{ "data": "opciones", 	"visible": true}
          ],
        stateSave: false,
        initComplete: function(){
            $(light).unblock();
        }
    });
}