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
	.mb-6{
		margin-bottom: 2.5em!important;
	}
	.mx-1{
		margin: .5em;
	}
	.mr-1{
		margin-right: 10px;
	}
	.navigation li a:hover, .navigation li a:focus {
		background-color: #3F51B5;
		color: #fff;
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
	.sidebar-default .navigation li > a:hover, .sidebar-default .navigation li > a:focus {
    	background-color: #3F51B5;
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

</style>
<input type="hidden" id="tipo_doc_selected" value="<?php echo $tipo_doc; ?>" />
<div class="page-header">
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
		<div class="col-md-12" style="margin-bottom: 15px;">
			<div class="panel" style="max-width: 1100px; margin: 0 auto;">
				


					

							<div class="form-group col-md-6">
								<div class="checkbox checkbox-switch">
									<label>
										<input name="opcion_envio_email" id="opcion_envio_email" type="checkbox" data-on-text="Si" data-off-text="No" class="switch" data-size="mini">
										¿Deseas Enviar el Comprobante Electrónico al Email del Cliente?
									</label>
								</div>
							</div>
							
						</div>

						
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
