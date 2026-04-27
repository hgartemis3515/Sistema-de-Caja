$(function() {
    //Inicio de librerias
    $('.select2').select2();
    //Botones a funciones
    $(".btn_save_empresatransporte").click(save);
    $(".btn_guardar_conductor").click(agregar_conductor);
    $(".btn_guardar_vehiculo").click(agregar_item_tabla_vehiculo);

    //Para buscar ruc
    $(".search_document").click(function() {
        consultar_numero_doc_cliente($("#id_cliente_documento"), $("#cliente_numerodocumento").val(), $("#cliente_nombre"), $("#cliente_direccion"), $("#select_codigoubigeo"), 6, $("#cliente_email"));
    });
    
    $(".search_document_dni").click(function() {
        if($("#type_document").val() == 1) {
            consultar_numero_doc_cliente('', $("#numero_doc_conductor").val(), $("#nombre_completo"), '', '', 1, '');
        }
	});

    //CRUD DE LA TABLA CONDUCTORES
    $(".btn_agregar_conductor").click(function() {
            $("#type_document").val(1).trigger("change").trigger("select2:select");;
            $("#tipo_licencia").val(0).trigger("change").trigger("select2:select");;
            $("#key_row").val("");
            $('#id_conductor_temporal').val(parseInt($('#id_conductor_temporal').val()) + 1); //Input temporal para id_conductor
            $("#frm_agregar_conductor")[0].reset();
            $("#vm_modal_conductores").modal("show");
    });

    $(".btn_editar_conductor").click(function() {
        $("#frm_agregar_conductor")[0].reset();
        var rowid = jQuery("#lista_conductores").jqGrid('getGridParam', 'selrow');
        if(rowid === undefined || rowid == '' || rowid <= 0) {
            swal({   
                title:'Error',   
                text: 'Por favor, selecciona un elemento para  poder editarlo',
                html: true,
                type: "error", 
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Ok",
            }, function() {
                return false
            });
        } else {
            editar_item_tabla_conductores(rowid);
            $("#vm_modal_conductores").modal("show");
        }
    });
    $(".btn_eliminar_conductor").click(eliminar_item_tabla_conductores);
    // END CRUD

    //CRUD DE LA TABLA VEHICULOS
    $(".btn_agregar_vehiculo").click(function() {
        $("#key_row_vehiculo").val("");
        $('#id_vehiculo_temporal').val(parseInt($('#id_vehiculo_temporal').val()) + 1); //Input temporal para id_vehiculo
        $("#frm_agregar_vehiculo")[0].reset();
        $("#vm_modal_vehiculo").modal("show");
    });

    $(".btn_editar_vehiculo").click(function() {
        $("#frm_agregar_vehiculo")[0].reset();
        var rowid = jQuery("#lista_vehiculos").jqGrid('getGridParam', 'selrow');
        if(rowid === undefined || rowid == '' || rowid <= 0) {
            swal({   
                title:'Error',   
                text: 'Por favor, selecciona un elemento para  poder editarlo',
                html: true,
                type: "error", 
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Ok",
            }, function() {
                return false
            });
        } else {
            editar_item_tabla_vehiculo(rowid);
            $("#vm_modal_vehiculo").modal("show");
        }
    });
    $(".btn_eliminar_vehiculo").click(eliminar_item_tabla_vehiculo);

    //Inicios de funciones
    get_lista_empresa(); 
    inicializar_tabla_conductor();
    inicializar_tabla_vehiculo();
    //Obteniendo listas de select
    get_lista_ubigeos($("#select_codigoubigeo"));
    
});

function consultar_numero_doc_cliente(input_idusuario, num_doc, input_nombre, input_direccion, input_ubigeo, tipo_doc, input_email) {
    $("#icon_search_document").hide();
    $("#icon_searching_document").show();
    $(".search_document").prop('disabled', true);
    
    $.ajax({
        url : '/facturacionv8/herramientas/get_data_cliente',
        data: {tipo_doc: tipo_doc, num_doc: num_doc},
        method :  'POST',
        dataType : "json"
    }).then(function(data){
        if(data.respuesta == 'ok') {
            if(tipo_doc == 1) { //DNI
                if(data.encontrado == true) {
                    if(data.api == true) {
                        input_nombre.val(data.data.nombre);
                    } else {
                        input_nombre.val(data.data.razon_social);
                    }
                }
            } else if(tipo_doc == 6) { //RUC
                input_idusuario.val('');
                if(data.encontrado == true) {
                    if(data.api == true) {
                        input_nombre.val(data.data.razon_social);
                        input_direccion.val(data.data.direccion);
                        if(typeof data.data.codigo_ubigeo !== 'undefined' && data.data.codigo_ubigeo != '') {
                            input_ubigeo.val(data.data.codigo_ubigeo).trigger("change").trigger("select2:select");
                        }
                    } else {
                        input_idusuario.val(data.data.idcliente);
                        input_nombre.val(data.data.razon_social);
                        input_direccion.val(data.data.direccion_fiscal);
                        input_email.val(data.data.email);
                        input_ubigeo.val(data.data.id_cod_ubigeo).trigger("change").trigger("select2:select");
                    }

                    if(typeof data.data.estado !== 'undefined' && data.data.estado != '') {
                        $("#estado_numerodocumento").removeClass();
                        $("#razonsocial_numerodocumento").removeClass();
                        $("#titulo_numerodocumento").html("N° de R.U.C.");
                        $("#titulo_nombrecliente").html("Razón Social");
                        
                        if(data.data.estado == 'ACTIVO' || data.data.estado == 'activo') {
                            $("#estado_numerodocumento").addClass("form-group col-md-6 text-success");
                            $("#razonsocial_numerodocumento").addClass("form-group col-md-6 text-success");
     
                            $("#titulo_numerodocumento").html($("#titulo_numerodocumento").html() + " (ESTADO: " + data.data.estado + ")");
                            $("#titulo_nombrecliente").html($("#titulo_nombrecliente").html() + " (ESTADO: " + data.data.estado + ")");
                        } else {
                            $("#estado_numerodocumento").addClass("form-group col-md-6 text-danger");
                            $("#razonsocial_numerodocumento").addClass("form-group col-md-6 text-danger");
    
                            $("#titulo_numerodocumento").html($("#titulo_numerodocumento").html() + " (ESTADO: " + data.data.estado + ")");
                            $("#titulo_nombrecliente").html($("#titulo_nombrecliente").html() + " (ESTADO: " + data.data.estado + ")");
                        }
                    } else {
                        $("#estado_numerodocumento").removeClass();
                        $("#razonsocial_numerodocumento").removeClass();
                        $("#estado_numerodocumento").addClass("form-group col-md-6");
                        $("#razonsocial_numerodocumento").addClass("form-group col-md-6");
                    }
                }
            }
            
            $(".icon_search_document").show();
            $(".icon_searching_document").hide();
            $(".search_document").prop('disabled', false);
        } else {
            swal({
                title: 'ERROR',
                text: data.mensaje,
                html: true,
                type: "error",
                confirmButtonText: "Ok",
                confirmButtonColor: "#2196F3"
            }, function(){
                $(".icon_search_document").show();
                $(".icon_searching_document").hide();
                $(".search_document").prop('disabled', false);
            });
        }
    }, function(reason){
        swal({
            title: 'ERROR',
            text: 'Error al conectarse a la SUNAT, recarga la página e inténtalo nuevamente!',
            html: true,
            type: "error",
            confirmButtonText: "Ok",
            confirmButtonColor: "#2196F3"
        }, function(){
            $(".icon_search_document").show();
            $(".icon_searching_document").hide();
            $(".search_document").prop('disabled', false);
        });
    });
}

function save(){
    //Para tomar la lista de conductores
    var gridData_conductor = jQuery("#lista_conductores").getRowData();
    var postData_conductor = JSON.stringify(gridData_conductor);
    //Para tomar la lista de vehiculos
    var gridData_vehiculo = jQuery("#lista_vehiculos").getRowData();
    var postData_vehiculo = JSON.stringify(gridData_vehiculo);
    //Variables del formulario completo
    var datastring = $("#frm_empresa_transporte").serializeArray();
    datastring.push({ name: "list_conductores", value: postData_conductor });
    datastring.push({ name: "lista_vehiculos", value: postData_vehiculo });
    $.ajax({
        url : '/facturacionv8/empresasdetransporte/save',
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
                get_lista_empresa();
                $("#frm_empresa_transporte")[0].reset();
                $("#select_codigoubigeo").select2("val", 0);
                $("#id_etransporte").val('');  
                $("#id_conductor_temporal").val('');  
                //Para limpiar lista de conductores
                $('#lista_conductores').jqGrid('clearGridData')
                $('#lista_conductores').jqGrid('setGridParam', {data: data, page: 1})
                $('#lista_conductores').trigger('reloadGrid');
                //Para limpiar lista de vehiculos
                $('#lista_vehiculos').jqGrid('clearGridData')
                $('#lista_vehiculos').jqGrid('setGridParam', {data: data, page: 1})
                $('#lista_vehiculos').trigger('reloadGrid');
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
function get_lista_empresa(){
    var light = $("#contenido_lista_empresa");

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
        url : '/facturacionv8/empresasdetransporte/get_lista_empresa',
        method :  'POST',
        dataType : "json"
    }).then(function(data){
            if(data.respuesta == 'ok') {
                $('#tbl_lista_empresa').DataTable({
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

function edit_empresa(idempresa){
	var light = $("#contenido_lista_empresa");

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
        url : '/facturacionv8/empresasdetransporte/get_data_empresa',
        method :  'POST',
        data: {idempresa: idempresa},
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
            //Para limpiar lista de conductores en cada nueva busqueda
            $('#lista_conductores').jqGrid('clearGridData')
            $('#lista_conductores').jqGrid('setGridParam', {data: data, page: 1})
            $('#lista_conductores').trigger('reloadGrid'); //aparentemente hace que recargue dos veces el index!
            //Para limpiar lista de vehiculos  en cada nueva busqueda
            $('#lista_vehiculos').jqGrid('clearGridData')
            $('#lista_vehiculos').jqGrid('setGridParam', {data: data, page: 1})
            $('#lista_vehiculos').trigger('reloadGrid'); //aparentemente hace que recargue dos veces el index!
            //Variables 
            $("#id_etransporte").val(data.etransporte.id_etransporte);
			$("#cliente_numerodocumento").val(data.etransporte.num_doc);
            $("#cliente_nombre").val(data.etransporte.razon_social);
            $("#select_codigoubigeo").val(data.etransporte.codigo_ubigeo).trigger("change").trigger("select2:select");
			$("#nombre_titular").val(data.etransporte.nombre_titular);
			$("#cliente_direccion").val(data.etransporte.direccion);
			$("#cliente_telefono").val(data.etransporte.telefono);
            $("#detalle_adicional").val(data.etransporte.nota);
            data.list_conductor.forEach( function(data_item_conductor, indice_conductor, array) {
				jQuery('#lista_conductores').addRowData(indice_conductor + 1, data_item_conductor, 'last');
            });
            data.list_vehiculo.forEach( function(data_item_vehiculo, indice_vehiculo, array) {
				jQuery('#lista_vehiculos').addRowData(indice_vehiculo + 1, data_item_vehiculo, 'last');
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
		});

    });
}
function eliminar_empresa(idempresa){
	var light = $("#contenido_lista_empresa");

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
    swal({
        title: "Confirmación!",
        text: "¿Estás seguro que deseas eliminar esta empresa? Al hacerlo, también se eliminarán todos sus conductores y vehículos",
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
                url : '/facturacionv8/empresasdetransporte/eliminar_empresa',
                data: {idempresa: idempresa},
                method :  'POST',
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
                        get_lista_empresa();
                    });
                } else {
                    swal({   
                        title:'Error',   
                        text: data.mensaje,
                        html: true,
                        type: "error", 
                        confirmButtonColor: "#00BCD4",   
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
                    confirmButtonColor: "#00BCD4",   
                    confirmButtonText: "Ok"
                }, function() {
                    $(light).unblock();
                });
            });
        }else{
            $(light).unblock();
            return;
        }
      
    });

}
/*----- Funciones de tabla agregar_conductores --------*/

function inicializar_tabla_conductor() {
	$.jgrid.no_legacy_api = true;
	$.jgrid.useJSON = true;

	lista_conductores = $('#lista_conductores');

	lista_conductores = lista_conductores.jqGrid({
        url: '',
        datatype: 'json',
        mtype: 'POST',
        colNames: [
            'id_conductor_temporal',
            'idconductor',
            'id_tipodocidentidad',
            'Tipo de documento',
            'Nro de documento',
            'Nombre completo',
            'Licencia de conducir',
            'Tipo de licencia',
            'Teléfono'
        ],
        colModel: [
            { name: 'id_conductor_temporal', index: '1', hidden: true },
            { name: 'idconductor', index: '1', hidden: true },
            { name: 'id_tipodocidentidad', index: '1',  hidden: true },
            { name: 'tipo_doc', index: '2', width: 160, },
            { name: 'numero_doc_conductor', index: '3', width: 200, },
            { name: 'nombre_completo', index: '4', width: 200, },
            { name: 'licencia', index: '5', width: 200 },
            { name: 'tipo_licencia', index: '6', width: 130 },
            { name: 'telefono', index: '7', width: 100, align: "right", sorttype: 'float' },
        ],
        height: 250,
        rowNum: 0,
        loadOnce: true,
        viewrecords: true,
        gridview: true,
        autowidth:true, 
        shrinkToFit:false,
        forceFit:true,
        cellEdit: true,
        sortname: 'codigo',
        cellsubmit: 'clientArray',
        editurl: 'clientArray',
        beforeRequest: function () {
            responsive_table($(".jqGrid"));
        },
        jsonReader: {
            repeatitems: false,
            root: 'lstLista'
        },
        gridComplete: function () {
            //SE EJECUTA CUANDO AGREGAS NUEVO REGISTRO A LA GRILLA
        },
        afterSaveCell: function (rowid, name, val, iRow, iCol) {
            //SE EJECUTA CUANDO MODIFICAMOS UN REGISTRO

        }
    });
}
function agregar_conductor() {
    var id_conductor_temporal =  $('#id_conductor_temporal').val(); //id temporal para cuando no tienen asignado
    var idconductor =  $('#id_conductor').val();
    var id_tipodocidentidad = parseInt($('#type_document').val()) + 0;
    var tipo_doc = $('#type_document').select2().find(":selected").text().split(' ');
    var numero_doc_conductor = $('#numero_doc_conductor').val();
    var nombre_completo = $('#nombre_completo').val();
    var licencia = $('#licencia_conducir').val();
    var tipo_licencia = $('#tipo_licencia').select2().find(":selected").text().split(' ');
    var telefono = $('#telefono').val();

    var data = {
        id_conductor_temporal: id_conductor_temporal,
        idconductor: idconductor,
        id_tipodocidentidad: id_tipodocidentidad,
        tipo_doc: tipo_doc[0],
        numero_doc_conductor: numero_doc_conductor,
        nombre_completo: nombre_completo,
        licencia: licencia, 
        tipo_licencia: tipo_licencia,
        telefono: telefono
    };
   
   var key_row = parseInt($("#key_row").val()) + 0;
   if(key_row > 0) {
        var su = jQuery('#lista_conductores').jqGrid('setRowData', key_row, data);
        $("#vm_modal_conductores").modal("hide");
    }  else if(id_conductor_temporal != '' ){
        var su = jQuery('#lista_conductores').addRowData(id_conductor_temporal, data, 'last');
        $("#vm_modal_conductores").modal("hide");
    } else{
        var su = jQuery('#lista_conductores').addRowData(idconductor, data, 'last');
        $("#vm_modal_conductores").modal("hide");
    }
}
function editar_item_tabla_conductores(rowid) {
    if(rowid === undefined || rowid == '' || rowid <= 0) {
        return false;
    }
    $("#key_row").val(rowid);
    var data = jQuery("#lista_conductores").jqGrid('getRowData', rowid);

    $("#key_row").val(rowid);
    $('#id_conductor_temporal').val(data.id_conductor_temporal);
    $('#idconductor').val(data.idconductor);
    $('#type_document').val(data.id_tipodocidentidad).trigger("change").trigger("select2:select");
    $('#numero_doc_conductor').val(data.numero_doc_conductor);
    $('#nombre_completo').val(data.nombre_completo);
    $('#licencia_conducir').val(data.licencia);
    $('#tipo_licencia').val(data.tipo_licencia);
    $('#telefono').val(data.telefono);

}
function eliminar_item_tabla_conductores() {

    var rowid = jQuery('#lista_conductores').jqGrid('getGridParam', 'selrow');
    var $grid = jQuery("#lista_conductores");

    //Tomar id de conductor ya existente en la tabla
    var data = jQuery("#lista_conductores").jqGrid('getRowData', rowid);
    $('#idconductor').val(data.idconductor);
    var idconductor =  data.idconductor;
   
    if(rowid == null) {
        swal({   
            title:'Error',   
            text: 'Debes seleccionar un conductor para poder eliminarlo!!',
            html: true,
            type: "error", 
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Ok",
        }, function() {
            
        });
    }  else if(idconductor != ''){
        $.ajax({
        url : '/facturacionv8/empresasdetransporte/eliminar_conductor',
        method :  'POST',
        data: {idconductor: idconductor},
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
            $grid.jqGrid('delRowData', rowid);
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
    else {
        $grid.jqGrid('delRowData', rowid);
        $('#id_conductor_temporal').val(parseInt($('#id_conductor_temporal').val()) - 1); //Input temporal para id_conductor
 
    }
}

/*----- / Funciones de tabla agregar_conductores --------*/

/*----- Funciones de tabla agregar_vehiculos --------*/
function inicializar_tabla_vehiculo() {
	$.jgrid.no_legacy_api = true;
	$.jgrid.useJSON = true;

	lista_vehiculos = $('#lista_vehiculos');

	lista_vehiculos = lista_vehiculos.jqGrid({
        url: '',
        datatype: 'json',
        mtype: 'POST',
        colNames: [
            'id_vehiculo_temporal',
            'idvehiculo',
            'Número de placa',
            'Marca',
            'Const. Inscripción',
            'Capacidad',
            'DGH'
        ],
        colModel: [
            { name: 'id_vehiculo_temporal', index: '1', hidden: true },
            { name: 'idvehiculo', index: '1', hidden: true },
            { name: 'vh_numero_placa', index: '2', width: 230, },
            { name: 'vh_marca', index: '3', width: 230, },
            { name: 'vh_const_inscripcion', index: '4', width: 210, },
            { name: 'vh_capacidad', index: '5', width: 210 },
            { name: 'vh_dgh', index: '6', width: 120, align: "right", sorttype: 'float' },
        ],
        height: 250,
        rowNum: 0,
        loadOnce: true,
        viewrecords: true,
        gridview: true,
        autowidth:true, 
        shrinkToFit:false,
        forceFit:true,
        cellEdit: true,
        sortname: 'codigo',
        cellsubmit: 'clientArray',
        editurl: 'clientArray',
        beforeRequest: function () {
            responsive_table($(".jqGrid"));
        },
        jsonReader: {
            repeatitems: false,
            root: 'lstLista'
        },
        gridComplete: function () {
            //SE EJECUTA CUANDO AGREGAS NUEVO REGISTRO A LA GRILLA
        },
        afterSaveCell: function (rowid, name, val, iRow, iCol) {
            //SE EJECUTA CUANDO MODIFICAMOS UN REGISTRO

        }
    });
}
function agregar_item_tabla_vehiculo() {
    var id_vehiculo_temporal =  $('#id_vehiculo_temporal').val(); //id temporal para cuando no tienen asignado
    var idvehiculo =  $('#idvehiculo').val();
    var vh_numero_placa = $('#vh_numero_placa').val();
    var vh_marca = $('#vh_marca').val();
    var vh_const_inscripcion = $('#vh_const_inscripcion').val();
    var vh_capacidad = $('#vh_capacidad').val();
    var vh_dgh = $('#vh_dgh').val();

    var data = {
        id_vehiculo_temporal: id_vehiculo_temporal,
        idvehiculo: idvehiculo,
        vh_numero_placa: vh_numero_placa,
        vh_marca: vh_marca,
        vh_const_inscripcion: vh_const_inscripcion,
        vh_capacidad: vh_capacidad,
        vh_dgh: vh_dgh
    };
   
   var key_row_vehiculo = parseInt($("#key_row_vehiculo").val()) + 0;
   if(key_row_vehiculo > 0) {
        var su = jQuery('#lista_vehiculos').jqGrid('setRowData', key_row_vehiculo, data);
        $("#vm_modal_vehiculo").modal("hide");
    }  else if(id_vehiculo_temporal != undefined ){
        var su = jQuery('#lista_vehiculos').addRowData(id_vehiculo_temporal, data, 'last');
        $("#vm_modal_vehiculo").modal("hide");
    } else{
        var su = jQuery('#lista_vehiculos').addRowData(idvehiculo, data, 'last');
        $("#vm_modal_vehiculo").modal("hide");
    }
}
function editar_item_tabla_vehiculo(rowid) {
    if(rowid === undefined || rowid == '' || rowid <= 0) {
        return false;
    }
    $("#key_row_vehiculo").val(rowid);
    var data = jQuery("#lista_vehiculos").jqGrid('getRowData', rowid);

    $("#key_row_vehiculo").val(rowid);
    $('#id_vehiculo_temporal').val(data.id_vehiculo_temporal); //id temporal para cuando no tienen asignado
    $('#idvehiculo').val(data.idvehiculo);
    $('#vh_numero_placa').val(data.vh_numero_placa);
    $('#vh_marca').val(data.vh_marca);
    $('#vh_const_inscripcion').val(data.vh_const_inscripcion);
    $('#vh_capacidad').val(data.vh_capacidad);
    $('#vh_dgh').val(data.vh_dgh);

}
function eliminar_item_tabla_vehiculo() {

    var rowid_vehiculo = jQuery('#lista_vehiculos').jqGrid('getGridParam', 'selrow');
    var $grid = jQuery("#lista_vehiculos");

    //Tomar id de vehículo ya existente en la tabla
    var data = jQuery("#lista_vehiculos").jqGrid('getRowData', rowid_vehiculo);
    $('#idvehiculo').val(data.idvehiculo);
    var idvehiculo =  data.idvehiculo;
    if(rowid_vehiculo == null) {
        swal({   
            title:'Error',   
            text: 'Debes seleccionar un vehículo para poder eliminarlo!',
            html: true,
            type: "error", 
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Ok",
        }, function() {
            
        });
     } else if(idvehiculo != ''){
            $.ajax({
            url : '/facturacionv8/empresasdetransporte/eliminar_vehiculo',
            method :  'POST',
            data: {idvehiculo: idvehiculo},
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
                $grid.jqGrid('delRowData', rowid_vehiculo);
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
    
        } else {
        $grid.jqGrid('delRowData', rowid_vehiculo);
        $('#id_vehiculo_temporal').val(parseInt($('#id_vehiculo_temporal').val()) - 1); //Input temporal para id_conductor
    }
}
/*----- /Funciones de tabla agregar_vehiculos --------*/

function responsive_table(table) {
    table.find('.ui-jqgrid').addClass('clear-margin span12').css('width', '');
    table.find('.ui-jqgrid-view').addClass('clear-margin span12').css('width', '');
    table.find('.ui-jqgrid-view > div').eq(1).addClass('clear-margin span12').css('width', '').css('min-height', '0');
    table.find('.ui-jqgrid-view > div').eq(2).addClass('clear-margin span12').css('width', '').css('min-height', '0');
    table.find('.ui-jqgrid-sdiv').addClass('clear-margin span12').css('width', '');
    table.find('.ui-jqgrid-pager').addClass('clear-margin span12').css('width', '');
}
