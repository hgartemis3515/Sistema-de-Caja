<style type="text/css" media="screen">

	[class^="icon-"], [class*=" icon-"] {
	font-family: 'icomoon' !important;
	 
	font-style: normal;
	font-weight: normal;
	font-variant: normal;
	text-transform: none;
	line-height: 1;
	min-width: 1em;
	display: inline-block;
	text-align: center;
	font-size: 16px;
	vertical-align: middle;
	position: relative;
	top: -1px;
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
	}
	.checker span {
    	color: #ffffff;
    	border: 2px solid #ffffff;
	}
	.custom-textarea:focus {
		/* outline: 0; */
		/* border-color: transparent; */
		border-bottom-color: #009688;
		-webkit-box-shadow: 0 1px 0 #009688;
		box-shadow: 0 1px 0 #009688;
		border-color: #ddd;
		outline: 0;
		-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(221, 221, 221, 0.6);
		box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(221, 221, 221, 0.6);
	}
	.custom-textarea {
		display: block;
		width: 100%;
		padding: 8px 16px;
		font-size: 13px;
		line-height: 1.5384616;
		color: #333333;
		background-color: transparent;
		background-image: none;
		border: 1px solid #ddd;
		border-radius: 3px;
		-webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
		box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
		-webkit-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
		-o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
		transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
	}
	.font-weight-bold{
		font-weight: 500;
    }
    .input-select-categoria .select2-selection--single {
		display: inline-grid;
		width: 100%;
	}
    .input-select2 .select2-container--default .selection .select2-selection.select2-selection--single {
        border-bottom-left-radius: 15px;
        border-top-left-radius: 15px;
        border-bottom-right-radius: 0;
        border-top-right-radius: 0;
    }
    .input-group .form-control:not(:first-child):not(:last-child), .input-group-addon:not(:first-child):not(:last-child), .input-group-btn:not(:first-child):not(:last-child) {
        border-bottom-left-radius: 15px;
        border-top-left-radius: 15px;
        border-bottom-right-radius: 0;
        border-top-right-radius: 0;
    }
	.mb-6{
		margin-bottom: 2.5em!important;
	}
	.mx-1{
		margin: .5em;
	}
	.mr-1{
		margin-right: 10px;
	}
	
	.navbar-brand{
		padding: 0px 10px;
	}
	.navbar-brand > img {
		height: auto!important;
		display: block;
		margin: auto;
	}
	.navbar-default .navbar-nav > li > a {
		color: #fff;
	}
	.navbar-info .navbar-nav > li > a {
		color: #fff;
		font-weight: 700;
	}
	.panel-factura {
		margin-bottom: 20px;
		background-color: #eeeded;
		border: 1px solid transparent;
		border-radius: 0px;
		-webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
		box-shadow: 0 0px 0px rgba(0, 0, 0, 0.05);
	}
	
	.stepy-header {
		margin: 0 auto;
		max-width: 500px;
	}
	.stepy-navigator {
		text-align: center;
	}

	.ui-jqgrid {
		position: relative !important;
		-moz-box-sizing: content-box !important;
		-webkit-box-sizing: content-box !important;
		box-sizing: content-box !important;
		-ms-touch-action: none !important;
		touch-action: none !important;
	}

	.ui-jqgrid-bdiv {
		overflow-x: scroll !important;
	}
	.resumen-ship{
		width: 100%;
		height: auto;
		padding: 0;
		box-sizing: border-box;
		border: solid 1px rgba(0,0,0,0.1);
		float: left;
		margin: 20px 0;
		position: relative;
		border-radius: 2px;
		padding: 20px 0;
	}
	.resumen-sec{
		width: 50%;
		height: auto;
		float: left;
		box-sizing: border-box;
		padding: 0 10px;        
	}
	.resumen-sec p{
		font-weight: normal;
		font-size: 14px;
		text-align: left;
		line-height: 25px;
		padding: 0 10px;
	}
	.resumen-sec p big{
		font-size: 16px;
		font-weight: bold;
	}
	.resumensecdos{
		border-left: dashed 1px rgba(0,0,0,0.2);
	}
	.resumen-sec ul{
		width: 100%;
		height: auto;
		padding: 0;
		margin: 0;
	}
	.resumen-sec ul li{
		list-style: none;
		width: 100%;
		text-align: left;
		margin: 0;
		padding: 5px;
		border-bottom: dashed 1px rgba(0,0,0,0.2);
		position: relative;
		color: #6e777f;
		text-transform: uppercase;
		border-radius: 2px;
		box-sizing: border-box;
	}
	.resumen-sec ul li:hover{
		background: rgba(0,0,0,0.05);
	}
	.resumen-sec ul li span{
		position: absolute;
		top: 5px;
		right: 5px;
		font-weight: bold;
		font-size: 14px;
	}
	.resumen-sec ul li:nth-child(3){
		border: none;
	}
	.resumen-sec ul li:last-child{
		font-size: 14px;
		font-weight: bold;
		background: rgba(0,0,0,0.05);
		padding: 5px;
		border: dashed 1px rgba(0,0,0,0.2);
	}

	@media (max-width: 540px){
		.resumen-sec{
			width: 100%;        
			padding: 10px 10px 0 10px;    
			border: none;
			border-bottom: dashed 1px rgba(0,0,0,0.2);
		}
		.resumensecdos{
			border: none;
		}
	}

	.sweet-alert button.cancel {
		background-color: #DD6B55;
		color: #ffffff;
	}

	.content_btn_opc_avanzadas1 {
		padding-top: 1.3em;
    	padding-right: 1.3em;
		display: grid;
		justify-content: end;
		align-content: end;
	}

</style>


<!-- Page header -->
<div class="page-header">
    <?php echo $html_suscripcion; ?>
    <div class="page-header-content" style="max-width: 1120px;margin: 0 auto;">
        <div class="page-title">
            <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Guía Transportista</span></h4>
        <a class="heading-elements-toggle"><i class="icon-more"></i></a></div>
        <div class="heading-elements">
            <div class="heading-btn-group">
                <a href="/facturacionv8/documentoelectronico/index/03/nuevo" class="btn btn-link btn-float has-text">
                    <img src="/facturacionv8/img/boleta.svg" style="width: 25px;"/>
                    <span>Boleta</span></a>
                <a href="/facturacionv8/documentoelectronico/index/01/nuevo" class="btn btn-link btn-float has-text">
                    <img src="/facturacionv8/img/factura.svg" style="width: 25px;"/>
                    <span>Factura</span></a>
                <a href="/facturacionv8/documentoelectronico/index/07/nuevo" class="btn btn-link btn-float has-text">
                    <img src="/facturacionv8/img/nota_credito.svg" style="width: 25px;"/>
                        <span>Nota Crédito</span></a>
                <a href="/facturacionv8/documentoelectronico/index/08/nuevo" class="btn btn-link btn-float has-text">
                    <img src="/facturacionv8/img/nota_debito.svg" style="width: 25px;"/>
                    <span>Nota Débito</span></a>
                <a href="/facturacionv8/reportes" class="btn btn-link btn-float has-text">
                    <img src="/facturacionv8/img/svg/analytics.svg" style="width: 25px;"/>
                    <span>Reporte de Ventas</span></a>
            </div>
        </div>
    </div>
</div>

<div class="content" id="content_guia_remision">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="panel" style="max-width: 1120px;margin: 0 auto;">
                <div class="content_btn_opc_avanzadas1" style="padding-top: 0px !important; position: relative; z-index: 127">
                    <div class="content_btn_opc_avanzadas2" style="margin-top: 13px;">
                        <div class="content_btn_opc_avanzadas3">
                            <label class="checkbox-inline checkbox-switchery checkbox-right switchery-xs">
                                <input type="checkbox" id="btn_opciones_avanzadas" class="switch2 btn_opciones_avanzadas">
                                Ver Opciones Avanzadas:
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="panel-body" id="content_panel_guia" style="z-index: 100;">
                    <form action="" class="frm_guia_remision" id="frm_guia_remision">
                        <input type="hidden" id="tipo_doc_guardado" value="<?php echo $tipo_doc_guardado; ?>" />
                        <input type="hidden" id="serie_doc_guardado" value="<?php echo $serie_doc_guardado; ?>" />
                        <input type="hidden" id="numero_doc_guardado" value="<?php echo $numero_doc_guardado; ?>" />

                        <div class="row">
                            <div class="col-lg-12" id="grupo_opciones_avanzadas" style="display: none;">
                                <div class="form-group col-md-3 col-xs-6">
                                    <div class="has-feedback has-feedback-left">
                                        <label><i class="icon-profile position-left"></i>Documento <span class="text-danger">*</span></label>
                                        <select title="Selecciona un Tipo de Documento Electrónico" data-placeholder="Selecciona un Tipo de Documento Electrónico" class="select select_tipo_doc_electronico" name="select_tipo_doc_electronico" id="select_tipo_doc_electronico" required>
                                            <option value="31">GUIA TRANSPORTISTA</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-md-3 col-xs-12">
                                    <div class="has-feedback has-feedback-left">
                                        <label><i class="icon-profile position-left"></i>Selecciona la Sucursal <span class="text-danger">*</span></label>
                                        <select title="Selecciona una sucursal" data-placeholder="Selecciona una sucursal" class="select select_sucursal" name="select_sucursal" id="select_sucursal" required>
                                            
                                        </select> 
                                    </div>
                                </div>
                                
                                <div class="form-group col-md-3 col-xs-6">
                                    <label><i class="icon-barcode2 position-left"></i> Serie:</label>
                                    <input type="text" name="serie_comprobante" id="serie_comprobante" value="-" class="form-control" readonly="readonly">
                                </div>
    
                                <div class="form-group col-md-3 col-xs-6">
                                    <label><i class="icon-calendar2 position-left"></i> Fecha.Doc:</label>
                                    <input type="text" name="fecha_comprobante" id="fecha_comprobante" placeholder="" class="form-control control_fecha">
                                </div>

                            </div>

                            <div class="col-lg-12 mt-1">
                                <fieldset>
                                    <legend class="text-bold">
                                            <img src="/facturacionv8/public/img/numbers/numero_uno.png" style="width: 25px; margin-right: 10px;"> <span class="text-uppercase">Datos del Remitente</span>
                                    </legend>
                                </fieldset>
                                <div class="row">
                                    <div class="form-group col-md-4" id="content_tipo_documento_remitente">
                                        <div class="has-feedback has-feedback-left">
                                            <label><i class="icon-user position-left"></i>Tipo Doc.Ident.<span class="text-danger">*</span></label>
                                            <select title="Selecciona el tipo de documento" data-placeholder="Selecciona el Tipo de Doc." class="tipo_documento_remitente select" name="tipo_documento_remitente" id="tipo_documento_remitente">
                                                    <option value="6">R.U.C.</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label><i class="icon-pencil position-left"></i> <span>N° Documento</span>: <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" inputmode="numeric" pattern="[0-9]*" title="Número de Documento" name="nro_documento_remitente" id="nro_documento_remitente" placeholder="Número de documento Aquí!" class="form-control nro_documento_remitente" value="<?php echo $contribuyente->ruc; ?>" required>
                                            <span class="input-group-btn">
                                                <button class="btn bg-indigo btn-icon legitRipple search_document_remitente" type="button">
                                                    <i class="icon-search4" id="icon_search_document_remitente"></i>
                                                    <i class="icon-spinner10 spinner position-left" style="display: none;" id="icon_searching_document_remitente"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
        
                                    <div class="form-group col-md-4" id="content_razon_social_remitente">
                                        <label><i class="icon-user position-left"></i> <span>Nombre Completo</span>: <span class="text-danger">*</span></label>
                                        <input type="text" title="Ingresa la Razón Social o Nombre" name="nombre_remitente" id="nombre_remitente" placeholder="Nombre o Razón Social Aquí" class="form-control nombre_remitente" value="<?php echo $contribuyente->razon_social; ?>" required>
                                    </div>
                                </div>
                            </div>


                            <div class="col-lg-12">
                                <fieldset>
                                    <legend class="text-bold">
                                        <img src="/facturacionv8/public/img/numbers/numero_dos.png" style="width: 25px; margin-right: 10px;"> Datos del Traslado
                                    </legend>
                                </fieldset>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label><i class="icon-calendar2 position-left"></i>Fecha Inicio traslado</label> <span class="text-danger">*</span>
                                            <input type="text" value="" class="form-control control_fecha" name="fecha_traslado" id="fecha_traslado" >
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label><i class="icon-stairs-up position-left"></i>Peso bruto (KGM)</label> <span class="text-danger">*</span>
                                            <input type="number" class="form-control" name="pesobruto" id="pesobruto">
                                        </div>
                                    </div>
                                </div>

                            </div> 


                            <div class="col-lg-12">
                                <fieldset>
                                    <legend class="text-bold">
                                            <img src="/facturacionv8/public/img/numbers/numero_tres.png" style="width: 25px; margin-right: 10px;">  <span class="text-uppercase">Datos del Transportista</span> 
                                    </legend>
                                </fieldset>
                                 <div class="row">
                                    
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label><i class="icon-stairs-up position-left"></i>Núm. Placa Transport.: </label> <span class="text-danger">*</span>
                                            <input type="text" class="form-control" name="transporte_nro_placa" id="transporte_nro_placa">
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label><i class="icon-stairs-up position-left"></i>Núm. Reg. MTC: </label>
                                            <input type="text" class="form-control" name="num_registro_mtc" id="num_registro_mtc">
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label><i class="icon-stairs-up position-left"></i>TUC Vehíc. Principal: </label>
                                            <input type="text" class="form-control" name="tuc_vehiculo_principal" id="tuc_vehiculo_principal">
                                        </div>
                                    </div>

                                </div>

                            </div>

                            <div class="col-lg-12 mt-1">
                                <fieldset>
                                    <legend class="text-bold">
                                            <img src="/facturacionv8/public/img/numbers/numero_cuatro.png" style="width: 25px; margin-right: 10px;"> <span class="text-uppercase">Datos del Conductor</span>
                                    </legend>
                                </fieldset>
                                <div class="row">
                                    <div class="form-group col-md-5" id="content_tipo_documento">
                                        <div class="has-feedback has-feedback-left">
                                            <label><i class="icon-user position-left"></i>Tipo Doc.Ident.<span class="text-danger">*</span></label>
                                            <select title="Selecciona el tipo de documento" data-placeholder="Selecciona el Tipo de Doc." class="tipo_documento_conductor select" name="tipo_documento_conductor" id="tipo_documento_conductor">
                                                    <!-- <option value="0">DOC.TRIB.NO.DOM.SIN.RUC</option> -->
                                                    <option value="1" selected>D.N.I.</option>
                                                    <!-- <option value="4">CARNET DE EXTRANJERIA</option> -->
                                                    <!-- <option value="6" >R.U.C.</option> -->
                                                    <!-- <option value="7">PASAPORTE</option> -->
                                                    <!-- <option value="A">CED. DIPLOMATICA DE IDENTIDAD</option> -->
                                                    <!-- <option value="B">OC.IDENT.PAIS.RESIDENCIA-NO.D</option> -->
                                                    <!-- <option value="C">TIN</option><option value="D">IN</option> -->
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-7">
                                        <label><i class="icon-pencil position-left"></i> <span id="titulo_numerodocumento">N° Documento</span>: <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" inputmode="numeric" pattern="[0-9]*" title="Número de Documento" name="nro_documento_conductor" id="nro_documento_conductor" placeholder="Número de documento Aquí!" class="form-control nro_documento_conductor" required>
                                            <span class="input-group-btn">
                                                <button class="btn bg-indigo btn-icon legitRipple search_document_conductor" type="button">
                                                    <i class="icon-search4" id="icon_search_document_conductor"></i>
                                                    <i class="icon-spinner10 spinner position-left" style="display: none;" id="icon_searching_document_conductor"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
        
                                    <div class="form-group col-md-6" id="razonsocial_numerodocumento">
                                        <label><i class="icon-user position-left"></i> <span>Nombre Completo</span>: <span class="text-danger">*</span></label>
                                        <input type="text" title="Ingresa la Razón Social o Nombre" name="nombre_conductor" id="nombre_conductor" placeholder="Nombre o Razón Social Aquí" class="form-control nombre_conductor"  required>
                                    </div>

                                    <div class="form-group col-md-6" id="content_licencia_conducir">
                                        <label><i class="icon-vcard position-left"></i> <span id="titulo_licencia_conducir">N° Lic. Conducir:</span></label>
                                        <input type="text" title="Ingresa el Número de la Licencia" name="conductor_num_licencia" id="conductor_num_licencia" placeholder="Número de Licencia de Conducir" class="form-control conductor_num_licencia"  required>
                                    </div>
                                </div>
                            </div> 


                            <div class="col-lg-12 mt-1">
                                <fieldset>
                                    <legend class="text-bold">
                                            <img src="/facturacionv8/public/img/numbers/numero_cinco.png" style="width: 25px; margin-right: 10px;"> <span class="text-uppercase">Datos del Destinatario</span>
                                    </legend>
                                </fieldset>
                                <div class="row">
                                    <div class="form-group col-md-4" id="content_tipo_documento">
                                        <div class="has-feedback has-feedback-left">
                                            <label><i class="icon-user position-left"></i>Tipo Doc.Ident.<span class="text-danger">*</span></label>
                                            <select title="Selecciona el tipo de documento" data-placeholder="Selecciona el Tipo de Doc." class="tipo_documento_destinatario select" name="tipo_documento_destinatario" id="tipo_documento_destinatario">
                                                    <option value="0">DOC.TRIB.NO.DOM.SIN.RUC</option>
                                                    <option value="1" selected>D.N.I.</option>
                                                    <option value="4">CARNET DE EXTRANJERIA</option>
                                                    <option value="6">R.U.C.</option>
                                                    <option value="7">PASAPORTE</option>
                                                    <option value="A">CED. DIPLOMATICA DE IDENTIDAD</option>
                                                    <option value="B">OC.IDENT.PAIS.RESIDENCIA-NO.D</option>
                                                    <option value="C">TIN</option><option value="D">IN</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label><i class="icon-pencil position-left"></i> <span>N° Documento</span>: <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" inputmode="numeric" pattern="[0-9]*" title="Número de Documento" name="nro_documento_destinatario" id="nro_documento_destinatario" placeholder="Número de documento Aquí!" class="form-control nro_documento_destinatario" required>
                                            <span class="input-group-btn">
                                                <button class="btn bg-indigo btn-icon legitRipple search_document_destinatario" type="button">
                                                    <i class="icon-search4" id="icon_search_document_destinatario"></i>
                                                    <i class="icon-spinner10 spinner position-left" style="display: none;" id="icon_searching_document_destinatario"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
        
                                    <div class="form-group col-md-4" id="razonsocial_numerodocumento">
                                        <label><i class="icon-user position-left"></i> <span>Nombre Completo</span>: <span class="text-danger">*</span></label>
                                        <input type="text" title="Ingresa la Razón Social o Nombre" name="nombre_destinatario" id="nombre_destinatario" placeholder="Nombre o Razón Social Aquí" class="form-control nombre_destinatario"  required>
                                    </div>
                                </div>
                            </div> 


                            <div class="col-lg-6">
                                <fieldset>
                                    <legend class="text-bold">
                                        <img src="/facturacionv8/public/img/numbers/numero_seis.png" style="width: 25px; margin-right: 10px;">  <span class="text-uppercase">Punto de partida</span> 
                                    </legend>
                                </fieldset>
                                
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label>
                                                <i class="icon-home2 position-left"></i>
                                                Dirección
                                            </label> <span class="text-danger">*</span>
                                            <input type="text" class="form-control form-control-sm" name="direccionpartida" id="direccionpartida" placeholder="Direccion">
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label>
                                                <i class="fa fa-map-marker mr-2" aria-hidden="true"></i>
                                                Ubigeo
                                            </label> <span class="text-danger">*</span>
                                            <select class="js-example-basic-single" name="ubigeo_partida" id="ubigeo_partida">
                                                <option selected value="">Seleccione una opción</option>
                                                
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-6" style="display:none;">
                                        <div class="form-group">
                                            <label>
                                                <i class="icon-home2 position-left"></i>
                                                Código Local Anexo
                                            </label> <span class="text-danger">*</span>
                                            <input type="text" class="form-control form-control-sm" name="codigo_local_partida" id="codigo_local_partida" placeholder="0000">
                                        </div>
                                    </div>
                                </div>
                              
                            </div>
                            <div class="col-lg-6">
                                <fieldset>
                                    <legend class="text-bold">
                                        <img src="/facturacionv8/public/img/numbers/numero_siete.png" style="width: 25px; margin-right: 10px;"> <span class="text-uppercase">Punto de llegada</span> 
                                    </legend>
                                </fieldset>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label>
                                                <i class="icon-home2 position-left"></i>
                                                Dirección
                                            </label> <span class="text-danger">*</span>
                                            <input type="text" class="form-control form-control-sm" name="direccionllegada" id="direccionllegada" placeholder="Direccion de Llegada">
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label>
                                                <i class="fa fa-map-marker mr-2" aria-hidden="true"></i>
                                                Ubigeo
                                            </label> <span class="text-danger">*</span>
                                            <select class="js-example-basic-single" name="ubigeo_llegada" id="ubigeo_llegada">
                                                <option selected value="">Seleccione una opción</option>
                                                
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-6" style="display:none;">
                                        <div class="form-group">
                                            <label>
                                                <i class="icon-home2 position-left"></i>
                                                Código Local Anexo
                                            </label> <span class="text-danger">*</span>
                                            <input type="text" value="0000" class="form-control form-control-sm" name="codigo_local_llegada" id="codigo_local_llegada" placeholder="0000">
                                        </div>
                                    </div>
                                </div>
                            </div> 

                            <div class="col-md-12">
                                <div class="tabbable">
                                    <ul class="nav nav-tabs nav-tabs-highlight">
                                        <li class="active">
                                            <a href="#tab_detalleguia" data-toggle="tab" class="legitRipple" aria-expanded="true">
                                                <img src="/facturacionv8/public/img/numbers/numero_ocho.png" style="width: 25px; margin-right: 10px;"> Detalle de guía de remisión:  <span class="text-danger">*</span>
                                            </a>
                                        </li>
                                    </ul>

                                    <div class="tab-content" style="border-bottom: solid 1px #dfdfdf; margin-bottom: 25px;">
                                        <div class="tab-pane active" id="tab_detalleguia">
                                            <div class="row">
                                                <div class="col-md-12 btn-options" style="text-align: right; padding-bottom: 4px;">
                                                    <button type="button" class="btn btn-primary btn-labeled btn-xs mx-1 text-uppercase font-weight-bold legitRipple btn_editar_producto"><b><i class="icon-pencil3"></i></b> editar</button>
                                                    <button type="button" class="btn btn-success btn-labeled btn-xs mx-1 text-uppercase font-weight-bold legitRipple btn_agregar_producto"><b><i class="icon-plus-circle2"></i></b> Agregar</button>
                                                    <button type="button" class="btn btn-danger btn-labeled btn-xs mx-1 text-uppercase font-weight-bold legitRipple btn_eliminar_producto"><b><i class="icon-cross2"></i></b> Eliminar</button>
                                                </div>
                                                <div class="col-md-12 mb-6">
                                                    <div class="jqGrid content_tabla_detalle">
                                                        <table id='detalle_guia' class='scroll'></table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane" id="tab_docreferencia">
                                            <div class="row">
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-lg-12">
                               <label>
                                   <i class="icon-notebook position-left"></i>
                                    Información adicional para SUNAT
                                </label>
                                <textarea rows="3" cols="3" name="informacion_adicional_sunat" class="custom-textarea form-control" placeholder="Escribe aquí una observación"></textarea>
                            </div>
                            
                            <div class="form-group col-lg-12 text-center">
                                <button class="btn bg-indigo mt-2 legitRipple btn_guardar_guia_transportista" id="btn_guardar_guia_transportista" type="button">
                                    <i class="icon-floppy-disk mr-2"></i>
                                    Guardar Guía Transportista
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="footer text-muted">
        © 2019. <a href="#">Facturación Electrónica</a> by <a href="#" target="_blank"><?php echo $data_empresa['nombre_empresa']; ?></a>
    </div>
</div>


<!-- vm_agregar_producto -->
<div id="vm_agregar_articulo" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="panel-heading bg-indigo-400">
                <h6 class="panel-title"><i class="icon-list mr-1"></i>	Seleccionar un Producto/Servicio</h6>
                <div class="heading-elements">
                    <form class="heading-form" action="#">
                        <div class="form-group">
                            <a href="/facturacionv8/producto/listaproductos" target="_blank" class="btn bg-primary font-weight-bold text-uppercase">
                                <i class=" icon-plus3 mr-1"></i>
                                Crear Nuevo
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="modal-body">
                <form action="#" id="frm_producto" method="get" accept-charset="utf-8">
                    <input type="hidden" id="producto_idproducto" name="producto_idproducto" />
                    <input type="hidden" id="key_row" name="key_row" value="" />
                    <div class="row">

                        <div class="col-md-12">
                            <label><i class="icon-cart-add position-left"></i>Aquí puedes buscar y seleccionar tu producto/Servicio!</label>
                            <div class="form-group has-feedback has-feedback-left">
                                <select name="select_producto_buscar" id="select_producto_buscar" data-placeholder="Selecciona un Producto/Servicio..." class="select_producto_buscar">
                                    <option></option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-12">
                            <label for="producto_descripcion"><i class="icon-file-text position-left"></i> Descripción: </label>
                            <input type="text" class="form-control" value="" name="producto_descripcion" id="producto_descripcion">
                        </div>

                        <div class="form-group col-md-4">
                            <label for="producto_codigo"><i class="icon-barcode2 position-left"></i>Código</label> 
                            <input type="text" class="form-control" value="" name="producto_codigo" id="producto_codigo" disabled>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="producto_unidadmedida"><i class="icon-stairs-up position-left"></i>Und/Medida</label>
                            <select class="form-control valid" name="producto_unidadmedida" id="producto_unidadmedida">
                                
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="producto_cantidad"><i class="icon-stack position-left"></i>Cantidad</label> 
                            <input type="text" class="form-control totales_input" value="1" name="producto_cantidad" id="producto_cantidad">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="producto_peso"><i class="icon-stack position-left"></i>Peso Unit. (KGM)</label> 
                            <input type="text" class="form-control totales_input" value="0" name="producto_peso" id="producto_peso">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="producto_peso_total"><i class="icon-stack position-left"></i>Peso Total. (KGM)</label> 
                            <input type="text" class="form-control totales_input" value="" name="producto_peso_total" id="producto_peso_total">
                        </div>

                        <div class="form-group col-md-12 text-right">
                            <button type="button" class="btn bg-indigo mx-1 font-weight-bold text-uppercase btn_agregarproducto_detalle"><i class="icon-floppy-disk mr-1"></i>Agregar a la Lista</button>
                            <button type="button" class="btn btn-default  mx-1 font-weight-bold text-uppercase" data-dismiss="modal"><i class="icon-cross2 mr-1"></i>Cerrar</button> 
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
<!-- /vm_agregar_producto -->

<!-- Modal para msg guardado -->
{{ partial('guiatransportista/vm_msg_guardado_guia') }}