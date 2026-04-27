<style>
a{
	color: #3F51B5;
}

h1{
	padding: 0;
	margin: 0;
}
html, body {
	height: 100%;
	margin: 0;
	
}
body{

	height: 100%;
	max-width: 100%;
	background-image: url(/facturacionv8/img/hero-6.jpg);
	background-position: center center;
	background-repeat: no-repeat; 
	background-attachment: fixed; 
	background-size: cover; 
	width: 100%; 
	height: 100%;  

	color: #000;
}
	/*
Buttons
======================*/
.btn.bg-indigo {
	-webkit-appearance: none;
	background: -webkit-gradient(to right, #7880f0 0%, #b4b9ff 50%, #3f51b5);
	background: linear-gradient(to right, #7880f0 0%, #b4b9ff 50%, #3f51b5);
	background-size: 500%;
	border: none;
	border-radius: 5rem;
	box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
	color: #fff;
	cursor: pointer;
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
	outline: none;
	-webkit-tap-highlight-color: transparent;
	-webkit-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}
.btn.bg-indigo:hover{
	animation-name: gradient;
	-webkit-animation-name: gradient;
	animation-duration: 2s;
	-webkit-animation-duration: s;
	animation-iteration-count: 1;
	-webkit-animation-iteration-count: 1;
	animation-fill-mode: forwards;
	-webkit-animation-fill-mode: forwards;
}

@keyframes gradient {
	0% {
		background-position: 0% 80%;
	}
	100% {
		background-position: 100%;
	}
	}
	@-webkit-keyframes fadeInDown{
		0%
	{
opacity:0;
-webkit-transform:translate3d(0,-10%,0);
transform:translate3d(0,-10%,0)}
to{
	opacity:1;
	-webkit-transform:none;transform:none}
}
@keyframes fadeInDown{
0%{
	opacity:0;-webkit-transform:translate3d(0,-10%,0);
transform:translate3d(0,-10%,0)
}
to{
	opacity:1;-webkit-transform:none;transform:none
}
}
.fadeInDown{
-webkit-animation-name:fadeInDown;
animation-name:fadeInDown
}
.form-control {
	border-radius: 15px;
	border: 1px solid #ddd;
}
.small, small {
	font-size: 14px;
	font-weight: 400;
}
.img-logo{
	margin-bottom: 2em;

}
.img-logo img{
	max-width: 100%;
	height: auto;
}

.input-eye{
	background: transparent;
	color: #3F51B5;
	border: 1px solid #ddd;
	border-bottom-left-radius: 0px!important;
	border-top-left-radius: 0px!important;
	border-bottom-right-radius: 15px!important;
	border-top-right-radius: 15px!important;
}
.lead{
	color: #fff;
}
.login-form{
	z-index: 2;
}
.logo{
	width: 250px
}
.panel {
	position: relative;
	display: -ms-flexbox;
	display: flex;
	-ms-flex-direction: column;
	flex-direction: column;
	min-width: 0;
	word-wrap: break-word;
	background-color: #fff;
	background-clip: border-box;
	border: 1px solid rgba(0,0,0,.125);
	border-radius: 50px 0 50px 0;
}

.page-content {
	position: relative;
}
.page-content::before {
	position: absolute;
		left: 0;
		right: 0;
		bottom: 0;
		z-index: 1;
		width: 100%;
		margin: 0 auto;
}
.shape-bottom img.bottom-shape {
	position: absolute;
	left: 0;
	right: 0;
	bottom: 0;
	width: 100%;
	margin: 0 auto;
}
.text-xs-center {
	text-align: center;
}

.g-recaptcha {
	display: inline-block;
}
.text-main-welcome{
	padding: 1em;
}
@media(min-width: 900px){
	.text-main-welcome{
		margin-top: 4em;
	}
	.content{
		display: flex;
		justify-content: center;
		align-items: center;
		overflow: auto;
	}
	.login-form{
		width: 500px;
	}
}
@media(min-width: 700px) and (max-width: 1025px){
	.login-form{
		width: 500px;
	}
	.content{
		display: flex;
		justify-content: center;
		align-items: center;
		overflow: auto;
	}
}
@media(min-width: 1500px){
	.text-main-welcome{
		margin-top: 10em;
	}
	.login-form{
		width: 500px;
	}
}
</style>
<div class="page-container">
	<div class="page-content">
		<div class="content-wrapper">
			<div class="text-center text-main-welcome" <?php if($accion == 'email_enviado') { echo ""; } else {echo 'style="display: none;"'; } ?>>
				<div class="img-logo" style="<?php if($data_empresa['url_domain'] != 'facturalaya.com'){ echo 'display: none;'; } ?>">
					<img src="https://arpsystem.com.pe/gestion-plantillas/humbertoguadalup/1/images/hero-logo2.png"  class="logo-info" width="500px" alt="">
				</div>
				<h1 class="text-white">Soporte <?php echo ucwords($data_empresa['url_domain']); ?></h1>
				<img src="/facturacionv8/img/sub.png"  class="subo"  alt="">
				<p class="lead">
					Hemos Enviado el Enlace de Recuperación del Password a tu Correo Electrónico.
				</p>
			</div>

			<div class="text-center text-main-welcome" <?php if($accion == 'recover') { echo ""; } else {echo 'style="display: none;"'; } ?>>
				<div class="img-logo" style="<?php if($data_empresa['url_domain'] != 'facturalaya.com'){ echo 'display: none;'; } ?>">
					<img src="https://arpsystem.com.pe/gestion-plantillas/humbertoguadalup/1/images/hero-logo2.png"  class="logo-info" width="500px" alt="">
				</div>
				<h1 class="text-white">Soporte <?php echo ucwords($data_empresa['url_domain']); ?></h1>
				<img src="/facturacionv8/img/sub.png"  class="subo"  alt="">
				<p class="lead">
					Escribe tu correo electrónico y enviaremos un correo con un enlace de recuperació a tu email...
				</p>
			</div>
			
			<div class="content">
				<form class="frm_recover_password" action="/facturacionv8/login/recoverpassword" method="get" id="frm_recover_password" <?php if($accion == 'recover') { echo ""; } else {echo 'style="display: none;"'; } ?>>
					<div class="panel panel-body login-form">
						<div class="col-md-12">
							<div class="text-center">
								<img src="<?php echo $data_empresa['logo_img_461']; ?>" class="logo" alt="">
								<h5 class="content-group">Recuperar Contraseña</h5>
								
								<div class="form-group">
									<label  class="label-form">
										<i class="icon-envelop mr-2"></i>
										Email
									</label>
									<input type="email" name="email" class="form-control input-login" placeholder="Email">
								</div>
								<div class="col-md-12 text-xs-center" style="margin-top: 10px; margin-bottom: 10px;">
									<div style="margin: 0 auto;" class="g-recaptcha" data-sitekey="<?php echo $data_empresa['captcha_key_public']; ?>" data-callback="enableBtn"></div>
								</div>
							</div>
						</div>
						
						<div class="col-md-12">
							<?= $this->flashSession->output() ?>
						</div>
						<div class="col-md-12">
							<div class="form-group text-center">
								<button type="submit" class="btn bg-indigo btn-login legitRipple btn_recuperarpass">Recuperar <i class="icon-circle-right2 position-right"></i></button>
							</div>
							
							<div class="text-center sign-up">
								<p class="ftz-17">¿No tienes cuenta? <a href="/facturacionv8/registro" target="_blank">Regístrate</a></p>
							</div>
						</div>
						
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="shape-bottom">
		<img src="/facturacionv8/img/hero-shape-bottom.svg" alt="shape" class="bottom-shape img-fluid">
	</div>
</div>
