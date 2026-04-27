<style>
@media (min-width: 769px) {
	.sidebar-xs .header-highlight .navbar-header .navbar-brand {
		padding-left: 0;
		padding-right: 0;
		background: url(<?php echo $data_empresa["logo_img_56"]; ?>) no-repeat center center;
		float: none;
		display: block;
		background-position: 13px 2px;
		background-size: 36px 39px;
	}
	.sidebar-xs .header-highlight .navbar-header .navbar-brand > img {
		display: none;
	}
	#navbar-logo{
		height: 39px!important;
		margin-top: 0px;
	}
}
@media only screen and (max-width: 760px) and (min-width: 300px){
	.collapse#demo1 {
		display: inherit;
	}
}
@media (min-width: 600px) and (max-width: 1024px) {
	.collapse#demo1 {
		display: inherit;
	}
		#demo1 .nav>li {
		position: relative;
		display: inline-block;
	}
}
/* Estilos personalizados */
.navbar.bg-indigo {
	background: linear-gradient(45deg, <?php echo $data_personalizacion["color_fondo_1_rgb"]; ?> 0%, <?php echo $data_personalizacion["color_fondo_2_rgb"]; ?> 100%);
}
.bg-indigo, .bg-primary {
	background: linear-gradient(45deg, <?php echo $data_personalizacion["color_fondo_1_rgb"]; ?> 0%, <?php echo $data_personalizacion["color_fondo_2_rgb"]; ?> 100%);
}

.btn-primary, .btn.bg-indigo, .label-primary, .btn-primary:active:hover, .btn-primary.active:hover, .open > .dropdown-toggle.btn-primary:hover, .btn-primary:active:focus, .btn-primary.active:focus, .open > .dropdown-toggle.btn-primary:focus, .btn-primary:active.focus, .btn-primary.active.focus, .open > .dropdown-toggle.btn-primary.focus {
	-webkit-appearance: none;
	background: -webkit-gradient(to right, <?php echo $data_personalizacion["color_fondo_1_rgb"]; ?> 0%, <?php echo $data_personalizacion["color_fondo_1_rgb"]; ?>85 50%,  <?php echo $data_personalizacion["color_fondo_2_rgb"]; ?>);
	background: linear-gradient(to right, <?php echo $data_personalizacion["color_fondo_1_rgb"]; ?> 0%,  <?php echo $data_personalizacion["color_fondo_1_rgb"]; ?>85 50%, <?php echo $data_personalizacion["color_fondo_2_rgb"]; ?>);
	background-size: 500%;

}
.navbar.navbar-inverse.bg-teal-400 {
	background: linear-gradient(45deg,  <?php echo $data_personalizacion["color_fondo_1_rgb"]; ?> 0%,  <?php echo $data_personalizacion["color_fondo_2_rgb"]; ?> 100%);
}
.dataTables_paginate .paginate_button.current, .dataTables_paginate .paginate_button.current:hover, .dataTables_paginate .paginate_button.current:focus {
	color: #fff;
	-webkit-appearance: none;
	background: -webkit-gradient(to right, <?php echo $data_personalizacion["color_fondo_1_rgb"]; ?> 0%, <?php echo $data_personalizacion["color_fondo_1_rgb"]; ?> 50%, <?php echo $data_personalizacion["color_fondo_2_rgb"]; ?>);
	background: linear-gradient(45deg,  <?php echo $data_personalizacion["color_fondo_1_rgb"]; ?> 0%,  <?php echo $data_personalizacion["color_fondo_2_rgb"]; ?> 100%);
}
.navbar-default .navbar-nav>.active>a, .navbar-default .navbar-nav>.active>a:focus, .navbar-default .navbar-nav>.active>a:hover {
	background: linear-gradient(45deg,  <?php echo $data_personalizacion["color_fondo_1_rgb"]; ?> 0%,  <?php echo $data_personalizacion["color_fondo_2_rgb"]; ?> 100%);
}

.navigation li a>i, .info-empresa p > i {
	background: -webkit-linear-gradient( <?php echo $data_personalizacion["color_fondo_1_rgb"]; ?>,  <?php echo $data_personalizacion["color_fondo_2_rgb"]; ?>);
		-webkit-background-clip: text;
	-webkit-text-fill-color: transparent;
	
}

.label-form i,  legend i {
	background: -webkit-linear-gradient( <?php echo $data_personalizacion["color_fondo_1_rgb"]; ?>,  <?php echo $data_personalizacion["color_fondo_2_rgb"]; ?>)!important;
	-webkit-background-clip: text!important;
	-webkit-text-fill-color: transparent;
}
.img-user-content::after, #img_upload_preview {
	border-left: 2px solid <?php echo $data_personalizacion["color_fondo_1_rgb"]?>;
}
.bootstrap-switch-handle-off.bootstrap-switch-primary, .bootstrap-switch-handle-on.bootstrap-switch-primary {
	color: #fff;
	background: linear-gradient(45deg,  <?php echo $data_personalizacion["color_fondo_1_rgb"]; ?> 0%,  <?php echo $data_personalizacion["color_fondo_2_rgb"]; ?> 100%);
}
.text-primary-800, .text-primary-800:focus, .text-primary-800:hover {
	color: <?php echo $data_personalizacion["color_fondo_1_rgb"]; ?>!important;
}
.border-primary-600 {
	border-color: <?php echo $data_personalizacion["color_fondo_1_rgb"]; ?>!important;
}
.nav-tabs.nav-tabs-highlight>li.active>a, .nav-tabs.nav-tabs-highlight>li.active>a:focus, .nav-tabs.nav-tabs-highlight>li.active>a:hover {
	border-top-color: <?php echo $data_personalizacion["color_fondo_1_rgb"]; ?>;
}
.form-control:not(.border-bottom-1):not(.border-bottom-2):not(.border-bottom-3):focus {
	border-color: <?php echo $data_personalizacion["color_fondo_1_rgb"]; ?>;
	transition-timing-function: ease;
}
.color-indigo, .nav-tabs.nav-tabs-bottom > li.active > a, .nav-tabs.nav-tabs-bottom > li.active > a:hover, .nav-tabs.nav-tabs-bottom > li.active > a:focus{
	color: <?php echo $data_personalizacion["color_fondo_1_rgb"]; ?>!important;
}
/* pantallas especiales */
.table100.ver1 .row100 td:hover, .nav-tabs.nav-tabs-bottom>li.active>a:after /* puede dar error */ {
	background-color: <?php echo $data_personalizacion["color_fondo_1_rgb"]; ?>!important;
	color: #fff;
}
.opciones_producto, .input-group-addon.bg-indigo{
	background: linear-gradient(45deg,  <?php echo $data_personalizacion["color_fondo_1_rgb"]; ?> 0%,  <?php echo $data_personalizacion["color_fondo_2_rgb"]; ?> 100%)!important;
}
.img-border-content {
	border: 1px solid <?php echo $data_personalizacion["color_fondo_1_rgb"]; ?>!important;
}
.btn-primary:not(:disabled):not(.disabled).active, .btn-primary:not(:disabled):not(.disabled):active, .show>.btn-primary.dropdown-toggle, .btn-primary.focus, .btn-primary:focus, .btn-primary:hover {
	color: #fff;
	background-color:  <?php echo $data_personalizacion["color_fondo_1_rgb"]; ?>!important;
	border-color:  <?php echo $data_personalizacion["color_fondo_1_rgb"]; ?>!important;
}
</style>
<div class="navbar bg-indigo navbar-default header-highlight navbar-inverse" id="navbar-indigo">
	<div class="navbar-header">
		<a class="navbar-brand" href="/" ><img src="<?php echo $data_empresa['logo_img_291']; ?>" alt="" id="navbar-logo"></a>
		<ul class="nav navbar-nav visible-xs-block">
			<li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
			<li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
		</ul>
	</div>
	<div class="navbar-collapse collapse" id="navbar-mobile">
		<ul class="nav navbar-nav">
			<li><a class="sidebar-control sidebar-main-toggle hidden-xs"><i class="icon-paragraph-justify3"></i></a></li>

			<li class="dropdown">
				<?php
				if(count($lista_grupo_vendedores) > 0) {
				?>
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<i class="icon-city"></i>
						<span class="position-right">Lista de Empresas</span>
						<span class="status-mark border-pink-300"></span>
					</a>
					
					<div class="dropdown-menu dropdown-content">
						<div class="dropdown-content-heading">
							Lista de Empresas
							<ul class="icons-list">
								<li><a href="#"><i class="icon-city"></i></a></li>
							</ul>
						</div>

						<ul class="media-list dropdown-content-body width-350">
							<?php
							foreach($lista_grupo_vendedores as $vendedor_grupo) {
							?>
							<li class="media">
								<div class="media-left">
									<a href="<?php echo $vendedor_grupo['url_acceso'] ?>" class="btn border-primary text-primary btn-flat btn-rounded btn-icon btn-sm"><img src="<?php echo $vendedor_grupo['url_image'] ?>" style="width: 40px;"></a>
								</div>

								<div class="media-body">
									<?php echo $vendedor_grupo['ruc'].': '.$vendedor_grupo['razon_social']; ?>
									<div class="media-annotation">Email: <?php echo $vendedor_grupo['email'] ?> <br /> Login: <a href="<?php echo $vendedor_grupo['url_acceso'] ?>" >Click Para Cambiar</a></div>
								</div>
							</li>
							<?php
							}
							?>
						</ul>
					</div>
				<?php
				}
				?>
			</li>

		</ul>
		<p class="navbar-text">
			<span class="label <?php if($tipo_envio_sunat != 'produccion'){ echo 'bg-success'; } else {echo 'bg-primary'; } ?>"><?php echo $tipo_envio_sunat; ?></span>
		</p>
		<div class="navbar-right">
			<ul class="nav navbar-nav">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle legitRipple drop-bell" data-toggle="dropdown" aria-expanded="false">
						<i class="icon-bell2"></i>
						<span class="visible-xs-inline-block position-right">Actividad</span>
						<span class="status-mark danger-pulse  border-danger-700"></span>
					
					</a>

					<div class="dropdown-menu dropdown-content">
						<div class="dropdown-content-heading">
							Actividad
							<ul class="icons-list">
								<li><a href="#"><i class="icon-menu7"></i></a></li>
							</ul>
						</div>

						<ul class="media-list dropdown-content-body width-350">
							<li class="media">
								<div class="media-left">
									<a href="#" class="btn bg-success-400 btn-rounded btn-icon btn-xs legitRipple padding-right-left-1">
										<i class="fa fa-refresh fa-1x"></i>
									</a>
								</div>

								<div class="media-body">
									<a href="#schedule">Tienes 
										<span class="badge badge-danger badge-inline position-right docs_pendientes_envio"></span> 
										pendientes
									</a>
								</div>
							</li>

							
						</ul>
					</div>
				</li>
				<li class="dropdown dropdown-user">
					<a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						<img src="<?php if(!isset($user['url_image'])){echo '/facturacionv8/img/man_default.svg'; } else { echo $user['url_image']; } ?>" alt="">
						<span><?php echo $user['nombre']?></span>
						<i class="caret"></i>
					</a>

					<ul class="dropdown-menu dropdown-menu-right">
						<li><a href="/facturacionv8/profile"><i class="icon-user-plus"></i> Mi perfil</a></li>
						<li style="display:none;"><a href="#"><i class="icon-coins"></i>Facturación</a></li>
						<li><a target="_blank" href="<?php echo $data_empresa['url_soporte']; ?>"><i class="icon-cog5"></i>Soporte</a></li>
						<li class="divider"></li>
						<li><a href="/facturacionv8/configcompany"><i class="icon-cog5"></i> Configurar empresa</a></li>
						<li><a href="/facturacionv8/login/logout"><i class="icon-switch2"></i> Cerrar sesión</a></li>
					</ul>
				</li>
			</ul>

			
		</div>
	</div>
</div>