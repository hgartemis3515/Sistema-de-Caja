<style>

.btn-labeled.btn-xs>b {
    padding: 10px; 
}
.btn-min{
	padding: 5px 11px;
}
.cursor-none{
	cursor: initial!important;
}

.img-border-content{
	border: 1px solid #7880f0;
}
/*.img-border{
	box-shadow: hsl(0, 0%, 80%) 0 0 16px;
	border: 4px solid #fff;
	border-radius: 5px;
}*/
.img-preview-pag{
	position: relative;
}

.img_content{
	position: relative;
	border: 2px dashed #ddd;
	border-radius: 2px;
	background-color: #fff;
	width: 100%;
	height: 120px;
	padding: 1em;
	color: #ddd;
}
.img_content:hover{
	border: 2px dashed #7880f0;
	cursor: pointer;
	transition: all .5s;
	color:#7880f0;
}
.img-panel{
	width: 100%;
	height: 140px;
}
.overflow-auto{
	overflow: auto;
	width: 100%;
  	height: 400px;
}
.content-img {
    font-size: 20px;
    text-transform: uppercase;
    font-weight: 700;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}
.plus-icon {
    display: block;
    font-size: 30px;
}
.ml-2{
	margin-left: 20px;
}
.mr-2{
	margin-right: 20px;
}
.mr-3{
	margin-right: 13px!important;
}
.mt-2{
	margin-top: 20px;
}
.mt-4{
	margin-top: 40px;
}
.p-1{
	padding: 10px!important;
}
.w-100{
	width: 100%;
}
.h-100{
	height: 100%;
}
/* Efecto overlay */

.image {
  display: block;
  width: 100%;
  height: auto;
}

.overlay {
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  height: 100%;
  width: 100%;
  opacity: 0;
  transition: .6s ease;
  background-color: rgba(0, 0, 0, 0.486);
}

.img-preview-pag:hover .overlay {
  opacity: 1;
  transition: .5s;
}

.text {
	color: white;
	font-size: 20px;
	position: absolute;
	top: 50%;
	left: 50%;
	-webkit-transform: translate(-50%, -50%);
	-ms-transform: translate(-50%, -50%);
	transform: translate(-50%, -50%);
	text-align: center;
	border: 1px solid #fff;
	border-radius: 20%;
	width: 30px;
	height: 30px;
}
.text a{
	color: #fff;
}
.text-initial{
	text-transform: initial!important;
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
/* Media queries 
=====================*/
@media(min-width: 360px){
	.form-control {
    	height: 33px;
	}
	.btn {
    	font-size: 12px;
		padding: 7px 12px
	}
	.form-group div[class*=col-lg-]:not(.control-label)+div[class*=col-lg-] {
    margin-top: 0px;
	}
	.btn-info-size {
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translate(-50%, 0);
	}
	.img-size-logo{
		height: 115px;
	}
	
}
@media (min-width: 600px) and (max-width: 1024px){
	.form-control {
    	height: 32px;
	}
	.btn-info-size {
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translate(-50%, 0);
	}
	.img-size-logo{
		height: 120px;
	}
	.col-sm-6 {
    	width: 50%;
		float: left;
	}

}
@media only screen and (max-width: 1170px) and (min-width: 770px){
	.form-group div[class*=col-lg-]:not(.control-label)+div[class*=col-lg-] {
    margin-top: 0px;
	}
	.btn-info-size {
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translate(-50%, 0);
	}
	.img-size-logo{
		height: 120px;
	}
}
@media(min-width: 900px){
	.btn-info-size {
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translate(-50%, 0);
}
	.form-control {
    	height: 32px;
	}
	.img-size-logo{
		height: 120px;
	}
}
</style>
<div class="page-header">
	<div class="page-header-content" style="max-width: 1100px; margin: 0 auto;">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Mi Website</span></h4>
		<a class="heading-elements-toggle"><i class="icon-more"></i></a></div>
		<div class="heading-elements">
			<div class="heading-btn-group">
				<a href="/facturacionv8/documentoelectronico/index/03/nuevo" class="btn btn-link btn-float has-text"><img src="/facturacionv8/img/boleta.svg" style="width: 25px;"/><span>Boleta</span></a>
				<a href="/facturacionv8/documentoelectronico/index/01/nuevo" class="btn btn-link btn-float has-text"><img src="/facturacionv8/img/factura.svg" style="width: 25px;"/><span>Factura</span></a>
				<a href="/facturacionv8/documentoelectronico/index/77/nuevo" class="btn btn-link btn-float has-text"><img src="/facturacionv8/img/nota_venta.svg" style="width: 25px;"/><span>Nota de Venta</span></a>
				<a href="/facturacionv8/documentoelectronico/index/88/nuevo" class="btn btn-link btn-float has-text"><img src="/facturacionv8/img/cotizacion.svg" style="width: 25px;"/><span>Cotización</span></a>
				<a href="/facturacionv8/dashboard" class="btn btn-link btn-float has-text"><img src="/facturacionv8/img/dashboard.svg" style="width: 25px;"/><span>Dashboard</span></a>
			</div>
		</div>
	</div>
</div>
<div class="content">
	<div class="row">
        <div class="col-lg-12">
            <div class="navbar navbar-default navbar-component navbar-xs"  style="max-width: 1100px; margin: 0 auto;">
                <ul class="nav navbar-nav visible-xs-block">
                    <li class="full-width text-center"><a data-toggle="collapse" data-target="#navbar-filter"><i class="icon-menu7"></i> </a></li>
                </ul>
                <div class="navbar-collapse collapse" id="navbar-filter">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="#activity" data-toggle="tab"><i class="icon-menu7 position-left"></i> Sitio Web</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-12" style="margin-top: 15px;">
            <div class="panel panel-flat  border-top-indigo" style="max-width: 1100px; margin: 0 auto;" id="content_panel_miwebsite">
                <div class="panel-body">
                    <fieldset class="content-group"><legend class="text-bold"><i class="icon-pencil5 mr-2" aria-hidden="true"></i><span class="text-uppercase">Página Principal</span> </legend></fieldset>
                    <div class="row">
                        <div class="col-lg-4 col-sm-5 col-md-5" id="content_pagina_home">
							<div class="img-preview-pag img-border">
								<img src="https://arpsystem.com.pe/facturacionv8/herramientas/verimage/imguser-5d9acff964a6c-385babd83501a81c518f05a9d1277831.png" alt="Avatar" class="image">
								<div class="overlay">
									<div class="text"><a href="#" target="_blank" rel="noopener noreferrer"><i class="fa fa-link fa-1x" aria-hidden="true"></i></a></div>
								</div>
							</div>
						</div>
                        <div class="col-lg-8 col-sm-7 col-md-7">
                            <div class="form-group">
								<label class="font-weight-bold">
									Dominio: (Ejem. miempresa.com )
								</label>
								<div class="input-group">
									<div class="input-group-btn">
										<span class="btn btn-default text-initial legitRipple">
											https://
										</span>
									</div>
									<input type="text" value="<?php echo $contribuyente->dominio; ?>" name="dominio_personalizado"  id="dominio_personalizado" class="form-control" placeholder="Ingresa tu dominio...">
									<div class="input-group-btn">
										<button id="btn_guardar_dominio" class="btn bg-indigo legitRipple" type="button">
											<i class="icon-floppy-disk mr-2"></i>Guardar
										</button>
									</div>
								</div>
							</div>
							
							<div class="form-group">
								<div class="row">
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 img-size-logo">
										<div class="img-preview-pag">
											<img class="w-100 h-100 p-1" id="img_logo_461" src="<?php echo $contribuyente->logo_461; ?>" alt="">
											<input type="hidden" value="<?php echo $contribuyente->logo_461; ?>" name="txt_logo_461" id="txt_logo_461" />
											<div class="overlay">
												<div class="text">
													<a href="javascript:void(0)" class="btn_logo461x95">
														<i class="fa fa-upload" aria-hidden="true"></i>
													</a>
												</div>
											</div>
										</div>
										<div class="text-center btn-info-size">
											<button type="button" class="btn btn-default btn-xs mt-2 btn-raised legitRipple btn_logo461x95"><i class="icon-make-group position-left"></i> 461x95px</button>
										</div>
									</div>

									<div class="col-lg-4 col-md-4 col-sm-4  col-xs-6 img-size-logo">
										<div class="img-preview-pag bg-indigo">
											<img class="w-100 h-100 p-1" id="img_logo_291" src="<?php echo $contribuyente->logo_291; ?>" alt="">
											<input type="hidden" value="<?php echo $contribuyente->logo_291; ?>" name="txt_logo_291" id="txt_logo_291" />
											<div class="overlay">
												<div class="text">
													<a href="javascript:void(0)" class="btn_logo291x60">
														<i class="fa fa-upload" aria-hidden="true"></i>
													</a>
												</div>
											</div>
										</div>
										<div class="box-text-tam text-center btn-info-size">
											<button type="button" class="btn btn-default btn-xs mt-2 btn-raised legitRipple btn_logo291x60"><i class="icon-make-group position-left"></i> 291x60px</button>
										</div>
									</div>

									<div class="col-lg-4 col-md-4 col-sm-4  col-xs-12 img-size-logo">
										<div class="img-preview-pag" style="max-width: 60px; margin: 0 auto;">
											<img class="w-100 h-100 p-1" id="img_logo_56" src="<?php echo $contribuyente->logo_56; ?>" alt="">
											<input type="hidden" value="<?php echo $contribuyente->logo_56; ?>" name="txt_logo_56" id="txt_logo_56" />
											<div class="overlay">
												<div class="text">
													<a href="javascript:void(0)" class="btn_logo56x56">
														<i class="fa fa-upload" aria-hidden="true"></i>
													</a>
												</div>
											</div>
										</div>
										<div class="box-text-tam text-center btn-info-size">
											<button type="button" class="btn btn-default btn-xs mt-2 btn-raised legitRipple btn_logo56x56"><i class="icon-make-group position-left"></i> 56x56px</button>
										</div>
									</div>

								</div>
							</div>
						</div>
						
					</div>
					<div class="row">
						<div class="col-lg-12 mt-4">
							<fieldset class="content-group"><legend class="text-bold"><i class="icon-list mr-2" aria-hidden="true"></i><span class="text-uppercase">Lista de páginas</span> </legend></fieldset>
							<div class="row" id="content_mispaginas"></div>
						</div>
					</div>
                </div>
            </div>
		</div>
    </div>
</div>

<!-- Modal v_1: seleccionar Plantilla -->
<div class="modal fade" id="vm01_seleccionarplantilla" tabindex="-1" role="dialog" aria-labelledby="image_1" aria-hidden="true">
	<div class="modal-dialog  modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="image_1"><i class="fa fa-file-code-o mr-3"></i>
					Elige una plantilla</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="vm01_body_seleccionarplantilla">
				<div class="row">
					<div class="col-lg-12">
						<div class="form-group">
							<label><i class="fa fa-filter mr-2"></i>Filtrar por categoría</label>
							<select name="select_idcategoria" id="select_idcategoria" class="select_minimizado form-control">
								<option value="0" selected>Mostrar Todos</option>
								<?php
								foreach($categorias as $categoria) {
									echo '<option value="'.$categoria->idcategoria.'">'.$categoria->nombre.'</option>';
								}
								?>
							</select>
						</div>
					</div>
					<div class="col-lg-12">
						<div class="row overflow-auto" id="content_plantillas"></div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>


<!-- Modal v_2 -->
<div class="modal fade" id="image_2" tabindex="-1" role="dialog" aria-labelledby="image_2" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="image_2">Modal title</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				Modal 2
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn bg-indigo">Guardar imagen</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal v_3 -->
<div class="modal fade" id="image_3" tabindex="-1" role="dialog" aria-labelledby="image_3" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="image_3">Modal title</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				Modal 3
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn bg-indigo">Guardar imagen</button>
			</div>
		</div>
	</div>
</div>


<!-- Ventana para Agregar imágen -->
<div id="vm_cargar_imagen" class="modal fade">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Importante</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<p>Es estrictamente necesario que la imágen tenga las siguientes dimensiones <strong id="dimensiones_imagen"></strong></p>
				<hr>
				<div class="row">
					<div class="form-group col-lg-9">
						<input id="fileimage" type="file" class="file-input" accept=".jpg,.gif,.png">
					</div>
					<div class="form-group col-lg-3">
						<div class="previewrecorteimg" style="width: 100%;"></div>
					</div>
						<img src="" id="imagenresultado" />
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary legitRipple" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-primary legitRipple" id="btn_guardarimagen"><i class="icon-spinner6 spinner position-left btn_guardarimagen_loading" style="display: none;"></i><i class="icon-floppy-disk position-left btn_guardarimagen_icono"></i> Guardar Imágen</button>
			</div>
		</div>
	</div>
</div>
<!-- /Ventana para Agregar imágen -->