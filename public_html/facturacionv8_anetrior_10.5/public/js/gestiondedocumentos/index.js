var dataTable_docs_sunat;
var dataTable_guiasremision;
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

	$("#rangofechas").daterangepicker({
		singleDatePicker: false,
		startDate: moment().subtract(60, 'days'),
		endDate:  moment(),
		minDate: moment().subtract(365, 'days'),
		maxDate: moment().add(2, 'years'),
		locale: {
			format: 'DD/MM/YYYY',
			daysOfWeek: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ],
			monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
			],
			monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul', 'Ago','Sep','Oct','Nov','Dic'],
			firstDay: 1
		}
	}, function(start, end) {
			$("#fechainicio").val(start.format('YYYY-MM-DD'));
			$("#fechafinal").val(end.format('YYYY-MM-DD'));
			let fecha_inicio = $("#fechainicio").val();
			let fecha_fin = $("#fechafinal").val();
			let id_contribuyente = $("#select_contribuyente").val();
			let estado_documento = $("#select_estado_comprobante").val();
			let tipo_documento = $("#select_tipo_comprobante").val();
			//get_lista_comprobantes(fecha_inicio, fecha_fin, id_contribuyente, estado_documento, tipo_documento);
		}
	);
	
	var switches = Array.prototype.slice.call(document.querySelectorAll('.switch2'));
    switches.forEach(function(html) {
        var switchery = new Switchery(html, {color: '#4CAF50'});
	});
	
	$(".opc_controles_avanzados").show("slide");

	//$("#btn_enviar_email").click(enviar_cpe_cliente);

	$(".btn_guardar_nuevo_ticket").click(function(){
		let id_contribuyente = $("#vestado_id_contribuyente").val();
		let id_tipodoc_electronico = $("#vestado_tipo_doc_electronico").val();
		let serie_comprobante = $("#vestado_serie_comprobante").val();
		let numero_comprobante = $("#vestado_numero_comprobante").val();
		let num_ticket = $("#txt_numero_ticket").val();
		let accion = 'actualizar_ticket';

		modificar_comprobante(id_contribuyente, id_tipodoc_electronico, serie_comprobante, numero_comprobante, accion, num_ticket);
	});

	$("#btn_resetear_documento").click(function(){
		let id_contribuyente = $("#vestado_id_contribuyente").val();
		let id_tipodoc_electronico = $("#vestado_tipo_doc_electronico").val();
		let serie_comprobante = $("#vestado_serie_comprobante").val();
		let numero_comprobante = $("#vestado_numero_comprobante").val();
		let accion = 'resetear';
		
		modificar_comprobante(id_contribuyente, id_tipodoc_electronico, serie_comprobante, numero_comprobante, accion);
	});

	$("#btn_recuperar_cdr").click(function(){
		let id_contribuyente = $("#vestado_id_contribuyente").val();
		let id_tipodoc_electronico = $("#vestado_tipo_doc_electronico").val();
		let serie_comprobante = $("#vestado_serie_comprobante").val();
		let numero_comprobante = $("#vestado_numero_comprobante").val();
		let accion = 'recover_cdr';
		
		modificar_comprobante(id_contribuyente, id_tipodoc_electronico, serie_comprobante, numero_comprobante, accion);
	});
	
	$("#btn_aprobar_manualmente").click(function(){
		let id_contribuyente = $("#vestado_id_contribuyente").val();
		let id_tipodoc_electronico = $("#vestado_tipo_doc_electronico").val();
		let serie_comprobante = $("#vestado_serie_comprobante").val();
		let numero_comprobante = $("#vestado_numero_comprobante").val();
		let accion = 'aprobar_manualmente';
		
		modificar_comprobante(id_contribuyente, id_tipodoc_electronico, serie_comprobante, numero_comprobante, accion);
	});

	$("#btn_anular_manualmente").click(function(){
		let id_contribuyente = $("#vestado_id_contribuyente").val();
		let id_tipodoc_electronico = $("#vestado_tipo_doc_electronico").val();
		let serie_comprobante = $("#vestado_serie_comprobante").val();
		let numero_comprobante = $("#vestado_numero_comprobante").val();
		let accion = 'anular_manualmente';
		
		modificar_comprobante(id_contribuyente, id_tipodoc_electronico, serie_comprobante, numero_comprobante, accion);
	});

	$("#select_contribuyente").select2();
	$("#select_contribuyente_excluir").select2();
	$('#select_estado_comprobante').select2({ minimumResultsForSearch: -1 });
	$('#select_tipo_comprobante').select2({ minimumResultsForSearch: -1 });

	$(".btn_extraer_cpe").click(function() {
		$('#content_barra_progreso .progress-bar').attr('data-transitiongoal', 0).progressbar({display_text: 'center', use_percentage: false});
		let fecha_inicio = $("#fechainicio").val();
		let fecha_fin = $("#fechafinal").val();
		let id_contribuyente = $("#select_contribuyente").val();
		let id_contribuyente_excluir = $("#select_contribuyente_excluir").val();
		let estado_documento = $("#select_estado_comprobante").val();
		let tipo_documento = $("#select_tipo_comprobante").val();
		get_lista_comprobantes(fecha_inicio, fecha_fin, id_contribuyente, estado_documento, tipo_documento, id_contribuyente_excluir);
	});
	
	$("#fechainicio").val(moment().subtract(60, 'days').format('YYYY-MM-DD'));
	$("#fechafinal").val(moment().format('YYYY-MM-DD'));
	let fecha_inicio = $("#fechainicio").val();
	let fecha_fin = $("#fechafinal").val();
	let estado_documento = $("#select_estado_comprobante").val();

	get_lista_comprobantes(fecha_inicio, fecha_fin, $("#select_contribuyente").val(), estado_documento, $("#select_tipo_comprobante").val());

	$(".vm_fcomprobante_fecha_comprobante").daterangepicker({
		singleDatePicker: true, 
		locale: { format: 'DD/MM/YYYY' },
		parentEl: $('#vm_cambiar_fecha_comprobante'),
		autoClose: true,
		singleDate : true,
		showShortcuts: false
	});

	$("#btn_guardar_fecha_documento").click(guardar_fecha_comprobante);

	var switches = Array.prototype.slice.call(document.querySelectorAll('.switch2'));
    switches.forEach(function(html) {
        var switchery = new Switchery(html, {color: '#4CAF50'});
    });

    $(".switch").bootstrapSwitch();
});

function guardar_fecha_comprobante() {
	var light = $('#vm_cambiar_fecha_comprobante');
	$(light).block({
		message: '<div class="loader"></div> <p><br />Guardando...</p>',
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
	
	var id_contribuyente = $("#vm_fcomprobante_id_contribuyente").val();
	var id_tipodoc_electronico = $("#vm_fcomprobante_id_tipodoc_electronico").val();
	var serie_comprobante = $("#vm_fcomprobante_serie_comprobante").val();
	var numero_comprobante = $("#vm_fcomprobante_numero_comprobante").val();
	var tipo_envio_sunat = $("#vm_fcomprobante_modo").val();
	var fecha_comprobante = $("#vm_fcomprobante_fecha_comprobante").val();
	
	//id: f8sjHDGDSAjdh
	$.ajax({
        url : '/facturacionv8/gestiondedocumentos/guardar_fecha_documento',
		method :  'POST',
		data: {id_contribuyente: id_contribuyente, id_tipodoc_electronico: id_tipodoc_electronico, serie_comprobante: serie_comprobante, numero_comprobante: numero_comprobante, tipo_envio_sunat: tipo_envio_sunat, fecha_comprobante: fecha_comprobante},
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			
			swal({   
				title: 'Necesitamos de tu Confirmación',   
				text: data.mensaje,
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
					//id: f8sjHDGDSAjdh
					$.ajax({
						url : '/facturacionv8/gestiondedocumentos/guardar_fecha_documento',
						method :  'POST',
						data: {id_contribuyente: id_contribuyente, id_tipodoc_electronico: id_tipodoc_electronico, serie_comprobante: serie_comprobante, numero_comprobante: numero_comprobante, tipo_envio_sunat: tipo_envio_sunat, fecha_comprobante: fecha_comprobante, confirmacion: 'si'},
						dataType : "json"
					}).then(
						function(data) {
							if(data.respuesta == 'ok') {
								swal({   
									title: data.titulo,   
									text: data.mensaje,
									html: true,
									type: "success",   
									confirmButtonColor: "#563d7c",
									confirmButtonText: "Ok"
								}, function(){  
									var recarga_tabla = $('#opcion_recarga_tabla').bootstrapSwitch('state');
									if(recarga_tabla) {
										dataTable_docs_sunat.ajax.reload(null, false);
									}
									$("#vm_cambiar_fecha_comprobante").modal("hide");
								});
								$(light).unblock();
								return;
							} else {
								swal({   
									title: data.titulo,   
									text: data.mensaje,
									html: true,
									type: "error",   
									confirmButtonColor: "#563d7c",   
									confirmButtonText: "OK"
								});
								$(light).unblock();
								return;
							}
						},
						function(reason){
							swal({   
								title: 'ERROR',   
								text: reason,
								html: true,
								type: "error",   
								confirmButtonColor: "#563d7c",   
								confirmButtonText: "OK"
							});
							$(light).unblock();
							return;
						}
					);
				} else {
					swal({   
						title: 'Está Bien!',   
						text: 'No hicimos ningún cambio!',
						html: true,
						type: "error",   
						confirmButtonColor: "#563d7c",   
						confirmButtonText: "OK"
					});
					
					$(light).unblock();
					return;
				}
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

function get_lista_comprobantes(fecha_inicio, fecha_fin, id_contribuyente = '0', estado_documento = 'todos', tipo_documento = 'todos', id_contribuyente_excluir = '') {

	var light = $("#content_lista_docs_general");
    $(light).block({
		message: '<div class="loading"> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
						<div class="loading-bar"></div> \
					</div> <p> <br />Buscando Documentos con Estado: <strong style="text-transform: uppercase">'+ estado_documento +'</strong></p>',
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
	
	dataTable_docs_sunat = $('#tbl_lista_documentos').DataTable( {
		"processing": true,
		"serverSide": true,
		"ajax":{
			url :"/facturacionv8/gestiondedocumentos/get_lista_docs_sunat", // json datasource
			data: {fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, id_contribuyente: id_contribuyente, estado_documento: estado_documento, tipo_documento: tipo_documento, id_contribuyente_excluir: id_contribuyente_excluir},
			type: "post",
			error: function(){
				
			}
		},
		"order": [[ 0, "desc" ]],
		buttons: [
            {
                text: '<i class="icon-arrow-up15 position-left"></i> Enviar Todos a SUNAT</span>',
                className: 'btn btn-success',
                action: function ( e, dt, node, config ) {
                    enviar_comprobantes();
                }
            },
			/*{
                text: '<i class="file-pencil position-left"></i> Validar en SUNAT</span>',
                className: 'btn btn-primary',
                action: function ( e, dt, node, config ) {
                    get_txt_validacion_sunat();
                }
            }*/
        ],
		"columns": [
			{ 
				'className': 'details-control', 
				'orderable': false, 
				'data': null, 
				'defaultContent': ''
			},
			{ "data": "fecha_registro" },
			{ "data": "comprobante" },
			{ "data": "cliente" },
			{ "data": "total" },
			{ "data": "pdf" },
			{ "data": "xml" },
			{ "data": "cdr" },
			{ "data": "sunat" },
			{ "data": "opciones" },
		],
		"lengthMenu": [ 10, 3, 4, 5, 25, 50, 75, 100, 200, 300, 500, 1000, 2000, 3000, 5000, 10000 ],
		orderCellsTop: true,
		fixedHeader: true,
		"bDestroy": true,
		initComplete: function(){
			$(light).unblock();
		}
	} );
}

function enviar_comprobantes() {
	// selecciona todas las filas de la tabla
	$("#barra_progreso_div").show("slide");

	const rows = document.querySelectorAll('#tbl_lista_documentos tbody tr');

	$('#content_barra_progreso .progress-bar').attr('data-transitiongoal', 0).progressbar({display_text: 'center', use_percentage: false});
	var $pb = $('#content_barra_progreso .progress-bar');
	$pb.attr('aria-valuemax', rows.length);

	// define la función que realiza la llamada AJAX
	function makeAjaxCall(url, data) {
		return $.ajax({
			url: url,
			type: 'POST',
			dataType : "json",
			data: data
		});
	}

	function processRow(index) {
		// si se ha procesado todas las filas, detiene la recursión
		if (index >= rows.length) {

			var recarga_tabla = $('#opcion_recarga_tabla').bootstrapSwitch('state');
			if(recarga_tabla) {
				//dataTable_docs_sunat.ajax.reload(null, false);
			}
			
		  	return;
		}
		
		const progress = (index / rows.length) * 100;
        $pb.attr('data-transitiongoal', (index + 1));
        $pb.progressbar({display_text: 'center', use_percentage: false});
		
		let id_row = rows[index].getAttribute('id');
		if(id_row != '') {
			let codigo	 = id_row.split('||');
			let documento = {
				"id_row"					: id_row,
				"id_contribuyente"			: codigo[0],
				"id_tipodoc_electronico"	: codigo[1],
				"serie_comprobante"			: codigo[2],
				"numero_comprobante"		: codigo[3],
				"tipo_envio_sunat"			: codigo[4],
				"estado_envio_sunat"		: codigo[5],
				"numero_ticket"				: codigo[6]
			};

			var id_campo_cliente = "#clie_" + id_row.replace(/\|\|/g, '_');
			var id_campo_fecha = "#fecha_" + id_row.replace(/\|\|/g, '_');
			var id_campo_doc = "#doc_" + id_row.replace(/\|\|/g, '_');
		
			// realiza la llamada AJAX
			makeAjaxCall('/facturacionv8/gestiondedocumentos/proceso_total_doc_sunat', documento)
				.then(function(response) {
					//console.log(response);
					if(response.respuesta == 'ok') {
						$(id_campo_cliente).html('\
						<div class="alert alert-success alert-bordered" style="max-width: 670px; white-space: normal; word-wrap: break-word;">\
							'+ response.mensaje +'\
						</div>');
					} else {
						$(id_campo_cliente).html('\
						<div class="alert alert-danger alert-bordered" style="max-width: 670px; white-space: normal; word-wrap: break-word;">\
							'+ response.mensaje +'\
						</div>');
					}
					
					processRow(index + 1);
				})
				.catch(function(error) {
					processRow(index + 1);
				});
		} else {
			processRow(index + 1);
		}
	}
	processRow(0);
}


//https://www.kuworking.com/javascript-loops-con-await-reduce

const wait = ms => new Promise((r, j) => setTimeout(r, ms))

async function enviar_comprobantes_ORIGINAL() { //YA NO SE UTILIZA!!!!!!!!!!
	swal({   
		title: 'Necesitamos tu Confirmación',   
		text: 'El Sistema Iniciará el Proceso de Envío a SUNAT, Esto puede tomar varios minutos y es posible que tu navegador se quede colgado!... No te preocupes, deja que el sistema termine el proceso y tu navegador volverá a la normalidad...',
		html: true,
		type: "warning",   
		showCancelButton: true,   
		confirmButtonColor: "#3f51b5",    //confirmButtonColor: "#3f51b5",   
		cancelButtonColor: "#DD6B55",
		confirmButtonText: "Si, Adelante!",   
		closeOnConfirm: true,
		showLoaderOnConfirm: true
	}, async function(isconfirmed){  
		if(isconfirmed) {
			var light = $("#content_lista_docs_general");
			$(light).block({
				message: '<div class="loading"> \
								<div class="loading-bar"></div> \
								<div class="loading-bar"></div> \
								<div class="loading-bar"></div> \
								<div class="loading-bar"></div> \
							</div> <p> <br />Se están procesando dos documentos mostramos...</p>',
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
			

			let array_documentos = [];
			$("#tbl_lista_documentos tr").each(function() {
				//'DT_RowId' => $item->id_contribuyente.'||'.$item->id_tipodoc_electronico.'||'.$item->serie_comprobante.'||'.$item->numero_comprobante.'||'.$item->tipo_envio_sunat.'||'.$item->estado_envio_sunat.'||'.$numero_ticket,
				let id_row = this.id;
				let opcion_estado_doc = $("#select_estado_comprobante").val();
				if(id_row != '') {
					let codigo	 = id_row.split('||');
					let documento = {
						"id_row"					: id_row,
						"opcion_estado_doc"			: opcion_estado_doc,
						"id_contribuyente"			: codigo[0],
						"id_tipodoc_electronico"	: codigo[1],
						"serie_comprobante"			: codigo[2],
						"numero_comprobante"		: codigo[3],
						"tipo_envio_sunat"			: codigo[4],
						"estado_envio_sunat"		: codigo[5],
						"numero_ticket"				: codigo[6],
					};

					array_documentos.push(documento);
				}
			});

			await wait(3000);
			
			await array_documentos.reduce(async (previousPromise, documento) => {
				await previousPromise
				if(documento.opcion_estado_doc == 'xml') {
					if(documento.estado_envio_sunat != 'pendiente') {
						const contents = await $.ajax({
							url : '/facturacionv8/reportedocumentos/enviar_documento_sunat',
							method :  'POST',
							async: false,
							data: {id_contribuyente: documento.id_contribuyente, id_tipodoc_electronico: documento.id_tipodoc_electronico, serie_comprobante: documento.serie_comprobante, numero_comprobante: documento.numero_comprobante, modo: 'solo_firma', confirmacion: 'si', modalidad_envio_cpe: "envio_en_lote"},
							dataType : "json",
							success: async function (data) {
								await wait(3000);
								console.log(documento);
							},
							error: async function (data) {
								
							}
						});

						console.log(contents);
					}
				} else {
					//facturas y sus notas de crédito y débito
					if(documento.estado_envio_sunat != 'aprobado' && documento.estado_envio_sunat != 'anulado') {
						if(documento.id_tipodoc_electronico == '01' || (documento.id_tipodoc_electronico == '07' && documento.serie_comprobante.charAt(0) == 'F') || (documento.id_tipodoc_electronico == '08' && documento.serie_comprobante.charAt(0) == 'F')) {
							//procesamos las facturas y sus notas
							const contents = await $.ajax({
								url : '/facturacionv8/gestiondedocumentos/procesar_facturas_notas',
								method :  'POST',
								async: false,
								dataType : "json",
								data: {id_contribuyente: documento.id_contribuyente, id_tipodoc_electronico: documento.id_tipodoc_electronico, serie_comprobante: documento.serie_comprobante, numero_comprobante: documento.numero_comprobante, modalidad_envio_cpe: "envio_en_lote"},
								success: async function (data) {
									await wait(3000);
									console.log(documento);
								},
								error: async function (data) {
									
								}
							});

							console.log(contents);
						}
					}

					//boletas y sus notas de crédito o débito
					if(documento.id_tipodoc_electronico == '03' || (documento.id_tipodoc_electronico == '07' && documento.serie_comprobante.charAt(0) == 'B') || (documento.id_tipodoc_electronico == '08' && documento.serie_comprobante.charAt(0) == 'B')) {
						const contents = await $.ajax({
							url : '/facturacionv8/gestiondedocumentos/procesar_boletas_notas',
							method :  'POST',
							dataType : "json",
							async: false,
							data: {id_contribuyente: documento.id_contribuyente, id_tipodoc_electronico: documento.id_tipodoc_electronico, serie_comprobante: documento.serie_comprobante, numero_comprobante: documento.numero_comprobante, modalidad_envio_cpe: "envio_en_lote"},
							success: async function (data) {
								await wait(3000);
								console.log(documento);
							},
							error: async function (data) {
								
							}
						});

						console.log(contents);
					}
				}
				
				return Promise.resolve();
			}, Promise.resolve());

			console.log('hemos acabado');
			dataTable_docs_sunat.ajax.reload(null, false);
			$(light).unblock();
		}
	});
}

function generar_xml_documento(id_contribuyente, id_tipodoc_electronico, serie_comprobante, numero_comprobante, modo, confirmacion) {
	$.ajax({
		url : '/facturacionv8/reportedocumentos/enviar_documento_sunat',
		method :  'POST',
		async: false,
		data: {id_contribuyente: id_contribuyente, id_tipodoc_electronico: id_tipodoc_electronico, serie_comprobante: serie_comprobante, numero_comprobante: numero_comprobante, modo: modo, confirmacion: confirmacion},
		dataType : "json"
	}).then(function(data) {
			return {
				data: data,
				mensaje: data.mensaje,
				id_contribuyente: id_contribuyente, 
				id_tipodoc_electronico: id_tipodoc_electronico, 
				serie_comprobante: serie_comprobante, 
				numero_comprobante: numero_comprobante, 
				modo: modo
			};
		}, function(reason){
			return false;
		}
	);
}

function modificar_comprobante(id_contribuyente, id_tipodoc_electronico, serie_comprobante, numero_comprobante, accion, num_ticket) {
	var light = $("#content_lista_docs_general");

	$(light).block({
		message: '<div class="loading"> \
				<div class="loading-bar"></div> \
				<div class="loading-bar"></div> \
				<div class="loading-bar"></div> \
				<div class="loading-bar"></div> \
			</div> <p> <br />Procesando ...</p>',

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
        url : '/facturacionv8/gestiondedocumentos/modificar_docelectronico',
        method :  'POST',
		dataType : "json",
		data: {id_contribuyente: id_contribuyente, id_tipodoc_electronico: id_tipodoc_electronico, serie_comprobante: serie_comprobante, numero_comprobante: numero_comprobante, accion: accion, num_ticket: num_ticket}
    }).then(function(data){

		if(data.respuesta == 'ok') {
			swal({   
				title: data.titulo,   
				text: data.mensaje,
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
						url : '/facturacionv8/gestiondedocumentos/modificar_docelectronico',
						method :  'POST',
						dataType : "json",
						data: {id_contribuyente: id_contribuyente, id_tipodoc_electronico: id_tipodoc_electronico, serie_comprobante: serie_comprobante, numero_comprobante: numero_comprobante, accion: accion, num_ticket: num_ticket, confirmacion: 'si'}
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
								var recarga_tabla = $('#opcion_recarga_tabla').bootstrapSwitch('state');
								if(recarga_tabla) {
									dataTable_docs_sunat.ajax.reload(null, false);
								}
								
								$("#vm_verificar_estado_sunat").modal("hide");
								$(light).unblock();
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
				} else {
					swal({   
						title: 'Está Bien!',   
						text: 'No hicimos ningún cambio!',
						html: true,
						type: "error",   
						confirmButtonColor: "#563d7c",   
						confirmButtonText: "OK"
					});
					
					$(light).unblock();
					return;
				}
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

function ver_estado_documento_sunat(id_contribuyente, id_tipodoc_electronico, serie_comprobante, numero_comprobante) {
	$("#vestado_id_contribuyente").val(id_contribuyente);
	$("#vestado_tipo_doc_electronico").val(id_tipodoc_electronico);
	$("#vestado_serie_comprobante").val(serie_comprobante);
	$("#vestado_numero_comprobante").val(numero_comprobante);

	$("#estado_num_doc_22").html(serie_comprobante + '-' + numero_comprobante);

	var light = $("#content_lista_docs_general");

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
		url : '/facturacionv8/gestiondedocumentos/verificar_estado_sunat',
		method :  'POST',
		dataType : "json",
		data: {id_contribuyente: id_contribuyente, id_tipodoc_electronico: id_tipodoc_electronico, serie_comprobante: serie_comprobante, numero_comprobante: numero_comprobante}
	}).then(function(data){
			if(data.respuesta == 'ok') {
				
				$("#btn_resetear_documento").show();
				$("#btn_aprobar_manualmente").show();
				$("#contenedor_numero_ticket").show();
				$("#btn_recuperar_cdr").show();

				$("#contenido_respuesta_sunat").html(data.html);
				$("#vm_verificar_estado_sunat").modal("show");
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

function enviar_resumen_individual_sunat(id_contribuyente, tipodoc, serie, numero, accion) {
    var light = $('#content_listado_documentos');
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
        url : '/facturacionv8/gestiondedocumentos/crear_resumen_individual',
        method :  'POST',
		dataType : "json",
		data: {id_contribuyente: id_contribuyente, tipodoc: tipodoc, serie: serie, numero: numero, accion: accion}
    }).then(function(data){

		if(data.respuesta == 'ok') {
			swal({   
				title: data.titulo,   
				text: data.mensaje,
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
						url : '/facturacionv8/gestiondedocumentos/crear_resumen_individual',
						method :  'POST',
						dataType : "json",
						data: {id_contribuyente: id_contribuyente, tipodoc: tipodoc, serie: serie, numero: numero, accion: accion, confirmacion: 'si'}
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
								//window.location.reload();
								dataTable_docs_sunat.ajax.reload(null, false);
								$(light).unblock();
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
				} else {
					swal({   
						title: 'Está Bien!',   
						text: 'No hicimos ningún cambio!',
						html: true,
						type: "error",   
						confirmButtonColor: "#563d7c",   
						confirmButtonText: "OK"
					});
					
					$(light).unblock();
					return;
				}
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

function descargar_cdr_resumen(ticket, idcontribuyente = 0) {
	var light = $('#content_lista_docs_general');
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
		url : '/facturacionv8/gestiondedocumentos/consultar_ticket',
		data: {numero_ticket: ticket, idcontribuyente: idcontribuyente},
        method :  'POST',
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
				//window.location.reload();
				dataTable_docs_sunat.ajax.reload(null, false);
				$(light).unblock();
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

function enviar_documento_sunat(id_contribuyente, id_tipodoc_electronico, serie_comprobante, numero_comprobante, modo) {
	var light = $('#content_listado_documentos');
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
        url : '/facturacionv8/gestiondedocumentos/enviar_documento_sunat',
		method :  'POST',
		data: {id_contribuyente: id_contribuyente, id_tipodoc_electronico: id_tipodoc_electronico, serie_comprobante: serie_comprobante, numero_comprobante: numero_comprobante, modo: modo},
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			swal({   
				title: data.titulo,   
				text: data.mensaje,
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
						url : '/facturacionv8/gestiondedocumentos/enviar_documento_sunat',
						method :  'POST',
						data: {id_contribuyente: id_contribuyente, id_tipodoc_electronico: id_tipodoc_electronico, serie_comprobante: serie_comprobante, numero_comprobante: numero_comprobante, modo: modo, confirmacion: 'si'},
						dataType : "json"
					}).then(
						function(data) {
							if(data.respuesta == 'ok') {
								swal({   
									title: data.titulo,   
									text: data.mensaje,
									html: true,
									type: "success",   
									confirmButtonColor: "#563d7c",   
									confirmButtonText: "OK"
								}, function(){
									//window.location.reload();
									var recarga_tabla = $('#opcion_recarga_tabla').bootstrapSwitch('state');
									if(recarga_tabla) {
										dataTable_docs_sunat.ajax.reload(null, false);
									}
								});
								$(light).unblock();
								return;
							} else {
								swal({   
									title: data.titulo,   
									text: data.mensaje,
									html: true,
									type: "error",   
									confirmButtonColor: "#563d7c",   
									confirmButtonText: "OK"
								});
								$(light).unblock();
								return;
							}
						},
						function(reason){
							swal({   
								title: 'ERROR',   
								text: reason,
								html: true,
								type: "error",   
								confirmButtonColor: "#563d7c",   
								confirmButtonText: "OK"
							});
							$(light).unblock();
							return;
						}
					);
				} else {
					swal({   
						title: 'Está Bien!',   
						text: 'No hicimos ningún cambio!',
						html: true,
						type: "error",   
						confirmButtonColor: "#563d7c",   
						confirmButtonText: "OK"
					});
					
					$(light).unblock();
					return;
				}
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

function enviar_email_cpe(id_contribuyente, tipo_doc, serie_doc, numero_comprobante, email) {
	$("#email_idcontribuyente").val(id_contribuyente);
	$("#email_tipo_doc").val(tipo_doc);
	$("#email_serie_doc").val(serie_doc);
	$("#email_numero_comprobante").val(numero_comprobante);
	$("#email_cliente").val(email);

	$("#vm_enviar_email").modal('show');
}

function enviar_cpe_cliente() {
	var light = $("#content_enviar_email_vm");

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

	let id_contribuyente = $("#email_idcontribuyente").val();
	let tipo_doc = $("#email_tipo_doc").val();
	let serie_doc = $("#email_serie_doc").val();
	let numero_comprobante = $("#email_numero_comprobante").val();
	let email= $("#email_cliente").val();

	$.ajax({
		url : '/facturacionv8/herramientas/enviar_cpe_email',
		method :  'POST',
		dataType : "json",
		data: {id_contribuyente: id_contribuyente, tipo_doc: tipo_doc, serie_doc: serie_doc, numero_comprobante: numero_comprobante, email: email}
	}).then(function(data){
			if(data.respuesta == 'ok') {
				swal({
					title: data.titulo,
					text: data.mensaje,
					html: true,
					type: "success",
					confirmButtonText: "Ok",
					confirmButtonColor: "#2196F3"
				}, function(){
					$("#email_idcontribuyente").val('');
					$("#email_tipo_doc").val('');
					$("#email_serie_doc").val('');
					$("#email_numero_comprobante").val('');
					$("#email_cliente").val('');

					$("#vm_enviar_email").modal('hide');
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

function ignorar_documento(id_contribuyente, tipo_doc, serie_doc, numero_comprobante) {
	var light = $('#content_listado_documentos');
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
        url : '/facturacionv8/gestiondedocumentos/ignorar_documento',
		method :  'POST',
		data: {id_contribuyente: id_contribuyente, id_tipodoc_electronico: tipo_doc, serie_comprobante: serie_doc, numero_comprobante: numero_comprobante},
        dataType : "json"
    }).then(function(data){
		if(data.respuesta == 'ok') {
			swal({   
				title: data.titulo,   
				text: data.mensaje,
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
						url : '/facturacionv8/gestiondedocumentos/ignorar_documento',
						method :  'POST',
						data: {id_contribuyente: id_contribuyente, id_tipodoc_electronico: tipo_doc, serie_comprobante: serie_doc, numero_comprobante: numero_comprobante, confirmacion: 'si'},
						dataType : "json"
					}).then(
						function(data) {
							if(data.respuesta == 'ok') {
								swal({   
									title: data.titulo,   
									text: data.mensaje,
									html: true,
									type: "success",   
									confirmButtonColor: "#563d7c",   
									confirmButtonText: "OK"
								}, function(){
									//window.location.reload();
									var recarga_tabla = $('#opcion_recarga_tabla').bootstrapSwitch('state');
									if(recarga_tabla) {
										dataTable_docs_sunat.ajax.reload(null, false);
									}
								});
								$(light).unblock();
								return;
							} else {
								swal({   
									title: data.titulo,   
									text: data.mensaje,
									html: true,
									type: "error",   
									confirmButtonColor: "#563d7c",   
									confirmButtonText: "OK"
								});
								$(light).unblock();
								return;
							}
						},
						function(reason){
							swal({   
								title: 'ERROR',   
								text: reason,
								html: true,
								type: "error",   
								confirmButtonColor: "#563d7c",   
								confirmButtonText: "OK"
							});
							$(light).unblock();
							return;
						}
					);
				} else {
					swal({   
						title: 'Está Bien!',   
						text: 'No hicimos ningún cambio!',
						html: true,
						type: "error",   
						confirmButtonColor: "#563d7c",   
						confirmButtonText: "OK"
					});
					
					$(light).unblock();
					return;
				}
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

function editar_fecha_comprobante(fecha_expira = '', id_contribuyente, id_tipodoc_electronico, serie_comprobante, numero_comprobante, tipo_envio_sunat) {
	$("#vm_fcomprobante_id_contribuyente").val(id_contribuyente);
	$("#vm_fcomprobante_id_tipodoc_electronico").val(id_tipodoc_electronico);
	$("#vm_fcomprobante_serie_comprobante").val(serie_comprobante);
	$("#vm_fcomprobante_numero_comprobante").val(numero_comprobante);
	$("#vm_fcomprobante_modo").val(tipo_envio_sunat);

	$("#vm_cambiar_fecha_comprobante").modal("show");

	if(fecha_expira != '') {
		$("#vm_fcomprobante_fecha_comprobante").val(fecha_expira);
		$(".vm_fcomprobante_fecha_comprobante").daterangepicker({
			singleDatePicker: true, 
			locale: { format: 'DD/MM/YYYY' },
			parentEl: $('#vm_cambiar_fecha_comprobante'),
			autoClose: true,
			singleDate : true,
			showShortcuts: false
		});
	}
}