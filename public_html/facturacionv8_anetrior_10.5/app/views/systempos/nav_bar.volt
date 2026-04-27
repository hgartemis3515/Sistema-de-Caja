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
                        <li><a href="#"><i class="icon-coins"></i>Facturación</a></li>
                        <li><a href="#"><i class="icon-cog5"></i>Soporte</a></li>
                        <li class="divider"></li>
                        <li><a href="/facturacionv8/configcompany"><i class="icon-cog5"></i> Configurar empresa</a></li>
                        <li><a href="/facturacionv8/login/logout"><i class="icon-switch2"></i> Cerrar sesión</a></li>
                    </ul>
                </li>
            </ul>

            
        </div>
    </div>
</div>