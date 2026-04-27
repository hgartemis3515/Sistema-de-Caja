<style>
img {
	height: auto;
	max-width: 100%;
}
.fl-2 {
	margin-top: 20px;
}
.mt-3{
	margin-top: 1.5em;
}
.mt-5{
	margin-top: 5em!important;
}
.mt-6{
	margin-top: 6em;
}
@media (min-width: 800px){

.display-block{
	display: block;
	margin: auto;
}

.box-flex{
	margin: 0 auto;
	display:flex;
}
.btn-default{
	background: #cecece!important;
}
.fl-2 {
    display: grid;
    justify-content: center;
}
.row {
    margin-left: -10px;
    margin-right: -10px;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
}
.row.justify-content-md-center {
    -ms-flex-pack: center!important;
    justify-content: center!important;
}

.pst-end {
    justify-self: center;
    align-self: end;
}
.text-initial{
	text-transform: initial!important;
}
}
</style>
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Dominio personalizado <?php if($usuario->id_contribuyente != $contribuyente->id_contribuyente){ echo ": ".$contribuyente->razon_social; } ?></span></h4>
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
<div class="content mt-3" id="content_dominio_personalizado">
	<input type="hidden" id="id_contribuyente" value="<?php echo $contribuyente->id_contribuyente; ?>" />
	<div class="row box-flex text-center">
		<div class="col-lg-5 fl-2 col-md-12">
			<div class="display-block">	
				<img id="img_logo461x95" src="<?php echo $contribuyente->logo_461; ?>" alt="">
				<input type="hidden" id="url_image_logo461x95" value="<?php echo $contribuyente->logo_461; ?>" />
            </div>
			<div class="pst-end mt-3">
				<button type="button" id="btn_logo461x95" class="btn bg-indigo legitRipple"><i class="fa fa-cloud-upload position-left"></i>Subir imagen 461x95</button>  
			</div>    
        </div>
        <div class="col-lg-4 fl-2 col-md-12">
			<div class="display-block">	
				<img id="img_logo291x60" src="<?php echo $contribuyente->logo_291; ?>" alt="">
				<input type="hidden" id="url_image_logo291x60" value="<?php echo $contribuyente->logo_291; ?>" />
            </div>
			<div class="pst-end mt-3">
				<button type="button" id="btn_logo291x60" class="btn bg-indigo legitRipple"><i class="fa fa-cloud-upload position-left"></i>Subir imagen 291x60</button>  
			</div>  
        </div>
        <div class="col-lg-3 fl-2 col-md-12">
			<div class="display-block">	
				<img id="img_logo56x56" src="<?php echo $contribuyente->logo_56; ?>" alt="">
				<input type="hidden" id="url_image_logo56x56" value="<?php echo $contribuyente->logo_56; ?>" />
            </div>
			<div class="pst-end mt-3">
				<button type="button" id="btn_logo56x56" class="btn bg-indigo legitRipple"><i class="fa fa-cloud-upload position-left"></i>Subir imagen 56x56</button>  
			</div>    
        </div>
    </div>
	<div class="row justify-content-md-center">
		<div class="col-lg-8  mt-5">
			<div class="form-group">
				<label class="font-weight-bold">
					Dominio: (Ejem. miempresa.com )
				</label>
				<div class="input-group">
					<div class="input-group-btn">
						<span class="btn btn-default text-initial legitRipple">
							http://
						</span>
					</div>
					<input type="text" name="dominio_personalizado" value="<?php echo $contribuyente->dominio; ?>" id="dominio_personalizado" class="form-control" placeholder="Ingresa tu dominio...">
					<div class="input-group-btn">
						<button id="btn_guardar_dominio" class="btn bg-indigo legitRipple" type="button">
							<i class="icon-floppy-disk mr-2"></i>Guardar
						</button>
					</div>
				</div>
			</div>
			<div class="text-center">
				<p>Dominio actual: <span class="font-weight-bold" id="url_nuevo_dominio"> http://<?php echo $contribuyente->dominio; ?></span></p>
			</div>
		</div>

		<div class="col-lg-6  mt-5">
			<div class="form-group">
				<label class="font-weight-bold">
					Secret Key Recaptcha
				</label>
				<div class="input-group">
					<div class="input-group-btn">
						<span class="btn btn-default text-initial legitRipple">
							KeySecret
						</span>
					</div>
					<input type="text" name="captcha_key_private" value="<?php echo $contribuyente->captcha_key_private; ?>" id="captcha_key_private" class="form-control" placeholder="captcha_key_private...">
				</div>
			</div>
		</div>

		<div class="col-lg-6  mt-5">
			<div class="form-group">
				<label class="font-weight-bold">
					Public Key Recaptcha
				</label>
				<div class="input-group">
					<div class="input-group-btn">
						<span class="btn btn-default text-initial legitRipple">
							PublicKey
						</span>
					</div>
					<input type="text" name="captcha_key_public" value="<?php echo $contribuyente->captcha_key_public; ?>" id="captcha_key_public" class="form-control" placeholder="captcha_key_public...">
				</div>
			</div>
		</div>
	</div>
	<div class="row mt-5">
		<div class="col-lg-12">
			<div class="panel">
				<div class="panel-body">
					<fieldset class="content-group">
						<legend class="text-bold">
							<span class="text-uppercase">Paso #1</span>
						</legend>
					</fieldset>
					<p class="card-text">
						Ingresa a tu cuenta donde haz comprado tu dominio, luego ingresar y busca la opción de "Administración de Registros DNS" (DNS records management) para tu dominio que has ingresado en la parte superior.</p>
				</div>
			</div>
		</div>
		<div class="col-lg-12">
			<div class="panel">
				<div class="panel-body">
					<fieldset class="content-group">
						<legend class="text-bold">
							<span class="text-uppercase">Paso #2</span>
						</legend>
					</fieldset>
					<p class="card-text">Crear un registro A, que apunte a la IP 192.196.159.79, si tienes dudas, contacta con nuestro soporte y te ayudaremos rápidamente!</p>
				</div>
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
				<p>Recuerda que la imágen debe ser cuadrada, y con un ancho máximo de 300x300px, en caso tengas una imágen más grande, puedes subirla sin problemas y nosotros te ayudaremos a recortar la imágen...</p>
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