<?php
use Phalcon\Di\Injectable;
class Elements extends Injectable {
    
    public function getSidebarMenu(){

        $controllerName = $this->view->getControllerName();
		$actionName = $this->view->getActionName();
		
        $auth = $this->session->get('authv8');
        $id_contribuyente = '';
        $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $auth['idusuario'])));
        if($usuario) {
            $id_contribuyente = $usuario->id_contribuyente;
        }
        $gestion_usuarios = new GestionuserController;
        $gestion_de_contribuyentes = new GestiondecontribuyentesController;
        
        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
        
        $menu_exclusivo = '';
        if($contribuyente->id_contribuyente == 1) { 
            //Menu que se mostrará solo a los clientes que son directos de facturalaya.com
            $menu_exclusivo = '
            <li class="nav-item nav-item-submenu"> 
                <a href="/facturacionv8/centrodeentrenamiento" target="_blank" class="nav-link active legitRipple">
                    <i class="icon-play"></i>
                    <span> 
                        Video Tutoriales
                    </span>
                </a> 
            </li>
            ';
        }

        $menu_opcional = '';

        if($auth['id_rol'] == 1 || $auth['id_rol'] == 2) {
            $menu_opcional = $menu_opcional.'
            <li class="menu_contiene_items"> 
                <a href="#" class="legitRipple"><i class="fa fa-building"></i>
                    <span>Contribuyentes</span>
                </a> 
                <ul class="nav nav-group-sub" data-submenu-title="Layouts" style="display: none;">
                    <li class="nav-item submenu_item">
                        <a href="/facturacionv8/gestiondecontribuyentes/videotutoriales" class="legitRipple"><i class="icon-clapboard-play" aria-hidden="true"></i> Videos SuperAdmin</a>
                    </li>

                    <li class="nav-item submenu_item">
                        <a href="/facturacionv8/gestiondedocumentos" class="legitRipple"><i class="icon-files-empty" aria-hidden="true"></i> Gestión C.P.E.</a>
                    </li>

                    <li class="nav-item submenu_item"><a href="/facturacionv8/gestiondecontribuyentes" class="legitRipple"><i class="fa fa-list"></i>Listar Contribuyentes</a></li>

                    <li class="nav-item submenu_item">
                        <a href="/facturacionv8/personalizaciondesistema" class="legitRipple"><i class="icon-gallery" aria-hidden="true"></i> Personalizar Sistema</a>
                    </li>
                </ul>
            </li>
            ';
        }

        if($auth['id_rol'] == 1 || $auth['id_rol'] == 2 || $auth['id_rol'] == 3 || $auth['id_rol'] == 5) {
            $menu_opcional = $menu_opcional.$menu_exclusivo.'
            <li class="menu_contiene_items"> 
                    <a href="#" class="legitRipple">
                    <i class="icon-cog5"></i>
                    <span>
                        Administración
                    </span>
                </a> 
                <ul class="nav nav-group-sub" data-submenu-title="Layouts" style="display: none;">
                    <li class="nav-item submenu_item">
                        <a href="/facturacionv8/configcompany" class="legitRipple"><i class="icon-cog5" aria-hidden="true"></i>Configurar Empresa</a>
                    </li>

                    <li class="nav-item submenu_item">
                        <a href="/facturacionv8/profile/index/facturacion" class="legitRipple"><i class="icon-coins" aria-hidden="true"></i>Facturación</a>
                    </li>
                    
                    <li class="nav-item submenu_item">
                        <a href="/facturacionv8/gestionuser" class="legitRipple"><i class="icon-users4" aria-hidden="true"></i>Gestión de Usuarios</a>
                    </li>

                    <li class="nav-item submenu_item">
                        <a href="/facturacionv8/branchoffice/lista_sucursales" class="legitRipple"><i class="fa fa-list" aria-hidden="true"></i>Listar Sucursales</a>
                    </li>

                    <li class="nav-item submenu_item">
                        <a href="/facturacionv8/gestioncuentadebanco" class="legitRipple"><i class="fa fa-bank" aria-hidden="true"></i>Bancos</a>
                    </li>

                    <li class="nav-item submenu_item">
                        <a href="/facturacionv8/gestioncondiciondepago" class="legitRipple"><i class="fa fa-calculator" aria-hidden="true"></i>Condiciones de Pago</a>
                    </li>

                </ul>
            </li>            
            ';
        }
		
        $menu_general = '
        <li class="menu_contiene_items"> 
            <a href="#" class="legitRipple">
                <i class="icon-file-eye"></i>
                <span>Comprobantes de pago</span>
            </a> 
            <ul class="nav nav-group-sub" data-submenu-title="Layouts" style="display: none;">
                <li class="nav-item submenu_item">
                    <a href="/facturacionv8/reportedocumentos" class="legitRipple">
                        <i class="icon-file-eye"></i>
                        <span>
                            Ver Facturas, Boletas, Notas
                        </span>
                    </a>
                </li>
                <li class="nav-item submenu_item">
                    <a href="/facturacionv8/documentoelectronico/index/01/nuevo" class="legitRipple">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        <span>
                            Emitir Factura
                        </span>
                    </a>
                </li>
                <li class="nav-item submenu_item">
                    <a href="/facturacionv8/documentoelectronico/index/03/nuevo" class="legitRipple">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        <span>
                            Emitir Boleta
                        </span>
                    </a>
                </li>
                <li class="nav-item submenu_item">
                    <a href="/facturacionv8/documentoelectronico/index/07/nuevo" class="legitRipple">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                        <span>
                            Emitir Nota Crédito
                        </span>
                    </a>
                </li>
                <li class="nav-item submenu_item">
                    <a href="/facturacionv8/documentoelectronico/index/08/nuevo" class="legitRipple">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                        <span>
                            Emitir Nota Débito
                        </span>
                    </a>
                </li>
                <li class="nav-item-divider"></li>
                <li class="nav-item submenu_item">
                    <a href="/facturacionv8/documentoelectronico/index/77/nuevo" class="legitRipple">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        <span>
                            Emitir Nota de Venta
                        </span>
                    </a>
                </li>
                <li class="nav-item submenu_item">
                    <a href="/facturacionv8/documentoelectronico/index/88/nuevo" class="legitRipple">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        <span>
                            Emitir Cotización
                        </span>
                    </a>
                </li>
                <li class="nav-item-divider"></li>
            </ul>
        </li>';

        if($gestion_usuarios->verificar_permisos($usuario, 'permisos_menus', 'opt_menu_cajachica') != false) {
            $menu_general = $menu_general.'
            <li class="nav-item nav-item-submenu"> 
                <a href="/facturacionv8/cajachica" class="nav-link legitRipple">
                    <i class="icon-calculator2"></i>
                    <span>
                        Caja Chica
                    </span>
                </a> 
            </li>';
        }

        if(!empty($id_contribuyente) && $gestion_de_contribuyentes->get_value_in_option_system($id_contribuyente, 'permitir_acceso_modulo_pos') != 'no') {
            $menu_general = $menu_general.'
            <li class="nav-item nav-item-submenu"> 
                <a href="/facturacionv8/systempos/" class="nav-link legitRipple  text-success">
                    <i class="icon-store2"></i>
                    <span>
                        Punto de Venta <span class="label bg-success">Nuevo</span>
                    </span>
                </a> 
            </li>';
        }

        if(!empty($id_contribuyente) && $gestion_de_contribuyentes->get_value_in_option_system($id_contribuyente, 'permitir_acceso_cuentas_cobrar') != 'no') {
            if($gestion_usuarios->verificar_permisos($usuario, 'permisos_menus', 'opt_menu_cuentasporcobrar') != false) {
                $menu_general = $menu_general.'
                <li class="nav-item nav-item-submenu"> 
                    <a href="/facturacionv8/cuentasporcobrar" class="nav-link legitRipple">
                        <i class="icon-cash3"></i>
                        <span>
                            Cuentas por Cobrar
                        </span>
                    </a> 
                </li>';
            }
        }

        if(!empty($id_contribuyente) && $gestion_de_contribuyentes->get_value_in_option_system($id_contribuyente, 'permitir_acceso_cuentas_pagar') != 'no') {
            if($gestion_usuarios->verificar_permisos($usuario, 'permisos_menus', 'opt_menu_cuentasporcobrar') != false) {
                $menu_general = $menu_general.'
                <li class="nav-item nav-item-submenu"> 
                    <a href="/facturacionv8/cuentasporpagar" class="nav-link legitRipple">
                        <i class="icon-credit-card"></i>
                        <span>
                            Cuentas por Pagar
                        </span>
                    </a> 
                </li>
                ';
            }
        }

        if(!empty($id_contribuyente) && $gestion_de_contribuyentes->get_value_in_option_system($id_contribuyente, 'permitir_acceso_compras') != 'no') {
            if($gestion_usuarios->verificar_permisos($usuario, 'permisos_menus', 'opt_menu_gestioncompras') != false) {
                $menu_general = $menu_general.'
                <li class="nav-item nav-item-submenu"> 
                    <a href="/facturacionv8/gestiondecompras" class="nav-link legitRipple">
                        <i class="icon-bag"></i>
                        <span>
                            Gestión de Compras
                        </span>
                    </a> 
                </li>';
            }
        }

        if($gestion_usuarios->verificar_permisos($usuario, 'permisos_menus', 'opt_menu_proveedores') != false) {
        $menu_general = $menu_general.'
        <li class="nav-item nav-item-submenu"> 
            <a href="/facturacionv8/gestiondeproveedores" class="nav-link legitRipple">
                <i class="icon-store2"></i>
                <span>
                    Gestión de Proveedores
                </span>
            </a> 
        </li>';
        }
    
        if($gestion_usuarios->verificar_permisos($usuario, 'permisos_menus', 'opt_menu_guiaremision') != false) {
        $menu_general = $menu_general.'
        <li class="menu_contiene_items">
            <a href="#" class="legitRipple">
                <i class="icon-truck"></i>
                <span>
                    Guías de Remisión
                </span>
            </a> 
            <ul class="nav nav-group-sub" data-submenu-title="Layouts" style="display: none;">
                <li class="nav-item submenu_item"><a href="/facturacionv8/guiaderemision" class="legitRipple"><i class="fa fa-plus" aria-hidden="true"></i>Crear Guía de Remisión</a></li>
                <li class="nav-item submenu_item"><a href="/facturacionv8/reportedocumentos/index/'.$id_contribuyente.'/09/" class="legitRipple"><i class="fa fa-list" aria-hidden="true"></i>Lista de Guías de Remisión</a></li>
                <li class="nav-item-divider"></li>
            </ul>
        </li>';
        }

        if($gestion_usuarios->verificar_permisos($usuario, 'permisos_menus', 'opt_menu_resumenboletas') != false) {
        $menu_general = $menu_general.'
        <li class="menu_contiene_items">
            <a href="#" class="legitRipple">
                <i class="icon-file-zip"></i>
                <span>
                    Resumen Diario Boletas
                </span>
            </a> 
            <ul class="nav nav-group-sub" data-submenu-title="Layouts" style="display: none;">
                <li class="nav-item submenu_item"><a href="/facturacionv8/resumendeboletas" class="legitRipple"><i class="fa fa-plus" aria-hidden="true"></i>Crear Resúmenes de Boletas</a></li>
                <li class="nav-item submenu_item"><a href="/facturacionv8/resumendeboletas" class="legitRipple"><i class="fa fa-list"></i> Ver Resúmenes de Boletas</a></li>
            </ul>
        </li>';
        }

        if($gestion_usuarios->verificar_permisos($usuario, 'permisos_menus', 'opt_menu_clientes') != false) {
        $menu_general = $menu_general.'
        <li class="menu_contiene_items"> 
                <a href="#" class="legitRipple">
                <i class="icon-users"></i>
                <span>
                    Clientes
                </span>
            </a> 
            <ul class="nav nav-group-sub" data-submenu-title="Layouts" style="display: none;">
                <li class="nav-item submenu_item">
                    <a href="/facturacionv8/client" class="legitRipple"><i class="icon-users"></i>Gestión de Clientes</a>
                </li>
            </ul>
        </li>';
        }

        if($gestion_usuarios->verificar_permisos($usuario, 'permisos_menus', 'opt_menu_inventario') != false) {
        $menu_general = $menu_general.'
        <li class="menu_contiene_items"> 
            <a href="#" class="legitRipple">
                <i class="icon-cart"></i>
                <span>
                    Inventario
                </span>
            </a> 
            <ul class="nav nav-group-sub" data-submenu-title="Layouts" style="display: none;">
                <li class="nav-item submenu_item">
                    <a href="/facturacionv8/reportekardex/kardexdeproducto" class="legitRipple"><i class="icon-stats-bars"></i>Kardex Valorizado</a>
                </li>
                <li class="nav-item submenu_item">
                    <a href="/facturacionv8/category" class="legitRipple"><i class="icon-pyramid"></i>Gestionar Categorías</a>
                </li>
                <li class="nav-item submenu_item">
                    <a href="/facturacionv8/producto/listaproductos" class="legitRipple"><i class="icon-clipboard2"></i>Gestión Productos/Servicios</a>
                </li>
            </ul>
        </li>';
        }

        if($gestion_usuarios->verificar_permisos($usuario, 'permisos_menus', 'opt_menu_top_clientes') == true || $gestion_usuarios->verificar_permisos($usuario, 'permisos_menus', 'opt_menu_top_vendedores') == true) { //si hay uno o más true entonces ingresa
            $menu_general = $menu_general.'
            <li class="menu_contiene_items"> 
                <a href="#" class="legitRipple">
                    <i class="icon-file-stats"></i>
                    <span>
                        Reportes
                    </span>
                </a>'; 
                $menu_general = $menu_general.'
                <ul class="nav nav-group-sub" data-submenu-title="Layouts" style="display: none;">';

                if(!empty($id_contribuyente) && $gestion_de_contribuyentes->get_value_in_option_system($id_contribuyente, 'permitir_acceso_top_clientes') != 'no') {
                    if($gestion_usuarios->verificar_permisos($usuario, 'permisos_menus', 'opt_menu_top_clientes') == true) {
                    $menu_general = $menu_general.'
                        <li class="nav-item submenu_item">
                            <a href="/facturacionv8/reportes/top_clientes" class="legitRipple"><i class="icon-users4"></i>Top Clientes</a>
                        </li>';
                    }
                }

                if(!empty($id_contribuyente) && $gestion_de_contribuyentes->get_value_in_option_system($id_contribuyente, 'permitir_acceso_top_vendedores') != 'no') {
                    if($gestion_usuarios->verificar_permisos($usuario, 'permisos_menus', 'opt_menu_top_vendedores') == true) {
                    $menu_general = $menu_general.'
                        <li class="nav-item submenu_item">
                            <a href="/facturacionv8/reportes/top_vendedores" class="legitRipple"><i class="icon-user-tie"></i>Top Colaboradores</a>
                        </li>';
                    }
                }

            $menu_general = $menu_general.'
                <li class="nav-item submenu_item">
                    <a href="/facturacionv8/reportes/reporte_productosvendidos" class="legitRipple"><i class="icon-stats-bars4"></i>Productos Más Vendidos</a>
                </li>
                <li class="nav-item submenu_item">
                    <a href="/facturacionv8/reportes/consolidado_ventas_producto" class="legitRipple"><i class="icon-file-presentation"></i>Consolidado Productos</a>
                </li>
                '; 
            $menu_general = $menu_general.'
            </ul></li>';
        }

        if(!empty($id_contribuyente) && $gestion_de_contribuyentes->get_value_in_option_system($id_contribuyente, 'permitir_acceso_modulo_contabilidad') != 'no') {
            if($gestion_usuarios->verificar_permisos($usuario, 'permisos_menus', 'opt_menu_contabilidad') != false) {
                $menu_general = $menu_general.'
                <li class="menu_contiene_items"> 
                    <a href="#" class="legitRipple">
                        <i class="fa fa-calculator"></i>
                        <span>
                            Contabilidad
                        </span>
                    </a> 
                    <ul class="nav nav-group-sub" data-submenu-title="Layouts" style="display: none;">
                        <li class="nav-item submenu_item">
                            <a href="/facturacionv8/reportes/libro_eventas" class="legitRipple"><i class="icon-cog2"></i>Libro Elect. Ventas</a>
                        </li>
                        <li class="nav-item submenu_item">
                            <a href="/facturacionv8/reportes/libro_ecompras" class="legitRipple"><i class="icon-cog2"></i>Libro Elect. Compras</a>
                        </li>
                        <li class="nav-item submenu_item">
                            <a href="/facturacionv8/reportes/reporte_detallado" class="legitRipple"><i class="icon-cog2"></i>Reporte Detall. Ventas</a>
                        </li>
                        <li class="nav-item submenu_item">
                            <a href="/facturacionv8/reportes/reporte_detallado_compras" class="legitRipple"><i class="icon-cog2"></i>Reporte Detall. Compras</a>
                        </li>
                    </ul>
                </li>';
            }
        }
        
        if(!empty($id_contribuyente) && $gestion_de_contribuyentes->get_value_in_option_system($id_contribuyente, 'permitir_acceso_modulo_sire') != 'no') {
            if($gestion_usuarios->verificar_permisos($usuario, 'permisos_menus', 'opt_menu_contabilidad') != false) {
                $menu_general = $menu_general.'
                <li class="menu_contiene_items"> 
                    <a href="#" class="legitRipple">
                        <i class="icon-clipboard5"></i>
                        <span>
                            SIRE
                        </span>
                    </a> 
                    <ul class="nav nav-group-sub" data-submenu-title="Layouts" style="display: none;">
                        <li class="nav-item submenu_item">
                            <a href="/facturacionv8/sire/ventas" class="legitRipple"><i class="icon-cog2"></i>SIRE Ventas</a>
                        </li>
                        <li class="nav-item submenu_item">
                            <a href="/facturacionv8/sire/compras" class="legitRipple"><i class="icon-cog2"></i>SIRE Compras</a>
                        </li>
                    </ul>
                </li>';
            }
        }

        if(!empty($id_contribuyente) && $id_contribuyente == 3938) {
            $menu_general = $menu_general.'
                <li class="menu_contiene_items"> 
                    <a href="#" class="legitRipple">
                        <i class="fa fa-calculator"></i>
                        <span>
                            FacturalaYa Contabilidad
                        </span>
                    </a> 
                    <ul class="nav nav-group-sub" data-submenu-title="Layouts" style="display: none;">
                        <li class="nav-item submenu_item">
                            <a href="/facturacionv8/facturalayacontabilidad" class="legitRipple"><i class="icon-cog2"></i>Ingresos</a>
                        </li>
                    </ul>
                </li>
                ';
        }

        $menu_general = $menu_general.'
        <li class="item_individual"> 
            <a href="/facturacionv8/login/logout" class="legitRipple">
                <i class="icon-exit2"></i>
                <span>
                    Cerrar Sesión
                </span>
            </a> 
        </li>
        ';

        echo '
        <li class="item_individual"> 
            <a href="/facturacionv8/dashboard" class="legitRipple">
                <i class="icon-home4"></i>
                <span>
                    Inicio
                </span>
            </a> 
        </li>
        '.$menu_opcional.$menu_general;
    }
    
}
?>