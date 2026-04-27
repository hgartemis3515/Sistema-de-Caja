<style>
[class^="fa-"], [class*=" fa-"] {
  
     
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
#navbar-indigo{
    display: none;
}
.btn-icon.btn-xs, .input-group-xs > .input-group-btn > .btn.btn-icon {
    padding-left: 10px;
    padding-right: 10px;
}
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
}
</style>
<div class="navbar bg-indigo navbar-default header-highlight navbar-inverse">
    <div class="navbar-header bg-indigo">
        <a class="navbar-brand" href="/" ><img style="height: 39px !important;" src="<?php echo $data_empresa['logo_img_291']; ?>" alt=""></a>
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
                    <a href="#" class="dropdown-toggle legitRipple" data-toggle="dropdown" aria-expanded="false" onclick="active_noti();">
                        <i class="icon-bell2"></i>
                        <span class="visible-xs-inline-block position-right">Actividad</span>
                        <?php
                            if($total != 0)
                            {
                                echo '<span class="status-mark border-pink-300"></span>';
                            }
                        ?>
                    </a>

                    <div class="dropdown-menu dropdown-content">
                        <div class="dropdown-content-heading">
                            Actividad
                            <ul class="icons-list">
                                <li><a href="#"><i class="icon-menu7"></i></a></li>
                            </ul>
                        </div>

                        <ul class="media-list dropdown-content-body width-350">
                            <?php
                            if($total != 0)
                            {
                                echo '<li class="media">
                                        <div class="media-left">
                                                <a href="#" class="btn bg-success-400 btn-rounded btn-icon btn-xs legitRipple"><i class="icon-spinner11"></i></a>
                                            </div>
                                <div class="media-body">      
                                    <a href="#schedule">'.$user['nombre'].'</a>, tienes<span class="badge badge-danger badge-inline position-right docs_pendientes_envio"></span> documentos pendientes. Chequea aquí!
                                </div>
                            </li>
                           
                            '; 
                            } else{
                                echo '<li class="media">No tienes notificaciones pendientes</li>';
                            }
                            ?>
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
