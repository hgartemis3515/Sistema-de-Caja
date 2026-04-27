<style>
a{
	color: #3F51B5;
}
html, body {
	height: 100%;
	margin: 0;
	
}
body{

	height: 100%;
	max-width: 100%;
	position:relative;
	z-index:1;
	color: #000;
}
body:after{
	content: "";
	position: absolute;
	top: 0; 
	left: 0;
	width: 100%; 
	height: 100%;  
	/* opacity: .1;  */
	z-index: -1;
	background-image: url(/facturacionv8/img/603.jpg);
	background-position: center center;
	background-repeat: no-repeat;
	background-attachment: fixed;
	background-size: cover;
}
.content{
	display: flex;
	justify-content: center;
	align-items: center;
}
.input-login,
.input-control{
	border: 0;
	border-bottom: 1.3px solid #ddd;
}
.input-eye{
	background: transparent;
	color: #3F51B5;
	border: none;
}
.logo{
	width: 250px
}
.panel {
		border-radius: 2rem;
		border: 0px;
		box-shadow: rgba(33,33,33,.08) 0 4px 24px 5px;
}
.page-container {
	width: 100%;
	display: table;
	table-layout: fixed;
}
@media(min-width: 900px){
	.content{
		margin-top: 8em;
	}
	.login-form{
		width: 350px;
	}
}
@media(min-width: 700px) and (max-width: 1025px){
	.content{
		margin-top: 12em;
	}
	.login-form{
		width: 400px;
	}
}
@media(min-width: 1500px){
	.content{
		margin-top: 10em;
	}
	.login-form{
		width: 400px;
	}
}
</style>
<div class="page-container">
	<div class="page-content">
		<div class="content-wrapper">
			<div class="content">
				<form  class="frm_login" action="/facturacionv8/login/session" method="post">
					<div class="panel panel-body login-form">
						<div class="text-center">
						<img src="<?php echo $data_empresa['logo_img_461']; ?>" class="logo" alt="">
						<h5 class="content-group">Iniciar Sesión</h5>
					</div>
					<div class="form-group">
						<label  class="label-form">
							<i class="icon-envelop mr-2"></i>
							Email
						</label>
						<input type="email" name="email" class="form-control input-login" placeholder="Email">
					</div>
					<div class="form-group">
						<label  class="label-form">
							<i class="icon-lock2 mr-2"></i> 
							Contraseña
						</label>
						<div class="form-group input-group">
							<input type="password" name="password" id="contrasena" class="form-control input-login" placeholder="contrasena">
							<span class="input-group-addon input-eye" id="show-passwd" action="hide"><i class="icon-eye-blocked"></i></span>
						</div>
					</div>
					<div class="form-group">
						<?= $this->flashSession->output() ?>
					</div>
					<div class="form-group text-center">
						<button type="submit" class="btn bg-indigo btn-login legitRipple">Acceder <i class="icon-circle-right2 position-right"></i></button>
					</div>
					<div class="text-center">
						<a class="ftz-17" href="login_password_recover.html">¿Olvidaste la Contraseña?</a>
					</div>
					<div class="text-center sign-up">
						<p class="ftz-17">¿No tienes cuenta? <a href="/facturacionv8/registro" target="_blank">Regístrate</a></p>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>