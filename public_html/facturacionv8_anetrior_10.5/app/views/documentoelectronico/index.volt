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
	.input-group .form-control:not(:first-child):not(:last-child), .input-group-addon:not(:first-child):not(:last-child), .input-group-btn:not(:first-child):not(:last-child) {
		border-top-left-radius: 15px;
		border-top-right-radius: 0px;
		border-bottom-right-radius: 0px;
		border-bottom-left-radius: 15px;
	}
	.cliente_nombre{
		border-bottom-left-radius: 15px!important; 
     	border-top-left-radius: 15px!important; 
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
	
	.opciones_producto {
		border-top-left-radius: 15px;
		border-top-right-radius: 15px;
		background: linear-gradient(45deg, #7880f0 0%, #b4b9ff 100%);
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
	li.li-close {
		position: absolute;
		top: .7em;
    	right: 1em
	}
	.icono-close{
		font-weight: 700;
    	color: #FFF;
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
	.label-form i {
    background: -webkit-linear-gradient(#7880f0, #3f51b5);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
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
@media only screen and (max-width: 400px) {
	.modal-dialog {
        position: relative;
    width: auto;
    margin: 0px!important;
    height: 100%;
    overflow: inherit!important;
    transform: inherit!important;
	
	}
}
@media (min-width: 600px) and (max-width: 1000px) {
	.modal-dialog {
    position: relative;
    width: auto; 
    margin: 0px!important;
    height: 100%;
    overflow: inherit!important;
	transform: inherit!important;
	}
}
/* FIN CSS SELECT 2 - RESULTADOS FORMATEADOS */
</style>
<input type="hidden" id="tipo_doc_selected" value="<?php echo $tipo_doc; ?>" />
<div class="page-header" style="display: none;">
	<?php echo $html_suscripcion; ?>
	<div class="page-header-content"  style="max-width: 1100px; margin: 0 auto;">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Documento eléctronico: <strong id="texto_nombre_documento">Factura</strong></span></h4>
		</div>
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
				<a href="/facturacionv8/reportedocumentos" class="btn btn-link btn-float has-text">
					<img src="/facturacionv8/img/lista_documentos.svg" style="width: 25px;"/>
					<span>Ver Documentos</span></a>
			</div>
		</div>
	</div>
</div>

<div class="content header-top">
	
	<div class="row">
		<div class="navbar navbar-default navbar-component navbar-xs" style="position: relative; z-index: 27; max-width: 1100px; margin: 0 auto; margin-top: 15px; margin-bottom: 15px;">
			<ul class="nav navbar-nav visible-xs-block">
				<li class="full-width text-center"><a data-toggle="collapse" data-target="#menu_punto_venta"><i class="icon-menu7"></i></a></li>
			</ul>

			<div class="navbar-collapse collapse" id="menu_punto_venta">
				<ul class="nav navbar-nav">
					<li class="active"><a id="titulo_tipo_documento" href="#activity" data-toggle="tab"><img src="/facturacionv8/img/boleta.svg" style="width: 18px;"/> Boleta</a></li>
				</ul>
				
				<ul class="nav navbar-nav navbar-right">
					
					<li><a class="btn_tipo_documento" data-tipodocumento="01" href="javascript:void(0)"><img id="img_factura" src="/facturacionv8/img/factura.svg" style="width: 18px;"/> Crear Factura</a></li>
					<li><a class="btn_tipo_documento" data-tipodocumento="03" href="javascript:void(0)"><img id="img_boleta" src="/facturacionv8/img/boleta.svg" style="width: 18px;"/> Crear Boleta</a></li>
					<li><a class="btn_tipo_documento" data-tipodocumento="77" href="javascript:void(0)"><img id="img_nota_venta" src="/facturacionv8/img/nota_venta.svg" style="width: 18px;"/> Nota de Venta</a></li>
					<li><a class="btn_tipo_documento" data-tipodocumento="88" href="javascript:void(0)"><img id="img_cotizacion" src="/facturacionv8/img/cotizacion.svg" style="width: 18px;"/> Cotización</a></li>
					
					<li class="dropdown mega-menu mega-menu-wide">
						<a href="#" data-toggle="dropdown" class="dropdown-toggle"><i class="icon-cog3"></i> Opc. Avanzadas <span class="caret"></span></a>
						<div class="dropdown-menu dropdown-content">
							<div class="dropdown-content-body">
								<div>
									
									<div class="row">
										<div class="col-sm-4">
											<div class="checkbox">
												<label>
													<input type="checkbox" id="opt_avanzadas_tipooperacion" name="opt_avanzadas_tipooperacion" class="control-primary opt_avanzadas">
													Tipo de Operación
												</label>
											</div>
										</div>

										<div class="col-sm-4">
											<div class="checkbox">
												<label>
													<input type="checkbox" id="opt_avanzadas_tipodocumento" name="opt_avanzadas_tipodocumento" class="control-info opt_avanzadas">
													Tipo de Documento Electrónico
												</label>
											</div>
										</div>

										<div class="col-sm-4">
											<div class="checkbox">
												<label>
													<input type="checkbox" id="opt_avanzadas_moneda" name="opt_avanzadas_moneda" class="control-success opt_avanzadas">
													Tipo de Moneda
												</label>
											</div>
										</div>

										<div class="col-sm-4">
											<div class="checkbox">
												<label>
													<input type="checkbox" id="opt_avanzadas_sucursal" name="opt_avanzadas_sucursal" class="control-danger opt_avanzadas">
													Lista de Sucursales
												</label>
											</div>
										</div>

										<div class="col-sm-4">
											<div class="checkbox">
												<label>
													<input type="checkbox" id="opt_avanzadas_seriecomprobante" name="opt_avanzadas_seriecomprobante" class="control-primary opt_avanzadas">
													Serie Comprobante
												</label>
											</div>
										</div>

										<div class="col-sm-4">
											<div class="checkbox">
												<label>
													<input type="checkbox" id="opt_avanzadas_numcomprobante" name="opt_avanzadas_numcomprobante" class="control-info opt_avanzadas">
													Número Comprobante
												</label>
											</div>
										</div>

										<div class="col-sm-4">
											<div class="checkbox">
												<label>
													<input type="checkbox" id="opt_avanzadas_fechacomprobante" name="opt_avanzadas_fechacomprobante" class="control-success opt_avanzadas">
													Fecha del Documento
												</label>
											</div>
										</div>

										<div class="col-sm-4">
											<div class="checkbox">
												<label>
													<input type="checkbox" id="opt_avanzadas_fechavenc_comprobante" name="opt_avanzadas_fechavenc_comprobante" class="control-danger opt_avanzadas">
													Fecha Vencimiento del Documento
												</label>
											</div>
										</div>

										<div class="col-sm-4">
											<div class="checkbox">
												<label>
													<input type="checkbox" id="opt_avanzadas_tipocambio" name="opt_avanzadas_tipocambio" class="control-primary opt_avanzadas">
													Tipo de Cambio (SUNAT)
												</label>
											</div>
										</div>

										<div class="col-sm-4">
											<div class="checkbox">
												<label>
													<input type="checkbox" id="opt_avanzadas_numeroplaca" name="opt_avanzadas_numeroplaca" class="control-info opt_avanzadas">
													N° Placa Vehículo
												</label>
											</div>
										</div>

										<div class="col-sm-4">
											<div class="checkbox">
												<label>
													<input type="checkbox" id="opt_avanzadas_numero_orden" name="opt_avanzadas_numero_orden" class="control-success opt_avanzadas">
													N° de Orden
												</label>
											</div>
										</div>

										<div class="col-sm-4">
											<div class="checkbox">
												<label>
													<input type="checkbox" id="opt_avanzadas_gremision_electronica" name="opt_avanzadas_gremision_electronica" class="control-danger opt_avanzadas">
													N° Guía Remisión Electrónica
												</label>
											</div>
										</div>

										<div class="col-sm-4">
											<div class="checkbox">
												<label>
													<input type="checkbox" id="opt_avanzadas_gremision_manual" name="opt_avanzadas_gremision_manual" class="control-primary opt_avanzadas">
													N° Guía Remisión Manual
												</label>
											</div>
										</div>

										<div class="col-sm-4" <?php if($usuario->id_rol == 4) { echo "style='display:none;' "; } ?>>
											<div class="checkbox">
												<label>
													<input type="checkbox" id="opt_avanzadas_lista_usuarios" name="opt_avanzadas_lista_usuarios" class="control-info opt_avanzadas">
													Lista de Colaboradores
												</label>
											</div>
										</div>

										<div class="col-sm-4">
											<div class="checkbox">
												<label>
													<input type="checkbox" id="opt_avanzadas_direccion" name="opt_avanzadas_direccion" class="control-success opt_avanzadas" checked>
													Dirección del Cliente
												</label>
											</div>
										</div>

										<div class="col-sm-4">
											<div class="checkbox">
												<label>
													<input type="checkbox" id="opt_avanzadas_ubigeo" name="opt_avanzadas_ubigeo" class="control-danger opt_avanzadas" checked>
													Ubigeo/Ubicación del Cliente
												</label>
											</div>
										</div>

										<div class="col-sm-4">
											<div class="checkbox">
												<label>
													<input type="checkbox" id="opt_avanzadas_numcelular" name="opt_avanzadas_numcelular" class="control-primary opt_avanzadas" checked>
													Número de Celular
												</label>
											</div>
										</div>

										<div class="col-sm-4">
											<div class="checkbox">
												<label>
													<input type="checkbox" id="opt_avanzadas_retencion" name="opt_avanzadas_retencion" class="control-primary opt_avanzadas">
													Retención
												</label>
											</div>
										</div>

										<div class="col-sm-4">
											<div class="checkbox">
												<label>
													<input type="checkbox" id="opt_avanzadas_etiquetas" name="opt_avanzadas_etiquetas" class="control-primary opt_avanzadas">
													Etiquetas
												</label>
											</div>
										</div>

										<div class="col-sm-4">
											<div class="checkbox">
												<label>
													<input type="checkbox" id="opt_avanzadas_igv_sunat" name="opt_avanzadas_igv_sunat" class="control-primary opt_avanzadas">
													IGV - SUNAT
												</label>
											</div>
										</div>

										<div class="col-sm-4">
											<div class="checkbox">
												<label>
													<input type="checkbox" id="opt_avanzadas_tiene_anticipos" name="opt_avanzadas_tiene_anticipos" class="control-primary opt_avanzadas">
													Anticipo
												</label>
											</div>
										</div>

									</div>
								</div>
							</div>
						</div>
					</li>

				</ul>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-12" style="margin-bottom: 15px;">
			<div class="panel" style="max-width: 1100px; margin: 0 auto;">
				<form action="" method="post" name="frm_documentoelectronico" id="frm_documentoelectronico" class="frm_documentoelectronico">
					<div class="panel-body" id="cuerpo_comprobante" style="padding-top: 0px !important;">

						<input type="hidden" name="documento_tipo_accion" id="documento_tipo_accion" value="<?php echo $accion_cpe; ?>" />
						
						<input type="hidden" name="id_usuario_sistema" id="id_usuario_sistema" value="<?php echo $usuario->idusuario; ?>" />
						<input type="hidden" name="doc_guardado_id_tipodoc_electronico" id="doc_guardado_id_tipodoc_electronico" value="<?php echo $tipo_doc; ?>" />
						<input type="hidden" name="doc_guardado_serie_comprobante" id="doc_guardado_serie_comprobante" value="<?php echo $serie_comprobante; ?>" />
						<input type="hidden" name="doc_guardado_numero_comprobante" id="doc_guardado_numero_comprobante" value="<?php echo $numero_comprobante; ?>" />

						<input type="hidden" name="tipo_doc_guardado" id="tipo_doc_guardado" value="<?php if(!empty($id_tipodoc_guardado)){echo $id_tipodoc_guardado; } ?>" />
						<input type="hidden" name="numero_doc_guardado" id="numero_doc_guardado" value="<?php if(!empty($numero_doc_guardado)){echo $numero_doc_guardado; } ?>" />
						<input type="hidden" name="serie_doc_guardado" id="serie_doc_guardado" value="<?php if(!empty($serie_doc_guardado)){echo $serie_doc_guardado; } ?>" />

						<input type="hidden" name="doc_modifica_id_tipodoc_electronico" id="doc_modifica_id_tipodoc_electronico" value="<?php echo $tipo_doc_modifica; ?>" />
						<input type="hidden" name="doc_modifica_serie_comprobante" id="doc_modifica_serie_comprobante" value="<?php echo $serie_comprobante_modifica; ?>" />
						<input type="hidden" name="doc_modifica_numero_comprobante" id="doc_modifica_numero_comprobante" value="<?php echo $numero_comprobante_modifica; ?>" />

						<input type="hidden" name="doc_impuesto_icbper" id="doc_impuesto_icbper" value="<?php echo $impuesto_icbper; ?>" />
						<input type="hidden" name="doc_regimen_retencion" id="doc_regimen_retencion" value="<?php echo $regimen_retencion; ?>" />

						<input type="hidden" name="c_restriccion_stock" id="c_restriccion_stock" value="<?php if($contribuyente->restriccion_stock == 'si') { echo 'si'; } else { echo 'no'; } ?>" />

						<!-- DATOS DEL COMPROBANTE QUE ORIGINA EL NUEVO COMPROBANTE -->
						<input type="hidden" name="origen_id_contribuyente" id="origen_id_contribuyente" value="<?php echo $origen_id_contribuyente; ?>" />
						<input type="hidden" name="origen_id_tipodoc_electronico" id="origen_id_tipodoc_electronico" value="<?php echo $origen_id_tipodoc_electronico; ?>" />
						<input type="hidden" name="origen_serie_comprobante" id="origen_serie_comprobante" value="<?php echo $origen_serie_comprobante; ?>" />
						<input type="hidden" name="origen_numero_comprobante" id="origen_numero_comprobante" value="<?php echo $origen_numero_comprobante; ?>" />
						<input type="hidden" name="origen_tipo_envio_sunat" id="origen_tipo_envio_sunat" value="<?php echo $origen_tipo_envio_sunat; ?>" />
						<!-- /DATOS DEL COMPROBANTE QUE ORIGINA EL NUEVO COMPROBANTE -->
						

						<div class="row" id="grupo_opciones_avanzadas" style="margin-top: 15px;">
							<div class="form-group col-md-3 col-xs-6" id="control_tipooperacion" style="display: none;">
								<div class="has-feedback has-feedback-left">
									<label class="label-form"><i class="icon-profile position-left"></i>Tipo de Operación <span class="text-danger">*</span></label>
									<select title="Selecciona Tipo Operación" data-placeholder="Selecciona Tipo Operación" class="tipo_operacion_docelectronico select" name="tipo_operacion_docelectronico" id="tipo_operacion_docelectronico">
										<?php
										foreach($tipo_operacion as $tipooperacion) {
											echo '<option value="'.$tipooperacion->id_codigotipooperacion.'">'.$tipooperacion->descripcion.'</option>';
										}
										?>
									</select>
								</div>
							</div>

							<div class="form-group col-md-3 col-xs-6" id="control_tipodocumento" style="display: none;">
								<div class="has-feedback has-feedback-left">
									<label class="label-form"><i class="icon-profile position-left"></i>Documento <span class="text-danger">*</span></label>
									<select title="Selecciona un Tipo de Documento Electrónico" data-placeholder="Selecciona un Tipo de Documento Electrónico" class="select select_tipo_doc_electronico" name="select_tipo_doc_electronico" id="select_tipo_doc_electronico" required>
										<option value="01">FACTURA</option>
										<option value="03">BOLETA</option>
										<option value="07">NOTA DE CRÉDITO</option>
										<option value="08">NOTA DE DÉBITO</option>
										<option value="77">NOTA DE VENTA</option>
										<option value="88">COTIZACIÓN</option>
									</select>
								</div>
							</div>

							<div class="form-group col-md-3 col-xs-6" id="control_moneda" style="display: none;">
								<div class="has-feedback has-feedback-left">
									<label class="label-form"><i class="icon-cash2 position-left"></i>Moneda <span class="text-danger">*</span></label>
									<select title="Selecciona el Tipo de Moneda" data-placeholder="Selecciona Tu Moneda" class="select codmoneda_comprobante" name="codmoneda_comprobante" id="codmoneda_comprobante" required>
										<option data-simbolo="S/" value="PEN" selected="">Soles (S/)</option>
										<option data-simbolo="$" value="USD">Dólares Americanos ($)</option>
									</select>
								</div>                        
							</div>

							<div class="form-group col-md-3 col-xs-12" id="control_sucursal" style="display: none;">
								<div class="has-feedback has-feedback-left">
									<label class="label-form"><i class="icon-profile position-left"></i>Selecciona la Sucursal <span class="text-danger">*</span></label>
									<select title="Selecciona una sucursal" data-placeholder="Selecciona una sucursal" class="select select_sucursal" name="select_sucursal" id="select_sucursal" required>
										
									</select>
								</div>
							</div>
							
							<div class="form-group col-md-3 col-xs-6" id="control_seriecomprobante" style="display: none;">
								<label class="label-form"><i class="icon-barcode2 position-left"></i> Serie:</label>
								<input type="text" name="serie_comprobante" id="serie_comprobante" value="-" class="form-control" readonly="readonly">
							</div>

							<div class="form-group col-md-3 col-xs-6" id="control_numcomprobante" style="display: none;">
								<label class="label-form"><i class="icon-file-text2 position-left"></i> Número:</label>
								<input type="text" name="numero_comprobante" id="numero_comprobante" value="-" class="form-control" readonly="readonly">
							</div>

							<div class="form-group col-md-3 col-xs-6" id="control_fechacomprobante" style="display: none;">
								<label class="label-form"><i class="icon-calendar2 position-left"></i> Fecha.Doc:</label>
								<input type="text" value="<?php echo date('d/m/Y'); ?>" name="fecha_comprobante" id="fecha_comprobante" placeholder="" class="form-control control_fecha">
							</div>

							<div class="form-group col-md-3 col-xs-6" id="control_fechavenc_comprobante" style="display: none;">
								<label class="label-form"><i class="icon-calendar2 position-left"></i> Fecha.Venc.:</label>
								<input type="text" value="<?php echo date('d/m/Y'); ?>" name="fecha_vence_comprobante" id="fecha_vence_comprobante" placeholder="" class="form-control control_fecha">
							</div>

							<div class="form-group col-md-3 col-xs-12" id="control_tipocambio" style="display: none;">
								<label class="label-form"><i class="icon-file-text2 position-left"></i> Tipo Cambio (SUNAT):</label>
								<input type="text" name="tipo_cambio_comprobante" id="tipo_cambio_comprobante" value="" class="form-control">
							</div>

							<div class="form-group col-md-3 col-xs-12" id="control_numeroplaca" style="display: none;">
								<label id="label_nro_placa_vehiculo" class="label-form"><i class="icon-file-text2 position-left"></i> N° Placa Vehículo:</label>
								<input type="text" name="nro_placa_vehiculo" id="nro_placa_vehiculo" placeholder="Número Placa" value="" class="form-control">
							</div>

							<div class="form-group col-md-3 col-xs-12" id="control_numero_orden" style="display: none;">
								<label id="label_nro_orden" class="label-form"><i class="icon-file-text2 position-left"></i> N° de Orden:</label>
								<input type="text" name="nro_orden" id="nro_orden" placeholder="Número de Orden" value="" class="form-control">
							</div>

							<div class="form-group col-md-3 col-xs-12" id="control_gremision_electronica" style="display: none;">
								<div class="has-feedback has-feedback-left">
									<label class="label-form"><i class="icon-profile position-left"></i>Guia de Remisión Electrónica </label>
									<select title="Selecciona una opción" data-placeholder="Selecciona una opción" class="select id_guia_remision_electronica" name="id_guia_remision_electronica" id="id_guia_remision_electronica" required>
										
									</select>
								</div>
							</div>

							<div class="form-group col-md-3 col-xs-12" id="control_gremision_manual" style="display: none;">
								<label class="label-form"><i class="icon-file-text2 position-left"></i> Guía Remisión (Manual):</label>
								<input type="text" name="guia_remision_manual" id="guia_remision_manual" placeholder="####-######" value="" class="form-control">
							</div>

							<div class="form-group col-md-3 col-xs-12" id="control_lista_usuarios" style="display: none;">
								<div class="has-feedback has-feedback-left">
									<label class="label-form"><i class="icon-profile position-left"></i>Asignar Venta a: <span class="text-danger">*</span></label>
									<select title="Selecciona el Usuario" data-placeholder="Selecciona el Usuario" class="select select_usuario_vendedor" name="select_usuario_vendedor" id="select_usuario_vendedor">
										<?php
										foreach($vendedores as $vendedor) {
											if($usuario->idusuario == $vendedor->idusuario) {
												echo '<option value="'.$vendedor->idusuario.'" selected>'.$vendedor->idusuario.'.- '.$vendedor->nombre.' '.$vendedor->apellido.'</option>';
											} else {
												echo '<option value="'.$vendedor->idusuario.'">'.$vendedor->idusuario.'.- '.$vendedor->nombre.' '.$vendedor->apellido.'</option>';
											}
										}
										?>
									</select>
								</div>
							</div>

							<div class="form-group col-md-3 col-xs-12" id="control_retencion" style="display: none;">
								<div class="has-feedback has-feedback-left">
									<label class="label-form"><i class="icon-profile position-left"></i>¿Aplica Retención?</label>
									<select title="¿Aplica Retención?" data-placeholder="¿Aplica Retención?" class="select select_aplica_retencion" name="select_aplica_retencion" id="select_aplica_retencion">
										<option value="no" selected>No</option>
										<option value="si">Si</option>
									</select>
								</div>
							</div>

							<div class="form-group col-md-3 col-xs-12" id="control_etiquetas" style="display: none;">
								<div class="has-feedback has-feedback-left">
									<label class="label-form"><i class="icon-profile position-left"></i>Etiquetas:</label>
									<select name="select_etiquetas" id="select_etiquetas" class="multiselect select_etiquetas" multiple="multiple">
										<?php
										foreach($lista_etiquetas as $etiqueta) {
											if($etiqueta['seleccionado'] == 'si') {
												echo '<option value="'.$etiqueta['id_etiqueta'].'" selected>'.$etiqueta['nombre'].'</option>';
											} else {
												echo '<option value="'.$etiqueta['id_etiqueta'].'">'.$etiqueta['nombre'].'</option>';
											}
										}
										?>
									</select>
								</div>
							</div>

							<div class="form-group col-md-3 col-xs-12" id="control_igv_sunat" style="display: none;">
								<div class="has-feedback has-feedback-left">
									<label class="label-form"><i class="icon-profile position-left"></i>IGV - SUNAT</label>
									<select title="IGV" data-placeholder="IGV" class="select select_igv_sunat" name="select_igv_sunat" id="select_igv_sunat">
										<option value="18" selected>18%</option>
										<option value="10">10%</option>
									</select>
								</div>
							</div>

							<div class="form-group col-md-3 col-xs-12" id="control_tiene_anticipos" style="display: none;">
								<div class="has-feedback has-feedback-left">
									<label class="label-form"><i class="icon-profile position-left"></i>¿Tiene Anticipos?</label>
									<select title="¿Tiene Anticipos?" data-placeholder="¿Tiene Anticipos?" class="select select_tiene_anticipos" name="select_tiene_anticipos" id="select_tiene_anticipos">
										<option value="no" selected>No</option>
										<option value="si">Si</option>
									</select>
								</div>
							</div>

						</div>

						<div class="row" id="contenido_nota_credito_debito" style="display:none;">
							<legend class="text-bold">Documento a Modificar: </legend>
							<div class="form-group col-md-3 data_no_modificable_notas">
								<div class="has-feedback has-feedback-left">
									<label class="label-form"><i class="icon-user position-left"></i>Tipo Doc.Electrónico:<span class="text-danger">*</span></label>
									<select title="Selecciona Tipo Doc.Electrónico" data-placeholder="Selecciona Tipo Doc.Electrónico" class="tipo_docelectronico_modificar select" name="tipo_docelectronico_modificar" id="tipo_docelectronico_modificar">
										<option value="01">FACTURA</option>
										<option value="03">BOLETA</option>
									</select>
								</div>
							</div>
							<div class="form-group col-md-4 data_no_modificable_notas">
								<button type="button" style="float: right; margin-top: 27px;" class="btn bg-indigo btn-icon legitRipple btn_search_docelectronico"><i class="icon-search4" id="icon_search_docelectronico"></i><i class="icon-spinner10 spinner position-left" style="display: none;" id="icon_searching_docelectronico"></i></button>
								<div style="overflow: hidden; padding-right: .5em;">
									<label class="label-form"><i class="icon-barcode2 position-left"></i>Serie y Número de Doc.Elect.:<span class="text-danger">*</span></label>
									<select title="Buscar Doc.Elect." data-placeholder="Buscar Doc.Elect." class="serie_numero_doc_modificar select" name="serie_numero_doc_modificar" id="serie_numero_doc_modificar">
									</select>
								</div>​
							</div>

							<div class="form-group col-md-5" id="contenido_motivo_nota_credito">
								<label class="label-form"><i class="icon-file-text2 position-left"></i>Motivo:<span class="text-danger">*</span></label>
								<select title="Motivo" data-placeholder="Motivo" class="id_motivo_nota_credito select" name="id_motivo_nota_credito" id="id_motivo_nota_credito">
									<option value="">Selecciona un Motivo</option>
								</select>
							</div>

							<div class="form-group col-md-5" id="contenido_motivo_nota_debito" style="display:none;">
								<label class="label-form"><i class="icon-file-text2 position-left"></i>Motivo:<span class="text-danger">*</span></label>
								<select title="Motivo" data-placeholder="Motivo" class="id_motivo_nota_debito select" name="id_motivo_nota_debito" id="id_motivo_nota_debito">
									<option value="">Selecciona un Motivo</option>
								</select>
							</div>
							
							<div class="col-md-12" id="content_data_doc_modificado" style="display:none;">
								<div class="panel panel-flat">
									<div class="panel-heading">
										<h6 class="panel-title"><strong id="preview_tipo_comprobante"></strong><a class="heading-elements-toggle"><i class="icon-more"></i></a></h6>
										<div class="heading-elements" id="preview_heading_comprobante">
											<span class="heading-text"><i class="icon-history text-warning position-left"></i> <span id="preview_fecha_comprobante">Jul 7, 10:30</span></span>
											<span class="label bg-success heading-text" id="preview_estado_enviosunat">Online</span>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<legend class="text-bold">Cliente: </legend>
							<input type="hidden" name="id_cliente_documento" id="id_cliente_documento" class="id_cliente_documento" value="" />
							
							<div class="col-md-3">
								<div class="form-group">
									<div class="has-feedback has-feedback-left">
										<label class="label-form"><i class="icon-user position-left"></i>Tipo Doc.Ident.<span class="text-danger">*</span></label>
										<select title="Selecciona el tipo de documento" data-placeholder="Selecciona el Tipo de Doc." class="cliente_tipo_docidentidad select" name="cliente_tipo_docidentidad" id="cliente_tipo_docidentidad">
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-4" id="estado_numerodocumento">
								<div class="form-group">
									<label class="label-form"><i class="icon-pencil position-left"></i> <span id="titulo_numerodocumento">N° de RUC</span>: <span class="text-danger">*</span></label>
									<div class="input-group">
										<input type="number" inputmode="numeric" pattern="[0-9]*" title="Número de Documento" name="cliente_numerodocumento" id="cliente_numerodocumento" placeholder="Número de documento Aquí!" class="form-control cliente_numerodocumento" required>
										<span class="input-group-btn">
											<button class="btn bg-indigo btn-icon legitRipple search_document" type="button">
												<i class="icon-search4" id="icon_search_document"></i>
												<i class="icon-spinner10 spinner position-left" style="display: none;" id="icon_searching_document"></i>
											</button>
										</span>
									</div>
								</div>
							</div>

							<div class="col-md-5" id="razonsocial_numerodocumento">
								<div class="form-group">
									<label class="label-form"><i class="icon-vcard position-left"></i> <span id="titulo_nombrecliente">Razón Social</span>: <span class="text-danger">*</span></label>
									<div class="input-group" id="content_razon_social_cliente" style="display: block;">
										<span class="input-group-btn" id="content_cliente_api_foto_src" style="display: none;">
											<div class="mr-2" style="width: 36px; height: 36px;">
												<img src="" id="cliente_api_foto_src" alt="" style="border-radius: 50%;width: 35px; height: 35px;cursor: pointer;">
											</div>
										</span>
										<input type="text" title="Ingresa la Razón Social o Nombre" name="cliente_nombre" id="cliente_nombre" placeholder="Nombre o Razón Social Aquí" class="form-control cliente_nombre">
									</div> 
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4" id="control_direccion">
								<div class="form-group">
									<label class="label-form"><i class="icon-home2 position-left"></i> Dirección: </label>
									<input type="text" title="Ingresa la dirección completa" name="cliente_direccion" id="cliente_direccion" placeholder="Escribe aquí la dirección completa" class="form-control cliente_direccion">
								</div>
							</div>

							<div class="col-md-4" id="control_ubigeo">
								<div class="form-group">
									<div class="has-feedback has-feedback-left">
										<label class="label-form"><i class="icon-sphere position-left"></i>Ubigeo: </label>
										<select title="Selecciona tu Código de Ubigeo" data-placeholder="Selecciona Tu Código de Ubigeo" class="select_codigoubigeo" name="select_codigoubigeo" id="select_codigoubigeo">
										</select>
									</div>  
								</div>
								                      
							</div>

							<div class="col-md-4" id="control_numcelular">
								<div class="form-group">
									<label class="label-form"><i class="fa fa-whatsapp position-left"></i>Num. Celular: </label>
									<input type="tel" title="Escribe el Número de Celular" maxlength="9" name="numero_celular" id="numero_celular" placeholder="Escribe el Número de Celualr" class="form-control numero_celular">
								</div>
							</div>

							<input type="hidden" name="cliente_api_foto" value="" id="cliente_api_foto" />
							<input type="hidden" name="cliente_api_fecha_nac" value="" id="cliente_api_fecha_nac" />
							<input type="hidden" name="cliente_api_sexo" value="" id="cliente_api_sexo" />

							<div class="col-md-6">
								<div class="form-group">
									<div class="checkbox checkbox-switch">
										<label class="label-form">
											<input name="opcion_envio_email" id="opcion_envio_email" type="checkbox" data-on-text="Si" data-off-text="No" class="switch" data-size="mini">
											¿Deseas Enviar el Comprobante Electrónico al Email del Cliente?
										</label>
									</div>
								</div>
							</div>
							<div class="col-md-6 content_email_cliente" style="display: none;">
								<div class="form-group">
									<label class="label-form"><i class="icon-envelop2 position-left"></i> Email: <span class="text-danger">*</span></label>
									<input type="email" title="Ingresa el email del cliente" name="cliente_email" id="cliente_email" placeholder="Escribe aquí el email del cliente" class="form-control cliente_email" required>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6" style="font-size: 12px; padding-top: 10px; padding-bottom: 10px; text-transform: uppercase; font-weight: 700;">
								Lista de productos:
							</div>
							<div class="col-md-6 btn-options" style="text-align: right; padding-bottom: 4px;">
								<button type="button" class="btn btn-primary btn-labeled btn-xs mx-1 text-uppercase font-weight-bold legitRipple btn_editarproducto"><b><i class="icon-pencil3"></i></b> editar</button>
								<button type="button" class="btn btn-success btn-labeled btn-xs mx-1 text-uppercase font-weight-bold legitRipple btn_agregarproducto"><b><i class="icon-plus-circle2"></i></b> Agregar (F1)</button>
								<button type="button" class="btn btn-danger btn-labeled btn-xs mx-1 text-uppercase font-weight-bold legitRipple btn_eliminarproducto"><b><i class="icon-cross2"></i></b> Eliminar</button>
								<button style="display: none;" type="button" class="btn btn-danger btn-labeled btn-xs mx-1 text-uppercase font-weight-bold legitRipple btn_resetear"><b><i class="icon-cross2"></i></b> RESET</button>
							</div>
							<div class="col-md-12 mb-6">
								<div class="jqGrid content_tabla_detalle">
									<table id='detalle_documento' class='scroll'></table>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-12">
								<legend class="text-bold">Detalle Documento: <span id="opt_agregar_cuotas_credito" style="display:none; text-transform: none; text-decoration: none; border-bottom: dashed 1px #0088cc; color: #3f51b5; cursor: pointer;">(Click Aquí para Agregar 2 o Más Cuotas)</span></legend>
							</div>
							<div class="row">
								<div class="col-lg-7">
									<div class="row" id="contenido_total_condicionespago">
										<div class="col-sm-3 col-md-3" id="content_tipo_venta" style="margin-bottom: 20px;display: none;">
											<label class="label-form">¿Es al Crédito?</label>
											<div class="checkbox checkbox-switch">
												<input name="opcion_tipo_venta" id="opcion_tipo_venta" type="checkbox" data-on-text="Si" data-off-text="No" class="switch" data-size="mini">
											</div>
										</div>

										<div class="col-sm-3 col-md-3" id="content_fecha_pago" style="margin-bottom: 20px; display: none;">
											<div class="form-group">
												<label class="label-form">Fecha de Pago: </label>
												<div class="input-group">
													<span style="cursor: pointer;" class="input-group-addon font-weight-bold dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i></span>

													<ul class="dropdown-menu dropdown-menu-right">
														<li><a href="javascript:void(0);" onclick="cambiar_fecha_credito(15)"><i class=" icon-calendar"></i> Crédito a 15 días</a></li>
														<li><a href="javascript:void(0);" onclick="cambiar_fecha_credito(30)"><i class=" icon-calendar2"></i> Crédito a 30 días</a></li>
														<li><a href="javascript:void(0);" onclick="cambiar_fecha_credito(60)"><i class=" icon-calendar3"></i> Crédito a 60 días</a></li>
														<li class="divider"></li>
														<li><a href="javascript:void(0);" onclick="cambiar_fecha_credito(90)"><i class=" icon-calendar"></i> Crédito a 90 días</a></li>
													</ul>

													<input type="text" value="<?php echo date('d/m/Y'); ?>" name="fecha_pago_comprobante" id="fecha_pago_comprobante" placeholder="" class="form-control control_fecha">
												</div>
											</div>
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

									<div class="col-sm-4 col-md-4"  id="content_descuento_porcentaje" style="margin-top: -8px;">
										<div class="form-group">
											<div class="checkbox checkbox-switchery switchery-xs" style="margin-top: 1px !important;">
												<label class="label-form">
													<input checked type="checkbox" name="opcion_tipo_descuento" id="opcion_tipo_descuento" class="switchery opcion_tipo_descuento" checked="checked">
													<span id="txt_titulo_opcion_descuento">Descuento en %</span>
												</label>
											</div>

											<div class="input-group" id="content_descuento_total" style="display: none;">
												<span class="input-group-addon font-weight-bold simbolo_descuento">S/.</span>
												<input type="number" inputmode="numeric" pattern="[0-9]*" title="Ingresa el Porcentaje de Descuento" name="txt_descuento_total" id="txt_descuento_total" placeholder="Monto Descuento Total" class="form-control txt_descuento_total input_modify_totales">
											</div>

											<div class="input-group" id="content_descuento_porcentaje_input">
												<span class="input-group-addon font-weight-bold simbolo_descuento">% </span>
												<input type="text" value="0.0" title="Ingresa el Porcentaje de Descuento" name="txt_descuento_porcentaje" id="txt_descuento_porcentaje" placeholder="Porcentaje de Descuento Total" class="form-control input_modify_totales txt_descuento_porcentaje">
											</div>
										</div>
									</div>
									<div class="col-lg-4 col-sm-4 col-md-4" id="content_total_recibido">
										<label for="total_recibido">Total Recibido <span class="simbolo_moneda">S/.</span></label>
										<div class="input-group">
											<span class="input-group-addon font-weight-bold simbolo_moneda">S/.</span>
											<input type="text" class="form-control" value="" name="total_recibido" id="total_recibido">
										</div>
									</div>
									<div class="col-lg-4 col-sm-4 col-md-4" id="content_total_vuelto">
										<label for="total_vuelto">Vuelto <span class="simbolo_moneda">S/.</span></label>
										<div class="input-group">
											<span class="input-group-addon font-weight-bold simbolo_moneda">S/.</span>
											<input type="text" class="form-control" value="" name="total_vuelto" id="total_vuelto" disabled="disabled">
										</div>
									</div>
									
									<div class="col-lg-12 col-sm-12 col-md-12" style="display:none;">
										<div class="form-group">
											<label class="label-form"><i class="icon-vcard position-left"></i> Otros Cargos <span class="simbolo_moneda">S/.</span>:</label>
											<input type="text" title="Ingresa otros montos" name="txt_otros_cargos_comprobante_input" id="txt_otros_cargos_comprobante_input" placeholder="Ingresa otros montos" class="form-control input_modify_totales txt_otros_cargos_comprobante_input" value="0.0">
										</div>
									</div>
									<div class="col-lg-12 col-sm-12 col-md-12">
										<div class="form-group">
											<h6><i class="icon-notebook position-left"></i> Observación:</h6>
											<div class="mb-15 mt-15">
												<textarea rows="3" cols="3" id="observacion_documento" name="observacion_documento" class="custom-textarea" placeholder="Escribe aquí una observación"></textarea>
											</div>
										</div>
									</div>
									
								</div>
								
								<div class="col-lg-5 col-sm-12 col-md-12">
									<div class="content-group" id="content_resumen_doc_electronico">
										<h6>Resumen:</h6>
										<div class="table-responsive no-border">
											<table class="table">
												<tbody>
													<tr id="row_sub_total_ventas" style="display:none;">
														<th>Sub Total Ventas:</th>
														<td class="text-right">
															<span class="simbolo_moneda">S/.</span> <span id="sub_total_ventas">0.0</span>
															<input type="hidden" name="txt_sub_total_ventas" id="txt_sub_total_ventas" value="0">
														</td>
													</tr>
													
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

													<tr id="row_descuento_documento">
														<th><span class="text-danger">(-)</span> Descuento Total:</th>
														<td class="text-right">
															<span class="simbolo_moneda">S/.</span> <span id="descuento_documento">0.0</span>
															<input type="hidden" name="txt_descuento_comprobante" id="txt_descuento_comprobante" value="0">
														</td>
													</tr>
													
													<tr id="row_igv_documento">
														<th>IGV: <span class="text-regular texto_facto_igv_sunat">(18%)</span></th>
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
													
													<tr id="row_total_documento">
														<th>Total:</th>
														<td class="text-right text-primary"><h5 class="text-semibold">
															<span class="simbolo_moneda">S/.</span> <span id="total_documento">0.0</span></h5>
															<input type="hidden" name="txt_total_comprobante" id="txt_total_comprobante" value="0">
														</td>
													</tr>

													<tr id="row_total_detraccion" style="display:none;">
														<th><span class="text-danger">(-)</span> Detracción:</th>
														<td class="text-right">
															<span class="simbolo_moneda">S/.</span> <span id="total_detraccion">0.0</span>
															<input type="hidden" name="txt_total_detraccion" id="txt_total_detraccion" value="0">
														</td>
													</tr>

													<tr id="row_total_anticipos" style="display:none;">
														<th><span class="text-danger">(-)</span> Anticipos:</th>
														<td class="text-right">
															<span class="simbolo_moneda">S/.</span> <span id="total_anticipos">0.0</span>
															<input type="hidden" name="txt_total_anticipos" id="txt_total_anticipos" value="0">
														</td>
													</tr>

													<tr id="row_total_a_pagar" style="display:none;">
														<th>Total a Pagar:</th>
														<td class="text-right">
															<span class="simbolo_moneda">S/.</span> <span id="total_a_pagar">0.0</span>
															<input type="hidden" name="txt_total_a_pagar" id="txt_total_a_pagar" value="0">
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

						<div class="row" id="content_info_percepcion" style="display: none;">
							<div class="col-md-12">
								<div class="content-group">
									<legend class="text-bold">Información del Tipo de Percepción: </legend>
								</div>
							</div>

							<div class="col-md-12">
								<div class="form-group col-md-6">
									<div class="has-feedback has-feedback-left">
										<label class="label-form"><i class="icon-calculator2 position-left"></i>Tipo de Percepción <span class="text-danger">*</span> :</label>
										<select title="Selecciona el Tipo de Percepcción" data-placeholder="Selecciona el Tipo de Percepcción" class="tipo_percepcion select" name="tipo_percepcion" id="tipo_percepcion">
											<?php
											foreach($tipo_percepcion as $percepcion) {
												echo '<option data-porcentaje="'.($percepcion->porcentaje + 0).'" value="'.$percepcion->codigo.'">'.$percepcion->descripcion.'</option>';
											}
											?>
										</select>
									</div>
								</div>

								<div class="form-group col-md-2 col-xs-4">
									<label class="label-form"><i class="icon-cash2 position-left"></i> Base:</label>
									<div class="input-group">
										<span class="input-group-addon font-weight-bold">S/ </span>
										<input type="text" name="monto_base_percepcion" id="monto_base_percepcion" value="" class="form-control" readonly>
									</div>
								</div>

								<div class="form-group col-md-2 col-xs-4">
									<label class="label-form"><i class="icon-percent position-left"></i> Porcentaje:</label>
									<div class="input-group">
										<span class="input-group-addon font-weight-bold">%</span>
										<input type="text" name="porcentaje_percepcion" id="porcentaje_percepcion" value="" class="form-control" readonly>
									</div>
								</div>

								<div class="form-group col-md-2 col-xs-4">
									<label class="label-form"><i class="icon-cash2 position-left"></i> Percepción:</label>
									<div class="input-group">
										<span class="input-group-addon font-weight-bold">S/ </span>
										<input type="text" name="monto_percepcion" id="monto_percepcion" value="" class="form-control" readonly>
									</div>
								</div>
							</div>
						</div>

						<div class="row" id="content_info_retencion" style="display: none;">
							<div class="col-md-12">
								<div class="content-group">
									<legend class="text-bold" id="titulo_info_retencion">Información de la Retención: </legend>
									<input id="monto_dolares_retencion" name="monto_dolares_retencion" type="hidden" value="0" />
								</div>
							</div>

							<div class="col-md-12">
								<div class="form-group col-md-4">
									<label class="label-form"><i class="icon-cash2 position-left"></i> Base Imponible:</label>
									<div class="input-group">
										<span class="input-group-addon font-weight-bold simbolo_moneda">S/.</span>
										<input type="text" name="base_imponible_retencion" id="base_imponible_retencion" value="" class="form-control" readonly>
									</div>
								</div>

								<div class="form-group col-md-4">
									<label class="label-form"><i class="icon-percent position-left"></i> Porcentaje Retención:</label>
									<div class="input-group">
										<span class="input-group-addon font-weight-bold">%</span>
										<input type="text" name="porcentaje_retencion" id="porcentaje_retencion" value="" class="form-control" readonly>
									</div>
								</div>

								<div class="form-group col-md-4">
									<label class="label-form"><i class="icon-cash2 position-left"></i> Monto Retención:</label>
									<div class="input-group">
										<span class="input-group-addon font-weight-bold simbolo_moneda">S/.</span>
										<input type="text" name="monto_retencion" id="monto_retencion" value="" class="form-control" readonly>
									</div>
								</div>

								<input type="hidden" id="monto_dolares_retencion" name="monto_dolares_retencion" value="0" />
								
							</div>
						</div>

						<div class="row" id="content_info_detraccion" style="display: none;">
							<div class="col-md-12">
								<div class="content-group">
									<legend class="text-bold" id="titulo_info_detraccion">Información de la Detracción: </legend>
									<input id="monto_dolares_detraccion" name="monto_dolares_detraccion" type="hidden" value="0" />
								</div>
							</div>

							<div class="col-md-12">
								<div class="form-group col-md-3" style="display: none;">
									<div class="has-feedback has-feedback-left">
										<label class="label-form"><i class="icon-calculator2 position-left"></i>Tipo de Pago <span class="text-danger">*</span> :</label>
										<select title="Selecciona el Tipo de Pago" data-placeholder="Selecciona el Tipo de Pago" class="detraccion_tipo_pago select" name="detraccion_tipo_pago" id="detraccion_tipo_pago">
											<?php
											foreach($mediosdepago as $mediopago) {
												echo '<option value="'.$mediopago->id_mediopago.'">'.$mediopago->descripcion.'</option>';
											}
											?>
										</select>
									</div>
								</div>

								<div class="form-group col-md-4">
									<div class="has-feedback has-feedback-left">
										<label class="label-form"><i class="icon-city position-left"></i>Cuenta de Banco de la Nación <span class="text-danger">*</span> :</label>
										<select title="Selecciona el Número de Cuenta" data-placeholder="Selecciona el Número de Cuenta" class="detraccion_id_numero_cuenta select" name="detraccion_id_numero_cuenta" id="detraccion_id_numero_cuenta">
											<option value="0">Selecciona una Cuenta de Detracción</option>';
											<?php
											foreach($cuentas_detracciones as $cuentadetraccion) {
												echo '<option value="'.$cuentadetraccion->id_cuentabanco.'">'.$cuentadetraccion->nro_cuenta.' - '.$cuentadetraccion->nombre_banco.'</option>';
											}
											?>
										</select>
									</div>
								</div>

								<div class="form-group col-md-4">
									<div class="has-feedback has-feedback-left">
										<label class="label-form"><i class="icon-basket position-left"></i>Código del Bien <span class="text-danger">*</span> :</label>
										<select title="Selecciona el Código del Bien" data-placeholder="Selecciona el Código del Bien" class="detraccion_codigo_bien select" name="detraccion_codigo_bien" id="detraccion_codigo_bien">
											<?php
											foreach($bienes_detracciones as $biendetraccion) {
												echo '<option data-porcentaje="'.($biendetraccion->porcentaje + 0).'" value="'.$biendetraccion->id_cod_detraccion.'">'.$biendetraccion->descripcion.' ('.$biendetraccion->porcentaje.')</option>';
											}
											?>
										</select>
									</div>
								</div>

								<div class="form-group col-md-2 col-xs-6">
									<label class="label-form"><i class="icon-percent position-left"></i> Procentaje:</label>
									<div class="input-group">
										<span class="input-group-addon font-weight-bold">%</span>
										<input type="text" name="porcentaje_detraccion" id="porcentaje_detraccion" value="" class="form-control" readonly>
									</div>
								</div>

								<div class="form-group col-md-2 col-xs-6">
									<label class="label-form"><i class="icon-cash2 position-left"></i> Monto:</label>
									<div class="input-group">
										<span class="input-group-addon font-weight-bold">S/ </span>
										<input type="text" name="monto_detraccion" id="monto_detraccion" value="" class="form-control" readonly>
									</div>
								</div>
								
								<div class="form-group col-md-12">
									<label class="label-form"><i class="icon-home2 position-left"></i> Información: </label>
									<input type="text" value="OPERACION SUJETA AL SISTEMA DE PAGO OBLIGACIONES TRIBUTARIAS DEL BANCO DE LA NACION" title="Texto Detracción" name="texto_detraccion" id="texto_detraccion" placeholder="Texto Detracción" class="form-control texto_detraccion">
								</div>

								<div class="form-group col-md-12">
									<div class="text-primary text-size-small">
										<i class="icon-info3 text-size-mini position-left"></i> Operacion Sujeta a Detracción: Debe existir al menos un artículo sujeto a detracción. Si existe más de uno, el facturador tomara el mayor porcentaje por una interpretación conservadora Resolución 183-204 SUNAT/15.08.2004.
									</div>
									<div class="text-primary text-size-small" id="informacion_equivalencia_dolares" style="display:none; margin-top: 10px;">
										<i class="icon-info3 text-size-mini position-left"></i> Según RS N° 183-2004/SUNAT indica que debemos utilizar el tipo de cambio venta () en la fecha de creación del documento, y según R.S. N° 178-2005/SUNAT se debe redondear a un número entero. Por tanto el monto resultante luego de redondear es: S/. y su equivalente en dólares es: 
									</div>
								</div> 
							</div>
						</div>

						<div class="row" id="content_tiene_anticipos" style="display: none;">
							<div class="col-lg-6 col-md-6 col-xs-6">
								<h6 class="text-bold" id="titulo_info_tiene_anticipos">Información de los Anticipos: <span id="html_total_anticipos"></span></h6>
							</div>
							<div class="col-lg-6 col-md-6 col-xs-6 text-right">
								<a href="javascript:void(0);" class="btn bg-teal-400 agregar_item_anticipo"><i class="fa fa-plus"></i> Agregar Anticipo</a>
							</div>
							<div class="col-md-12">
								<table class="table" id="tbl_lista_anticipos" style="margin-top: 5px;">
									<thead id="tbl_head_lista_anticipos">
										<tr class="bg-primary">
											<td>Tipo Doc.</td>
											<td>Serie</td>
											<td>Correlativo</td>
											<td>Monto Anticipo</td>
											<td>Opción</td>
										</tr>
									</thead>
									<tbody id="tbl_body_lista_anticipos" counter-id="1">
										
									</tbody>
								</table>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12" id="content_modo_envio_a_sunat">
								<div class="content-group">
									<legend class="text-bold">Selecciona el modo de envío: </legend>

									<div class="col-md-4">
										<div class="radio">
											<label class="label-form">
												<input type="radio" value="solo_firma" id="mod_envio_sunat_solo_firma" name="modalidad_envio_sunat" class="control-primary" <?php if($modalidad_envio_sunat=='solo_firma'){echo 'checked="checked"';} ?>>
												Solo Firmar e Imprimir
											</label>
										</div>
									</div>
									
									<div class="col-md-4">
										<div class="radio">
											<label class="label-form">
												<input type="radio" value="inmediato" id="mod_envio_sunat_inmediato" name="modalidad_envio_sunat" class="control-success" <?php if($modalidad_envio_sunat=='inmediato'){echo 'checked="checked"';} ?>>
												Enviar a SUNAT ahora mismo!
											</label>
										</div>
									</div>
									
									<div class="col-md-4">
										<div class="radio">
											<label class="label-form">
												<input type="radio" value="no_enviar" id="mod_envio_sunat_no_enviar" name="modalidad_envio_sunat" class="control-info" <?php if($modalidad_envio_sunat=='no_enviar'){echo 'checked="checked"';} ?>>
												Solo Guardar la Venta
											</label>
										</div>
									</div>
								</div>
							</div>
							
							<div class="col-md-12" id="respuesta_proceso"></div>
							<div class="col-md-12 text-center btn-options" style="padding-bottom: 25px;">
								<button id="btn_guardar_doc_electronico" type="button" class="btn btn-primary btn-labeled btn-xs legitRipple text-uppercase font-weight-bold"><b><i class="icon-floppy-disk"></i></b> Guardar Documento Electrónico (F12)</button>
							</div>
						</div>
						
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- Modal data cliente -->
<div id="modal_cliente_data" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title"><i class="icon-vcard position-left"></i>Datos del cliente</h5>
            </div>

            <div class="modal-body">
				{{ partial('documentoelectronico/ficha_reniec') }}
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn bg-indigo">Guardar cambios</button>
            </div>
        </div>
    </div>
</div>
<!-- /Modal data cliente -->
{{ partial('documentoelectronico/vm_agregar_item') }}

<!-- Modal para msg guardado -->
{{ partial('documentoelectronico/vm_msg_guardado') }}

<!-- Modal para cuotas -->
{{ partial('documentoelectronico/vm_lista_cuota') }}

<!-- Modal para mostrar el Stock en Varias Sucursales -->
{{ partial('producto/modal_ver_stock_varias_sucursales') }}

<script>
	var num_decimales = <?php echo $num_decimales; ?>;
	var impuesto_icbper = <?php echo $impuesto_icbper; ?>;
</script>