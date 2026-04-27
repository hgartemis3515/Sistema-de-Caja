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
	.close {
    text-shadow: none;
    opacity: 1;
	}
	.close span{
		font-weight: 700;
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
	li.li-close {
		position: absolute;
		top: .7em;
    	right: 1em
	}
	.icono-close{
		font-weight: 700;
    	color: #FFF;
	}
	.opciones_producto{
		position: relative;
	}
	.mb-6{
		margin-bottom: 2.5em!important;
	}
	.mx-1{
		margin: .5em;
	}
	.mr-1, .mr-2{
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
	
	.panel-factura {
		margin-bottom: 20px;
		background-color: #eeeded;
		border: 1px solid transparent;
		border-radius: 0px;
		-webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
		box-shadow: 0 0px 0px rgba(0, 0, 0, 0.05);
	}
	.opciones_producto {
		border-top-left-radius: 15px;
		border-top-right-radius: 15px;
		background: linear-gradient(45deg, #7880f0 0%, #b4b9ff 100%);
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
	.row.justify-content-end {
		display: -ms-flexbox;
		display: flex;
		-ms-flex-wrap: wrap;
		flex-wrap: wrap;
		-ms-flex-pack: end!important;
		justify-content: flex-end!important;
		margin: 10px 0px;
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

	.checkbox.checkbox-switch {
		margin: 15px 0px;
		text-align: center;
	}
	.bootstrap-switch {margin-top: 5px;}
	/* LOADING */
	.loading {
		/* position: absolute; */
		top: 50%;
		left: 50%;
	}
	.loading-bar {
		display: inline-block;
		width: 4px;
		height: 18px;
		border-radius: 4px;
		animation: loading 1s ease-in-out infinite;
	}
	.loading-bar:nth-child(1) {
		background-color: #3f51b5;
		animation-delay: 0;
	}
	.loading-bar:nth-child(2) {
		background-color: #2196f3;
		animation-delay: 0.09s;
	}
	.loading-bar:nth-child(3) {
		background-color: #4caf50;
		animation-delay: .18s;
	}
	.loading-bar:nth-child(4) {
		background-color: #00bcd4;
		animation-delay: .27s;
	}

	@keyframes loading {
		0% {
		transform: scale(1);
		}
		20% {
		transform: scale(1, 2.2);
		}
		40% {
		transform: scale(1);
		}
	}
	/* /LOADING */

	/* CSS PARA SELECT 2 - MUESTRA RESULTADOS FORMATEADOS */
	.select2-result-repository {
		padding-top: 4px;
		padding-bottom: 3px
	}

	.select2-result-repository__avatar {
		float: left;
		width: 60px;
		margin-right: 10px
	}

	.select2-result-repository__avatar img {
		width: 100%;
		height: auto;
		border-radius: 2px
	}

	.select2-result-repository__meta {
		margin-left: 70px
	}

	.select2-result-repository__title {
		color: black;
		font-weight: 700;
		word-wrap: break-word;
		line-height: 1.1;
		margin-bottom: 4px
	}

	.select2-result-repository__forks,.select2-result-repository__stargazers {
		margin-right: 1em
	}

	.select2-result-repository__forks,.select2-result-repository__stargazers,.select2-result-repository__watchers {
		display: inline-block;
		color: #aaa;
		font-size: 11px
	}

	.select2-result-repository__description {
		font-size: 13px;
		color: #777;
		margin-top: 4px
	}

	.select2-results__option--highlighted .select2-result-repository__title, .select2-results__option--highlighted .select2-result-repository__description {
		color: #000;
	}

	.select2-results__option--highlighted .select2-result-repository__forks,.select2-results__option--highlighted .select2-result-repository__stargazers,.select2-results__option--highlighted .select2-result-repository__description,.select2-results__option--highlighted .select2-result-repository__watchers {
		color: #000;
	}
	.select2-results__option[aria-selected=true] {
		background-color: #f5f5f5;
		color: #000;
	}



	/* FIN CSS SELECT 2 - RESULTADOS FORMATEADOS */
</style>
<input type="hidden" id="tipo_doc_selected" value="<?php echo $tipo_doc; ?>" />
<div class="page-header" style="max-width: 1100px; margin: 0 auto;">
	<div class="page-title">
		<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold" id="texto_titulo_general">Registro de Documento de Compra</span></h4>
	</div>
</div>

<div class="content header-top">
	<div class="row">
		<?php
		if($usuario->id_rol != 4) {
		?>
		<!-- Paneles Configuración Usuario SOL y Clave SOL, y Keys -->
		<div class="col-md-12" id="configuracion_data_sol">
			<div class="panel" style="max-width: 1100px; margin: 0 auto;">
				<div class="panel-body">

					<div class="row">

						<div class="col-md-12 text-right">
							<div class="content_btn_opc_avanzadas1" style="padding-top: 0px !important;">
								<div class="content_btn_opc_avanzadas2" style="margin-top: 5px;">
									<div class="content_btn_opc_avanzadas3">
										<label class="checkbox-inline checkbox-switchery checkbox-right switchery-xs">
											<input type="checkbox" id="btn_opciones_accesos_sunat" value="<?php echo $sunat_u_sol_principal; ?>" class="switch2 btn_opciones_accesos_sunat">
											Datos de Acceso SUNAT:
										</label>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-12 opc_datos_operaciones_sunat" style="display:none;">
							<h6 class="text-semibold">Ingresa tus Datos de Acceso a Operaciones en Línea</h6>
						</div>


						<div class="form-group col-md-6 opc_datos_operaciones_sunat" style="display:none;">
							<label class="label-form">
								<i class="icon-user mr-2"></i> Usuario Sol Principal
							</label>
							<input type="text" class="form-control form-control-sm" name="usuario_sol_principal" value="<?php echo $sunat_u_sol_principal; ?>" id="usuario_sol_principal" placeholder="Usuario Sol Principal">
						</div>


						<div class="form-group col-md-6 opc_datos_operaciones_sunat" style="display:none;">
							<label class="label-form">
								<i class="icon-lock mr-2"></i> Password Usuario Sol
							</label>
							<input class="form-control form-control-sm" type="text" name="password_usuario_sol" value="<?php echo $sunat_p_sol_principal; ?>" id="password_usuario_sol" placeholder="Password Sol Principal">
						</div>

						<div class="col-md-12 text-right opc_datos_operaciones_sunat" style="display:none;">
							<button class="btn bg-indigo legitRipple" id="btn_guardar_datos_acceso" type="button"><i class="icon-floppy-disk mr-2"></i>Guardar Datos de Acceso</button>
						</div>
					</div>

				</div>
			</div>
		</div>
		<!-- /Paneles Configuración Usuario SOL y Clave SOL, y Keys -->
		<?php
		}
		?>
	</div>

	{{ partial('gestiondecompras/top_menu_tipo_document') }}
	
	<div class="row">
	
		<div class="col-md-12" style="margin-bottom: 15px;">
			<div class="panel" style="max-width: 1100px; margin: 0 auto;">
				<div class="panel-body" id="cuerpo_comprobante">
					<form action="#" id="frm_datacompra" name="frm_datacompra" method="post" accept-charset="utf-8">
						<input type="hidden" value="<?php echo $tipo_doc; ?>" id="tipo_doc_seleccionado" />
						<div class="row" style="display:none;">
							<fieldset>
								<legend class="text-bold">Tipo de Comprobante: </legend>
							</fieldset>

							<div class="col-md-2 col-xs-4">
								<div class="radio">
									<label>
										<input type="radio" value="01" name="tipo_comprobante" id="tipo_factura" class="tipo_comprobante control-primary" checked="checked">
										Factura
									</label>
								</div>
							</div>
							
							<div class="col-md-2 col-xs-4">
								<div class="radio">
									<label>
										<input type="radio" value="03" name="tipo_comprobante" id="tipo_boleta" class="tipo_comprobante control-success">
										Boleta
									</label>
								</div>
							</div>

							<div class="col-md-3 col-xs-4">
								<div class="radio">
									<label>
										<input type="radio" value="99" name="tipo_comprobante" id="tipo_ordencompra" class="tipo_comprobante control-info">
										Orden de Compra
									</label>
								</div>
							</div>
							
							<div class="col-md-2 col-xs-4">
								<div class="radio">
									<label>
										<input type="radio" value="00" name="tipo_comprobante" id="tipo_otro" class="tipo_comprobante control-warning">
										Otro Doc. Compra
									</label>
								</div>
							</div>

							<div class="col-md-3 col-xs-4">
								<div class="radio">
									<label>
										<input type="radio" value="07" name="tipo_comprobante" id="tipo_notacredito" class="tipo_comprobante control-danger">
										Nota de Crédito
									</label>
								</div>
							</div>
							
							<div class="col-md-3 col-xs-4">
								<div class="radio">
									<label>
										<input type="radio" value="08" name="tipo_comprobante" id="tipo_notadebito" class="tipo_comprobante control-info">
										Nota de Débito
									</label>
								</div>
							</div>

						</div>

						<div class="row content_opcion_buscar_cpe">
							<div class="col-md-12" style="margin-bottom:20px;">
								<div class="checkbox checkbox-switch">
									<label>
										
										¿Deseas Buscar el CPE en SUNAT? <input name="opcion_buscar_cpe" id="opcion_buscar_cpe" type="checkbox" data-on-text="Si" data-off-text="No" class="switch" data-size="mini" >
									</label>
								</div>
							</div>
						</div>

						<div class="row data_sunat_cpe">
							<div class="col-md-3">
								<label><i class="icon-vcard position-left"></i> RUC Emisor:</label>
								<input type="text" autocomplete="nope" name="ruc_emisor_apicpe" id="ruc_emisor_apicpe" value="<?php if(isset($ruc_proveedor) && !empty($ruc_proveedor)) { echo $ruc_proveedor; } ?>" class="form-control input_editable">
							</div>
							<div class="col-md-3">
								<label><i class="icon-barcode2 position-left"></i> Serie Factura:</label>
								<input type="text" autocomplete="nope" name="serie_comprobante_apicpe" id="serie_comprobante_apicpe" value="<?php if(isset($serie_doc) && !empty($serie_doc)) { echo $serie_doc; } ?>" class="form-control input_editable">
							</div>
							<div class="col-md-3">
								<label><i class="icon-pencil position-left"></i> Correlativo:</label>
								<input type="text" autocomplete="nope" name="correlativo_comprobante_apicpe" id="correlativo_comprobante_apicpe" value="<?php if(isset($correlativo_doc) && !empty($correlativo_doc)) { echo $correlativo_doc; } ?>" class="form-control input_editable">
							</div>
							<div class="col-md-3">
								<button type="button" style="    margin-top: 26px;" class="btn btn-success btn-labeled legitRipple btn_get_cpe_sunat"><b><img style="max-width: 15px;" src="/facturacionv8/img/sunat_logo.png" /></b> Buscar en SUNAT</button>
							</div>
						</div>
						
						<div class="row data_manual_cpe" style="display: none;">
							<fieldset style="margin-top: 20px;">
								<legend class="text-bold" id="texto_datos_comprobante">Datos de la Factura: </legend>
							</fieldset>

							<div class="form-group col-md-3  col-xs-6" id="content_serie_comprobante">
								<label><i class="icon-barcode2 position-left"></i> Serie:</label>
								<input type="text" autocomplete="nope" name="serie_comprobante" id="serie_comprobante" value="" class="form-control input_editable">
							</div>

							<div class="form-group col-md-3  col-xs-6" id="content_numero_comprobante">
								<label><i class="icon-file-text2 position-left"></i> Número (6 números):</label>
								<input type="number" inputmode="numeric" pattern="[0-9]*" autocomplete="nope" name="numero_comprobante" id="numero_comprobante" value="" class="form-control input_editable">
							</div>

							<div class="form-group col-md-3 col-xs-6" id="content_fecha_comprobante">
								<label><i class="icon-calendar2 position-left"></i> Fecha.Doc:</label>
								<input type="text" value="<?php echo date('d/m/Y'); ?>" name="fecha_comprobante" id="fecha_comprobante" placeholder="" class="form-control control_fecha">
							</div>
							
							<div class="form-group col-md-3 col-xs-6" id="content_codmoneda_comprobante">
								<div class="has-feedback has-feedback-left">
									<label><i class="icon-cash2 position-left"></i>Moneda <span class="text-danger">*</span></label>
									<select title="Selecciona el Tipo de Moneda" data-placeholder="Selecciona Tu Moneda" class="select codmoneda_comprobante" name="codmoneda_comprobante" id="codmoneda_comprobante" required>
									
									</select>
								</div>                        
							</div>

							<div class="form-group col-md-3" id="content_tipo_cambio_comprobante" style="display: none;">
								<label><i class="icon-file-text2 position-left"></i> Tipo Cambio (SUNAT):</label>
								<input type="text" autocomplete="nope" name="tipo_cambio_comprobante" id="tipo_cambio_comprobante" value="" class="form-control input_editable">
							</div>
							
						</div>

						<div class="row" id="content_comprobante_modifica" style="display: none;">
							<fieldset style="margin-top: 20px;">
								<legend class="text-bold">Datos del Comprobante que Modifica: </legend> 
							</fieldset>

							<div class="form-group col-md-2">
								<div class="has-feedback has-feedback-left">
									<label><i class="icon-user position-left"></i>Tipo Doc.Electrónico:<span class="text-danger">*</span></label>
									<select title="Selecciona Tipo Doc.Electrónico" data-placeholder="Selecciona Tipo Doc.Electrónico" class="tipo_doc_modifica select" name="tipo_doc_modifica" id="tipo_doc_modifica">
										<option value="01">FACTURA</option>
										<option value="03">BOLETA</option>
									</select>
								</div>
							</div>
							<div class="form-group col-md-2">
								<label><i class="icon-file-text2 position-left"></i> SerieDoc.:</label>
								<input type="text" autocomplete="nope" name="serie_doc_modifica" id="serie_doc_modifica" value="" placeholder="SERIE" class="form-control input_editable">
							</div>

							<div class="form-group col-md-2">
								<label><i class="icon-file-text2 position-left"></i> Num.Doc.:</label>
								<input type="text" autocomplete="nope" name="numero_doc_modifica" id="numero_doc_modifica" value="" placeholder="NUMERO" class="form-control input_editable">
							</div>

							<div class="form-group col-md-3" id="contenido_motivo_nota_credito">
								<label><i class="icon-file-text2 position-left"></i>Motivo N.Crédito:<span class="text-danger">*</span></label>
								<select title="Motivo" data-placeholder="Motivo" class="id_motivo_nota_credito select" name="id_motivo_nota_credito" id="id_motivo_nota_credito">
									<option value="">Selecciona un Motivo</option>
								</select>
							</div>

							<div class="form-group col-md-3" id="contenido_motivo_nota_debito" style="display:none;">
								<label><i class="icon-file-text2 position-left"></i>Motivo N.Débito:<span class="text-danger">*</span></label>
								<select title="Motivo" data-placeholder="Motivo" class="id_motivo_nota_debito select" name="id_motivo_nota_debito" id="id_motivo_nota_debito">
									<option value="">Selecciona un Motivo</option>
								</select>
							</div>

							<div class="form-group col-md-3" id="fecha_doc_modifica">
								<label><i class="icon-calendar2 position-left"></i> Fecha.Doc.Modificado:</label>
								<input type="text" value="<?php echo date('d/m/Y'); ?>" name="fecha_doc_modifica" id="fecha_doc_modifica" placeholder="" class="form-control control_fecha">
							</div>
							
						</div>

						<div class="row data_manual_cpe" style="display: none;">
							<fieldset style="margin-top: 20px;">
								<legend class="text-bold">Datos del Proveedor: </legend>
							</fieldset>

							<input type="hidden" name="id_proveedor_documento" id="id_proveedor_documento" class="id_proveedor_documento" value="" />
								
							<div class="form-group col-md-3 col-xs-6">
								<div class="has-feedback has-feedback-left">
									<label><i class="icon-user position-left"></i>Tipo Doc.Ident.<span class="text-danger">*</span></label>
									<select title="Selecciona el tipo de documento" data-placeholder="Selecciona el Tipo de Doc." class="proveedor_tipo_docidentidad select" name="proveedor_tipo_docidentidad" id="proveedor_tipo_docidentidad">
									</select>
								</div>
							</div>
							<div class="form-group col-md-4 col-xs-6" id="estado_numerodocumento">
								<label><i class="icon-pencil position-left"></i> <span id="titulo_numerodocumento">N° de RUC</span>: <span class="text-danger">*</span></label>
								<div class="input-group">
									<input type="number" inputmode="numeric" pattern="[0-9]*" title="Número de Documento" name="proveedor_numerodocumento" id="proveedor_numerodocumento" placeholder="Número de documento Aquí!" class="form-control proveedor_numerodocumento input_editable" value="" required>
									<span class="input-group-btn">
										<button class="btn bg-indigo btn-icon legitRipple search_document" type="button">
											<i class="icon-search4" id="icon_search_document"></i>
											<i class="icon-spinner10 spinner position-left" style="display: none;" id="icon_searching_document"></i>
										</button>
									</span>
								</div>
							</div>

							<div class="form-group col-md-5 col-xs-12" id="razonsocial_numerodocumento">
								<label><i class="icon-vcard position-left"></i> <span id="titulo_nombrecliente">Razón Social</span>: <span class="text-danger">*</span></label>
								<input type="text" title="Ingresa la Razón Social o Nombre" name="proveedor_nombre" id="proveedor_nombre" placeholder="Nombre o Razón Social Aquí" class="form-control proveedor_nombre"  required>
							</div>

							<div class="form-group col-md-6 col-xs-6">
								<label><i class="icon-home2 position-left"></i> Dirección: </label>
								<input type="text" title="Ingresa la dirección completa" name="proveedor_direccion" id="proveedor_direccion" placeholder="Escribe aquí la dirección completa" class="form-control proveedor_direccion">
							</div>

							<div class="form-group col-md-6 col-xs-6">
								<div class="has-feedback has-feedback-left">
									<label><i class="icon-sphere position-left"></i>Ubigeo: </label>
									<select title="Selecciona tu Código de Ubigeo" data-placeholder="Selecciona Tu Código de Ubigeo" class="select_codigoubigeo" name="select_codigoubigeo" id="select_codigoubigeo">
									</select>
								</div>                        
							</div>
						</div>

						<div class="row data_manual_cpe" style="display: none;">
							<div class="col-md-6" style="font-size: 12px; padding-top: 10px; padding-bottom: 10px; text-transform: uppercase; font-weight: 700;">
								Lista de productos:
							</div>
							<div class="col-md-6 btn-options" style="text-align: right; padding-bottom: 4px;">
								
								<button type="button" class="btn btn-primary btn-labeled btn-xs mx-1 text-uppercase font-weight-bold legitRipple btn_editarproducto"><b><i class="icon-pencil3"></i></b> editar</button>
								<button type="button" class="btn btn-success btn-labeled btn-xs mx-1 text-uppercase font-weight-bold legitRipple btn_agregarproducto"><b><i class="icon-plus-circle2"></i></b> Agregar</button>
								<button type="button" class="btn btn-danger btn-labeled btn-xs mx-1 text-uppercase font-weight-bold legitRipple btn_eliminarproducto"><b><i class="icon-cross2"></i></b> Eliminar</button>
							</div>
							<div class="col-md-12 mb-6">
								<div class="jqGrid content_tabla_detalle">
									<table id='detalle_documento' class='scroll'></table>
								</div>
							</div>
						</div>

						<div class="row data_manual_cpe" style="display: none;">
							<div class="col-md-12">
								<legend class="text-bold">Detalle Documento: </legend>
							</div>
							<div class="row">
								<div class="col-md-7">
									

									<div class="row" id="contenido_total_condicionespago">
										<div class="col-sm-3 col-md-3" id="content_tipo_venta" style="margin-bottom: 17px; display: none;">
											<label class="label-form">¿Es al Crédito?</label>
											<div class="checkbox checkbox-switch">
												<input name="opcion_tipo_venta" id="opcion_tipo_venta" type="checkbox" data-on-text="Si" data-off-text="No" class="switch" data-size="mini">
											</div>
										</div>
										
										<div class="col-sm-3 col-md-3" id="content_fecha_pago" style="margin-bottom: 20px; display: none;">
											<label class="label-form"><i class="icon-calendar2 position-left"></i> Fecha de Pago:</label>
											<input type="text" value="<?php echo date('d/m/Y'); ?>" name="fecha_pago_comprobante" id="fecha_pago_comprobante" placeholder="" class="form-control control_fecha">
										</div>
										<div class="col-sm-3 col-md-3" id="content_monto_adeudado" style="display: none;">
											<div class="form-group">
												<label class="label-form">Monto Deuda: </label>
												<div class="input-group">
													<span class="input-group-addon font-weight-bold simbolo_moneda">S/.</span>
													<input type="text" value="" title="Monto Adeudado" name="txt_monto_adeudado" id="txt_monto_adeudado" placeholder="Adeudado" class="form-control txt_monto_adeudado">
												</div>
											</div>
										</div>

										<div class="col-sm-3 col-md-3" id="content_pago_parcial" style="margin-bottom: 20px; display: none;">
											<div class="form-group">
												<label class="label-form" for="pago_parcial">Monto Pagado: </label>
												<div class="input-group">
													<span class="input-group-addon font-weight-bold simbolo_moneda">S/.</span>
													<input type="text" class="form-control" value="" name="txt_pago_parcial" id="pago_parcial" placeholder="Pagado">
												</div>
											</div>
										</div>
										
										<div class="col-sm-6 col-md-6" id="content_condicionpago_comprobante">
											<div class="form-group">
												<div class="has-feedback has-feedback-left">
													<label class="label-form"><i class="icon-cash2 position-left"></i>Forma de Pago: </label>
													<select title="Selecciona una condición de pago" data-placeholder="Selecciona una condición de pago" class="select condicionpago_comprobante" name="condicionpago_comprobante" id="condicionpago_comprobante">
														<?php
														foreach($lista_condiciones_pago as $condicionpago) {
															if($condicionpago->tipo == 'contado') {
																echo "<option data-tipocondicion='".$condicionpago->tipo."' value='".$condicionpago->id_condicionpago."' selected>".$condicionpago->condicionpago."</option>";
															} else {
																echo "<option data-tipocondicion='".$condicionpago->tipo."' value='".$condicionpago->id_condicionpago."'>".$condicionpago->condicionpago."</option>";
															}
														}
														?>
													</select>
												</div>
											</div>
										</div>

										<div class="col-lg-4 col-sm-4 col-md-4" id="content_numero_operacion" style="display: none;">
											<div class="form-group">
												<label class="label-form"><i class="fa fa-hashtag position-left"></i> Num.Ope.: </label>
												<input type="text" value="" title="Número de Operación" name="txt_numero_operacion" id="txt_numero_operacion" placeholder="Número de Operación" class="form-control txt_numero_operacion">
											</div>
										</div>

										<div class="col-lg-4 col-sm-4 col-md-4" id="content_fecha_deposito">
											<div class="form-group">
												<label class="label-form"><i class="fa fa-calendar-check-o position-left"></i> Fecha Depósito: </label>
												<input type="text" value="<?php echo date('d/m/Y'); ?>" name="fecha_deposito" id="fecha_deposito" placeholder="" class="form-control control_fecha">
											</div>
										</div>	

										<div class="col-sm-6 col-md-6" id="content_cuenta_banco_deposito">
											<div class="form-group">
												<div class="has-feedback has-feedback-left">
													<label class="label-form"><i class="fa fa-bank position-left"></i>Banco: </label>
													<select title="Seleccionar Banco" data-placeholder="Seleccionar Banco" class="select_cuenta_banco_deposito select" name="select_cuenta_banco_deposito" id="select_cuenta_banco_deposito">
														<option value="0">Selecc. Banco</option>
														<?php
														foreach($cuentas_banco as $banco) {
															echo '<option value="'.$banco->id_cuentabanco.'">'.$banco->nro_cuenta.' - '.$banco->nombre_banco.'</option>';
														}
														?>
													</select>
												</div>
											</div>
										</div>
										
									</div>



									<div class="col-md-6"  id="content_descuento_porcentaje">
										<div class="form-group">
											<div class="checkbox checkbox-switchery switchery-xs" style="margin-top: 1px !important;">
												<label>
													<input checked type="checkbox" name="opcion_tipo_descuento" id="opcion_tipo_descuento" class="switchery opcion_tipo_descuento" checked="checked">
													<span id="txt_titulo_opcion_descuento">Descuento en Porcentaje</span>
												</label>
											</div>

											<div class="input-group" id="content_descuento_total" style="display: none;">
												<span class="input-group-addon font-weight-bold simbolo_descuento">S/.</span>
												<input type="number" inputmode="numeric" pattern="[0-9]*" title="Ingresa el Porcentaje de Descuento" name="txt_descuento_total" id="txt_descuento_total" placeholder="Porcentaje de Descuento Total" class="form-control txt_descuento_total input_modify_totales">
											</div>

											<div class="input-group" id="content_descuento_porcentaje_input">
												<span class="input-group-addon font-weight-bold simbolo_descuento">% </span>
												<input type="text" value="0.0" title="Ingresa el Porcentaje de Descuento" name="txt_descuento_porcentaje" id="txt_descuento_porcentaje" placeholder="Porcentaje de Descuento Total" class="form-control input_modify_totales txt_descuento_porcentaje">
											</div>

											
										</div>
									</div>

									<div class="col-md-6">
										<div class="has-feedback has-feedback-left">
											<label class="label-form" style="margin-bottom: 10px;"><i class="icon-box-add"></i> Almacén: <span class="text-danger">*</span></label>
											<select title="Selecciona una sucursal" data-placeholder="Selecciona una sucursal" class="select select_sucursal" name="select_sucursal" id="select_sucursal">
												
											</select>
										</div>
									</div>
									
									<div class="col-sm-12 col-md-12" id="input_otros_cargos" style="display:none;">
										<div class="form-group">
											<label><i class="icon-vcard position-left"></i> Otros Cargos <span class="simbolo_moneda">S/.</span>:</label>
											<input type="text" title="Ingresa otros montos" name="txt_otros_cargos_comprobante_input" id="txt_otros_cargos_comprobante_input" placeholder="Ingresa otros montos" class="form-control input_modify_totales txt_otros_cargos_comprobante_input" value="0.0">
										</div>
									</div>
									<div class="col-sm-12 col-md-12">
										<div class="form-group">
											<h6><i class="icon-notebook position-left"></i> Observación:</h6>
											<div class="mb-15 mt-15">
												<textarea rows="3" cols="3" name="observacion_documento" class="custom-textarea" placeholder="Escribe aquí una observación"></textarea>
											</div>
										</div>
									</div>
								</div>
								
								<div class="col-md-5">
									<div class="content-group" id="content_resumen_doc_electronico">
										<h6>Resumen:</h6>
										<div class="table-responsive no-border">
											<table class="table">
												<tbody>
													<tr id="row_gravada_documento">
														<th>Gravada:</th>
														<td class="text-right">
															<span class="simbolo_moneda">S/.</span> <span id="gravada_documento">0.0</span>
															<input type="hidden" name="txt_gravada_comprobante" id="txt_gravada_comprobante" value="0">
														</td>
													</tr>
													<tr id="row_exonerada_documento" style="display:none;">
														<th>Exonerada:</th>
														<td class="text-right">
															<span class="simbolo_moneda">S/.</span> <span id="exonerada_documento">0.0</span>
															<input type="hidden" name="txt_exonerada_comprobante" id="txt_exonerada_comprobante" value="0">
														</td>
													</tr>
													<tr id="row_inafecta_documento" style="display:none;">
														<th>Inafecta:</th>
														<td class="text-right">
															<span class="simbolo_moneda">S/.</span> <span id="inafecta_documento">0.0</span>
															<input type="hidden" name="txt_inafecta_comprobante" id="txt_inafecta_comprobante" value="0">
														</td>
													</tr>
													<tr id="row_exportacion_documento" style="display:none;">
														<th>Exportación:</th>
														<td class="text-right">
															<span class="simbolo_moneda">S/.</span> <span id="exportacion_documento">0.0</span>
															<input type="hidden" name="txt_exportacion_comprobante" id="txt_exportacion_comprobante" value="0">
														</td>
													</tr>
													<tr id="row_igv_documento">
														<th>IGV: <span class="text-regular">(18%)</span></th>
														<td class="text-right">
															<span class="simbolo_moneda">S/.</span> <span id="igv_documento">0.0</span>
															<input type="hidden" name="txt_igv_comprobante" id="txt_igv_comprobante" value="0">
														</td>
													</tr>
													<tr id="row_gratuita_documento" style="display:none;">
														<th>Gratuita:</th>
														<td class="text-right">
															<span class="simbolo_moneda">S/.</span> <span id="gratuita_documento">0.0</span>
															<input type="hidden" name="txt_gratuita_comprobante" id="txt_gratuita_comprobante" value="0">
														</td>
													</tr>
													<tr id="row_icbper_documento" style="display:none;">
														<th>Imp.ICBPER:</th>
														<td class="text-right">
															<span class="simbolo_moneda">S/.</span> <span id="icbper_documento">0.0</span>
															<input type="hidden" name="txt_icbper_comprobante" id="txt_icbper_comprobante" value="0">
														</td>
													</tr>
													<tr id="row_otros_cargos_documento" style="display:none;">
														<th><span class="text-primary">(+)</span> Otros Cargos:</th>
														<td class="text-right">
															<span class="simbolo_moneda">S/.</span> <span id="otros_cargos_documento">0.0</span>
															<input type="hidden" name="txt_otros_cargos_comprobante" id="txt_otros_cargos_comprobante" value="0">
														</td>
													</tr>
													<tr id="row_descuento_documento">
														<th><span class="text-danger">(-)</span> Descuento Total:</th>
														<td class="text-right">
															<span class="simbolo_moneda">S/.</span> <span id="descuento_documento">0.0</span>
															<input type="hidden" name="txt_descuento_comprobante" id="txt_descuento_comprobante" value="0">
														</td>
													</tr>
													<tr id="row_total_documento">
														<th>Total:</th>
														<td class="text-right text-primary"><h5 class="text-semibold">
															<span class="simbolo_moneda">S/.</span> <span id="total_documento">0.0</span></h5>
															<input type="hidden" name="txt_total_comprobante" id="txt_total_comprobante" value="0">
														</td>
													</tr>
												</tbody>
											</table>
										</div>
										<input type="hidden" name="txt_total_letras" id="txt_total_letras" value="" />
									</div>
								</div>
							</div>
							
						</div>
					</form>

					
					<div class="col-md-12 text-center data_manual_cpe" style="padding-bottom: 25px; display: none;">
						<button id="btn_guardar_compra" type="button" class="btn btn-primary btn-labeled btn-xs legitRipple text-uppercase font-weight-bold"><b><i class="icon-floppy-disk"></i></b> Guardar Documento Electrónico</button>
					</div>
				</div>
			</div>

			<div class="panel data_sunat_cpe" style="max-width: 1100px; margin: 0 auto; margin-top: 25px;">
				<div class="panel-body" id="cuerpo_comprobante_xml">
					<div class="row data_sunat_cpe">
						<div class="col-md-12">
							<div class="alert alert-success alert-styled-left content-group">¡Si tienes el archivo .XML también puedes subirlo al sistema para su lectura automática!</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<div class="col-md-12">
									<input type="file" class="file-styled" name="file_xml_comprobante" id="file_xml_comprobante" placeholder="Selecciona tu Certificado" accept=".xml">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>




<!-- vm_agregar_articulo -->
<div id="vm_agregar_articulo" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body" style="padding: 0px 10px 10px 10px;" id="content_popup_producto">
				<div class="row">
					<div class="tabbable">
						<ul class="nav nav-tabs bg-indigo opciones_producto">
							<li class="active"><a href="#buscar_producto" data-toggle="tab"><i class="icon-search4 position-left"></i> Buscar Producto</a></li>
							<li><a href="#registrar_producto" data-toggle="tab"><i class="icon-file-plus position-left"></i> Registrar Nuevo Producto</a></li>
							<li class="li-close"><button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true" class="icono-close">&times;</span>
							  </button></li>
						</ul>

						<div class="tab-content" style="padding: 0px 15px 10px 15px;">
							<div class="tab-pane active" id="buscar_producto">
								{{ partial('gestiondecompras/buscar_producto') }}
							</div>

							<div class="tab-pane" id="registrar_producto">
								{{ partial('gestiondecompras/registrar_producto') }}
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
<!-- /vm_agregar_articulo -->

<script>
	var num_decimales = <?php echo $numero_decimales; ?>;
	var impuesto_icbper = <?php echo $impuesto_icbper; ?>;
</script>



<div id="modal_msg_guardado" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
			<div class="modal-header">
                <h5 class="modal-title" id="titulo_msg_guardado"></h5>
			</div>
            <div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div class="alert alert-success alert-bordered" id="mensaje_msg_guardado">
							
						</div>
					</div>
					<div class="col-md-12 btn-options" style="text-align: right; padding-bottom: 4px;">
						<a href="#" target="_blank" id="enlace_a4_msg_guardado" class="btn btn-link btn-float has-text"><img src="/facturacionv8/img/svg/pdf_cpe.svg" style="width: 25px;"><span>A4</span></a>
						<a href="#" target="_blank" id="enlace_ticket_msg_guardado" class="btn btn-link btn-float has-text"><img src="/facturacionv8/img/svg/ticket_cpe.svg" style="width: 25px;"><span>Ticket</span></a>
						<a href="#" target="_blank" id="enlace_whatsapp_msg_guardado" class="btn btn-link btn-float has-text"><img src="/facturacionv8/img/svg/whatsapp.svg" style="width: 25px;"><span>WhatsApp</span></a>
						<a href="#" target="_blank" id="enlace_guiaremision_msg_guardado" class="btn btn-link btn-float has-text"><img src="/facturacionv8/img/svg/guia_remision.svg" style="width: 25px;"><span>Crear Guía</span></a>
					</div>
					<div class="col-md-12" id="content_pdf_preview_ticket">
						
					</div>
					<div class="col-md-12" style="display: none;" id="content_pdf_preview_a4">
						
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<a href="/facturacionv8/gestiondecompras" type="button" class="btn bg-success">Lista de Compras</a>
                <a href="/facturacionv8/gestiondecompras/registrarcompra" type="button" class="btn bg-indigo">Crear Nuevo Documento</a>
            </div>
        </div>
    </div>
</div>