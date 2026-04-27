<?php
class GestionuserController extends ControllerBase
{
	public function indexAction() {
		$this->setTitle('Gestión Usuarios');
        $this->view->setTemplateAfter('template_new');

        $this->assets
        ->addCss($this->baseUri . "public/css/new_style.css");
        $this->assets
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/switchery.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/uniform.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/selects/select2.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/datatables.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/extensions/buttons.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/notifications/sweet_alert.min.js?i=v2")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js")
            ->addJs($this->baseUri . "public/template/assets/js/core/app.js?i=v2")
            ->addJs($this->baseUri . "public/js/general.js?j=".rand()) 
			->addJs($this->baseUri . "public/js/gestionuser.js?i=".rand()); 
    }

    public function insertAction(){
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $codigo = empty($datapost['codigo'])?'':$datapost['codigo'];
            $rol = isset($datapost['rol'])?intval($datapost['rol']) + 0 : 0;
            $nombre = empty($datapost['nombre'])?'':$datapost['nombre'];
            $apellido = empty($datapost['apellido'])?'':$datapost['apellido'];
            $password = empty($datapost['password'])?'':$datapost['password'];
            $email = empty($datapost['email'])?'':$datapost['email'];
            $celular = empty($datapost['celular'])?'':$datapost['celular'];
            $telefono = empty($datapost['telefono'])?'':$datapost['telefono'];
            $id_user = !isset($datapost['idusuario'])?0:intval($datapost['idusuario']) + 0;
            $idsucursal = intval($datapost['idsucursal']) + 0;
            
            $ver_ventas_totales = empty($datapost['ver_ventas_totales'])?'no':'si';
            $modificacion_almacen = empty($datapost['modificacion_almacen'])?'no':'si';
            $ventas_multisucursal = empty($datapost['ventas_multisucursal'])?'no':'si';
            $acceso_mod_compras = empty($datapost['acceso_mod_compras'])?'no':'si';
            $modificacion_multi_almacen = empty($datapost['modificacion_multi_almacen'])?'no':'si';
            $validar_precio_minimo = empty($datapost['restriccion_precio_venta_minimo'])?'no':'si';
            
            $permisos_para_registros = array(
                'opt_registro_ventas' => !isset($datapost['opt_registro_ventas'])?'todas_las_sucursales':(!in_array($datapost['opt_registro_ventas'], array('sucursal_asignada', 'todas_las_sucursales', 'prohibir'))?'todas_las_sucursales':$datapost['opt_registro_ventas']), //sucursal_asignada, todas_las_sucursales, prohibir
                'opt_registro_compras' => !isset($datapost['opt_registro_compras'])?'todas_las_sucursales':(!in_array($datapost['opt_registro_compras'], array('sucursal_asignada', 'todas_las_sucursales', 'prohibir'))?'todas_las_sucursales':$datapost['opt_registro_compras']), //sucursal_asignada, todas_las_sucursales, prohibir
                'opt_registro_productos' => !isset($datapost['opt_registro_productos'])?'todas_las_sucursales':(!in_array($datapost['opt_registro_productos'], array('sucursal_asignada', 'todas_las_sucursales', 'prohibir'))?'todas_las_sucursales':$datapost['opt_registro_productos']), //sucursal_asignada, todas_las_sucursales, prohibir
                'opt_cambio_fechaemision' => !isset($datapost['opt_cambio_fechaemision'])?'permitir':(!in_array($datapost['opt_cambio_fechaemision'], array('permitir', 'prohibir'))?'permitir':$datapost['opt_cambio_fechaemision']), //permitir, prohibir
                'opt_registro_documentos' => array(
                    'boleta' => !isset($datapost['opt_registro_documentos2'])?false:(!in_array('boleta', explode(',', $datapost['opt_registro_documentos2']))?false:true), //true, false
                    'factura' => !isset($datapost['opt_registro_documentos2'])?false:(!in_array('factura', explode(',', $datapost['opt_registro_documentos2']))?false:true), //true, false
                    'nota_venta' => !isset($datapost['opt_registro_documentos2'])?false:(!in_array('nota_venta', explode(',', $datapost['opt_registro_documentos2']))?false:true), //true, false
                    'cotizacion' => !isset($datapost['opt_registro_documentos2'])?false:(!in_array('cotizacion', explode(',', $datapost['opt_registro_documentos2']))?false:true), //true, false
                    'guias_remision' => !isset($datapost['opt_registro_documentos2'])?false:(!in_array('guias_remision', explode(',', $datapost['opt_registro_documentos2']))?false:true), //true, false
                    'notas_credito' => !isset($datapost['opt_registro_documentos2'])?false:(!in_array('notas_credito', explode(',', $datapost['opt_registro_documentos2']))?false:true), //true, false
                    'notas_debito' => !isset($datapost['opt_registro_documentos2'])?false:(!in_array('notas_debito', explode(',', $datapost['opt_registro_documentos2']))?false:true), //true, false

                    'anulacion_boleta' => !isset($datapost['opt_registro_documentos2'])?false:(!in_array('anulacion_boleta', explode(',', $datapost['opt_registro_documentos2']))?false:true), //true, false
                    'anulacion_factura' => !isset($datapost['opt_registro_documentos2'])?false:(!in_array('anulacion_factura', explode(',', $datapost['opt_registro_documentos2']))?false:true), //true, false
                    'anulacion_nota_venta' => !isset($datapost['opt_registro_documentos2'])?false:(!in_array('anulacion_nota_venta', explode(',', $datapost['opt_registro_documentos2']))?false:true), //true, false
                    'anulacion_cotizacion' => !isset($datapost['opt_registro_documentos2'])?false:(!in_array('anulacion_cotizacion', explode(',', $datapost['opt_registro_documentos2']))?false:true), //true, false
                    'anulacion_guias_remision' => !isset($datapost['opt_registro_documentos2'])?false:(!in_array('anulacion_guias_remision', explode(',', $datapost['opt_registro_documentos2']))?false:true), //true, false
                    'anulacion_notas_credito' => !isset($datapost['opt_registro_documentos2'])?false:(!in_array('anulacion_notas_credito', explode(',', $datapost['opt_registro_documentos2']))?false:true), //true, false
                    'anulacion_notas_debito' => !isset($datapost['opt_registro_documentos2'])?false:(!in_array('anulacion_notas_debito', explode(',', $datapost['opt_registro_documentos2']))?false:true), //true, false

                    'editar_cotizacion' => !isset($datapost['opt_registro_documentos2'])?false:(!in_array('editar_cotizacion', explode(',', $datapost['opt_registro_documentos2']))?false:true), //true, false
                ),
                'opt_registro_cuentascobrar'  => !isset($datapost['opt_registro_cuentascobrar'])?'todas_las_sucursales':(!in_array($datapost['opt_registro_cuentascobrar'], array('sucursal_asignada', 'todas_las_sucursales', 'prohibir'))?'todas_las_sucursales':$datapost['opt_registro_cuentascobrar']), //sucursal_asignada, todas_las_sucursales, prohibir
            );
            
            $permisos_para_reportes = array(
                'opt_verreportes_ventas' => !isset($datapost['opt_verreportes_ventas'])?'todas_las_sucursales':(!in_array($datapost['opt_verreportes_ventas'], array('propias', 'sucursal_asignada', 'todas_las_sucursales'))?'todas_las_sucursales':$datapost['opt_verreportes_ventas']), //propias, sucursal_asignada, todas_las_sucursales
                'opt_verreportes_contabilidad' => !isset($datapost['opt_verreportes_contabilidad'])?'permitir':(!in_array($datapost['opt_verreportes_contabilidad'], array('permitir', 'denegar'))?'permitir':$datapost['opt_verreportes_contabilidad']), //permitir, denegar
                'opt_verreportes_reportes' => array(
                    'detalle_ventas' => !isset($datapost['opt_verreportes_reportes2'])?true:(!in_array('detalle_ventas', explode(',', $datapost['opt_verreportes_reportes2']))?false:true), //true, false
                    'top_estadisticas_dashboard' => !isset($datapost['opt_verreportes_reportes2'])?true:(!in_array('top_estadisticas_dashboard', explode(',', $datapost['opt_verreportes_reportes2']))?false:true), //true, false
                    'productos_mas_vendidos' => !isset($datapost['opt_verreportes_reportes2'])?true:(!in_array('productos_mas_vendidos', explode(',', $datapost['opt_verreportes_reportes2']))?false:true), //true, false
                    'kardex' => !isset($datapost['opt_verreportes_reportes2'])?true:(!in_array('kardex', explode(',', $datapost['opt_verreportes_reportes2']))?false:true), //true, false
                    'top_clientes' => !isset($datapost['opt_verreportes_reportes2'])?true:(!in_array('top_clientes', explode(',', $datapost['opt_verreportes_reportes2']))?false:true), //true, false
                    'top_vendedores' => !isset($datapost['opt_verreportes_reportes2'])?true:(!in_array('top_vendedores', explode(',', $datapost['opt_verreportes_reportes2']))?false:true), //true, false
                ),
                'opt_verreportes_cuentascobrar' => !isset($datapost['opt_verreportes_cuentascobrar'])?'todas_las_sucursales':(!in_array($datapost['opt_verreportes_cuentascobrar'], array('propias', 'sucursal_asignada', 'todas_las_sucursales'))?'todas_las_sucursales':$datapost['opt_verreportes_cuentascobrar']), //propias, sucursal_asignada, todas_las_sucursales
            );
            
            $permisos_menus = array(
                'opt_menu_cajachica' => !isset($datapost['opt_menu_cajachica'])?false:(($datapost['opt_menu_cajachica'] == 'on')?true:false),
                'opt_menu_cuentasporcobrar' => !isset($datapost['opt_menu_cuentasporcobrar'])?false:(($datapost['opt_menu_cuentasporcobrar'] == 'on')?true:false),
                'opt_menu_gestioncompras' => !isset($datapost['opt_menu_gestioncompras'])?false:(($datapost['opt_menu_gestioncompras'] == 'on')?true:false),
                'opt_menu_proveedores' => !isset($datapost['opt_menu_proveedores'])?false:(($datapost['opt_menu_proveedores'] == 'on')?true:false),
                'opt_menu_guiaremision' => !isset($datapost['opt_menu_guiaremision'])?false:(($datapost['opt_menu_guiaremision'] == 'on')?true:false),
                'opt_menu_resumenboletas' => !isset($datapost['opt_menu_resumenboletas'])?false:(($datapost['opt_menu_resumenboletas'] == 'on')?true:false),
                'opt_menu_clientes' => !isset($datapost['opt_menu_clientes'])?false:(($datapost['opt_menu_clientes'] == 'on')?true:false),
                'opt_menu_inventario' => !isset($datapost['opt_menu_inventario'])?false:(($datapost['opt_menu_inventario'] == 'on')?true:false),
                'opt_menu_contabilidad' => !isset($datapost['opt_menu_contabilidad'])?false:(($datapost['opt_menu_contabilidad'] == 'on')?true:false),
                'opt_menu_top_clientes' => !isset($datapost['opt_menu_top_clientes'])?false:(($datapost['opt_menu_top_clientes'] == 'on')?true:false),
                'opt_menu_top_vendedores' => !isset($datapost['opt_menu_top_vendedores'])?false:(($datapost['opt_menu_top_vendedores'] == 'on')?true:false)
            );

            $permisos_otros = array(
                'opt_otros_costocompra' => !isset($datapost['opt_otros_costocompra'])?false:(($datapost['opt_otros_costocompra'] == 'on')?true:false),
            );

            $permisos = array(
                'permisos_para_registros' => $permisos_para_registros,
                'permisos_para_reportes' => $permisos_para_reportes,
                'permisos_menus' => $permisos_menus,
                'permisos_otros' => $permisos_otros
            );

            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            
            if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Código';
                $resp['mensaje'] = 'Lo sentimos! Debes generar un código, no se permiten campos vacíos.';
                echo json_encode($resp);
                exit();
            }
            
            if($usuario->id_contribuyente == 2043) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Usuario';
                $resp['mensaje'] = 'Esta es una cuenta demo para todos nuestros amigos, Usted no debería cambiar los datos de esta cuenta!';
                echo json_encode($resp);
                exit();
            }

            $herramientas = new HerramientasController;
            $resp_permisos = $herramientas->verificar_permisos_usuario($usuario->idusuario);
            if($resp_permisos['respuesta'] == 'error') {
                echo json_encode($resp_permisos);
                exit();
            }

            if($rol <= 0) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Código';
                $resp['mensaje'] = 'Selecciona el Rol para el Usuario';
                echo json_encode($resp);
                exit();
            }

            //Verificando campos 
            if(empty($codigo)){
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Lo sentimos! Debes generar un código, no se permiten campos vacíos.';
                echo json_encode($msj);
                exit();
            }
            if(empty($rol)){
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Lo sentimos! Debes seleccionar el Rol del usuario, no se permiten campos vacíos.';
                echo json_encode($msj);
                exit();
            }
            
            if(!in_array($rol, array(3, 4))) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'El Rol Seleccionado no es Válido';
                echo json_encode($msj);
                exit();
            }
            if(empty($nombre)){
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Lo sentimos! Debes escribir el Nombre del usuario,  no se permiten campos vacíos.';
                echo json_encode($msj);
                exit();
            }
            if(empty($apellido)){
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Lo sentimos! Debes escribir el Apellido del usuario,  no se permiten campos vacíos.';
                echo json_encode($msj);
                exit();
            }
            if(empty($email)){
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Lo sentimos! Debes escribir un E-mail/Correo Eléctronico, no se permiten campos vacíos.';
                echo json_encode($msj);
                exit();
            }

            $sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $idsucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
            if(!$sucursal) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Lo sentimos! La Sucursal Seleccionada es Inválida!';
                echo json_encode($msj);
                exit();
            }

            $this->db->begin();

            $new_user = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente:", 'bind' => array('idusuario' => $id_user, 'id_contribuyente' => $usuario->id_contribuyente)));
            if(!$new_user) {
                $usuario_save = Usuario::findFirst(array("email = :email:", 'bind' => array('email' => $email)));
                if($usuario_save) {
                    $this->db->rollback();
                    $msj['respuesta'] = 'error';
                    $msj['titulo'] = 'Error';
                    $msj['mensaje'] = 'El email ingresado ya se utilizó con otra cuenta!';
                    echo json_encode($msj);
                    exit();
                }

                $new_user = new Usuario();
                $new_user->fecha_registro = date('Y-m-d H:i:s');
                $new_user->id_contribuyente = $usuario->id_contribuyente;
                $new_user->id_rol = $rol;

                if(empty($password)){
                    $this->db->rollback();
                    $msj['respuesta'] = 'error';
                    $msj['titulo'] = 'Error';
                    $msj['mensaje'] = 'Lo sentimos! Debes escribir la contraseña del usuario,  no se permiten campos vacíos.';
                    echo json_encode($msj);
                    exit();
                }
            } else {
                if($new_user->id_rol == 3 || $new_user->id_rol == 5) {
                    $this->db->rollback();
                    $msj['respuesta'] = 'error';
                    $msj['titulo'] = 'Error';
                    $msj['mensaje'] = 'El Usuario es un Usuario Administrador, no se permite editar! Se debe ingresar con la cuenta del usuario administrador y desde la sección de perfil se debe cambiar los datos!';
                    echo json_encode($msj);
                    exit();
                }

                if($new_user->email != $email) {
                    $usuario_save = Usuario::findFirst(array("email = :email:", 'bind' => array('email' => $email)));
                    if($usuario_save) {
                        $this->db->rollback();
                        $msj['respuesta'] = 'error';
                        $msj['titulo'] = 'Error';
                        $msj['mensaje'] = 'El email ingresado ya se utilizó con otra cuenta!';
                        echo json_encode($msj);
                        exit();
                    }
                }

                if($new_user->id_rol != 5) {
                    $new_user->id_rol = $rol;
                }
            }

            if($new_user->id_rol == 1 || $new_user->id_rol == 2 || $new_user->id_rol == 3) {
                $new_user->ver_ventas_totales = 'si';
                $new_user->modificacion_almacen = 'si';
                $new_user->ventas_multisucursal = 'si';
                $new_user->acceso_mod_compras = 'si';
                $new_user->modificacion_multi_almacen = 'si';
                $new_user->validar_precio_minimo = 'no';
            } else {
                $new_user->ver_ventas_totales = $ver_ventas_totales;
                $new_user->modificacion_almacen = $modificacion_almacen;
                $new_user->ventas_multisucursal = $ventas_multisucursal;
                $new_user->acceso_mod_compras = $acceso_mod_compras;
                $new_user->modificacion_multi_almacen = $modificacion_multi_almacen;
                $new_user->validar_precio_minimo = $validar_precio_minimo;
            }
            
            $new_user->codigo = $codigo;
            $new_user->nombre = $nombre;
            $new_user->apellido = $apellido;
            if(!empty($celular)) { $new_user->celular = $celular; }
            if(!empty($telefono)) { $new_user->telefono = $telefono; }
            $new_user->email = $email;
            $new_user->idsucursal = $idsucursal;
            $new_user->permisos = json_encode($permisos);
            
            if(!empty($password)) { $new_user->password = $password; }
            
            try {
                if(!$new_user->save()) {
                    $msg = '';
                    foreach ($new_user->getMessages() as $message) {
                        $msg = $msg.$message."</br>\n";
                    }
                    $this->db->rollback();
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = $msg;
                    echo json_encode($resp);
                    exit();
                }
            } catch (Exception $e) {
                $this->saveLogger($e);
                $this->db->rollback();
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = $e->getMessage();
                echo json_encode($resp);
                exit();
            }

            //permisos agregados en la tabla usuario_opciones
            $modificar_precio_en_pantalla_venta = !isset($datapost['modificar_precio_en_pantalla_venta'])?'no':$datapost['modificar_precio_en_pantalla_venta'];
            $modificar_precio_en_pantalla_venta = $modificar_precio_en_pantalla_venta == 'on'?'si':'no';

            $venta_debajo_precio_minimo = !isset($datapost['venta_debajo_precio_minimo'])?'no':$datapost['venta_debajo_precio_minimo'];
            $venta_debajo_precio_minimo = $venta_debajo_precio_minimo == 'on'?'si':'no';

            $resp_opcion_usuario = $this->set_usuario_opcion($new_user->id_contribuyente, $new_user->idusuario, 'modificar_precio_en_pantalla_venta', $modificar_precio_en_pantalla_venta);
            if($resp_opcion_usuario['respuesta'] == 'error') {
                $this->db->rollback();
                echo json_encode($resp_opcion_usuario);
                exit();
            }

            $resp_opcion_usuario = $this->set_usuario_opcion($new_user->id_contribuyente, $new_user->idusuario, 'venta_debajo_precio_minimo', $venta_debajo_precio_minimo);
            if($resp_opcion_usuario['respuesta'] == 'error') {
                $this->db->rollback();
                echo json_encode($resp_opcion_usuario);
                exit();
            }
            
            $ocultar_opciones_avanzadas_dashboard = !isset($datapost['ocultar_opciones_avanzadas_dashboard'])?'si':$datapost['ocultar_opciones_avanzadas_dashboard'];
            $ocultar_opciones_avanzadas_dashboard = $ocultar_opciones_avanzadas_dashboard == 'on'?'si':'no';
            $resp_opcion_usuario = $this->set_usuario_opcion($new_user->id_contribuyente, $new_user->idusuario, 'ocultar_opciones_avanzadas_dashboard', $ocultar_opciones_avanzadas_dashboard);
            if($resp_opcion_usuario['respuesta'] == 'error') {
                $this->db->rollback();
                echo json_encode($resp_opcion_usuario);
                exit();
            }

            $opt_rango_mostrar_ventas = !isset($datapost['opt_rango_mostrar_ventas'])?'ultimos_30_dias':$datapost['opt_rango_mostrar_ventas'];
            if(!in_array($opt_rango_mostrar_ventas, array('ultimos_30_dias', 'mes_actual', 'dia_actual'))) {
                $opt_rango_mostrar_ventas = 'ultimos_30_dias';
            }
            $resp_opcion_usuario = $this->set_usuario_opcion($new_user->id_contribuyente, $new_user->idusuario, 'opt_rango_mostrar_ventas', $opt_rango_mostrar_ventas);
            if($resp_opcion_usuario['respuesta'] == 'error') {
                $this->db->rollback();
                echo json_encode($resp_opcion_usuario);
                exit();
            }
            
            $this->db->commit();
            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Correcto';
            $resp['mensaje'] = 'Los datos del usuario se guardaron correctamente!';
            $resp['idcategoria'] = $new_user->idusuario;
            $resp['usuario'] = $new_user;
            echo json_encode($resp);
            exit();
        }
    }

    public function getListaUsuariosAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error en Código';
                $msj['mensaje'] = 'Lo sentimos! Debes generar un código, no se permiten campos vacíos.';
                echo json_encode($msj);
                exit();
            }

            if($usuario->id_rol == 4) {
                $resp['respuesta'] = 'ok';
                $resp['id_rol'] = $usuario->id_rol;
                $resp['lista'] = array();
                echo json_encode($resp);
                exit();
            }

            $total_registros = count(Usuario::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente))));

            $columnas = array(
                0 => 'idusuario',
                1 => 'id_contribuyente',
                2 => 'codigo',
                3 => 'id_rol',
                4 => 'nombre',
                5 => 'apellido',
                6 => 'celular',
                7 => 'telefono',
                8 => 'email',
                9 => 'fecha_registro',
                10 => 'estado'
            );

            $query = "SELECT * FROM usuario WHERE id_contribuyente = :id_contribuyente ";

            if(!empty($datapost['search']['value'])) {
                $query = $query." AND (codigo like :termino or email like :termino or nombre like :termino or apellido like :termino or estado like :termino) ";
            }

            $total_filas_filtradas = $this->get_num_rows_filtered_usuarios($query, $usuario->id_contribuyente, $datapost['search']['value']);

            $query = $query." ORDER BY ".$columnas[$datapost['order'][0]['column']]." ".$datapost['order'][0]['dir']." LIMIT ".intval($datapost['start']).",".intval($datapost['length'])."";

            try {
                $sentencia = $this->db->prepare($query);
                $sentencia->bindParam(':id_contribuyente', $usuario->id_contribuyente, PDO::PARAM_INT);
                if(!empty($datapost['search']['value'])) {
                    $termino_busqueda = '%'.$datapost['search']['value'].'%';
                    $sentencia->bindParam(':termino', $termino_busqueda, PDO::PARAM_STR);
                }
                $sentencia->execute();
            } catch (Exception $e) {
                $this->saveLogger($e);
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Código';
                $resp['mensaje'] = $e->getMessage();
                echo json_encode($resp);
                exit();
            }
            
            $array_lista = array();
            $n = 0;
            while($fila = $sentencia->fetch()) {
                $item = (object)$fila;

                if($item->estado == 'activo') {
                    $estado = '<span class="label label-success">Activo</span>';
                } else {
                    $estado = '<span class="label label-danger">Inactivo</span>';
                }

                $rol = RolUsuario::findFirst(array("id_rol = :id_rol:", 'bind' => array('id_rol' => $item->id_rol)));
                
                if($item->id_rol == 1) {
                    $rol_html = '<span class="label label-primary">Admin General</span>';
                    $rol = 'SuperAdmin';
                } else if($item->id_rol == 2) {
                    $rol_html = '<span class="label label-primary">Soporte General</span>';
                    $rol = 'SuperSoporte';
                } else if($item->id_rol == 3) {
                    $rol_html = '<span class="label label-primary">Administrador</span>';
                    $rol = 'Admin';
                } else if($item->id_rol == 4) {
                    $rol_html = '<span class="label label-primary">Vendedor</span>';
                    $rol = 'Colaborador';
                } else if($item->id_rol == 5) {
                    $rol_html = '<span class="label label-primary">Socio Reseller</span>';
                    $rol = 'Socio Estrat.';
                } else {
                    $rol_html = '<span class="label label-primary">Desconocido</span>';
                    $rol = 'Usuario';
                }

                $datos_acceso = '
                <div class="media-left media-middle">
                    <a href="javascript:void(0)"><img src="'.$item->url_image.'" class="img-circle img-xs" alt=""></a>
                </div>

                <div class="media-body">
                    <a href="#" class="display-inline-block text-default">'.$item->nombre.' '.$item->apellido.'</a>
                    <div class="text-muted text-size-small"><span class="status-mark border-danger position-left"></span> '.$rol.': '.$item->email.'</div>
                </div>
                ';
                
                $sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $item->idsucursal, 'id_contribuyente' => $item->id_contribuyente)));
                if(!$sucursal) {
                    $sucursal_nombre = '-';
                } else {
                    $sucursal_nombre = $sucursal->nombre.' (ID: '.$sucursal->idsucursal.')';
                }

                
                
                $opciones = '<ul class="icons-list text-center">
                                <li class="dropdown">
                                    <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li><a onclick="editar_usuario('.$item->idusuario.')" data-idusuario="'.$item->idusuario.'" href="javascript:void(0)"><i class="icon-pencil4"></i> Editar</a></li>
                                        <li><a onclick="eliminar_usuario('.$item->idusuario.')" data-idusuario="'.$item->idusuario.'" href="javascript:void(0)"><i class="icon-user-cancel"></i> Eliminar</a></li>
                                    </ul>
                                </li>
                            </ul>';
                
                $array_lista[] = array(
                    'idusuario'             => $item->idusuario,
                    'codigo'                => $item->codigo,
                    'rol'                   => $rol_html,
                    'usuario'               => $datos_acceso,
                    'celular'               => $item->celular,
                    'telefono'              => $item->telefono,
                    'email'                 => $item->email,
                    'fecha_registro'        => date("d-m-Y", strtotime($item->fecha_registro)), 
                    'estado'                => $estado,
                    'opciones'              => $opciones
                );
            }

            $json_data = array(
                "draw"            => intval($datapost['draw']),
                "recordsTotal"    => intval($total_registros),  //Número total de registros que cumplen con los criterios iniciales
                "recordsFiltered" => $total_filas_filtradas, //Total registros filtrados
                "data"            => $array_lista,   // total data array
                "numero_n"        => $n
            );

            echo json_encode($json_data);
            exit();
        }
    }

    public function get_num_rows_filtered_usuarios($query, $id_contribuyente, $termino_busqueda) {
        try {
            $sentencia = $this->db->prepare($query);
            $sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
            if(!empty($termino_busqueda)) {
                $termino_busqueda = '%'.$termino_busqueda.'%';
                $sentencia->bindParam(':termino', $termino_busqueda, PDO::PARAM_STR);
            }
            $sentencia->execute();
        } catch (Exception $e) {
            $this->saveLogger($e);
            return 0;
        }

        $n = 0;
        while($fila = $sentencia->fetch()) {
            $n++;
        }

        return $n;
    }

    public function eliminarUsuarioAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error en Código';
                $msj['mensaje'] = 'Lo sentimos! Debes generar un código, no se permiten campos vacíos.';
                echo json_encode($msj);
                exit();
            }

            $herramientas = new HerramientasController;
            $resp_permisos = $herramientas->verificar_permisos_usuario($usuario->idusuario);
            if($resp_permisos['respuesta'] == 'error') {
                echo json_encode($resp_permisos);
                exit();
            }

            $iduser = !isset($datapost['idusuario'])?0:intval($datapost['idusuario']) + 0;
            $user = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente:", 'bind' => array('idusuario' => $iduser, 'id_contribuyente' => $usuario->id_contribuyente)));
            if(!$user) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'El Usuario No Existe';
                echo json_encode($msj);
                exit();
            }

            if($user->id_rol == 1 || $user->id_rol == 2 || $user->id_rol == 3 || $user->id_rol == 5) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'El Usuario es un Usuario Administrador, no se permite eliminar o desactivar!';
                echo json_encode($msj);
                exit();
            }
            
            $user->estado = 'inactivo';

            try {
                if(!$user->save()) {
                    $msg = '';
                    foreach ($user->getMessages() as $message) {
                        $msg = $msg.$message."</br>\n";
                    }
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = $msg;
                    echo json_encode($resp);
                    exit();
                }
            } catch (Exception $e) {
                $this->saveLogger($e);
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = $e->getMessage();
                echo json_encode($resp);
                exit();
            }

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Correcto';
            $resp['mensaje'] = 'Se desactivó correctamente al usuario: '.$user->nombre.' '.$user->apellido;
            echo json_encode($resp);
            exit();
        }
    }

    public function getDataUsuarioAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error en Código';
                $msj['mensaje'] = 'Lo sentimos! Debes generar un código, no se permiten campos vacíos.';
                echo json_encode($msj);
                exit();
            }

            $herramientas = new HerramientasController;
            $resp_permisos = $herramientas->verificar_permisos_usuario($usuario->idusuario);
            if($resp_permisos['respuesta'] == 'error') {
                echo json_encode($resp_permisos);
                exit();
            }

            $iduser = !isset($datapost['idusuario'])?0:intval($datapost['idusuario']) + 0;
            $user = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente:", 'bind' => array('idusuario' => $iduser, 'id_contribuyente' => $usuario->id_contribuyente)));
            if(!$user) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El Usuario No Existe';
                echo json_encode($resp);
                exit();
            }

            $resp['respuesta'] = 'ok';
            $resp['usuario'] = $user;
            $resp['usuario_opcion'] = array(
                'modificar_precio_en_pantalla_venta' => $this->get_usuario_opcion($user->id_contribuyente, $user->idusuario, 'modificar_precio_en_pantalla_venta'),
                'venta_debajo_precio_minimo' => $this->get_usuario_opcion($user->id_contribuyente, $user->idusuario, 'venta_debajo_precio_minimo'),
                'ocultar_opciones_avanzadas_dashboard' => $this->get_usuario_opcion($user->id_contribuyente, $user->idusuario, 'ocultar_opciones_avanzadas_dashboard'),
                'opt_rango_mostrar_ventas' => $this->get_usuario_opcion($user->id_contribuyente, $user->idusuario, 'opt_rango_mostrar_ventas')
            );
            echo json_encode($resp);
            exit();
        }
    }

    public function verificar_permisos($usuario, $tipo, $opcion, $sub_opcion = null) {
        if(empty($usuario->permisos)) {
            return "acceso_total";
        }
        
        $permisos = json_decode($usuario->permisos, true);
        if($permisos === false || is_null($permisos)){
            return "acceso_denegado1";
        }

        if(!isset($permisos[$tipo])) {
            //básicamente aquí las opciones por defecto.
            if($tipo == 'permisos_otros' && $opcion == 'opt_otros_costocompra') {
                return true;
            }

            return "acceso_denegado";
        }

        if(!isset($permisos[$tipo][$opcion])) {
            if($tipo == 'permisos_para_registros') {
                if($opcion == 'opt_cambio_fechaemision') {
                    return 'permitir';
                }

                if($opcion == 'opt_registro_cuentascobrar') {
                    return 'todas_las_sucursales';
                }
            }

            if($tipo == 'permisos_para_reportes') {
                if($opcion == 'opt_verreportes_cuentascobrar') {
                    return 'todas_las_sucursales';
                }
            }

            if($tipo == 'permisos_otros' && $opcion == 'opt_otros_costocompra') {
                if($opcion == 'opt_otros_costocompra') {
                    return true;
                }
            }

            return 'acceso_denegado';
        }

        if(!is_array($permisos[$tipo][$opcion])) {
            return $permisos[$tipo][$opcion];
        } else {
            if(is_array($permisos[$tipo][$opcion]) && empty($sub_opcion)) {
                return 'existe';
            }
        }

        if(!empty($sub_opcion)) {
            $array_sub_opcion = $permisos[$tipo][$opcion];
            if(!isset($array_sub_opcion[$sub_opcion])) {

                if($sub_opcion == 'anulacion_boleta' || $sub_opcion == 'anulacion_factura' || $sub_opcion == 'anulacion_nota_venta' || $sub_opcion == 'anulacion_cotizacion' || $sub_opcion == 'anulacion_guias_remision' || $sub_opcion == 'anulacion_notas_credito' || $sub_opcion == 'anulacion_notas_debito') {
                    return true;
                }

                return false;
            }

            if($sub_opcion == 'anulacion_boleta' || $sub_opcion == 'anulacion_factura' || $sub_opcion == 'anulacion_nota_venta' || $sub_opcion == 'anulacion_cotizacion' || $sub_opcion == 'anulacion_guias_remision' || $sub_opcion == 'anulacion_notas_credito' || $sub_opcion == 'anulacion_notas_debito') {
                if($usuario->id_rol == 1 || $usuario->id_rol == 2 || $usuario->id_rol == 3 || $usuario->id_rol == 5) {
                    return true;
                }
            }
            
            return $array_sub_opcion[$sub_opcion];
        }
        
        return false;        
    }
}
?>