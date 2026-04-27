<style>
.sidebar-dark .nav-sidebar .nav-item-divider, .sidebar-light .card[class*=bg-]:not(.bg-light):not(.bg-white):not(.bg-transparent) .nav-sidebar .nav-item-divider {
	background-color: rgba(255,255,255,.1);
}
.nav-sidebar .nav-item-divider {
	margin: .5rem 0;
	height: 1px;
}
.navigation > li ul li a {
	padding-left: 30px;
}
</style>
<div class="sidebar sidebar-main sidebar-default">
	<div class="sidebar-content">
		<!-- User menu -->
		<div class="sidebar-user">
			<div class="category-content">
				<!-- Div nuevo del profile -->
				<div class="media-icon-user profile_div_grande">
					<div class="img-user-content">
						<a href="/facturacionv8/profile" class="media-default-img">
							<img style="border-radius: 50%;" src="<?php if(!isset($user['url_image'])){echo '/facturacionv8/img/man_default.svg'; } else { echo $user['url_image']; } ?>" class="img-sm" alt="">
						</a>
					</div>
					<div class="info-user-text">
						<p>
							<span class="media-heading text-semibold"><?php echo $user['nombre'].' '.$user['apellido']; ?> <br /> <?php if(isset($user['ruc'])){ echo $user['ruc']; } ?></span>
							<?php
							if($user['id_rol'] == 1) {
								$icono_rol = 'icon-user-tie';
							} elseif ($user['id_rol'] == 2) {
								$icono_rol = 'icon-user-tie';
							} elseif ($user['id_rol'] == 3) {
								$icono_rol = 'icon-user-tie';
							} elseif ($user['id_rol'] == 4) {
								$icono_rol = 'icon-user-check';
							} else {
								$icono_rol = 'icon-user-tie';
							}
							?>
						</p>
						<span class="btn bg-indigo">
							<i class="<?php echo $icono_rol; ?> text-size-small"></i> &nbsp;<?php echo $user['rol_alias']; ?>
						</span>
						<div class="info-empresa" style="display: none;">
							<p class="ruc-user"><i class="icon-user mr-2"></i><?php if(isset($user['ruc'])){ echo $user['ruc']; } ?></p>
							<p class="razon-user"><i class="icon-briefcase mr-2"></i><?php echo $user['razon_social']; ?></p>
						</div>
					</div>
				</div>
				<!-- Div anterior, solo debe mostrarse al reducirse el sidebar -->
				<div class="media profile_div_pequeno" style="display: none;">
					<a href="#" class="media-left"><img style="border-radius: 50%;" src="<?php if(!isset($user['url_image'])){echo '/facturacionv8/img/man_default.svg'; } else { echo $user['url_image']; } ?>" class="img-sm" alt=""></a>
					<div class="media-body">
						<span class="media-heading text-semibold"><?php echo $user['nombre'].' '.$user['apellido']; ?></span>
						<?php
						if($user['id_rol'] == 1) {
							$icono_rol = 'icon-user-tie';
						} elseif ($user['id_rol'] == 2) {
							$icono_rol = 'icon-user-tie';
						} elseif ($user['id_rol'] == 3) {
							$icono_rol = 'icon-user-tie';
						} elseif ($user['id_rol'] == 4) {
							$icono_rol = 'icon-user-check';
						} else {
							$icono_rol = 'icon-user-tie';
						}
						?>
						<div class="text-size-mini text-muted">
							<i class="<?php echo $icono_rol; ?> text-size-small"></i> &nbsp;<?php echo $user['rol_alias']; ?>
							
						</div>
						
					</div>

					<div class="media-right media-middle">
						<ul class="icons-list">
							<li>
								<a href="/facturacionv8/profile"><i class="icon-cog3"></i></a>
							</li>
						</ul>
					</div>
					
				</div>
			</div>
		</div>
		<div class="sidebar-category sidebar-category-visible">
			<div class="category-content no-padding">
				<ul class="navigation navigation-main navigation-accordion" id="sidebar_principal">
					<!-- Main -->
					{{ elements.getSidebarMenu() }} 
				</ul>
			</div>
		</div>
	</div>
</div>