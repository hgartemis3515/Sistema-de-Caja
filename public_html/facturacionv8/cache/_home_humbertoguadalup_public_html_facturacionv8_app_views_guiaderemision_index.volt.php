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
            <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Guía de Remisión del Remitente Electrónica</span></h4>
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
                                            <option value="09">GUIA DE REMISIÓN</option>
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
                            <div class="col-lg-12">
                                <fieldset>
                                    <legend class="text-bold">
                                        <img src="/facturacionv8/public/img/numbers/one.svg" style="width: 25px; margin-right: 10px;"> Cliente
                                    </legend>
                                </fieldset>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label><i class="icon-file-text2 position-left"></i>Selecciona un Cliente:</label> <span class="text-danger">*</span> 
                                            <div class="input-group input-select2">
                                                <select class="js-example-basic-single" name="idcliente" id="idcliente">
                                                    <option selected value="">Escribe el Número de RUC o Razón Social</option>
                                                </select>
                                                <span class="input-group-btn">
                                                    <button class="btn bg-indigo legitRipple" type="button" data-toggle="modal" data-target="#new_cliente">
                                                        <i class="icon-plus-circle2 mr-2"></i> Agregar Nuevo Cliente
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label><i class="icon-envelop2 position-left"></i> Email (opcional):</label>
								        <input type="email" title="Ingresa el email del cliente" name="cliente_email" id="cliente_email" placeholder="Escribe aquí el email del cliente" class="form-control cliente_email" required>
                                    </div>

                                </div>
                            </div> 
                            <div class="col-lg-12 mt-1">
                                <fieldset>
                                    <legend class="text-bold">
                                            <img src="/facturacionv8/public/img/numbers/two.svg" style="width: 25px; margin-right: 10px;">  <span class="text-uppercase">Datos de traslado</span> 
                                    </legend>
                                </fieldset>
                                 <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label><i class="icon-file-text2 position-left"></i>Motivo del Traslado</label> <span class="text-danger">*</span>
                                            <select name="motivo_traslado" id="motivo_traslado" class="form-control">
                                                <?php
                                                foreach($motivos_de_traslado as $motivo_traslado) {
                                                    echo '<option value="'.$motivo_traslado->id_motivotraslado.'">'.$motivo_traslado->descripcion.'</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label><i class="icon-file-text2 position-left"></i>Modalidad de traslado</label> <span class="text-danger">*</span>
                                            <select name="modalidad_traslado" id="modalidad_traslado" class="form-control">
                                                <?php
                                                foreach($modalidades_de_traslado as $modalidad_traslado) {
                                                    echo '<option value="'.$modalidad_traslado->id_modalidadtraslado.'">'.$modalidad_traslado->descripcion.'</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label><i class="icon-calendar2 position-left"></i>Fecha inicial de traslado</label> <span class="text-danger">*</span>
                                            <input type="text" value="" class="form-control control_fecha" name="fecha_traslado" id="fecha_traslado" >
                                        </div>
                                    </div>
                                    <div class="col-lg-12" id="content_texto_otro_motivo_traslado" style="display: none;">
                                        <div class="form-group">
                                            <label><i class="icon-stairs-up position-left"></i>Especifique el Motivo de Traslado: </label> <span class="text-danger">*</span>
                                            <input type="text" class="form-control" name="otro_motivo_traslado" id="otro_motivo_traslado">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label><i class="icon-stairs-up position-left"></i>Peso bruto (KGM)</label> <span class="text-danger">*</span>
                                            <input type="number" class="form-control" name="pesobruto" id="pesobruto">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label><i class="icon-stack position-left"></i>Número de bultos</label> <span class="text-danger">*</span>
                                            <input type="number" class="form-control" name="numero_bultos" id="numero_bultos">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label><i class="icon-stack position-left"></i>Nro Contenedor/Nro Precinto</label>
                                            <input type="text" class="form-control" placeholder="Ejem. CONT7878/2JMPRE" name="numero_contenedor" id="numero_contenedor">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label><i class="icon-barcode2 position-left"></i>Código de puerto</label>
                                            <select name="codigo_puerto" id="codigo_puerto" class="form-control">
                                                <option value="-">Selecciona una Opción</option>
                                                <?php
                                                foreach($codigos_de_puerto as $codigo_puerto) {
                                                    echo '<option value="'.$codigo_puerto->id_codigopuerto.'">'.$codigo_puerto->id_codigopuerto.'.- '.$codigo_puerto->descripcion.'</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-lg-12 mt-1">
                                <fieldset>
                                    <legend class="text-bold">
                                            <img src="/facturacionv8/public/img/numbers/three.svg" style="width: 25px; margin-right: 10px;"> <span class="text-uppercase" id="titulo_transporte">Datos de transporte</span>
                                    </legend>
                                </fieldset>
                                <div class="row">
                                    <div class="form-group col-md-3" id="content_tipo_documento">
                                        <div class="has-feedback has-feedback-left">
                                            <label><i class="icon-user position-left"></i>Tipo Doc.Ident.<span class="text-danger">*</span></label>
                                            <select title="Selecciona el tipo de documento" data-placeholder="Selecciona el Tipo de Doc." class="transporte_tipo_docidentidad select" name="transporte_tipo_docidentidad" id="transporte_tipo_docidentidad">
                                                    <option value="0">DOC.TRIB.NO.DOM.SIN.RUC</option>
                                                    <option value="1">D.N.I.</option>
                                                    <option value="4">CARNET DE EXTRANJERIA</option>
                                                    <option value="6" selected>R.U.C.</option>
                                                    <option value="7">PASAPORTE</option>
                                                    <option value="A">CED. DIPLOMATICA DE IDENTIDAD</option>
                                                    <option value="B">OC.IDENT.PAIS.RESIDENCIA-NO.D</option>
                                                    <option value="C">TIN</option><option value="D">IN</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4" id="content_transporte_numerodocumento">
                                        <label><i class="icon-pencil position-left"></i> <span id="titulo_numerodocumento">N° RUC Emp.Transp</span>: <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" inputmode="numeric" pattern="[0-9]*" title="Número de Documento" name="transporte_numerodocumento" id="transporte_numerodocumento" placeholder="Número de documento Aquí!" class="form-control transporte_numerodocumento" required>
                                            <span class="input-group-btn">
                                                <button class="btn bg-indigo btn-icon legitRipple search_document" type="button">
                                                    <i class="icon-search4" id="icon_search_document"></i>
                                                    <i class="icon-spinner10 spinner position-left" style="display: none;" id="icon_searching_document"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
        
                                    <div class="form-group col-md-5" id="razonsocial_numerodocumento">
                                        <label><i class="icon-user position-left"></i> <span id="titulo_nombrecliente">Razón Social</span>: <span class="text-danger">*</span></label>
                                        <input type="text" title="Ingresa la Razón Social o Nombre" name="transporte_nombre" id="transporte_nombre" placeholder="Nombre o Razón Social Aquí" class="form-control transporte_nombre"  required>
                                    </div>

                                    <div class="form-group col-md-6" id="content_licencia_conducir" style="display: none;">
                                        <label><i class="icon-vcard position-left"></i> <span id="titulo_licencia_conducir">N° Licencia Conducir:</span></label>
                                        <input type="text" title="Ingresa el Número de la Licencia" name="conductor_num_licencia" id="conductor_num_licencia" placeholder="Número de Licencia de Conducir" class="form-control conductor_num_licencia"  required>
                                    </div>

                                    <div class="form-group col-md-6" id="content_numero_placa" style="display: none;">
                                        <label><i class="icon-truck position-left"></i> <span>N° Placa Vehíc.</span>: <span class="text-danger">*</span></label>
                                        <input type="text" title="Ingresa el Número de Placa" name="transporte_num_placa" id="transporte_num_placa" placeholder="Número de Placa Transporte" class="form-control transporte_num_placa"  required>
                                    </div>
                                </div>
                            </div> 
                            <div class="col-lg-6">
                                <fieldset>
                                    <legend class="text-bold">
                                        <img src="/facturacionv8/public/img/numbers/four.svg" style="width: 25px; margin-right: 10px;">  <span class="text-uppercase">Punto de partida</span> 
                                    </legend>
                                </fieldset>
                                <div class="form-group">
                                    <label>
                                        <i class="icon-home2 position-left"></i>
                                        Dirección
                                    </label> <span class="text-danger">*</span>
                                    <input type="text" class="form-control form-control-sm" name="direccionpartida" id="direccionpartida" placeholder="Direccion">
                                </div>
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
                            <div class="col-lg-6">
                                <fieldset>
                                    <legend class="text-bold">
                                        <img src="/facturacionv8/public/img/numbers/five.svg" style="width: 25px; margin-right: 10px;"> <span class="text-uppercase">Punto de llegada</span> 
                                    </legend>
                                </fieldset>
                                 <div class="form-group">
                                    <label>
                                        <i class="icon-home2 position-left"></i>
                                        Dirección
                                    </label> <span class="text-danger">*</span>
                                    <input type="text" class="form-control form-control-sm" name="direccionllegada" id="direccionllegada" placeholder="Direccion de Llegada">
                                </div>
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

                            <div class="col-md-12">
                                <div class="tabbable">
                                    <ul class="nav nav-tabs nav-tabs-highlight">
                                        <li class="active">
                                            <a href="#tab_detalleguia" data-toggle="tab" class="legitRipple" aria-expanded="true">
                                                <img src="/facturacionv8/public/img/numbers/six.svg" style="width: 25px; margin-right: 10px;"> Detalle de guía de remisión:  <span class="text-danger">*</span>
                                            </a>
                                        </li>
                                        <li class="">
                                            <a href="#tab_docreferencia" data-toggle="tab" class="legitRipple" aria-expanded="false">
                                                <img src="/facturacionv8/public/img/numbers/seven.svg" style="width: 25px; margin-right: 10px;"> Doc. de Referencia:
                                            </a>
                                        </li>
                                    </ul>

                                    <div class="tab-content" style="border-bottom: solid 1px #dfdfdf; margin-bottom: 25px;">
                                        <div class="tab-pane active" id="tab_detalleguia">
                                            <div class="row">
                                                <div class="col-md-12 btn-options" style="text-align: right; padding-bottom: 4px;">
                                                    <button type="button" class="btn btn-info btn-labeled btn-xs mx-1 text-uppercase font-weight-bold legitRipple btn_importar_items"><b><i class="icon-file-download"></i></b> Importar Items</button>
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
                                                <div class="form-group col-md-12" style="display: none;">
                                                    <div class="has-feedback has-feedback-left">
                                                        <label><i class="icon-profile position-left"></i>Selecciona un Documento Electrónico: </label>
                                                        <select title="Selecciona un Tipo de Documento Electrónico" data-placeholder="Selecciona un Tipo de Documento Electrónico" class="select select_doc_referencia" name="select_doc_referencia" id="select_doc_referencia">
                                                            
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <div class="has-feedback has-feedback-left">
                                                        <label><i class="icon-profile position-left"></i>Tipo Documento: </label>
                                                        <select title="Selecciona un Tipo de Documento Electrónico" data-placeholder="Selecciona un Tipo de Documento Electrónico" class="select select_tipo_doc_referencia" name="select_tipo_doc_referencia" id="select_tipo_doc_referencia">
                                                            <option value="01">Factura</option>
                                                            <option value="03">Boleta</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group col-md-4">
                                                    <label><i class="icon-barcode2 position-left"></i> Serie:</label>
                                                    <input type="text" placeholder="XXXX" name="serie_doc_referencia" id="serie_doc_referencia" value="" class="form-control">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label><i class="icon-barcode2 position-left"></i> Número:</label>
                                                    <input type="text" placeholder="########" name="numero_doc_referencia" id="numero_doc_referencia" value="" class="form-control">
                                                </div>
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

                            <div class="col-md-12" id="content_modo_envio_a_sunat">
                                <fieldset class="content-group">
                                    <legend class="text-bold">Selecciona el modo de envío: </legend>
    
                                    <div class="col-md-4">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" value="solo_firma" id="mod_envio_sunat_solo_firma" name="modalidad_envio_sunat" class="control-primary" <?php if($modalidad_envio_sunat=='solo_firma'){echo 'checked="checked"';} ?>>
                                                Solo Firmar e Imprimir
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" value="inmediato" id="mod_envio_sunat_inmediato" name="modalidad_envio_sunat" class="control-success" <?php if($modalidad_envio_sunat=='inmediato'){echo 'checked="checked"';} ?>>
                                                Enviar a SUNAT ahora mismo!
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" value="no_enviar" id="mod_envio_sunat_no_enviar" name="modalidad_envio_sunat" class="control-info" <?php if($modalidad_envio_sunat=='no_enviar'){echo 'checked="checked"';} ?>>
                                                Solo Guardar
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            
                            <div class="form-group col-lg-12 text-center">
                                <button class="btn bg-indigo mt-2 legitRipple btn_save_guia" type="button">
                                    <i class="icon-floppy-disk mr-2"></i>
                                    Guardar Guía de Remisión
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


<!-- Modal agregar clientes -->
<div class="modal fade" id="new_cliente" tabindex="-1" role="dialog" aria-labelledby="new_cliente" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="content_vm_agregar_cliente">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Datos del Nuevo Cliente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" class="frm_addclient" id="frm_addclient">
                    
                    <div class="row">
                        <div class="col-lg-12" style="display: none;">
                            <div class="form-group">
                                <label  class="label-form">
                                    <i class="icon-user mr-2"></i>
                                    Código: <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="text" name="codigo" id="txt_codigo" class="form-control" placeholder="Código">
                                    <span class="input-group-btn">
                                        <button class="btn bg-indigo legitRipple btn_generar_codigo" type="button">
                                            <i class="icon-rotate-ccw3 mr-2"></i>
                                            Generar
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label  class="label-form">
                                    <i class="icon-user mr-2"></i> 
                                    TipoDoc.: <span class="text-danger">*</span>
                                </label>
                                <select class="select" name="type_document" id="type_document" required>
                                    <option selected value="">Seleccione una opción</option>
                                    <?php                                    
                                        foreach ($tipo_identidad as $value) {
                                            if($value->id_tipodocidentidad == 1) {
                                                echo "<option selected='selected' value='".$value->id_tipodocidentidad."'>".$value->nombre."</option>";
                                            } else {
                                                echo "<option value='".$value->id_tipodocidentidad."'>".$value->nombre."</option>";
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-8" id="estado_numerodocumento">
                            <label><i class="icon-pencil position-left"></i> <span id="titulo_numerodocumento_newclient">N° Documento</span>: <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" inputmode="numeric" pattern="[0-9]*" title="Número de Documento" name="doc_id" id="newclient_numerodocumento" placeholder="Número de documento Aquí!" class="form-control newclient_numerodocumento" required>
                                <span class="input-group-btn">
                                    <button class="btn bg-indigo btn-icon legitRipple search_document_newclient" type="button">
                                        <i class="icon-search4" id="icon_search_document_newclient"></i>
                                        <i class="icon-spinner10 spinner position-left" style="display: none;" id="icon_searching_document_newclient"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label  class="label-form">
                                    <i class="icon-users2 mr-2"></i> 
                                    Razón social/Nombre Completo: <span class="text-danger">*</span>
                                </label> <span style="display: none;" class="label label-danger lbl_estado_empresa lbl_estado_inactivo">Inactivo</span>
                                <span style="display: none;" class="label label-success lbl_estado_empresa lbl_estado_activo">Activo</span>
                                <input type="text" class="form-control form-control-sm" name="razon_social" id="razon_social" placeholder="Nombre Comercial">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label  class="label-form">
                                    <i class="icon-envelop mr-2"></i>
                                    Email:
                                </label>
                                <input type="email" class="form-control form-control-sm" name="email" id="email" placeholder="Email">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label  class="label-form">
                                    <i class="icon-address-book mr-2"></i>
                                    Dirección fiscal: 
                                </label>
                                <input type="text" class="form-control form-control-sm" name="direccionfiscal" id="direccionfiscal" placeholder="Direccion fiscal">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label  class="label-form">
                                    <i class="fa fa-map-marker mr-2" aria-hidden="true"></i>
                                    Ubigeo:
                                </label>
                                <select class="" name="ubigeo" id="ubigeo_client">
                                    <option selected value="">Seleccione una opción</option>
                                </select>
                            </div>	
                        </div>
                        
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secundary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn bg-indigo btn_saveclient">Guardar cambios</button>
            </div>
        </div>
    </div>
</div> 
<!-- /Modal agregar clientes -->

<!-- vm_agregar_articulo -->
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
<!-- /primary modal -->

<!-- vm_importar_item_detalle -->
<div id="vm_importar_items" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-indigo">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h6 class="modal-title"> <i class="icon-plus-circle2"></i> &nbsp; Actualización Masiva de Productos</h6>
			</div>
			<div class="modal-body" id="content_vm_importar_items">
				<form name="frm_importar_items" id="frm_importar_items" action="#" method="post" enctype="multipart/form-data"> 
					<div class="panel panel-body">
						<div class="media no-margin stack-media-on-mobile">
							<div class="media-left media-middle">
								<i class="fa fa-cloud-upload fa-2x text-muted no-edge-top"></i>
							</div>

							<div class="media-body">
								<h6 class="media-heading text-semibold">Descargar Productos de: <strong class="text-primary" id="actualizacion_nombre_sucursal"></strong></h6>
								<span class="text-muted">Para poder actualizar en Lote debes descargar todos tus productos y volverlos a subir respetando el formato!</span>
							</div>
 
							<div class="media-right media-middle">
								<a href="/facturacionv8/plantilla_items_guia.xlsx" id="enlace_descarga_productos" target="_blank" class="btn btn-primary legitRipple">Descargar Plantilla</a>
							</div>
						</div>
					</div>
					<div class="panel panel-body">
						<div class="col-md-12">
							<input type="file" class="file-styled" name="file_data_items" id="file_data_items" placeholder="Selecciona un Archivo">
						</div>
					</div>

					<div class="row">
						<div class="col-md-12" style="margin-top: 10px;">
							<button type="button" class="btn btn-default mr-2" data-dismiss="modal">Cerrar</button>
							<button class="btn btn-primary legitRipple btn_iniciar_importacion_items" id="btn_iniciar_importacion_items" type="button">
								<i class="icon-file-download mr-2"></i> Importar Items
							</button>
						</div>
					</div>
					
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /vm_importar_item_detalle -->