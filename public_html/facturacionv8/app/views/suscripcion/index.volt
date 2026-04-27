<style>
.addon_text{
	padding-top: 10px; 
	font-weight:700; 
	text-transform: uppercase;     
	background: -webkit-linear-gradient(#7880f0, #3f51b5);
	-webkit-background-clip: text;
	-webkit-text-fill-color: transparent;
}
.card-single {
    background: #fff;
    padding: .5em 0;
    margin: .5em 0;
}
.flaticon-appointment:before, .flaticon-appointment:before, .flaticon-appointment:after, .flaticon-appointment:after, .flaticon-booking:before, .flaticon-booking:before, .flaticon-booking:after, .flaticon-booking:after {
    font-family: Flaticon;
    font-size: 40px;
    font-style: normal;
    margin-left: 0px;
}
.navbar-brand > img {
    margin-top: 0px;
    height: 100%;
}
.btn.focus, .btn:focus {
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(120, 128, 240, .5);
	color: #fff;
}
.btn.btn-success:focus, .btn.btn-success:active:focus, .btn.btn-success.active:focus, .btn.btn-success.focus, .btn.btn-success:active.focus, .btn.btn-success.active.focus{
	-webkit-appearance: none;
    background: -webkit-gradient(to right, #7880f0 0%, #b4b9ff 50%, #3f51b5);
    background: linear-gradient(to right, #7880f0 0%, #b4b9ff 50%, #3f51b5);
    background-size: 500%;
}
.btn-secondary {
    color: #fff;
    background-color: #6c757d;
    border-color: #6c757d;
    padding: 10px 30px;
    font-size: 16px;
    border-radius: 30px;
}
.dolar {
    font-size: 14px;
    position: absolute;
    top: 5px;
    left: -27px;
}
.faqs-payment {
    position: absolute;
    top: 15%;
    left: 15px;
    width: 270px;
}
.faqs-payment p {
    line-height: 14px;
    font-size: 12px;
    padding: 0;
    margin: 6px;
	margin-bottom: 13px;
}
.form-control {
    display: block;
    width: 100%;
    height: 35px;
    padding: .375rem .75rem;
    font-size: 12px;
    font-weight: 400;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
}
.label-form i, legend i {
    background: -webkit-linear-gradient(#7880f0, #3f51b5);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.input-group-addon i{
    background: -webkit-linear-gradient(#7880f0, #3f51b5);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.line-medium {
    width: 150px;
    display: block;
    margin: auto;
	padding-bottom: 1.5em;
}
.modal-body {
    position: relative;
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
    padding: 0rem;
}
.nav-tabs.nav-tabs-highlight>li.active>a, .nav-tabs.nav-tabs-highlight>li.active>a:focus, .nav-tabs.nav-tabs-highlight>li.active>a:hover {
    border-top-color: transparent;
}
.nav-tabs-left > .tab-content {
    padding: 20px;
	background: #fff;
}
.nav-tabs-left > .nav-tabs-highlight > li.active > a, .nav-tabs-left > .nav-tabs-highlight > li.active > a:hover, .nav-tabs-left > .nav-tabs-highlight > li.active > a:focus {
    border-top-color: #ddd;
    border-left-color: #7880f0;
    background: #fff;
}

.pace-done {
    padding-right: 0!important;
}

.text-price{
	font-size: 12px;
}
.input-group-addon, .input-group-btn {
    width: auto;
    white-space: nowrap;
    vertical-align: middle;
}
.features-info {
    text-align: left;
    font-size: 14px;
	background-color: rgba(230, 231, 255, 0.95);
	position: relative;
	margin-top: 1em;
	font-weight: 500;
}
.features-info li {
    padding: 5px 18px;
    border-bottom: 1px solid #dce1e5;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: justify;
    -ms-flex-pack: justify;
    font-size: 14px;
    justify-content: space-between;
}
.features-info-show[aria-expanded="false"]:after {
    content: '\f078';
    font-weight: 800;
    font-family: "Font Awesome 5 Free";
	position: absolute;
	right: 10px;
}
@media (max-width: 768px){
	.navbar-nav:last-child {
		border-bottom: 0;
		padding: 0 3em;
	}

}
@media (min-width: 992px){
	.modal-lg, .modal-xl {
		max-width: 900px;
	}
	.position-bottom-fixed {
		position: absolute;
		bottom: 10px;
		left: 10px;
	}
}
@media (min-width: 600px) and (max-width: 1024px){
	.modal-dialog {
		max-width: 90%;
		margin: 1.75rem auto;
	}
}
@media (min-width: 300px) and (max-width: 568px){
	.modal-dialog {
		max-width: 90%;
		margin: 1.75rem auto;
	}
	#accordion .card > .card-header h4 {
    font-size: 14px;
    color: rgba(63,81,181,1);
}
}
@media (max-width: 767px){
	.single-box-plans {
		background-color: white;
		padding: 2em 0rem 0rem;
		margin: 2em  auto;
		border: solid 1px #e9e9e9;
		border-radius: 5px;
		width: 90%;
		-ms-flex: 0 0 83.333333%;
		flex: 0 0 83.333333%;
		max-width: 83.333333%;
	}
}
/* Css Crd */
.input-group .form-control {
    z-index: initial;
}
.ccicon {
    height: 28px;
    position: absolute;
    right: 0px;
    top: 4px;
    width: 60px;
    z-index: 2;
}
.input-group-addon:not(:first-child):not(:last-child), .input-group-btn:not(:first-child):not(:last-child), .input-group .form-control:not(:first-child):not(:last-child) {
    border-radius: 0;
    z-index: 1;
}
.can-toggle.demo-rebrand-2 label .can-toggle__switch.mensual:after {
    top: 2px;
    left: 10px;
    border-radius: 30px;
    width: 60px;
    line-height: 35px;
    font-size: 13px;
}


.can-toggle.demo-rebrand-2 label .can-toggle__switch:after {
    top: 2px;
    left: 5px;
    border-radius: 30px;
    width: 60px;
    line-height: 35px;
    font-size: 13px;
}
.nav-tabs:before {
    content: '';
}
.plan__details {
	font-weight: 400;
    color: rgb(146, 144, 144);
    font-size: 12px;
}
</style>
<!-- Nav Bar -->
<div class="navbar-area">
	<div class="nav-main-01">
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
			<div class="container">
				<a class="navbar-brand" href="#">
					<img src="https://arpsystem.com.pe/gestion-plantillas/humbertoguadalup/2/img/logo.png" class="logo-brand" width="200px" alt="">
				</a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse stroke" id="navbarSupportedContent">
					<ul class="navbar-nav ml-auto text-uppercase">
						<li class="nav-item">
							<a class="nav-link" href="/facturacionv8/dashboard">Dashboard </a>
						</li>
						<a href="#planes" class="btn btn-success btn-border-radius ml-4">Comprar ahora</a>
						
					</ul>
				</div>
			</div>
		</nav>
	</div>
</div>
<!-- End Navbar Area -->
 
<!-- Start Main Banner  -->
<div class="main-banner-one text-center">
	<div class="container">
		<div class="single-header-text">
			<h1>Inicia Hoy Mismo <br> ¡Con La Facturación Electrónica!</h1>
			<h5>Y Sin Tediosos Contratos que te Amarren.</h5>
		</div>
		<!-- <div class="single-header-img mt-5 display-responsive">
			<img src="https://arpsystem.com.pe/gestion-plantillas/humbertoguadalup/2/img/frame_laptop.png" class="bounce-animate img-header-laptop" alt="">
		</div> -->
	</div>
</div>
<!-- End Main Banner  -->
<!-- Star Plans-->
<div class="section-white">
	<div class="container">
		<div class="row">
			<div class="col-lg-3 align-self-center text-center">
				<div class="can-toggle demo-rebrand-2 mx-auto d-inline-block">
					<input id="check_tipo_pago" name="tipo_pago" type="checkbox" checked>
					<label for="check_tipo_pago">
						<div class="can-toggle__switch" data-checked="Anual" data-unchecked="Mensual"></div>
					</label>
					<div class="can-toggle__label-text text-center mb-4">
						25% de descuento al facturar anualmente
					</div>
				</div>
			</div>
			<div class="col-lg-9" id="planes">
				<div class="row">
					<div class="col-lg-4 col-md-6 mb-3 text-center plant_mensual">
						<div class="single-box-plans">
							<div class="plan__plant">
								<i class="flaticon-appointment color-secundary mb-4 icon_pan" style="font-size: 30px;"></i>
							</div>
							<h6 class="plan__name">Plan Básico</h6>
							<h2 class="plan__price">
								<span class="precio_basico ">
									<span class="dolar-plan">$</span>325
									<span class="billing-period"> /USD</span>
								</span>
							</h2>
							<small class="plan__details periodo1 display-block" id="plan__details_plan_basico">Facturación Mensual</small>
							<!-- Boton compra Anual -->
							<button type="button" class="btn  btn-success  mb-3 btn_plan_anual mt-3 btn_compra_culqi" data-idplan="<?php echo $id_plan_manual_97; ?>" data-title="Plan Básico - Suscripción Anual" data-description="Plan Básico - Suscripción Anual" data-amount="<?php echo $monto_plan_anual_97; ?>"  data-textbutton="Acceder al Plan Básico" data-toggle="modal" data-target="#vm_tarjeta"><b><i class="icon-cart"></i></b> Comprar</button>

							<!-- Boton compra Mensual -->
							<button type="button" class="btn btn-default-theme mb-3 btn_plan_mensual mt-3 btn_compra_culqi" data-idplan="<?php echo $id_plan_mensual_97; ?>" data-title="Plan Básico - Suscripción Mensual" data-description="Plan Básico - Suscripción Mensual" data-amount="<?php echo $monto_plan_mensual_97; ?>"  data-textbutton="Acceder al Plan Básico" data-toggle="modal" data-target="#vm_tarjeta"><b><i class="icon-cart"></i></b> Comprar</button>

							<div class="features-info">
								<a title="Ver características del plan" class="w-100 d-block p-3 text-left d-md-none features-info-show" data-toggle="collapse" href="#collapse_plan1" role="button" aria-expanded="false" aria-controls="collapse_plan1">
								  Mostrar Características
								</a>
								<div class="collapse" id="collapse_plan1">
								  <div class="text-left">
									<ul class="p-0 m-0">
										<li class="features-info__section-title">
											Características
										</li>
										<li>
											Crear Facturas
										</li>
										<li>
											Crear Boletas  
										</li>
										<li>
											Crear Notas de Venta 
										</li>
										<li>Crear Notas de Crédito	</li>
										<li>Crear Notas de Débito</li>
										<li>Duplicar y Copiar Documentos Electrónicos</li>
										<li>Búsqueda de RUC</li>
										<li>Búsqueda de DNI</li>
										<li>Ventas con operaciones gravadas, exoneradas, inafectas, gratuitas y de exportación</li>
										<li>Impuesto ICBPER</li>
										<li>1 Sucursal</li>
										<li>1 Almacén</li>
										<li>2 Usuarios	</li>
									  </ul>
								  </div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-4 col-md-6 mb-3  text-center plant_mensual">
						<div class="single-box-plans">
							<div class="plan__plant">
								<i class="flaticon-appointment color-secundary mb-4 icon_pan" style="font-size: 30px;"></i>
							</div>
							<h6 class="plan__name">Plan Emprededor</h6>
							<h2 class="plan__price">
								<span class="precio_emprendedor">
									<span class="dolar-plan">$</span>500
									<span class="billing-period"> /USD</span>
								</span>
							</h2>
							<small class="plan__details periodo1 display-block" id="plan__details_plan_emprendedor">Facturación Mensual</small>
							<!-- Botón Anual -->
							<button type="button" class="btn btn-success mb-3 btn_plan_anual mt-3 btn_compra_culqi" data-idplan="<?php echo $id_plan_anual_129; ?>" data-title="Plan Emprendedor - Suscripción Anual"  data-description="Plan Emprendedor - Suscripción Anual" data-amount="<?php echo $monto_plan_anual_129; ?>" data-textbutton="Acceder al Plan Emprendedor" data-toggle="modal" data-target="#vm_tarjeta"><b><i class="icon-cart"></i></b> Comprar</button>

							<!-- Boton compra Mensual  -->
							<button type="button" class="btn btn-default-theme mb-3 btn_plan_mensual mt-3 btn_compra_culqi" data-idplan="<?php echo $id_plan_mensual_129; ?>" data-title="Plan Emprendedor - Suscripción Mensual"  data-description="Plan Emprendedor - Suscripción Mensual" data-amount="<?php echo $monto_plan_mensual_129; ?>" data-textbutton="Acceder al Plan Emprendedor" data-toggle="modal" data-target="#vm_tarjeta"><b><i class="icon-cart"></i></b> Comprar</button>

							<div class="features-info">
								<a title="Ver características del plan" class="w-100 d-block p-3 text-left d-md-none features-info-show" data-toggle="collapse" href="#collapse_plan_2" role="button" aria-expanded="false" aria-controls="collapse_plan_2">
								  Mostrar Características
								</a>
								<div class="collapse" id="collapse_plan_2">
								  <div class="text-left">
									<ul class="p-0 m-0">
										<li class="features-info__section-title">
											Características
										</li>
										<li>
											Crear Facturas
										</li>
										<li>
											Crear Boletas  
										</li>
										<li>
											Crear Notas de Venta 
										</li>
										<li>Crear Notas de Crédito	</li>
										<li>Crear Notas de Débito</li>
										<li>Duplicar y Copiar Documentos Electrónicos</li>
										<li>Búsqueda de RUC</li>
										<li>Búsqueda de DNI</li>
										<li>Ventas con operaciones gravadas, exoneradas, inafectas, gratuitas y de exportación</li>
										<li>Impuesto ICBPER</li>
										<li>Descuentos Globales</li>
										<li>Sucursales Ilimitadas</li>
										<li>Almacenes Ilimitados</li>
										<li>Usuarios Ilimitados</li>
										<li>Crear Guías de Remisión Electrónica</li>
										<li>Reporte de Ventas</li>
										<li>Reporte detallado de Ventas</li>
									</ul>
								  </div>
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-4 col-md-12 mb-3 text-center plant_mensual">
							<div class="single-box-plans">
							<div class="plan__plant">
								<i class="flaticon-appointment color-secundary mb-4 icon_pan" style="font-size: 30px;"></i>
							</div>
							<h6 class="plan__name">Plan Empresarial</h6>
							<h2 class="plan__price">
								<span class="precio_empresarial">
									<span class="dolar-plan">$</span>900
									<span class="billing-period"> /USD</span>
								</span>
							</h2>
							<small class="plan__details periodo1 display-block" id="plan__details_plan_empresarial">Facturación Mensual</small>
							<!-- Botón Anual -->
							<button type="button" class="btn btn-success mb-3 btn_plan_anual mt-3 btn_compra_culqi" data-idplan="<?php echo $id_plan_anual_139; ?>" data-title="Plan Empresarial - Suscripción Anual"   data-description="Plan Empresarial - Suscripción Anual" data-amount="<?php echo $monto_plan_anual_139; ?>" data-textbutton="Acceder al Plan Empresarial" data-toggle="modal" data-target="#vm_tarjeta"><b><i class="icon-cart"></i></b> Comprar</button>

							<!-- Botón Mensual  -->
							<button type="button" class="btn btn-default-theme mb-3 btn_plan_mensual mt-3 btn_compra_culqi" data-idplan="<?php echo $id_plan_mensual_139; ?>" data-title="Plan Empresarial - Suscripción Mensual"   data-description="Plan Empresarial - Suscripción Mensual" data-amount="<?php echo $monto_plan_mensual_139; ?>" data-textbutton="Acceder al Plan Empresarial" data-toggle="modal" data-target="#vm_tarjeta"><b><i class="icon-cart"></i></b> Comprar</button>

							<div class="features-info">
								<a title="Ver características del plan" class="w-100 d-block p-3 text-left d-md-none features-info-show" data-toggle="collapse" href="#collapse_plan3" role="button" aria-expanded="false" aria-controls="collapse_plan3">
								  Mostrar Características
								</a>
								<div class="collapse" id="collapse_plan3">
								  <div class="text-left">
									<ul class="p-0 m-0">
										<li class="features-info__section-title">
											Características
										</li>
										<li>
											Crear Facturas
										</li>
										<li>
											Crear Boletas  
										</li>
										<li>
											Crear Notas de Venta 
										</li>
										<li>Crear Notas de Crédito	</li>
										<li>Crear Notas de Débito</li>
										<li>Duplicar y Copiar Documentos Electrónicos</li>
										<li>Búsqueda de RUC</li>
										<li>Búsqueda de DNI</li>
										<li>Ventas con operaciones gravadas, exoneradas, inafectas, gratuitas y de exportación</li>
										<li>Impuesto ICBPER</li>
										<li>Descuentos Globales</li>
										<li>Sucursales Ilimitadas</li>
										<li>Almacenes Ilimitados</li>
										<li>Usuarios Ilimitados</li>
										<li>Crear Guías de Remisión Electrónica</li>
										<li>Reporte de Ventas</li>
										<li>Reporte detallado de Ventas</li>
										<li>Módulo de Compras</li>
										<li>Búsqueda de Facturas de Compra</li>
										<li>Kardex</li>
										<li>Gestión de Almacén con Entrada y Salidas</li>
										<li>Ventas al Crédito</li>
										<li>Módulo de Cuentas por Cobrar</li>
										<li>Detracciones</li>
										<li>Percepciones</li>
										<li>Libro Electrónico de Compras</li>
										<li>Caja Chica</li>
										<li>MultiPrecio para un Producto</li>
										<li>Libro Electrónico de Ventas</li>
										<li>Ventas en Dólares y Soles</li>
										<li>Dominio Personalizado</li>
										<li>Acceso a 15 Plantillas Web</li>
										<li>Tendrás tu Propio Sitio Web de Ventas</li>
									</ul>
								  </div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="table-responsive mt-5">
			<table class="table d-none d-md-block">
				<thead>
					<tr class="text-center">
						<th scope="col" class="text-left" width="300px">CARACTERÍSTICAS</th>
						<th scope="col">Plan Básico</th>
						<th scope="col">Plan Emprendedor</th>
						<th scope="col">Plan Empresarial</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Crear Facturas  <span class="float-right" data-toggle="tooltip" title="" data-original-title="La cantidad de dominios que puede conectar dentro de un sitio. Con los embudos, puede conectar un dominio personalizado a cada embudo."> </span></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
					</tr>
					<tr>
						<td>Crear Boletas  <span class="float-right" data-toggle="tooltip" title="" data-original-title="Los Embudos de Marketing o llamados funnels le permiten realizar un seguimiento de los objetivos y medir la conversión de cada paso."> </span>
						</td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
					</tr>
					<tr>
						<td>Crear Notas de Venta  <span class="float-right" data-toggle="tooltip" title="" data-original-title="Se mide en GB ya que por cada 1GB equivalen a unos 1.000 visitantes por mes"> </span></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
					</tr>
					<tr>
						<td>Crear Notas de Crédito</td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
					</tr>
					<tr>
						<td>Crear Notas de Débito<span class="float-right" data-toggle="tooltip" title="" data-original-title="Crea increíbles Tiendas en línea con integración de pagos"> </span></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
					</tr>
					<tr>
						<td>Duplicar y Copiar Documentos Electrónicos <span class="float-right" data-toggle="tooltip" title="" data-original-title="Obten más de 100+ plantillas que están diseñadas, probadas y optimizadas para las conversiones."> </span></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
					</tr>
					<tr>
						<td>Búsqueda de RUC  <span class="float-right" data-toggle="tooltip" title="" data-original-title="Crea en pocos clics sitios web completos"> </span></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
					</tr>
					<tr>
						<td>Búsqueda de DNI  <span class="float-right" data-toggle="tooltip" title="" data-original-title="Organiza mejor a tus clientes con nuestro CRM, mensajes, etiquetas"> </span></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
					</tr>
					<tr>
						<td>Ventas con operaciones gravadas, exoneradas, inafectas, gratuitas y de exportación  <span class="float-right" data-toggle="tooltip" title="" data-original-title="Puedes dividir por pruebas de rendimiento los pasos del Funnel (Embudos de Marketing) y elegir un ganador determinado por la conversión. También puedes ver el historial de todas las pruebas A/B."> </span></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
					</tr>
					
					<tr>
						<td>Impuesto ICBPER  <span class="float-right" data-toggle="tooltip" title="" data-original-title="Análisis detallados sobre los Funnels (Embudos de Marketing) y cada paso para ayudarte a comprender y optimizar su rendimiento."> </span></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
					</tr>
					<tr>
						<td>Descuentos Globales  <span class="float-right" data-toggle="tooltip" title="" data-original-title="Puedes crear un blog totalmente funcional para crear artículos, programar y tener comentarios para cada publicación."> </span></td>
						<td class="text-center"><i class="far fa-times-circle text-danger"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
					</tr>
					<tr>
						<td>Sucursales Ilimitadas <span class="float-right" data-toggle="tooltip" title="" data-original-title="Comunidad - Grupo Privado de Revolución Digital donde encontraras empresarios, emprendedores y expertos en temas de marketing digital."> </span></td>
						<td class="text-center">1 Sucursal </td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
					</tr>
					<tr>
						<td>Almacenes Ilimitados  <span class="float-right" data-toggle="tooltip" title="" data-original-title="Entrenamientos que cuenta con todo lo que necesitas para poder dominar el Marketing Digital e impulsar tus ventas de una manera dramática y una gran comunidad que te compartira experiencia y conocimiento"> </span></td>
						<td class="text-center">1 Almacén</td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
					</tr>

					<tr>
						<td>Usuarios Ilimitados  <span class="float-right" data-toggle="tooltip" title="" data-original-title="Certificado SSL (Protocolo de seguridad) gratuito para todos los sitios web creados en la plataforma."> </span></td>
						<td class="text-center">2 Usuarios</td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
					</tr>
					<tr>
						<td>Crear Guías de Remisión Electrónica  <span class="float-right" data-toggle="tooltip" title="" data-original-title="Podrás aceptar pagos en linea utilizando Stripe, Braintree, 2Checkout, Paypal, PayU, Cobro Contra Entega y Transferencia Bancaria"> </span></td>
						<td class="text-center"><i class="far fa-times-circle text-danger"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
					</tr>
					<tr>
						<td>Reporte de Ventas  <span class="float-right" data-toggle="tooltip" title="" data-original-title="Integra tus formularios con plataformas externas para llevar campañas de email marketing"> </span></td>
						<td class="text-center"><i class="far fa-times-circle text-danger"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
					</tr>
					<tr>
						<td>Reporte detallado de Ventas  <span class="float-right" data-toggle="tooltip" title="" data-original-title="Integraión exclusiva con WhatsApp"> </span></td>
						<td class="text-center"><i class="far fa-times-circle text-danger"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
					</tr>
					
					<tr>
						<td>Libro Electrónico de Ventas  <span class="float-right" data-toggle="tooltip" title="" data-original-title="Nuestros especialistas en soporte técnico están allí para ayudarlo en cada paso del camino por correo electrónico"> </span></td>
						<td class="text-center"><i class="far fa-times-circle text-danger"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
					</tr>
					<tr>
						<td>Módulo de Compras  <span class="float-right" data-toggle="tooltip" title="" data-original-title="Soporte 7 días a la semana especializado en la plataforma Exur"> </span></td>
						<td class="text-center"><i class="far fa-times-circle text-danger"></i></td>
						<td class="text-center"><i class="far fa-times-circle text-danger"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
					</tr>
					<tr>
						<td>Búsqueda de Facturas de Compra  <span class="float-right" data-toggle="tooltip" title="" data-original-title="Mejora tus habilidades con guías y recursos gratuitos."> </span></td>
						<td class="text-center"><i class="far fa-times-circle text-danger"></i></td>
						<td class="text-center"><i class="far fa-times-circle text-danger"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
					</tr>
						<td>Kardex <span class="float-right" data-toggle="tooltip" title="" data-original-title="Podrás aceptar pagos en linea utilizando Stripe, Braintree, 2Checkout, Paypal, PayU, Cobro Contra Entega y Transferencia Bancaria"> </span></td>
						<td class="text-center"><i class="far fa-times-circle text-danger"></i></td>
						<td class="text-center"><i class="far fa-times-circle text-danger"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
					</tr>
					<tr>
						<td>Gestión de Almacén con Entrada y Salidas  <span class="float-right" data-toggle="tooltip" title="" data-original-title="Integra tus formularios con plataformas externas para llevar campañas de email marketing"> </span></td>
						<td class="text-center"><i class="far fa-times-circle text-danger"></i></td>
						<td class="text-center"><i class="far fa-times-circle text-danger"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
					</tr>
					<tr>
						<td>Ventas al Crédito  <span class="float-right" data-toggle="tooltip" title="" data-original-title="Integraión exclusiva con WhatsApp"> </span></td>
						<td class="text-center"><i class="far fa-times-circle text-danger"></i></td>
						<td class="text-center"><i class="far fa-times-circle text-danger"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
					</tr>
					
					<tr>
						<td>Módulo de Cuentas por Cobrar  <span class="float-right" data-toggle="tooltip" title="" data-original-title="Nuestros especialistas en soporte técnico están allí para ayudarlo en cada paso del camino por correo electrónico"> </span></td>
						<td class="text-center"><i class="far fa-times-circle text-danger"></i></td>
						<td class="text-center"><i class="far fa-times-circle text-danger"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
					</tr>
					<tr>
						<td>Detracciones  <span class="float-right" data-toggle="tooltip" title="" data-original-title="Soporte 7 días a la semana especializado en la plataforma Exur"> </span></td>
						<td class="text-center"><i class="far fa-times-circle text-danger"></i></td>
						<td class="text-center"><i class="far fa-times-circle text-danger"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
					</tr>
					<tr>
						<td>Percepciones  <span class="float-right" data-toggle="tooltip" title="" data-original-title="Mejora tus habilidades con guías y recursos gratuitos."> </span></td>
						<td class="text-center"><i class="far fa-times-circle text-danger"></i></td>
						<td class="text-center"><i class="far fa-times-circle text-danger"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
					</tr>
						<td>Libro Electrónico de Compras  <span class="float-right" data-toggle="tooltip" title="" data-original-title="Integra tus formularios con plataformas externas para llevar campañas de email marketing"> </span></td>
						<td class="text-center"><i class="far fa-times-circle text-danger"></i></td>
						<td class="text-center"><i class="far fa-times-circle text-danger"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
					</tr>
					<tr>
						<td>Caja Chica  <span class="float-right" data-toggle="tooltip" title="" data-original-title="Integraión exclusiva con WhatsApp"> </span></td>
						<td class="text-center"><i class="far fa-times-circle text-danger"></i></td>
						<td class="text-center"><i class="far fa-times-circle text-danger"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
					</tr>
				
					<tr>
						<td>MultiPrecio para un Producto  <span class="float-right" data-toggle="tooltip" title="" data-original-title="Nuestros especialistas en soporte técnico están allí para ayudarlo en cada paso del camino por correo electrónico"> </span></td>
						<td class="text-center"><i class="far fa-times-circle text-danger"></i></td>
						<td class="text-center"><i class="far fa-times-circle text-danger"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
					</tr>
					<tr>
						<td>Ventas en Dólares y Soles  <span class="float-right" data-toggle="tooltip" title="" data-original-title="Soporte 7 días a la semana especializado en la plataforma Exur"> </span></td>
						<td class="text-center"><i class="far fa-times-circle text-danger"></i></td>
						<td class="text-center"><i class="far fa-times-circle text-danger"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
					</tr>
					<tr>
						<td>Dominio Personalizado  <span class="float-right" data-toggle="tooltip" title="" data-original-title="Mejora tus habilidades con guías y recursos gratuitos."> </span></td>
						<td class="text-center"><i class="far fa-times-circle text-danger"></i></td>
						<td class="text-center"><i class="far fa-times-circle text-danger"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
					</tr>
					<tr>
						<td>Acceso a 15 Plantillas Web  <span class="float-right" data-toggle="tooltip" title="" data-original-title="Soporte 7 días a la semana especializado en la plataforma Exur"> </span></td>
						<td class="text-center"><i class="far fa-times-circle text-danger"></i></td>
						<td class="text-center"><i class="far fa-times-circle text-danger"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
					</tr>
					<tr>
						<td>Tendrás tu Propio Sitio Web de Ventas  <span class="float-right" data-toggle="tooltip" title="" data-original-title="Mejora tus habilidades con guías y recursos gratuitos."> </span></td>
						<td class="text-center"><i class="far fa-times-circle text-danger"></i></td>
						<td class="text-center"><i class="far fa-times-circle text-danger"></i></td>
						<td class="text-center"><i class="far fa-check-circle color-secundary"></i></td>
					</tr>
				</tbody>
				<tbody class="">
				<tr>
					<td>&nbsp;</td>
					<td class="text-center">
						<!-- Boton compra Anual -->
						<button type="button" class="btn  btn-success  mb-3 btn_plan_anual mt-3 btn_compra_culqi" data-idplan="<?php echo $id_plan_anual_97; ?>" data-title="Plan Básico - Suscripción Anual" data-description="Plan Básico - Suscripción Anual" data-amount="<?php echo $monto_plan_anual_97; ?>"  data-textbutton="Acceder al Plan Básico" data-toggle="modal" data-target="#vm_tarjeta"><b><i class="icon-cart"></i></b> Comprar</button>

						<!-- Boton compra Mensual -->
						<button type="button" class="btn btn-default-theme mb-3 btn_plan_mensual mt-3 btn_compra_culqi" data-idplan="<?php echo $id_plan_mensual_97; ?>" data-title="Plan Básico - Suscripción Mensual" data-description="Plan Básico - Suscripción Mensual" data-amount="<?php echo $monto_plan_mensual_97; ?>"  data-textbutton="Acceder al Plan Básico" data-toggle="modal" data-target="#vm_tarjeta"><b><i class="icon-cart"></i></b> Comprar</button>

					</td>
					<td class="text-center">
						<!-- Botón Anual -->
						<button type="button" class="btn btn-success mb-3 btn_plan_anual mt-3 btn_compra_culqi" data-idplan="<?php echo $id_plan_anual_129; ?>" data-title="Plan Emprendedor - Suscripción Anual"  data-description="Plan Emprendedor - Suscripción Anual" data-amount="<?php echo $monto_plan_anual_129; ?>" data-textbutton="Acceder al Plan Emprendedor" data-toggle="modal" data-target="#vm_tarjeta"><b><i class="icon-cart"></i></b> Comprar</button>

						<!-- Boton compra Mensual  -->
						<button type="button" class="btn btn-default-theme mb-3 btn_plan_mensual mt-3 btn_compra_culqi" data-idplan="<?php echo $id_plan_mensual_129; ?>" data-title="Plan Emprendedor - Suscripción Mensual"  data-description="Plan Emprendedor - Suscripción Mensual" data-amount="<?php echo $monto_plan_mensual_129; ?>" data-textbutton="Acceder al Plan Emprendedor" data-toggle="modal" data-target="#vm_tarjeta"><b><i class="icon-cart"></i></b> Comprar</button>

					</td>
					<td class="text-center">
						<!-- Botón Anual -->
						<button type="button" class="btn btn-success mb-3 btn_plan_anual mt-3 btn_compra_culqi" data-idplan="<?php echo $id_plan_anual_139; ?>" data-title="Plan Empresarial - Suscripción Anual"   data-description="Plan Empresarial - Suscripción Anual" data-amount="<?php echo $monto_plan_anual_139; ?>" data-textbutton="Acceder al Plan Empresarial" data-toggle="modal" data-target="#vm_tarjeta"><b><i class="icon-cart"></i></b> Comprar</button>

						<!-- Botón Mensual  -->
						<button type="button" class="btn btn-default-theme mb-3 btn_plan_mensual mt-3 btn_compra_culqi" data-idplan="<?php echo $id_plan_mensual_139; ?>" data-title="Plan Empresarial - Suscripción Mensual"   data-description="Plan Empresarial - Suscripción Mensual" data-amount="<?php echo $monto_plan_mensual_139; ?>" data-textbutton="Acceder al Plan Empresarial" data-toggle="modal" data-target="#vm_tarjeta"><b><i class="icon-cart"></i></b> Comprar</button>

					</td>
				</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="section-boder-bottom section-padding" style="display: none;">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-10 col-md-6">
				<img src="https://exur-exur.netdna-ssl.com/daniel/exur/planes-home.png" class="img-fluid mx-auto d-block" alt="Características para transformar tu negocio con exur">
			</div>
			<div class="col-10 col-md-6 align-self-center text-left color-white">
				<h3 class="color-white">
				Características para transformar tu negocio
				</h3>
				<p>
				Ahora que sabes que Exur se ajusta a tu presupuesto, la próxima pregunta probablemente sea si tenemos todas las funciones que necesita para alcanzar tus grandes objetivos.
				</p>
				<p>
				Descubre cómo podemos ayudarte a hacer crecer tu negocio:
				</p>
				<a title="Características para transformar tu negocio con exur" class="btn btn-white hvr-outline-in btn-border-radius" href="">
				Leer más
				</a>
			</div>
			</div>
	</div>
</div>
<div class="section-grey">
	<div class="container">
	<div class="row gutter-md">
		<div class="col-12 text-center">
			<h3>Preguntas Frecuentes (FAQ's)</h3>
		</div>
		<div class="text-left list-container mt-5 col-12">
			<div id="accordion">
				<div class="card border-bottom">
				<div class="card-header" id="heading0">
					<h4 class="collapse-title" data-toggle="collapse" data-target="#collapse0" aria-expanded="false" aria-controls="collapse0">¿Qué tipo de soporte puedo esperar?
		</h4>
				</div>
				<div id="collapse0" class="collapse mb-4" aria-labelledby="heading0" data-parent="#accordion">
					<div class="card-body">
					<p>
						Envíanos un correo electrónico en cualquier momento con preguntas o comentarios. 
					</p>
					<p>
						Tambien nos puedes comunicarte con nosotros vía whatsapp.
					</p>
					<p>
						Te garantizamos una respuesta inmediata.
					</p>
					</div>
				</div>
				</div>
				<div class="card border-bottom">
				<div class="card-header" id="heading1">
					<h4 class="collapse-title" data-toggle="collapse" data-target="#collapse1" aria-expanded="false" aria-controls="collapse1">¿Cuál es su compromiso con el servicio?</h4>
				</div>
				<div id="collapse1" class="collapse mb-4" aria-labelledby="heading1" data-parent="#accordion">
					<div class="card-body">
						<p>
						Para garantizar la máxima fiabilidad, utilizamos un alojamiento altamente seguro basado en la nube. 
						</p>
						<p>
						Todo el sistema y páginas se monitorean automáticamente cada pocos minutos y se realizan copias de seguridad cada pocas horas, así que ten la seguridad de que siempre estamos al tanto de todo.
						</p>
					</div>
				</div>
				</div>
				<div class="card border-bottom">
				<div class="card-header" id="heading2">
					<h4 class="collapse-title" data-toggle="collapse" data-target="#collapse2" aria-expanded="false" aria-controls="collapse2">¿Cuál es la diferencia entre precios anuales y mensuales?</h4>
				</div>
				<div id="collapse2" class="collapse mb-4" aria-labelledby="heading2" data-parent="#accordion">
					<div class="card-body">
						Los planes anuales se facturan como un pago único una vez al año con un descuento del 25%. Los planes mensuales se facturan todos los meses en la fecha que te registres.
					</div>
				</div>
				</div>
				<div class="card border-bottom">
				<div class="card-header" id="heading3">
					<h4 class="collapse-title" data-toggle="collapse" data-target="#collapse3" aria-expanded="false" aria-controls="collapse3">¿Puedo cambiar de plan?</h4>
				</div>
				<div id="collapse3" class="collapse mb-4" aria-labelledby="heading3" data-parent="#accordion">
					<div class="card-body">
						Puedes cambiar fácilmente los planes a través de nuestro equipo de atención al cliente, comunicate por cualquiera de nuestros canales (Correo electronico o Chat), en donde podran hacer los cambios que solicites. 
					</div>
				</div>
				</div>
				<div class="card border-bottom">
				<div class="card-header" id="heading4">
					<h4 class="collapse-title" data-toggle="collapse" data-target="#collapse4" aria-expanded="false" aria-controls="collapse4">¿Necesito mucha experiencia técnica para usar FacturalaYa?</h4>
				</div>
				<div id="collapse4" class="collapse mb-4" aria-labelledby="heading4" data-parent="#accordion">
					<div class="card-body">
					<p>
						No necesitas experiencia técnica. Debido a que contamos con un sistema fácil de utilizar y muy intuitivo. Por lo único que tendras que preocuparte es por ingresar la información correcta en cada uno de tus comprobantes.
					</p>
					</div>
				</div>
				</div>
				<div class="card border-bottom">
				<div class="card-header" id="heading5">
					<h4 class="collapse-title" data-toggle="collapse" data-target="#collapse5" aria-expanded="false" aria-controls="collapse5">¿Puedo conectar mi propio dominio a FacturalaYa? ¿Quién aloja todas las páginas de mi sitio?</h4>
				</div>
				<div id="collapse5" class="collapse mb-4" aria-labelledby="heading5" data-parent="#accordion">
					<div class="card-body">
						<p>
						Puedes conectar tu dominio con FacturalaYa solo siguiendo unos sencillos pasos pero no solo eso, Facturalaya te brinda un certificado de seguridad (SSL) en tu sitio y el alojamiento de todas tus paginas estarán completamente alojadas por FacturalaYa.
						</p>
					</div>
				</div>
				</div>
				<div class="card border-bottom">
				<div class="card-header" id="heading9">
					<h4 class="collapse-title" data-toggle="collapse" data-target="#collapse9" aria-expanded="false" aria-controls="collapse9">¿Qué formas de pago aceptan? </h4>
				</div>
				<div id="collapse9" class="collapse mb-4" aria-labelledby="heading9" data-parent="#accordion">
					<div class="card-body">Aceptamos tarjetas de crédito y débito Visa, MasterCard y American Express y también aceptamos Transferencias bancarias, si deseas hacer una transferencia comunícate con nuestro equipo de soporte o envíanos un mensaje vía whatsapp al 956295282, indicando tu número de RUC.</div>
				</div>
				</div>
			</div>
		</div>
	</div>
	</div>
</div>
<div class="section-bg-1 section-min-padding " style="display: none;">
	<div class="container py-lg-5">
	<div class="row">
		<div class="col-12 text-center color-white">
		<h4 class=" font-1">
			¡Comencemos ahora!
		</h4>
		<h2 class=" text h4-band">
			Crea tu sitio web y tu embudo
		</h2>
		<h4 class="delgado">
			Nunca habia sido tan fácil crear un sitio web y menos crear una campaña de marketing como si fueras un experto
		</h4>
		<div class="mt-5 pt-2">
			<a href="#" class="btn btn-white btn-border-radius hvr-outline-in" id="ingresar" title="Descubre todos los planes que tiene exur">
			Comenzar Ahora
			</a>
		</div>
		</div>
	</div>
	</div>
</div>
<!-- Start Footer Area -->
<footer class="footer-area">
	<div class="container">
		<div class="text-center color-white">
			<img src="https://arpsystem.com.pe/gestion-plantillas/humbertoguadalup/2/img/logo_footer.png" width="200" alt="footer-logo" class="mb-3">
				<!-- Email -->
				<p class="foo-email">E: <a href="mailto:yourdomain@mail.com">facturalaya.srl@gmail.com</a></p>
				<p>G: Alex Roel Castañeda Aquino</p> 
				<p>P: +51 956295282</p> 
				<!-- <p style="font-size: 12px;">Múltiples métodos de pago</p>
				<div class="card-img text-center">
					<img src="/facturacionv8/img/svg/paymen-icon-visa.webp" alt="" width="60px" class="mr-2 card-single">
					<img src="/facturacionv8/img/svg/paymen-icon-mastercard.webp" alt="" width="60px" class="mr-2 card-single">
					<img src="/facturacionv8/img/svg/paymen-icon-amex.webp" alt="" width="60px" class="mr-2 card-single">
				</div>
				<p style="font-size: 12px;">Compra Segura <i class="ml-2  icon-shield-check text-success"></i></p>
				 -->
				 <img src="https://arpsystem.com.pe/gestion-plantillas/humbertoguadalup/2/img/verificacion.png" width="100px" alt="">
			</div>
		</div>
	
		<div class="copyright-area">
			<p><i class="far fa-copyright"></i> Copyright - 2020</p>
		</div>
	</div>
</footer>
<!-- End Footer Area -->

<!-- Arrow top area -->
<div class="go-top-area">
	<div class="go-top-wrap">
		<div class="go-top-btn-wrap">
			<div class="go-top go-top-btn active">
					<i class="flaticon-up-arrow"></i>
				<i class="flaticon-up-arrow"></i>
			</div>
		</div>
	</div>
</div>

<!-- Modal Plan -->

<div class="modal fade" id="vm_tarjeta" tabindex="-1" role="dialog" aria-labelledby="vm_tarjetaTitle" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="vm_tarjetaTitle"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="tabbable nav-tabs-vertical nav-tabs-left">
					<div class="position-bottom-fixed display-responsive">
						<img src="/facturacionv8/img/secure.png" alt="" width="250px">
					</div>
					
					<div class="faqs-payment display-responsive">
						<hr class="line-medium mb-2">
						<p class="font-weight-bold text-success">Preguntas frecuentes: </p>
						<p class="font-weight-bold text-success"><i class="mr-2 icon-question6"></i>¿Podré cancelar mi suscripción?</p>
						<p>Si! podrás cancelar tu suscripción en cualquier momento.<br /></p>
						<p></p>
						<p class="font-weight-bold text-success"><i class="mr-2 icon-question6"></i>¿Ustedes son Proveedores Autorizados por SUNAT?</p>
						<p>Si! Desde el 26 de Septiembre del 2018 mediante resolución N° 064-005-0002737 fuimos autorizados como proveedores de servicios electrónicos autorizados por SUNAT.<br /></p>
						<p class="font-weight-bold text-success"><i class="mr-2 icon-question6"></i>¿Tendré acceso a soporte?</p>
						<p>Si!, tendrás acceso las 24 horas del día, los 365 días del año.</p>
					</div>
					<ul class="nav nav-tabs nav-tabs-highlight">
						<li class="active">
							<a href="#left-tab1" data-toggle="tab"> 
								<h3 class="title_tab"><span class="text-success font-weight-bold"><img src="https://arpsystem.com.pe/gestion-plantillas/humbertoguadalup/2/img/icons/new.svg" width="20px" class="mr-2" alt=""> Plan Básico: </span>
								</h3>
								
							</a>
						</li>
					</ul>

					<div class="tab-content">
						<div class="tab-pane active has-padding" id="left-tab1">
							<form>
								<input type="hidden" id="id_plan" value="" >
								<div class="row">
									<div class="col-lg-12">
										<div class="text-center">
											<div class="star_plan">
												<img src="https://arpsystem.com.pe/gestion-plantillas/humbertoguadalup/2/img/icons/star.svg" width="20px" class="mr-2">
												<img src="https://arpsystem.com.pe/gestion-plantillas/humbertoguadalup/2/img/icons/star.svg" width="30px" class="mr-2">
												<img src="https://arpsystem.com.pe/gestion-plantillas/humbertoguadalup/2/img/icons/star.svg" width="20px" class="mr-2">
											</div>
											
											<h2 class="plan__price plan_price_modal text-success">
												<span class="precio_basico position-relative">
													<span class="dolar">$</span>300</span>
													<span class="billing-period"> /USD</span>
												</span>
											</h2> 
											<p class="text-price">¡Estás a punto de ahorrar <span class="font-weight-bold text-success"> $ 85</span>! Cancela en cualquier momento.</p>
											<hr class="line-medium">
										</div>
									</div>
									<div class="col-lg-6 col-md-6">
										<div class="form-group">
											<label class="label-form">Correo Electrónico</label>
											<div class="input-group">
												<span class="input-group-addon"><i class="icon-envelop"></i></span>
												<input type="text" class="form-control card_email" data-culqi="card[email]" id="card[email]">
											</div>
										</div>
									</div>
									<div class="col-lg-6 col-md-6">
										<div class="form-group">
											<label>Teléfono</label>
											<div class="input-group">
												<span class="input-group-addon"><i class="icon-phone2"></i></span>
												<input type="text" class="form-control" data-culqi="card[telefono]" id="tarjeta_telefono">
											</div>
										</div>
									</div>
									<div class="col-lg-6 col-md-6">
										<div class="form-group">
											<label class="label-form">Dirección</label>
											<div class="input-group">
												<span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
												<input type="text" class="form-control" data-culqi="card[direccion]" id="tarjeta_direccion">
											</div>
										</div>
									</div>
									<div class="col-lg-6 col-md-6">
										<div class="form-group">
											<label>Ciudad</label>
											<div class="input-group">
												<span class="input-group-addon"><i class="icon-office"></i></span>
												<input type="text" class="form-control" data-culqi="card[direccion]" id="tarjeta_ciudad">
											</div>
										</div>
									</div>
								
									<div class="col-lg-12 col-md-12">
										<div class="form-group">
											<div class="field-container" style="display: block;">
												<label>Número de tarjeta</label>
												<div class="input-group">
													<span class="input-group-addon"><i class="icon-credit-card2"></i></span>
													<input type="text" class="form-control cardnumber" data-culqi="card[number]" pattern="[0-9]*" inputmode="numeric" id="card[number]">
													<svg id="ccicon" class="ccicon" width="750" height="471" viewBox="0 0 750 471" version="1.1" xmlns="http://www.w3.org/2000/svg"
													xmlns:xlink="http://www.w3.org/1999/xlink">
									
												</svg>
												</div>
												
											</div>
										</div>
									</div>
									<div class="col-lg-4 col-md-6">
										<div class="form-group">
											<label>CVV</label>
											<div class="input-group">
												<span class="input-group-addon"><i class="icon-credit-card2"></i></span>
												<input type="text" class="form-control securitycode"  data-culqi="card[cvv]" id="card[cvv]" pattern="[0-9]*" inputmode="numeric">
											</div>
										
										</div>
									</div>
									<div class="col-lg-8 col-md-6">
										<div class="form-group">
											<label>Fecha expiración de la tarjeta (MM/YYYY)</label>
											<div class="row">
												<div class="col-lg-5 col-md-5 col-5">
													<div class="input-group">
														<span class="input-group-addon addon_text">Mes</span>
														<input size="2" class="form-control expirationdate_month" data-culqi="card[exp_month]" id="card[exp_month]" type="text" pattern="[0-9]*" inputmode="numeric">
													</div>
												</div>
												<div class="col-lg-1 col-md-1 m-0 col-1">
													<span>/</span>
												</div>
												<div class="col-lg-5 col-md-5 m-0 col-5">
													<div class="input-group">
														<span class="input-group-addon addon_text">Año</span>
														<input size="4"  class="form-control expirationdate_year" data-culqi="card[exp_year]" id="card[exp_year]"  type="text" pattern="[0-9]*" inputmode="numeric">
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-lg-12 aling-main">
										<div class="form-group">
											<div class="row">
												<div class="col-lg-12">
													<p style="font-size: 12px;">Múltiples métodos de pago</p>
												</div>
												<div class="col-lg-8 col-md-6">
													<img src="/facturacionv8/img/svg/paymen-icon-visa.webp" alt="" width="60px" class="mr-2">
													<img src="/facturacionv8/img/svg/paymen-icon-mastercard.webp" alt="" width="60px" class="mr-2">
													<img src="/facturacionv8/img/svg/paymen-icon-amex.webp" alt="" width="60px" class="mr-2">
												</div>
												<div class="col-lg-4 col-md-6">
													<p style="font-size: 12px;">Compra Segura <i class="ml-2  icon-shield-check text-success"></i></p>
												</div>
											</div>
										</div>
									</div>
									<div class="col-lg-12 d-lg-none text-center">
										<img src="/facturacionv8/img/secure.png" alt="" width="250px">
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success"  id="btn_pagar"><i class="mr-2  icon-arrow-right6"></i>Continuar</button>
			</div>
		</div>
	</div>
</div>