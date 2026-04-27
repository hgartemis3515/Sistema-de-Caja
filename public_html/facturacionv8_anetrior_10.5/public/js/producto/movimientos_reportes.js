var dataTable_lista_ingresos;
var dataTable_lista_salidas;
var dataTable_lista_traslados;

$(function() {
    $(".rep_in_sa_id_sucursal").select2();
    $(".rep_in_sa_idproducto").select2();
    $(".rep_traslados_id_sucursal_o").select2();
    $(".rep_traslados_id_sucursal_d").select2();
    
	$(".select2_minimizado").select2({
        minimumResultsForSearch: -1
	});

    $("#btn_ingresos_reporte").on('click', function() {
        $("#vm_reporte_in_sa").modal({
			backdrop: 'static',
			keyboard: false
		});

		$("#opt_tab_ingresos").trigger('click');
		$("#tab_ingresos_salidas_content").hide();
    });

	$("#btn_salidas_reporte").on('click', function() {
        $("#vm_reporte_in_sa").modal({
			backdrop: 'static',
			keyboard: false
		});

		$("#opt_tab_salidas").trigger('click');
		$("#tab_ingresos_salidas_content").hide();
    });

    $("#btn_traslados_reporte").on('click', function() {
        $("#vm_reporte_traslados").modal({
			backdrop: 'static',
			keyboard: false
		});

        $("#tab_traslados_content").hide();
    });

    iniciliazar_buscador_producto();

    $("#btn_extraer_salidas_entradas").on('click', get_lista_ingresos_salidas);
    $("#btn_extraer_traslados").on('click', get_lista_traslados);

	$(".control_fecha_in_sa").daterangepicker({
        singleDatePicker: true, 
        drops: 'down', 
        locale: { format: 'DD/MM/YYYY' }
    });

    $(".control_fecha_traslados").daterangepicker({
        singleDatePicker: true, 
        drops: 'down', 
        locale: { format: 'DD/MM/YYYY' }
    });

    /*$(".control_traslado").on('change', function() {
        $("#tab_traslados_content").hide();
    });*/

    $(".control_in_sa").on('change', function() {
        $("#tab_ingresos_salidas_content").hide();
    });

    $("#rep_in_sa_id_sucursal").on('change', function() {
        $("#tab_ingresos_salidas_content").hide();
        $("#rep_in_sa_idproducto").empty();
    });
});

function get_lista_ingresos_salidas() {
    var light = $('#body_vm_reporte_in_sa');
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

    var id_sucursal = $("#rep_in_sa_id_sucursal").val();
    var id_producto = $("#rep_in_sa_idproducto").val();
    var fecha_inicio = $("#rep_in_sa_fecha_inicio").val();
    var fecha_fin = $("#rep_in_sa_fecha_fin").val();
	
	dataTable_lista_ingresos = $('#tbl_lista_ingresos').DataTable( {
        "processing": true,
        "dom": 'Blfrtip',
		"serverSide": true,
		"ajax":{
			url :"/facturacionv8/productomovimientos/get_lista_movimientos", // json datasource
			data: {id_sucursal: id_sucursal, id_producto: id_producto, fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, tipo_movimiento: 'ingreso'},
			type: "post",
			error: function(){
				$(".tbl_lista_ingresos-error").html("");
				$("#tbl_lista_ingresos").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
				$("#tbl_lista_ingresos_processing").css("display","none");
				
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
            { "data": "serie_correlativo", "visible": true},
            { "data": "mov_tipo", "visible": false },
            { "data": "mov_id_sucursal", "visible": false },
            { "data": "mov_fecha_movimiento", "visible": true },
            { "data": "mov_nota", "visible": true },
            { "data": "det_o_id_prod", "visible": false },
            { "data": "det_o_cod_prod", "visible": true },
            { "data": "det_o_nom_prod", "visible": true },
            { "data": "det_cantidad", "visible": true },
            { "data": "opciones", "visible": true },
          ],
        stateSave: false,
        initComplete: function(){
            /*
            var $searchInput = $('div.dataTables_filter input');
            $searchInput.unbind();
            $searchInput.bind('keyup', function(e) {
                window.clearTimeout(dataTableFilterTimeout);
                var valor_busqueda = this.value;
                //if(valor_busqueda.length > 3) {
                    dataTableFilterTimeout = setTimeout(function(){
                        dataTable_lista_ingresos.search( valor_busqueda ).draw();
                    }, dataTableFilterWait);
                //}
            });
            */
            $(light).unblock();
        }
    });

	dataTable_lista_salidas = $('#tbl_lista_salidas').DataTable( {
        "processing": true,
        "dom": 'Blfrtip',
		"serverSide": true,
		"ajax":{
			url :"/facturacionv8/productomovimientos/get_lista_movimientos", // json datasource
			data: {id_sucursal: id_sucursal, id_producto: id_producto, fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, tipo_movimiento: 'salida'},
			type: "post",
			error: function(){
				$(".tbl_lista_salidas-error").html("");
				$("#tbl_lista_salidas").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
				$("#tbl_lista_salidas_processing").css("display","none");
				
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
            { "data": "serie_correlativo", "visible": true},
            { "data": "mov_tipo", "visible": false },
            { "data": "mov_id_sucursal", "visible": false },
            { "data": "mov_fecha_movimiento", "visible": true },
            { "data": "mov_nota", "visible": true },
            { "data": "det_o_id_prod", "visible": false },
            { "data": "det_o_cod_prod", "visible": true },
            { "data": "det_o_nom_prod", "visible": true },
            { "data": "det_cantidad", "visible": true },
            { "data": "opciones", "visible": true },
          ],
        stateSave: false,
        initComplete: function(){
            /*
            var $searchInput = $('div.dataTables_filter input');
            $searchInput.unbind();
            $searchInput.bind('keyup', function(e) {
                window.clearTimeout(dataTableFilterTimeout);
                var valor_busqueda = this.value;
                //if(valor_busqueda.length > 3) {
                    dataTableFilterTimeout = setTimeout(function(){
                        dataTable_lista_salidas.search( valor_busqueda ).draw();
                    }, dataTableFilterWait);
                //}
            });
            */
            $(light).unblock();
        }
    });

	$("#tab_ingresos_salidas_content").show();
}

function get_lista_traslados() {
    var light = $('#body_vm_reporte_traslados');
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

    var id_sucursal = $("#rep_traslados_id_sucursal_o").val();
    var id_sucursal_destino = $("#rep_traslados_id_sucursal_d").val();
    var fecha_inicio = $("#rep_traslados_fecha_inicio").val();
    var fecha_fin = $("#rep_traslados_fecha_fin").val();
	
	dataTable_lista_traslados = $('#tbl_lista_traslados').DataTable( {
        "processing": true,
        "dom": 'Blfrtip',
		"serverSide": true,
		"ajax":{
			url :"/facturacionv8/productomovimientos/get_lista_movimientos", // json datasource
			data: {id_sucursal: id_sucursal, id_sucursal_destino: id_sucursal_destino, fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, tipo_movimiento: 'traslado'},
			type: "post",
			error: function(){
				$(".tbl_lista_ingresos-error").html("");
				$("#tbl_lista_ingresos").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
				$("#tbl_lista_ingresos_processing").css("display","none");
				
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
            { "data": "serie_correlativo", "visible": true, className: "bg-primary-400 text-left"},
            { "data": "mov_fecha_movimiento", "visible": true, className: "bg-primary-400 text-left"},
            { "data": "usuario", "visible": false, className: "bg-primary-400 text-left"},
            { "data": "mov_nota", "visible": false, className: "bg-primary-400 text-left"},

            { "data": "info_sucursal_origen", "visible": false},
            { "data": "det_o_id_prod", "visible": false},
            { "data": "det_o_cod_prod", "visible": true},
            { "data": "det_o_nom_prod", "visible": true},

            { "data": "det_cantidad", "visible": true, className: "bg-primary-400 text-center"},
            
            { "data": "info_sucursal_destino", "visible": false},
            { "data": "det_d_id_prod", "visible": false},
            { "data": "det_d_cod_prod", "visible": true},
            { "data": "det_d_nom_prod", "visible": true},

            { "data": "opciones", "visible": true, className: "bg-primary-400 text-center"}
          ],
        stateSave: false,
        initComplete: function(){
            /*
            var $searchInput = $('div.dataTables_filter input');
            $searchInput.unbind();
            $searchInput.bind('keyup', function(e) {
                window.clearTimeout(dataTableFilterTimeout);
                var valor_busqueda = this.value;
                //if(valor_busqueda.length > 3) {
                    dataTableFilterTimeout = setTimeout(function(){
                        dataTable_lista_traslados.search( valor_busqueda ).draw();
                    }, dataTableFilterWait);
                //}
            });
            */
            $(light).unblock();
            $("#tab_traslados_content").show();
        }
    });
}

function iniciliazar_buscador_producto() {
    $("#rep_in_sa_idproducto").select2({
        language: "es",
        dropdownParent: $('#vm_reporte_in_sa'),
        minimumResultsForSearch: Infinity,
        ajax: {
          type: "post",
          url: "/facturacionv8/herramientas/get_sugerencias_producto",
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
                q: params.term, // search term
                page: params.page,
                idsucursal: $("#rep_in_sa_id_sucursal").val()
            };
          },
          processResults: function (data, params) {
            // parse the results into the format expected by Select2
            // since we are using custom formatting functions we do not need to
            // alter the remote JSON data, except to indicate that infinite
            // scrolling can be used
            params.page = params.page || 1;
  
            return {
              results: data.items,
              pagination: {
                more: (params.page * 30) < data.total_count
              }
            };
          },
          cache: true
        },
        escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
        minimumInputLength: 1,
        templateResult: formatRepo,
        templateSelection: formatRepoSelection
    });
}

function formatRepo (data) {
    var $container;

    if (data.loading) {
        return data.text;
    }

    if(data.imagen == '') {
        $container = get_html_item_busqueda();
    } else { 
        try {
            var imagenes_producto = jQuery.parseJSON(data.imagen);
            if(imagenes_producto.cuadradas.length > 0) {
                $.each(imagenes_producto.cuadradas, function( index, url_imagen ) {
                    //console.log(url_imagen);
                    $container = get_html_item_busqueda(url_imagen);
                    return false;
                });
            } else if(imagenes_producto.verticales.length > 0) {
                $.each(imagenes_producto.verticales, function( index, url_imagen ) {
                    $container = get_html_item_busqueda(url_imagen);
                    return false;
                });
            } else if(imagenes_producto.horizontales.length > 0) {
                $.each(imagenes_producto.horizontales, function( index, url_imagen ) {
                    $container = get_html_item_busqueda(url_imagen);
                    return false;
                });
            } else {
                $container = get_html_item_busqueda(url_imagen);
            }
            
        } catch (e) {
            $container = get_html_item_busqueda();
        };
    }

    $container.find(".select2-result-repository__title").text(data.text);
    $container.find(".select2-result-repository__description").text('Código: ' + data.codigo);
    $container.find(".select2-result-repository__forks").append('Stock: ' + data.stock + ' ' + data.unidad);
    $container.find(".select2-result-repository__stargazers").append(data.precio);
    $container.find(".select2-result-repository__watchers").append('Cate: ' + data.categoria);

    return $container;
}

function get_html_item_busqueda(img = '') {
    if(img == '') {
        return $(
            "<div class='select2-result-repository clearfix'>" +
                "<div class='select2-result-repository__meta' style='margin-left: 1px !important;'>" +
                "<div class='select2-result-repository__title text-primary'></div>" +
                "<div class='select2-result-repository__description'></div>" +
                "<div class='select2-result-repository__statistics'>" +
                    "<div class='select2-result-repository__forks'><i class='icon-stairs-up'></i> </div>" +
                    "<div class='select2-result-repository__stargazers'><i class='icon-cash2'></i> </div>" +
                    "<div class='select2-result-repository__watchers'><i class='icon-users'></i> </div>" +
                "</div>" +
                "</div>" +
            "</div>"
            );
    } else {
        return $(
            "<div class='select2-result-repository clearfix'>" +
                "<div class='select2-result-repository__avatar'><img src='" + img + "' /></div>" +
                "<div class='select2-result-repository__meta'>" +
                "<div class='select2-result-repository__title text-primary'></div>" +
                "<div class='select2-result-repository__description'></div>" +
                "<div class='select2-result-repository__statistics'>" +
                    "<div class='select2-result-repository__forks'><i class='icon-stairs-up'></i> </div>" +
                    "<div class='select2-result-repository__stargazers'><i class='icon-cash2'></i> </div>" +
                    "<div class='select2-result-repository__watchers'><i class='icon-users'></i> </div>" +
                "</div>" +
                "</div>" +
            "</div>"
        );
    }
}

function formatRepoSelection (data) {
    return data.full_name || data.text;
}