<div class="custom-navbar-pt d-flex">
    <div class="wrap-logo-navbar  d-flex">
        <div class="menu-sidebar-left">
            <i class="ph-list"></i>
        </div>
        <div class="logo-img">
            <img src="<?php echo $data_empresa['logo_img_461']; ?>" alt="logo header" class="logo-custom-header">
        </div>
        
    </div>
    <div class="custom-header-items d-flex">
        <div class="header-single-item  d-flex position-relative tooltip-pt">
            <div id="internet_conexion_content" class="wifi-online label bg-success d-flex">
                <span><i class="ph-wifi-high"></i></span>
                <p id="internet_conexion_text">Disponible</p>
            </div>
            <div class="box-tooltip-pt">
                <p>Tienes conexión y todas las funciones están disponibles para hacer crecer tu negocio.</p>
            </div>
        </div>
        <div class="header-single-item  tooltip-pt">
            <div class="dropwdown-wrap-pt">
                <div class="title-drop-main">
                    <a href="JavaScript:void(0)">
                        <i class="icon-loop3"></i>
                    </a>
                </div>
                <ul class="dropdown-menu-pt" id="dropdown_nav_info_sincronizacion">
                    <li id="sync_status_general" class="text-success font-weight-bold ">Sincronización al día: 14/11/2024 11:10:24 AM</li>
                    <li id="sync_status_cliente"><a href="javascript:void(0)" class="align-items d-flex btn_sincro_pt"><i class="ph-check-circle"></i>Tabla Clientes</a></li>
                    <li id="sync_status_categoria"><a href="javascript:void(0)" class="align-items d-flex btn_sincro_pt"><i class="ph-check-circle"></i>Tabla Categorías</a></li>
                    <li id="sync_status_condiciondepago"><a href="javascript:void(0)" class="align-items d-flex btn_sincro_pt"><i class="ph-check-circle"></i>Tabla Modalidade Pago</a></li>
                    <li id="sync_status_cuentabanco"><a href="javascript:void(0)" class="align-items d-flex btn_sincro_pt"><i class="ph-check-circle"></i>Cuentas Bancarias</a></li>
                    <li id="sync_status_sunatcodigoubigeo"><a href="javascript:void(0)" class="align-items d-flex btn_sincro_pt"><i class="ph-check-circle"></i>Ubigeos</a></li>
                    <li id="btn_forzar_sincronizacion_total" class="text-success font-weight-bold "><a href="javascript:void(0)" class="align-items d-flex"><i class="ph-download-simple"></i> Forzar Sincronización Total</a></li>
                </ul>
            </div>
            <div class="box-tooltip-pt">
                <p>Sincronización</p>
            </div>
        </div>
        <div class="header-single-item  d-flex position-relative tooltip-pt">
            <div class="dropwdown-wrap-pt btn_configurar_punto_venta">
                <div class="title-drop-main">
                    <a href="JavaScript:void(0)">
                        <i class="ph-wrench"></i>
                    </a>
                </div>
            </div>
            
            <div class="box-tooltip-pt">
                <p>Configurar Punto de Venta</p>
            </div>
        </div>
        <div class="header-single-item">
            
            <div class="dropwdown-wrap-pt">
                <div class="title-drop-main">
                    <a href="JavaScript:void(0)">
                        <i class="icon-grid2"></i>
                    </a>
                </div>
                <ul class="dropdown-menu-pt">
                    <li class="d-flex justify-content-between text-icon-drop btn_menu_navbar_enlaces_system" data-enlace="/facturacionv8/dashboard">
                        <p class="drop-icon"><i class="icon-home4"></i></p>
                        <p class="d-flex d-flex-column">
                            <span>Dashboard</span>
                            <small class="small-text color-tertiary">Pantalla Inicial de Todo el Sistema</small>
                        </p>
                    </li>
                    <li class="d-flex justify-content-between text-icon-drop btn_menu_navbar_enlaces_system" data-enlace="/facturacionv8/gestiondecompras">
                        <p class="drop-icon"><i class="icon-bag"></i></p>
                        <p class="d-flex d-flex-column">
                            <span>Compras</span>
                            <small class="small-text color-tertiary">Registro de Documentos de Compras</small>
                        </p>
                    </li>
                    <li class="d-flex justify-content-between text-icon-drop btn_menu_navbar_enlaces_system" data-enlace="/facturacionv8/reportes/reporte_detallado">
                        <p class="drop-icon"><i class="icon-file-stats"></i></p>
                        <p class="d-flex d-flex-column">
                            <span>Reporte de Ventas</span>
                            <small class="small-text color-tertiary">Podrás ver el Reporte detallado de ventas</small>
                        </p>
                    </li>
                </ul>
            </div>
        </div>
        <div class="header-single-item">
            <div class="dropwdown-wrap-pt">
                <div class="profile-user-pt">
                    <a href="JavaScript:void(0)">
                        <span class="letter-profile"><?php echo substr($user['nombre'], 0, 1);?></span>
                        <span class="name-user-profile"><?php echo $user['nombre'].' '.$user['apellido']?></span>
                        <!-- <i class="icon-arrow-down22"></i> -->
                    </a>
                </div>
                <ul class="dropdown-menu-pt">
                    <li style="display:none;" class="d-flex">Facturación electrónica <span class="label bg-default">Desactivada</span></li>
                    <li><a href="/facturacionv8/profile"><i class="icon-user-plus"></i> Mi perfil</a></li>
                    <li><a href="#"><i class="icon-coins"></i>Facturación</a></li>
                    <li><a target="_blank" href="<?php echo $data_empresa['url_soporte']; ?>"><i class="icon-cog5"></i>Soporte</a></li>
                    <li><a href="/facturacionv8/configcompany"><i class="icon-cog5"></i> Configurar empresa</a></li>
                    <li><a href="/facturacionv8/login/logout"><i class="icon-switch2"></i> Cerrar sesión</a></li>
                </ul>
            </div>
            
        </div>
    </div>
</div>