<?php
class SystemposController extends ControllerBase
{
	public function indexAction() {
        $resp_manifest = $this->getManifestAction('/public/js/systempos/manifest_systempos.json');
        if($resp_manifest['respuesta'] == 'error') {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error en Archivo';
            $resp['mensaje'] = 'No se encontró el archivo de manifiesto de la aplicación!!';
            echo json_encode($resp);
            exit();
        }
        $manifest = $resp_manifest['manifest'];

        $auth = $this->session->get('authv8');
        $idusuario = intval($auth['idusuario']);
        $usuario = Usuario::findFirst("idusuario = ".$idusuario);
        if(!$usuario) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error en Usuario';
            $resp['mensaje'] = 'Lo sentimos! Es posible que se haya cerrado la sessión, <a style="color: #1c81d1;" href="/session" target="_blank">haz click aquí para ingresar de nuevo</a>, luego de iniciar sessión, puedes regresar a esta pantalla y recargar la página y continuar...';
            echo json_encode($resp);
            exit();
        }

        $contribuyente = Contribuyente::findFirst("id_contribuyente = ".$usuario->id_contribuyente);

        $validacion = $this->validacion($contribuyente, $usuario);
        if (!$validacion['paso_validacion']) {
            // Redirigir a validacionAction() pasando los detalles necesarios
            $this->dispatcher->forward([
                'controller' => 'systempos',
                'action'     => 'validacion',
                'params'     => ['validacion' => $validacion],
            ]);
            return;
        }

		$this->setTitle('Punto de venta');
        $this->view->setTemplateAfter('vacio');

        $this->assets
        ->addCss($this->baseUri . "public/extras/flexdatalist/jquery.flexdatalist.min.css")
        ->addCss($this->baseUri . "public/templatev4/assets/icons/phosphor/styles.min.css")
        ->addCss($this->baseUri . "public/css/new_style.css")
        ->addCss($this->baseUri . $manifest['systempos/css_minified']);

        $this->assets
        ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/switchery.min.js?i=v2")
        ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/uniform.min.js?i=v2")
        ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/selects/select2.min.js?i=v2")
        ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/notifications/sweet_alert.min.js?i=v2")
        ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/switch.min.js?i=v2")
        ->addJs($this->baseUri . "public/js/general.js?j=".rand()) 
        ->addJs($this->baseUri . "public/js/apisunat.js?i=v3")
        
        ->addJs($this->baseUri . $manifest['systempos/js_minified_combined']);
        

        $anio_actual =  date("Y");
        $icbper = SunatIcbper::findFirst(array("anio = :anio:", 'bind' => array('anio' => $anio_actual)));
        if(!$icbper) {
            $impuesto_icbper = '0.5';
        } else {
            $impuesto_icbper = $icbper->monto;
		}
        
        $tipo_identidad = SunatTipodocidentidad::find();
        $this->view->tipo_identidad = $tipo_identidad;
        $this->view->lista_sucursales = $this->getTotalProductosPorSucursal($usuario->id_contribuyente);
        $this->view->idusuario = $idusuario;
        $this->view->id_contribuyente = $usuario->id_contribuyente;
        $this->view->num_decimales = $contribuyente->num_decimales;
        $this->view->id_sucursal_asignada = $validacion['idsucursal_evaluada'];
        $this->view->data_tipo_cambio = $this->getTipoCambio();
        $this->view->impuesto_icbper = $impuesto_icbper;
    }

    //funcion que retorna un array {tipo_cambio_venta: 3.5, tipo_cambio_compra: 3.4, fecha_consulta: '2021-09-01'}
    public function getTipoCambio($date = '') {
        // Si no se proporciona una fecha, se usa la fecha actual
        if ($date == '') {
            $date = date('Y-m-d');
        }
    
        $maxAttempts = 10;
        $attempt = 0;
        $tipo_cambio = null;
        
        // Bucle para buscar el tipo de cambio de la fecha o fechas anteriores
        while ($attempt < $maxAttempts) {
            $tipo_cambio = SunatTipodecambio::findFirst([
                'conditions' => 'fecha = :fecha:',
                'bind' => ['fecha' => $date]
            ]);
    
            if ($tipo_cambio) {
                // Si se encuentra el tipo de cambio, se devuelve el valor
                return [
                    'tipo_cambio_venta' => $tipo_cambio->venta,
                    'tipo_cambio_compra' => $tipo_cambio->compra,
                    'fecha_consulta' => $date
                ];
            }
    
            // Si no se encontró, retrocede un día y aumenta el intento
            $date = date('Y-m-d', strtotime($date . ' -1 day'));
            $attempt++;
        }
    
        // Si después de 10 intentos no encuentra, busca el último valor registrado
        $tipo_cambio = SunatTipodecambio::findFirst([
            'order' => 'fecha DESC'
        ]);
    
        if ($tipo_cambio) {
            // Si se encuentra el último valor registrado, se devuelve
            return [
                'tipo_cambio_venta' => $tipo_cambio->venta,
                'tipo_cambio_compra' => $tipo_cambio->compra,
                'fecha_consulta' => $tipo_cambio->fecha
            ];
        }
    
        // Si no hay ningún tipo de cambio en la base de datos, se devuelven valores predeterminados
        return [
            'tipo_cambio_venta' => 3.0,
            'tipo_cambio_compra' => 3.0,
            'fecha_consulta' => $date
        ];
    }

    public function validacionAction() {
        $this->setTitle('Validación de Acceso al POS');
        $this->view->setTemplateAfter('vacio');

        $auth = $this->session->get('authv8');
        $idusuario = intval($auth['idusuario']);
        $usuario = Usuario::findFirst("idusuario = ".$idusuario);
        if(!$usuario) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error en Usuario';
            $resp['mensaje'] = 'Lo sentimos! Es posible que se haya cerrado la sessión, <a style="color: #1c81d1;" href="/session" target="_blank">haz click aquí para ingresar de nuevo</a>, luego de iniciar sessión, puedes regresar a esta pantalla y recargar la página y continuar...';
            echo json_encode($resp);
            exit();
        }

        $contribuyente = Contribuyente::findFirst("id_contribuyente = ".$usuario->id_contribuyente);

        $validacion = $this->dispatcher->getParam('validacion');
        $this->view->validacion = $validacion;
        $this->view->esAdministrador = $validacion['el_usuario_es_administrador'];
    }

    public function validacion($contribuyente, $usuario) {

        $gestion_de_contribuyentes = new GestiondecontribuyentesController();
        $permiso_pos = $gestion_de_contribuyentes->get_value_in_option_system($usuario->id_contribuyente, 'permitir_acceso_modulo_pos');

        $result_validacion = [
            'tiene_sucursales_creadas'                => false,
            'tiene_productos_creados'                 => false,
            'el_usuario_es_administrador'             => false,
            'el_usuario_tiene_sucursal_asignada_activa' => false,
            'el_usuario_tiene_permisos_para_usar_pos' => ($permiso_pos != 'no') ? true : false,
            'idsucursal_evaluada'                     => 0,
            'paso_validacion'                         => false,
        ];

        // Verificar si la empresa tiene sucursales creadas y activas
        $sucursales_activas = Sucursal::find([
            'conditions' => 'id_contribuyente = :id_contribuyente: AND estado = "activo"',
            'bind'       => [
                'id_contribuyente' => $contribuyente->id_contribuyente,
            ],
        ]);

        if (count($sucursales_activas) > 0) {
            $result_validacion['tiene_sucursales_creadas'] = true;
        }

        // Verificar si el usuario es administrador
        $roles_administradores = [1, 2, 3, 5];
        if (in_array($usuario->id_rol, $roles_administradores)) {
            $result_validacion['el_usuario_es_administrador'] = true;
        }

        if ($result_validacion['el_usuario_es_administrador']) {
            // Si es administrador, usamos la primera sucursal activa para la validación
            if ($result_validacion['tiene_sucursales_creadas']) {
                $sucursal = $sucursales_activas[0]; // Tomamos la primera sucursal activa
                $result_validacion['idsucursal_evaluada'] = $sucursal->idsucursal;

                // Verificar si la sucursal tiene productos activos
                $total_productos = Producto::count([
                    'conditions' => 'id_contribuyente = :id_contribuyente: AND idsucursal = :idsucursal: AND estado = "activo"',
                    'bind'       => [
                        'id_contribuyente' => $contribuyente->id_contribuyente,
                        'idsucursal'       => $sucursal->idsucursal,
                    ],
                ]);

                if ($total_productos > 0) {
                    $result_validacion['tiene_productos_creados'] = true;
                }
            }
        } else {
            // Si es colaborador, verificamos su sucursal asignada
            if ($usuario->idsucursal > 0) {
                $sucursal = Sucursal::findFirst([
                    'conditions' => 'id_contribuyente = :id_contribuyente: AND idsucursal = :idsucursal: AND estado = "activo"',
                    'bind'       => [
                        'id_contribuyente' => $contribuyente->id_contribuyente,
                        'idsucursal'       => $usuario->idsucursal,
                    ],
                ]);

                if ($sucursal) {
                    $result_validacion['el_usuario_tiene_sucursal_asignada_activa'] = true;
                    $result_validacion['idsucursal_evaluada'] = $sucursal->idsucursal;

                    // Verificar si la sucursal tiene productos activos
                    $total_productos = Producto::count([
                        'conditions' => 'id_contribuyente = :id_contribuyente: AND idsucursal = :idsucursal: AND estado = "activo"',
                        'bind'       => [
                            'id_contribuyente' => $contribuyente->id_contribuyente,
                            'idsucursal'       => $sucursal->idsucursal,
                        ],
                    ]);

                    if ($total_productos > 0) {
                        $result_validacion['tiene_productos_creados'] = true;
                    }
                }
            }
        }

        // Verificar si se cumplen las condiciones para pasar la validación
        if ($result_validacion['tiene_sucursales_creadas'] && $result_validacion['tiene_productos_creados'] && $result_validacion['el_usuario_tiene_permisos_para_usar_pos']) {
            $result_validacion['paso_validacion'] = true;
        }

        return $result_validacion;
    }

    public function getPrimerSucursal($id_contribuyente) {
        //obtener la primer sucursal creada activa
        $sucursal = Sucursal::findFirst([
            'conditions' => 'id_contribuyente = :id_contribuyente: AND estado = :estado:',
            'bind' => [
                'id_contribuyente' => $id_contribuyente,
                'estado' => 'activo'
            ],
            'order' => 'idsucursal ASC'
        ]);

        if(!$sucursal) {
            return 0;
        }

        return $sucursal->idsucursal;
    }

    public function getTotalProductosPorSucursal($id_contribuyente) {
        
        $sucursales = Sucursal::find([
            'conditions' => 'id_contribuyente = :id_contribuyente: AND estado = :estado:',
            'bind' => [
                'id_contribuyente' => $id_contribuyente,
                'estado' => 'activo'
            ]
        ]);
        
        $lista_sucursales = [];

        foreach($sucursales as $sucursal) {
            
            $cantidad_productos = Producto::count([
                'conditions' => 'id_contribuyente = :id_contribuyente: AND idsucursal = :idsucursal: and estado = "activo"',
                'bind' => [
                    'id_contribuyente' => $id_contribuyente,
                    'idsucursal' => $sucursal->idsucursal
                ]
            ]);

            $lista_sucursales[] = (object) [
                'idsucursal' => $sucursal->idsucursal,
                'nombre' => $sucursal->nombre,
                'cantidad_productos' => $cantidad_productos,
                'factura_serie' => $sucursal->factura_serie,
                'boleta_serie'  => $sucursal->boleta_serie
            ];
        }

        return $lista_sucursales;
    }

    public function getProductosAction() {
        $this->view->disable();
        $request = $this->request;
    
        if ($request->isAjax() == true) {
            // Datos de sesión
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst([
                "idusuario = :idusuario:", 
                'bind' => ['idusuario' => $idusuario]
            ]);
    
            if (!$usuario) {
                $resp = [
                    'respuesta' => 'error',
                    'titulo' => 'Error en Usuario',
                    'mensaje' => 'Lo sentimos! Es posible que se haya cerrado la sesión... <a style="color: #1c81d1;" href="/session" target="_blank">Haz click aquí para ingresar de nuevo</a>'
                ];
                echo json_encode($resp);
                exit();
            }
    
            $contribuyente = Contribuyente::findFirst([
                "id_contribuyente = :id_contribuyente:", 
                'bind' => ['id_contribuyente' => $usuario->id_contribuyente]
            ]);
    
            if (!$contribuyente) {
                $resp = [
                    'respuesta' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => 'No existe la empresa que intenta configurar!!'
                ];
                echo json_encode($resp);
                exit();
            }
    
            // Capturar datos enviados desde el frontend
            $datapost = $this->request->getPost();
            $idsucursal = $datapost['idsucursal'];
            $offset = isset($datapost['offset']) ? (int) $datapost['offset'] : 0;
            $limit = isset($datapost['limit']) ? (int) $datapost['limit'] : 500;
    
            $sucursal = Sucursal::findFirst([
                "id_contribuyente = :id_contribuyente: and idsucursal = :idsucursal:",
                'bind' => [
                    'id_contribuyente' => $usuario->id_contribuyente,
                    'idsucursal' => $idsucursal
                ]
            ]);
    
            if (!$sucursal) {
                $resp = [
                    'respuesta' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => 'No existe la sucursal que intenta configurar!!'
                ];
                echo json_encode($resp);
                exit();
            }
    
            // Obtener los productos con paginación
            $resp = $this->get_lista_productos($usuario->id_contribuyente, $idsucursal, $offset, $limit);
            echo json_encode($resp);
            exit();
        }
    }

    public function get_lista_productos($id_contribuyente, $id_sucursal, $offset = 0, $limit = 500) {
        // Consulta con paginación usando offset y limit
        $productos = Producto::find([
            "conditions" => "id_contribuyente = :id_contribuyente: AND idsucursal = :idsucursal: AND estado = 'activo'",
            "bind" => [
                'id_contribuyente' => $id_contribuyente,
                'idsucursal' => $id_sucursal
            ],
            "order" => "idproducto DESC",
            "limit" => $limit,
            "offset" => $offset
        ]);
    
        $lista_productos = [];
        foreach ($productos as $producto) {
            $unidad_medida  = SunatUnidadmedida::findFirst([
                "idunidad = :idunidad:",
                'bind' => ['idunidad' => $producto->id_unidad_medida]
            ]);

            $moneda = SunatMoneda::findFirst([
                "id_codigomoneda = :id_codigomoneda:",
                'bind' => ['id_codigomoneda' => $producto->id_cod_moneda]
            ]);

            $unidades_presentacion = ProductoPresentacion::find([
                "idproducto = :idproducto: and estado = 'activo'",
                'bind' => ['idproducto' => $producto->idproducto]
            ]);
            
            $presentaciones = array();
            foreach($unidades_presentacion as $presentacion) {
                $unidad_medida_presentacion = SunatUnidadmedida::findFirst([
                    "idunidad = :idunidad:",
                    'bind' => ['idunidad' => $presentacion->idunidad]
                ]);

                $presentaciones[] = array(
                    'tipo' => 'presentacion',
                    'id_unidad_medida' => $presentacion->idunidad,
                    'id_unidad_base'   => $presentacion->idunidad_base,
                    'nombre' => $presentacion->nombre,
                    'codigo' => $presentacion->codigo,
                    'simbolo' => $unidad_medida_presentacion->simbolo,
                    'cantidad_und_base' => (float) $presentacion->cantidad_und_base ?: 1,
                    'estado' => $presentacion->estado,
                    'precio_con_igv' => (float) $presentacion->precio_con_igv,
                    'precio_sin_igv' => (float) $presentacion->precio_sin_igv,
                    'precio_minimo' => (float) $presentacion->precio_minimo,
                    'id_presentacion' => $presentacion->id_presentacion
                );
            }

            $listaprecio = array();
            $precios = ProductoListaprecio::find([
                "idproducto = :idproducto: and estado = 'activo'",
                'bind' => ['idproducto' => $producto->idproducto]
            ]);

            foreach($precios as $precio) {
                $listaprecio[] = array(
                    'idprecio' => (int) $precio->idprecio,
                    'idproducto' => (int) $precio->idproducto,
                    'nombre' => $precio->nombre,
                    'precio' => (int) $precio->precio,
                    'estado' => $precio->estado
                );
            }

            $lista_productos[] = [
                'idsucursal'            => $producto->idsucursal,
                'codigo'                => $producto->codigo,
                'unidadmedida_tipo'     => 'unidad_base',
                'id_unidad_medida'      => $producto->id_unidad_medida,
                'unidadmedida_nombre'   => $unidad_medida->nombre,
                'unidadmedida_simbolo'  => $unidad_medida->simbolo,
                'unidadmedida_codigo'   => $unidad_medida->codigo,
                'id_cod_detraccion'     => $producto->id_cod_detraccion,
                'id_tipoafectacionigv'  => $producto->id_tipoafectacionigv,
                'id_categoria'          => $producto->id_categoria,
                'nombre'                => $producto->nombre,
                'idproducto'            => $producto->idproducto,
                'id_cod_moneda'         => $producto->id_cod_moneda,
                'moneda_nombre'         => $moneda->nombre,
                'moneda_simbolo'        => $moneda->simbolo,
                'tipo_cambio_sunat'     => $producto->tipo_cambio_sunat,
                'precio_compra'         => $producto->precio_compra,
                'valor_sin_igv'         => $producto->valor_sin_igv,
                'valor_con_igv'         => $producto->valor_con_igv,
                'precio_venta_minimo'   => $producto->precio_venta_minimo,
                'nota'                  => $producto->nota,
                'foto'                  => $producto->foto,
                'stock'                 => $producto->stock,
                'stock_minimo'          => $producto->stock_minimo,
                'fecha_registro'        => $producto->fecha_registro,
                'estado'                => $producto->estado,
                'afecto_icbper'         => $producto->afecto_icbper,
                'multi_precio'          => $producto->multi_precio,
                'fecha_vencimiento'     => $producto->fecha_vencimiento,
                'marca'                 => $producto->marca,
                'codigos_presentaciones' => $producto->codigos_presentaciones,
                'porcentaje_pventa'     => $producto->porcentaje_pventa,
                'porcentaje_pminimo'    => $producto->porcentaje_pminimo,
                'costo_promedio'        => $producto->costo_promedio,
                'factor_igv'            => $producto->factor_igv,
                'peso'                  => $producto->peso,
                'destacado'             => $producto->destacado,
                'unidades_presentacion' => $presentaciones,
                'listaprecio'           => $listaprecio
            ];
        }
    
        return [
            'respuesta' => 'ok',
            'productos' => $lista_productos
        ];
    }

    public function destacarProductoAction() {
        $this->view->disable();
        $request = $this->request;
    
        if ($request->isAjax() == true) {
            // Datos de sesión
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst([
                "idusuario = :idusuario:", 
                'bind' => ['idusuario' => $idusuario]
            ]);
    
            if (!$usuario) {
                $resp = [
                    'respuesta' => 'error',
                    'titulo' => 'Error en Usuario',
                    'mensaje' => 'Lo sentimos! Es posible que se haya cerrado la sesión... <a style="color: #1c81d1;" href="/session" target="_blank">Haz click aquí para ingresar de nuevo</a>'
                ];
                echo json_encode($resp);
                exit();
            }
    
            $contribuyente = Contribuyente::findFirst([
                "id_contribuyente = :id_contribuyente:", 
                'bind' => ['id_contribuyente' => $usuario->id_contribuyente]
            ]);
    
            if (!$contribuyente) {
                $resp = [
                    'respuesta' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => 'No existe la empresa que intenta configurar!!'
                ];
                echo json_encode($resp);
                exit();
            }
    
            // Capturar datos enviados desde el frontend
            $datapost = $this->request->getPost();
            $idproducto = $datapost['idproducto'];
            $valor = ($datapost['value'] == 'si') ? 'si' : 'no';

            $producto = Producto::findFirst([
                "idproducto = :idproducto: AND id_contribuyente = :id_contribuyente:",
                'bind' => [
                    'idproducto' => $idproducto,
                    'id_contribuyente' => $usuario->id_contribuyente
                ]
            ]);

            if(!$producto) {
                $resp = [
                    'respuesta' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => 'No se encontró el producto seleccionado!!'
                ];
                echo json_encode($resp);
                exit();
            }

            $producto->destacado = $valor;
            try {
                if(!$producto->save()) {
                    $resp = [
                        'respuesta' => 'error',
                        'titulo' => 'Error',
                        'mensaje' => 'No se pudo guardar el producto como favorito!!'
                    ];
                    echo json_encode($resp);
                    exit();
                }
            } catch (Exception $e) {
                $this->saveLogger($e);
                $resp = [
                    'respuesta' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => 'No se pudo guardar como favorito!!'
                ];
                echo json_encode($resp);
                exit();
            }

            $resp = [
                'respuesta' => 'ok',
                'titulo' => 'Producto Destacado',
                'mensaje' => 'El producto se ha guardado como favorito!!',
                'valor' => $valor,
                'idproducto' => $idproducto,
                'idsucursal' => $producto->idsucursal
            ];
            echo json_encode($resp);
            exit();
        }
    }

    public function getTotalUsuarioAction() {
        $this->view->disable();
        $request = $this->request;
    
        if ($request->isAjax() == true) {
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst([
                "idusuario = :idusuario:", 
                'bind' => ['idusuario' => $idusuario]
            ]);
    
            if (!$usuario) {
                $resp = [
                    'respuesta' => 'error',
                    'titulo' => 'Error en Usuario',
                    'mensaje' => 'Lo sentimos! Es posible que se haya cerrado la sesión... <a style="color: #1c81d1;" href="/session" target="_blank">Haz click aquí para ingresar de nuevo</a>'
                ];
                echo json_encode($resp);
                exit();
            }
    
            $contribuyente = Contribuyente::findFirst([
                "id_contribuyente = :id_contribuyente:", 
                'bind' => ['id_contribuyente' => $usuario->id_contribuyente]
            ]);
    
            if (!$contribuyente) {
                $resp = [
                    'respuesta' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => 'No existe la empresa que intenta configurar!!'
                ];
                echo json_encode($resp);
                exit();
            }
    
            $total_items = Usuario::count([
                'conditions' => 'id_contribuyente = :id_contribuyente: and estado = "activo"',
                'bind' => ['id_contribuyente' => $usuario->id_contribuyente]
            ]);
    
            $resp = [
                'respuesta' => 'ok',
                'total' => $total_items
            ];

            echo json_encode($resp);
            exit();
        }
    }
    
    //function que devuelve el total de registros de la tabla: cliente, Si en caso la respuesta es correcta devolverá un json: { "respuesta": "ok", "total": $total_items }
    public function getTotalClienteAction() {
        $this->view->disable();
        $request = $this->request;
    
        if ($request->isAjax() == true) {
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst([
                "idusuario = :idusuario:", 
                'bind' => ['idusuario' => $idusuario]
            ]);
    
            if (!$usuario) {
                $resp = [
                    'respuesta' => 'error',
                    'titulo' => 'Error en Usuario',
                    'mensaje' => 'Lo sentimos! Es posible que se haya cerrado la sesión... <a style="color: #1c81d1;" href="/session" target="_blank">Haz click aquí para ingresar de nuevo</a>'
                ];
                echo json_encode($resp);
                exit();
            }
    
            $contribuyente = Contribuyente::findFirst([
                "id_contribuyente = :id_contribuyente:", 
                'bind' => ['id_contribuyente' => $usuario->id_contribuyente]
            ]);
    
            if (!$contribuyente) {
                $resp = [
                    'respuesta' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => 'No existe la empresa que intenta configurar!!'
                ];
                echo json_encode($resp);
                exit();
            }
    
            $total_items = Cliente::count([
                'conditions' => 'id_contribuyente = :id_contribuyente:',
                'bind' => ['id_contribuyente' => $usuario->id_contribuyente]
            ]);
    
            $resp = [
                'respuesta' => 'ok',
                'total' => $total_items
            ];

            echo json_encode($resp);
            exit();
        }
    }

    //function que devuelve el total de registros (estado = 'activo') de la tabla: categoria, Si en caso la respuesta es correcta devolverá un json: { "respuesta": "ok", "total": $total_items }
    public function getTotalCategoriaAction() {
        $this->view->disable();
        $request = $this->request;
    
        if ($request->isAjax() == true) {
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst([
                "idusuario = :idusuario:", 
                'bind' => ['idusuario' => $idusuario]
            ]);
    
            if (!$usuario) {
                $resp = [
                    'respuesta' => 'error',
                    'titulo' => 'Error en Usuario',
                    'mensaje' => 'Lo sentimos! Es posible que se haya cerrado la sesión... <a style="color: #1c81d1;" href="/session" target="_blank">Haz click aquí para ingresar de nuevo</a>'
                ];
                echo json_encode($resp);
                exit();
            }
    
            $contribuyente = Contribuyente::findFirst([
                "id_contribuyente = :id_contribuyente:", 
                'bind' => ['id_contribuyente' => $usuario->id_contribuyente]
            ]);
    
            if (!$contribuyente) {
                $resp = [
                    'respuesta' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => 'No existe la empresa que intenta configurar!!'
                ];
                echo json_encode($resp);
                exit();
            }
    
            $total_items = Categoria::count([
                'conditions' => 'id_contribuyente = :id_contribuyente: AND estado = "activo"',
                'bind' => ['id_contribuyente' => $usuario->id_contribuyente]
            ]);
    
            $resp = [
                'respuesta' => 'ok',
                'total' => $total_items
            ];

            echo json_encode($resp);
            exit();
        }
    }

    //function que devuelve el total de registros (estado = 'activo') de la tabla: cuentabanco, Si en caso la respuesta es correcta devolverá un json: { "respuesta": "ok", "total": $total_items }
    public function getTotalCuentabancoAction() {
        $this->view->disable();
        $request = $this->request;
    
        if ($request->isAjax() == true) {
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst([
                "idusuario = :idusuario:", 
                'bind' => ['idusuario' => $idusuario]
            ]);
    
            if (!$usuario) {
                $resp = [
                    'respuesta' => 'error',
                    'titulo' => 'Error en Usuario',
                    'mensaje' => 'Lo sentimos! Es posible que se haya cerrado la sesión... <a style="color: #1c81d1;" href="/session" target="_blank">Haz click aquí para ingresar de nuevo</a>'
                ];
                echo json_encode($resp);
                exit();
            }
    
            $contribuyente = Contribuyente::findFirst([
                "id_contribuyente = :id_contribuyente:", 
                'bind' => ['id_contribuyente' => $usuario->id_contribuyente]
            ]);
    
            if (!$contribuyente) {
                $resp = [
                    'respuesta' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => 'No existe la empresa que intenta configurar!!'
                ];
                echo json_encode($resp);
                exit();
            }
    
            $total_items = Cuentabanco::count([
                'conditions' => 'id_contribuyente = :id_contribuyente: AND estado = "activo" and tipo_cuenta <> "cuenta_detracciones"',
                'bind' => ['id_contribuyente' => $usuario->id_contribuyente]
            ]);
    
            $resp = [
                'respuesta' => 'ok',
                'total' => $total_items
            ];

            echo json_encode($resp);
            exit();
        }
    }

    //function que devuelve el total de registros de la tabla: sunatcodigoubigeo, Si en caso la respuesta es correcta devolverá un json: { "respuesta": "ok", "total": $total_items }
    public function getTotalSunatcodigoubigeoAction() {
        $this->view->disable();
        $request = $this->request;
    
        if ($request->isAjax() == true) {
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst([
                "idusuario = :idusuario:", 
                'bind' => ['idusuario' => $idusuario]
            ]);
    
            if (!$usuario) {
                $resp = [
                    'respuesta' => 'error',
                    'titulo' => 'Error en Usuario',
                    'mensaje' => 'Lo sentimos! Es posible que se haya cerrado la sesión... <a style="color: #1c81d1;" href="/session" target="_blank">Haz click aquí para ingresar de nuevo</a>'
                ];
                echo json_encode($resp);
                exit();
            }
    
            $contribuyente = Contribuyente::findFirst([
                "id_contribuyente = :id_contribuyente:", 
                'bind' => ['id_contribuyente' => $usuario->id_contribuyente]
            ]);
    
            if (!$contribuyente) {
                $resp = [
                    'respuesta' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => 'No existe la empresa que intenta configurar!!'
                ];
                echo json_encode($resp);
                exit();
            }
    
            $total_items = SunatCodigoubigeo::count();
    
            $resp = [
                'respuesta' => 'ok',
                'total' => $total_items
            ];

            echo json_encode($resp);
            exit();
        }
    }
    
    public function getTotalSunatTipoafectacionigvAction() {
        $this->view->disable();
        $request = $this->request;
    
        if ($request->isAjax() == true) {
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst([
                "idusuario = :idusuario:", 
                'bind' => ['idusuario' => $idusuario]
            ]);
    
            if (!$usuario) {
                $resp = [
                    'respuesta' => 'error',
                    'titulo' => 'Error en Usuario',
                    'mensaje' => 'Lo sentimos! Es posible que se haya cerrado la sesión... <a style="color: #1c81d1;" href="/session" target="_blank">Haz click aquí para ingresar de nuevo</a>'
                ];
                echo json_encode($resp);
                exit();
            }
    
            $contribuyente = Contribuyente::findFirst([
                "id_contribuyente = :id_contribuyente:", 
                'bind' => ['id_contribuyente' => $usuario->id_contribuyente]
            ]);
    
            if (!$contribuyente) {
                $resp = [
                    'respuesta' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => 'No existe la empresa que intenta configurar!!'
                ];
                echo json_encode($resp);
                exit();
            }
    
            $total_items = SunatTipoafectacionigv::count();
    
            $resp = [
                'respuesta' => 'ok',
                'total' => $total_items
            ];

            echo json_encode($resp);
            exit();
        }
    }

    public function getTotalSunatUnidadmedidaAction() {
        $this->view->disable();
        $request = $this->request;
    
        if ($request->isAjax() == true) {
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst([
                "idusuario = :idusuario:", 
                'bind' => ['idusuario' => $idusuario]
            ]);
    
            if (!$usuario) {
                $resp = [
                    'respuesta' => 'error',
                    'titulo' => 'Error en Usuario',
                    'mensaje' => 'Lo sentimos! Es posible que se haya cerrado la sesión... <a style="color: #1c81d1;" href="/session" target="_blank">Haz click aquí para ingresar de nuevo</a>'
                ];
                echo json_encode($resp);
                exit();
            }
    
            $contribuyente = Contribuyente::findFirst([
                "id_contribuyente = :id_contribuyente:", 
                'bind' => ['id_contribuyente' => $usuario->id_contribuyente]
            ]);
    
            if (!$contribuyente) {
                $resp = [
                    'respuesta' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => 'No existe la empresa que intenta configurar!!'
                ];
                echo json_encode($resp);
                exit();
            }
    
            $total_items = SunatUnidadmedida::count();
    
            $resp = [
                'respuesta' => 'ok',
                'total' => $total_items
            ];

            echo json_encode($resp);
            exit();
        }        
    }

    //function que devuelve el total de los registros de la tabla condiciondepago, Si en caso la respuesta es correcta devolverá un json: { "respuesta": "ok", "total": $total_items }
    public function getTotalCondiciondepagoAction() {
        $this->view->disable();
        $request = $this->request;
    
        if ($request->isAjax() == true) {
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst([
                "idusuario = :idusuario:", 
                'bind' => ['idusuario' => $idusuario]
            ]);
    
            if (!$usuario) {
                $resp = [
                    'respuesta' => 'error',
                    'titulo' => 'Error en Usuario',
                    'mensaje' => 'Lo sentimos! Es posible que se haya cerrado la sesión... <a style="color: #1c81d1;" href="/session" target="_blank">Haz click aquí para ingresar de nuevo</a>'
                ];
                echo json_encode($resp);
                exit();
            }
    
            $contribuyente = Contribuyente::findFirst([
                "id_contribuyente = :id_contribuyente:", 
                'bind' => ['id_contribuyente' => $usuario->id_contribuyente]
            ]);
    
            if (!$contribuyente) {
                $resp = [
                    'respuesta' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => 'No existe la empresa que intenta configurar!!'
                ];
                echo json_encode($resp);
                exit();
            }
            
            //debe ser filtrado por id_contribuyente y estado = 'activo'
            $total_items = Condiciondepago::count([
                'conditions' => 'id_contribuyente = :id_contribuyente: AND estado = "activo"',
                'bind' => ['id_contribuyente' => $usuario->id_contribuyente]
            ]);
    
            $resp = [
                'respuesta' => 'ok',
                'total' => $total_items
            ];

            echo json_encode($resp);
            exit();
        }
    }

    //función que recibe tres parámetros $id_contribuyente, $offset = 0, $limit = 500, debe extraer los ítems de la tabla cliente con estado = "activo" que pertenecen a id_contribuyente, y devolver un array con los campos: idcliente, id_tipodocidentidad, codigo, num_doc, razon_social.
    public function getItemsCliente($id_contribuyente, $offset = 0, $limit = 500) {
        $clientes = Cliente::find([
            'conditions' => 'id_contribuyente = :id_contribuyente: AND estado = "activo"',
            'bind' => ['id_contribuyente' => $id_contribuyente],
            'order' => 'idcliente DESC',
            'limit' => $limit,
            'offset' => $offset
        ]);

        $lista_clientes = [];
        foreach ($clientes as $cliente) {
            $lista_clientes[] = [
                'idcliente'             => $cliente->idcliente,
                'id_tipodocidentidad'   => $cliente->id_tipodocidentidad,
                'codigo'                => $cliente->codigo,
                'num_doc'               => $cliente->num_doc,
                'razon_social'          => $cliente->razon_social,
                'celular'               => $cliente->celular,
                'id_codigoubigeo'       => $cliente->id_cod_ubigeo,
                'direccion'             => $cliente->direccion_fiscal,
            ];
        }

        $resp['respuesta'] = 'ok';
        $resp['items'] = $lista_clientes;
        return $resp;
    }

    //function para actualizar la información de un usuario, si el campo idcliente es mayor a cero y existe para el id_contribuyente, y tiene el estado activo, entonces se debe actualizar ese registro con los datos enviados, si en caso existe pero está inactivo entonces se tiene que volver a activar y actualizar con los datos que ingresan, si no existe entonces se debe crear un nuevo registro con los datos enviados.
    public function actualizarClienteAction() {
        $this->view->disable();
        $request = $this->request;
    
        if ($request->isAjax() == true) {
            // Obtener el usuario autenticado
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'] ?? null;
    
            if (!$idusuario) {
                $resp = [
                    'respuesta' => 'error',
                    'titulo' => 'Error en Usuario',
                    'mensaje' => 'Sesión no iniciada o expirada. Por favor, inicie sesión nuevamente.'
                ];
                echo json_encode($resp);
                exit();
            }
    
            $usuario = Usuario::findFirst([
                'conditions' => 'idusuario = :idusuario:',
                'bind' => ['idusuario' => $idusuario]
            ]);
    
            if (!$usuario) {
                $resp = [
                    'respuesta' => 'error',
                    'titulo' => 'Error en Usuario',
                    'mensaje' => 'Usuario no encontrado.'
                ];
                echo json_encode($resp);
                exit();
            }
    
            // Obtener el contribuyente asociado al usuario
            $contribuyente = Contribuyente::findFirst([
                'conditions' => 'id_contribuyente = :id_contribuyente:',
                'bind' => ['id_contribuyente' => $usuario->id_contribuyente]
            ]);
    
            if (!$contribuyente) {
                $resp = [
                    'respuesta' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => 'No existe la empresa asociada al usuario.'
                ];
                echo json_encode($resp);
                exit();
            }
    
            // Obtener datos enviados por POST
            $datapost = $this->request->getPost();
            $idcliente = isset($datapost['idcliente']) ? (int)$datapost['idcliente'] : 0;
            $id_tipodocidentidad = $datapost['id_tipodocidentidad'] ?? '';
            $codigo = $datapost['codigo'] ?? '';
            $num_doc = $datapost['num_doc'] ?? '';
            $razon_social = $datapost['razon_social'] ?? '';
            $celular = $datapost['celular'] ?? '';
            $validacion_celular = $this->validarCelular($celular);
            $celular = $validacion_celular ? $validacion_celular : "";
            $id_codigoubigeo = $datapost['id_codigoubigeo'] ?? '';
            $direccion = $datapost['direccion'] ?? '';

            if(!empty($id_codigoubigeo)) {
                $SunatCodigoubigeo = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $id_codigoubigeo)));
                if(!$SunatCodigoubigeo){
                    $id_codigoubigeo = '';
                }
            }

            $tipodocidentidad = SunatTipodocidentidad::findFirst(array("id_tipodocidentidad = :id_tipodocidentidad:", 'bind' => array('id_tipodocidentidad' => $id_tipodocidentidad)));
            if(!$tipodocidentidad){
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo siento, ese  tipo de Documento de Identidad no existe. Por favor selecciona uno Válido';
                echo json_encode($resp);
                exit();
            }

            if(empty($num_doc)){
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El número de documento no puede estar vacío';
                echo json_encode($resp);
                exit();
            }

            $herramientas = new HerramientasController;
    
            // Inicializar variable de cliente
            $cliente = null;
    
            if ($idcliente > 0) {
                // Intentar obtener el cliente por idcliente y id_contribuyente
                $cliente = Cliente::findFirst([
                    'conditions' => 'idcliente = :idcliente: AND id_contribuyente = :id_contribuyente:',
                    'bind' => [
                        'idcliente' => $idcliente,
                        'id_contribuyente' => $usuario->id_contribuyente
                    ]
                ]);
    
                if ($cliente) {
                    // Si el cliente está inactivo, activarlo
                    if ($cliente->estado == 'inactivo') {
                        $cliente->estado = 'activo';
                    }
    
                    // Actualizar solo campos no vacíos
                    if (!empty($razon_social)) {
                        $cliente->razon_social = $razon_social;
                    }
                    if (!empty($celular)) {
                        $cliente->celular = $celular;
                    }
                    if (!empty($id_codigoubigeo)) {
                        $cliente->id_cod_ubigeo = $id_codigoubigeo;
                    }
                    if (!empty($direccion)) {
                        $cliente->direccion_fiscal = $direccion;
                    }
                } else {
                    // Cliente no encontrado con el idcliente proporcionado
                    $resp = [
                        'respuesta' => 'error',
                        'titulo' => 'Error',
                        'mensaje' => 'Cliente no encontrado.'
                    ];
                    echo json_encode($resp);
                    exit();
                }
            } else {
                // idcliente es cero o menor, buscar por id_tipodocidentidad y num_doc
                $cliente = Cliente::findFirst([
                    'conditions' => 'id_contribuyente = :id_contribuyente: AND id_tipodocidentidad = :id_tipodocidentidad: AND num_doc = :num_doc:',
                    'bind' => [
                        'id_contribuyente' => $usuario->id_contribuyente,
                        'id_tipodocidentidad' => $id_tipodocidentidad,
                        'num_doc' => $num_doc
                    ]
                ]);
    
                if ($cliente) {
                    // Si el cliente está inactivo, activarlo
                    if ($cliente->estado == 'inactivo') {
                        $cliente->estado = 'activo';
                    }
    
                    // Actualizar solo campos no vacíos
                    if (!empty($razon_social)) {
                        $cliente->razon_social = $razon_social;
                    }
                    if (!empty($celular)) {
                        $cliente->celular = $celular;
                    }
                    if (!empty($id_codigoubigeo)) {
                        $cliente->id_cod_ubigeo = $id_codigoubigeo;
                    }
                    if (!empty($direccion)) {
                        $cliente->direccion_fiscal = $direccion;
                    }
                } else {
                    // Cliente no existe, crear uno nuevo
                    $cliente = new Cliente();
                    $cliente->id_contribuyente = $usuario->id_contribuyente;
                    $cliente->estado = 'activo';
    
                    // Asignar campos enviados
                    $codigo = $herramientas->gettoken(10);
                    $cliente->id_tipodocidentidad = $id_tipodocidentidad;
                    $cliente->codigo = $codigo;
                    $cliente->num_doc = $num_doc;
                    $cliente->razon_social = $razon_social;
                    $cliente->celular = $celular;
                    $cliente->id_cod_ubigeo = $id_codigoubigeo;
                    $cliente->direccion_fiscal = $direccion;
                }
            }
    
            // Intentar guardar el cliente
            try {
                if (!$cliente->save()) {
                    $messages = $cliente->getMessages();
                    $errorMessage = '';
                    foreach ($messages as $message) {
                        $errorMessage .= $message . "\n";
                    }
                    $resp = [
                        'respuesta' => 'error',
                        'titulo' => 'Error al guardar',
                        'mensaje' => 'No se pudo guardar el cliente. ' . $errorMessage
                    ];
                    echo json_encode($resp);
                    exit();
                }
            } catch (\Exception $e) {
                // Registrar el error y responder
                $this->saveLogger($e);
                $resp = [
                    'respuesta' => 'error',
                    'titulo' => 'Error de Excepción',
                    'mensaje' => 'Ocurrió un error al guardar el cliente.'
                ];
                echo json_encode($resp);
                exit();
            }
    
            // Respuesta exitosa
            $resp = [
                'respuesta' => 'ok',
                'titulo' => 'Cliente Guardado',
                'mensaje' => 'El cliente se ha guardado correctamente.',
                'idcliente' => $cliente->idcliente
            ];
            echo json_encode($resp);
            exit();
        }
    }

    public function validarCelular($celular) {
        // Eliminar todos los espacios en blanco
        $celular = preg_replace('/\s+/', '', $celular);
    
        // Eliminar caracteres no numéricos, excepto el signo '+'
        $celular = preg_replace('/[^\d\+]/', '', $celular);
    
        // Verificar si el número comienza con '00', '0', o '+'
        if (preg_match('/^(00|\+|0)/', $celular)) {
            // Reemplazar '00' o '0' inicial por '+'
            $celular = preg_replace('/^00/', '+', $celular);
            $celular = preg_replace('/^0/', '+', $celular);
        } else {
            // Si no comienza con ninguno de estos, agregar '+'
            $celular = '+' . $celular;
        }
    
        // Eliminar signos '+' adicionales
        $celular = preg_replace('/\++/', '+', $celular);
    
        // Verificar que solo queden dígitos después del '+'
        if (!preg_match('/^\+\d+$/', $celular)) {
            // Número inválido
            return false;
        }
    
        // Verificar la longitud del número (opcional, ajustar según tus necesidades)
        $longitud = strlen($celular);
        if ($longitud < 10 || $longitud > 15) {
            // Número inválido por longitud incorrecta
            return false;
        }
    
        // Retornar el número de celular validado y estandarizado
        return $celular;
    }

    public function buscarClienteAction() {
        $this->view->disable();
        $request = $this->request;
    
        if ($request->isAjax() == true) {
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst([
                "idusuario = :idusuario:", 
                'bind' => ['idusuario' => $idusuario]
            ]);
    
            if (!$usuario) {
                $resp = [
                    'respuesta' => 'error',
                    'titulo' => 'Error en Usuario',
                    'mensaje' => 'Lo sentimos! Es posible que se haya cerrado la sesión... <a style="color: #1c81d1;" href="/session" target="_blank">Haz click aquí para ingresar de nuevo</a>'
                ];
                echo json_encode($resp);
                exit();
            }
    
            $contribuyente = Contribuyente::findFirst([
                "id_contribuyente = :id_contribuyente:", 
                'bind' => ['id_contribuyente' => $usuario->id_contribuyente]
            ]);
    
            if (!$contribuyente) {
                $resp = [
                    'respuesta' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => 'No existe la empresa que intenta configurar!!'
                ];
                echo json_encode($resp);
                exit();
            }
    
            $datapost = $this->request->getPost();
            $id_tipodocidentidad = $datapost['id_tipodocidentidad'];
            $num_doc = $datapost['num_doc'];
    
            $resp = $this->buscarClientePorNumDocumento($usuario->id_contribuyente, $id_tipodocidentidad, $num_doc);
            echo json_encode($resp);
            exit();
        }
    }

    //funcion para buscar un cliente por tipo y número de documento
    public function buscarClientePorNumDocumento($id_contribuyente, $id_tipodocidentidad, $num_doc) {
        try {
            $cliente = Cliente::findFirst([
                'conditions' => 'id_contribuyente = :id_contribuyente: AND id_tipodocidentidad = :id_tipodocidentidad: AND num_doc = :num_doc: AND estado = "activo"',
                'bind' => [
                    'id_contribuyente'      => $id_contribuyente,
                    'id_tipodocidentidad'   => $id_tipodocidentidad,
                    'num_doc'               => $num_doc
                ]
            ]);
        } catch (Exception $e) {
            $this->saveLogger($e);
            return [
                'respuesta' => 'error',
                'mensaje' => 'Error al buscar el cliente',
                'error' => $e->getMessage()
            ];
        }

        if ($cliente) {
            return [
                'respuesta' => 'ok',
                'cliente' => array(
                    'idcliente'             => $cliente->idcliente,
                    'id_tipodocidentidad'   => $cliente->id_tipodocidentidad,
                    'codigo'                => $cliente->codigo,
                    'num_doc'               => $cliente->num_doc,
                    'razon_social'          => $cliente->razon_social,
                    'celular'               => $cliente->celular,
                    'id_codigoubigeo'       => $cliente->id_cod_ubigeo,
                    'direccion'             => $cliente->direccion_fiscal,
                )
            ];
        }

        //si no se encuentra en la base de datos entonces debemos registrarlo pero antes buscar la data en la sunat
        $herramientas = new HerramientasController();
        $tipo_doc = '';
        if($id_tipodocidentidad == 6) {
            $tipo_doc = 'ruc';
        } else if($id_tipodocidentidad == 1) {
            $tipo_doc = 'dni';
        }

        $data_sunat = $herramientas->get_data_api_busquedas($tipo_doc, $num_doc);
        if($data_sunat['respuesta'] == 'error') {
            return [
                'respuesta'     => 'error',
                'mensaje'       => 'No se encontró el cliente'
            ];
        }

        if($id_tipodocidentidad == 6) {
            if(isset($data_sunat['data']->ruc) && $data_sunat['data']->ruc != '' && isset($data_sunat['data']->razon_social) && $data_sunat['data']->razon_social != '') {
                return [
                    'respuesta' => 'ok',
                    'cliente' => [
                        'idcliente'             => 0,
                        'id_tipodocidentidad'   => $id_tipodocidentidad,
                        'codigo'                => '',
                        'num_doc'               => $num_doc,
                        'razon_social'          => $data_sunat['data']->razon_social,
                        'celular'               => '',
                        'id_codigoubigeo'       => isset($data_sunat['data']->codigo_ubigeo) ? $data_sunat['data']->codigo_ubigeo : '',
                        'direccion'             => (isset($data_sunat['data']->direccion) && isset($data_sunat['texto_ubigeo']) && $data_sunat['data']->direccion != '' && $data_sunat['texto_ubigeo'] != '') ? $this->correctDireccion($data_sunat['data']->direccion, $data_sunat['texto_ubigeo']) : ''
                    ]
                ];
            }
        } else if($id_tipodocidentidad == 1) {
            if(isset($data_sunat['data']->nombre) && $data_sunat['data']->nombre != '' && isset($data_sunat['data']->dni) && $data_sunat['data']->dni != '') {
                return [
                    'respuesta' => 'ok',
                    'cliente' => [
                        'idcliente'             => 0,
                        'id_tipodocidentidad'   => $id_tipodocidentidad,
                        'codigo'                => '',
                        'num_doc'               => $num_doc,
                        'razon_social'          => $data_sunat['data']->nombre,
                        'celular'               => '',
                        'id_codigoubigeo'       => '',
                        'direccion'             => ''
                    ]
                ];
            }
        }

        return [
            'respuesta' => 'error',
            'mensaje' => 'No se encontró el cliente'
        ];
    }

    public function generateUbigeoPattern($ubigeo_text) {
        // Escapar caracteres especiales de regex
        $ubigeo_pattern = preg_quote($ubigeo_text, '/');
    
        // Reemplazar guiones con espacios opcionales o guiones
        $ubigeo_pattern = str_replace('\-', '[\-\s]*', $ubigeo_pattern);
    
        // Reemplazar espacios con '\s+' para coincidir con uno o más espacios
        $ubigeo_pattern = preg_replace('/\\\\\s+/', '\s+', $ubigeo_pattern);
    
        return $ubigeo_pattern;
    }
    
    public function correctDireccion($direccion, $ubigeo_text) {
        // Generar el patrón regex para el ubigeo
        $ubigeo_pattern = $this->generateUbigeoPattern($ubigeo_text);
    
        // Construir el patrón regex para coincidir con el ubigeo al final de la dirección
        $pattern = '/'. $ubigeo_pattern . '$/iu';
    
        // Intentar eliminar el ubigeo del final de la dirección
        $corrected_direccion = preg_replace($pattern, '', $direccion);
    
        return trim($corrected_direccion);
    }

    public function getItemsUsuarioAction() {
        $this->view->disable();
        $request = $this->request;
    
        if ($request->isAjax() == true) {
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst([
                "idusuario = :idusuario:", 
                'bind' => ['idusuario' => $idusuario]
            ]);
    
            if (!$usuario) {
                $resp = [
                    'respuesta' => 'error',
                    'titulo' => 'Error en Usuario',
                    'mensaje' => 'Lo sentimos! Es posible que se haya cerrado la sesión... <a style="color: #1c81d1;" href="/session" target="_blank">Haz click aquí para ingresar de nuevo</a>'
                ];
                echo json_encode($resp);
                exit();
            }
    
            $contribuyente = Contribuyente::findFirst([
                "id_contribuyente = :id_contribuyente:", 
                'bind' => ['id_contribuyente' => $usuario->id_contribuyente]
            ]);
    
            if (!$contribuyente) {
                $resp = [
                    'respuesta' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => 'No existe la empresa que intenta configurar!!'
                ];
                echo json_encode($resp);
                exit();
            }
    
            $offset = $request->getPost('offset', 'int', 0);
            $limit = $request->getPost('limit', 'int', 500);
    
            $resp = $this->getItemsUsuario($usuario->id_contribuyente, $offset, $limit);
            echo json_encode($resp);
            exit();
        }
    }

    public function getItemsUsuario($id_contribuyente, $offset = 0, $limit = 500) {
        // Consulta con paginación usando offset y limit
        $usuarios = Usuario::find([
            "conditions" => "id_contribuyente = :id_contribuyente: AND estado = 'activo'",
            "bind" => ['id_contribuyente' => $id_contribuyente],
            "order" => "idusuario DESC",
            "limit" => $limit,
            "offset" => $offset
        ]);
    
        $lista_usuarios = [];
        foreach ($usuarios as $usuario) {    
            $lista_usuarios[] = [
                'id_contribuyente'  => (int) $usuario->id_contribuyente,
                'idusuario'         => (int) $usuario->idusuario,
                'nombre'            => $usuario->nombre,
                'apellido'          => $usuario->apellido
            ];
        }
    
        return [
            'respuesta' => 'ok',
            'items' => $lista_usuarios
        ];
    }

    //función que recibe tres parámetros $id_contribuyente, $offset = 0, $limit = 500, debe extraer los ítems de la tabla categoria con estado = "activo" que pertenecen a id_contribuyente, y devolver un array con los campos: id_categoria, nombre.
    public function getItemsCategoria($id_contribuyente, $offset = 0, $limit = 500) {
        $categorias = Categoria::find([
            'conditions' => 'id_contribuyente = :id_contribuyente: AND estado = "activo"',
            'bind' => ['id_contribuyente' => $id_contribuyente],
            'order' => 'idcategoria DESC',
            'limit' => $limit,
            'offset' => $offset
        ]);

        $lista_categorias = [];
        foreach ($categorias as $categoria) {
            $lista_categorias[] = [
                'idcategoria' => $categoria->idcategoria,
                'nombre' => $categoria->nombre
            ];
        }

        $resp['respuesta'] = 'ok';
        $resp['items'] = $lista_categorias;
        return $resp;
    }

    //función que recibe tres parámetros $id_contribuyente, $offset = 0, $limit = 500, debe extraer los ítems de la tabla cuentabanco con estado = "activo" que pertenecen a id_contribuyente, y devolver un array con los campos: id_cuentabanco, id_codigomoneda, tipo_cuenta, nombre_banco, estado.
    public function getItemsCuentabanco($id_contribuyente, $offset = 0, $limit = 500) {
        $cuentas = Cuentabanco::find([
            'conditions' => 'id_contribuyente = :id_contribuyente: AND estado = "activo" and tipo_cuenta <> "cuenta_detracciones"',
            'bind' => ['id_contribuyente' => $id_contribuyente],
            'order' => 'id_cuentabanco DESC',
            'limit' => $limit,
            'offset' => $offset
        ]);

        $lista_cuentas = [];
        foreach ($cuentas as $cuenta) {
            $moneda = SunatMoneda::findFirst([
                'id_codigomoneda = :id_codigomoneda:',
                'bind' => ['id_codigomoneda' => $cuenta->id_codigomoneda]
            ]);

            $lista_cuentas[] = [
                'id_cuentabanco' => $cuenta->id_cuentabanco,
                'id_codigomoneda' => $cuenta->id_codigomoneda,
                'tipo_cuenta' => $cuenta->tipo_cuenta,
                'nombre_banco' => $cuenta->nombre_banco,
                'estado' => $cuenta->estado,
                'moneda_nombre' => $moneda->nombre,
                'moneda_simbolo' => $moneda->simbolo
            ];
        }
        
        $resp['respuesta'] = 'ok';
        $resp['items'] = $lista_cuentas;
        return $resp;
    }

    //función que recibe dos parámetros $offset = 0, $limit = 500, debe extraer los ítems de la tabla sunatcodigoubigeo, y devolver un array con los campos: codigo_ubigeo, departamento, provincia, distrito.
    public function getItemsSunatcodigoubigeo($offset = 0, $limit = 500) {
        $ubigeos = SunatCodigoubigeo::find([
            'order' => 'codigo_ubigeo ASC',
            'limit' => $limit,
            'offset' => $offset
        ]);

        $lista_ubigeos = [];
        foreach ($ubigeos as $ubigeo) {
            $lista_ubigeos[] = [
                'codigo_ubigeo'     => $ubigeo->codigo_ubigeo,
                'departamento'      => $ubigeo->departamento,
                'provincia'         => $ubigeo->provincia,
                'distrito'          => $ubigeo->distrito
            ];
        }

        $resp['respuesta'] = 'ok';
        $resp['items'] = $lista_ubigeos;
        return $resp;
    }

    //función que recibe dos parámetros $offset = 0, $limit = 500, debe extraer los ítems de la tabla condiciondepago con estado = "activo", y devolver un array con los campos: id_condicionpago, nombre.
    public function getItemsCondiciondepago($id_contribuyente, $offset = 0, $limit = 500) {
        $condiciones = Condiciondepago::find([
            'conditions' => 'id_contribuyente = :id_contribuyente: AND estado = "activo"',
            'bind' => ['id_contribuyente' => $id_contribuyente],
            'order' => 'id_condicionpago DESC',
            'limit' => $limit,
            'offset' => $offset
        ]);

        $lista_condiciones = [];
        foreach ($condiciones as $condicion) {
            $lista_condiciones[] = [
                'id_condicionpago' => $condicion->id_condicionpago,
                'nombre' => $condicion->condicionpago,
                'tipo' => $condicion->tipo
            ];
        }
 
        $resp['respuesta'] = 'ok';
        $resp['items'] = $lista_condiciones;
        return $resp;      
    }

    public function getItemsCondiciondepagoAction() {
        $this->view->disable();
        $request = $this->request;
    
        if ($request->isAjax() == true) {
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst([
                "idusuario = :idusuario:", 
                'bind' => ['idusuario' => $idusuario]
            ]);
    
            if (!$usuario) {
                $resp = [
                    'respuesta' => 'error',
                    'titulo' => 'Error en Usuario',
                    'mensaje' => 'Lo sentimos! Es posible que se haya cerrado la sesión... <a style="color: #1c81d1;" href="/session" target="_blank">Haz click aquí para ingresar de nuevo</a>'
                ];
                echo json_encode($resp);
                exit();
            }

            // Capturar datos enviados desde el frontend
            $datapost = $this->request->getPost();
            $offset = isset($datapost['offset']) ? (int) $datapost['offset'] : 0;
            $limit = isset($datapost['limit']) ? (int) $datapost['limit'] : 500;
            
            $resp = $this->getItemsCondiciondepago($usuario->id_contribuyente, $offset, $limit);
            echo json_encode($resp);
            exit();
        }
    }

    public function getItemsClienteAction() {
        $this->view->disable();
        $request = $this->request;
    
        if ($request->isAjax() == true) {
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst([
                "idusuario = :idusuario:", 
                'bind' => ['idusuario' => $idusuario]
            ]);
    
            if (!$usuario) {
                $resp = [
                    'respuesta' => 'error',
                    'titulo' => 'Error en Usuario',
                    'mensaje' => 'Lo sentimos! Es posible que se haya cerrado la sesión... <a style="color: #1c81d1;" href="/session" target="_blank">Haz click aquí para ingresar de nuevo</a>'
                ];
                echo json_encode($resp);
                exit();
            }
    
            $contribuyente = Contribuyente::findFirst([
                "id_contribuyente = :id_contribuyente:", 
                'bind' => ['id_contribuyente' => $usuario->id_contribuyente]
            ]);
    
            if (!$contribuyente) {
                $resp = [
                    'respuesta' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => 'No existe la empresa que intenta configurar!!'
                ];
                echo json_encode($resp);
                exit();
            }
    
            // Capturar datos enviados desde el frontend
            $datapost = $this->request->getPost();
            $offset = isset($datapost['offset']) ? (int) $datapost['offset'] : 0;
            $limit = isset($datapost['limit']) ? (int) $datapost['limit'] : 500;
    
            $resp = $this->getItemsCliente($usuario->id_contribuyente, $offset, $limit);
            echo json_encode($resp);
            exit();
        }
    }

    public function getItemsSunatTipoafectacionigvAction() {
        $this->view->disable();
        $request = $this->request;
    
        if ($request->isAjax() == true) {
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst([
                "idusuario = :idusuario:", 
                'bind' => ['idusuario' => $idusuario]
            ]);
    
            if (!$usuario) {
                $resp = [
                    'respuesta' => 'error',
                    'titulo' => 'Error en Usuario',
                    'mensaje' => 'Lo sentimos! Es posible que se haya cerrado la sesión... <a style="color: #1c81d1;" href="/session" target="_blank">Haz click aquí para ingresar de nuevo</a>'
                ];
                echo json_encode($resp);
                exit();
            }
    
            // Capturar datos enviados desde el frontend
            $datapost = $this->request->getPost();
            $offset = isset($datapost['offset']) ? (int) $datapost['offset'] : 0;
            $limit = isset($datapost['limit']) ? (int) $datapost['limit'] : 500;
    
            $resp = $this->getItemsSunatTipoafectacionigv($offset, $limit);
            echo json_encode($resp);
            exit();
        }
    }

    public function getItemsSunatUnidadmedidaAction() {
        $this->view->disable();
        $request = $this->request;
    
        if ($request->isAjax() == true) {
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst([
                "idusuario = :idusuario:", 
                'bind' => ['idusuario' => $idusuario]
            ]);
    
            if (!$usuario) {
                $resp = [
                    'respuesta' => 'error',
                    'titulo' => 'Error en Usuario',
                    'mensaje' => 'Lo sentimos! Es posible que se haya cerrado la sesión... <a style="color: #1c81d1;" href="/session" target="_blank">Haz click aquí para ingresar de nuevo</a>'
                ];
                echo json_encode($resp);
                exit();
            }
    
            $contribuyente = Contribuyente::findFirst([
                "id_contribuyente = :id_contribuyente:", 
                'bind' => ['id_contribuyente' => $usuario->id_contribuyente]
            ]);
    
            if (!$contribuyente) {
                $resp = [
                    'respuesta' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => 'No existe la empresa que intenta configurar!!'
                ];
                echo json_encode($resp);
                exit();
            }
    
            // Capturar datos enviados desde el frontend
            $datapost = $this->request->getPost();
            $offset = isset($datapost['offset']) ? (int) $datapost['offset'] : 0;
            $limit = isset($datapost['limit']) ? (int) $datapost['limit'] : 500;
    
            $resp = $this->getItemsSunatUnidadmedida($offset, $limit);
            echo json_encode($resp);
            exit();
        }
    }

    public function getItemsSunatUnidadmedida($offset = 0, $limit = 500) {
        $unidades_medida = SunatUnidadmedida::find([
            'order' => 'idunidad ASC',
            'limit' => $limit,
            'offset' => $offset
        ]);

        $lista_unidades_medida = [];
        foreach ($unidades_medida as $unidad_medida) {
            $lista_unidades_medida[] = [
                'id_unidadmedida'   => $unidad_medida->idunidad,
                'nombre'            => $unidad_medida->nombre,
                'simbolo'           => $unidad_medida->simbolo
            ];
        }

        $resp['respuesta'] = 'ok';
        $resp['items'] = $lista_unidades_medida;
        return $resp;
    }

    public function getItemsSunatTipoafectacionigv($offset = 0, $limit = 500) {
        $tipos_afectacion = SunatTipoafectacionigv::find([
            'order' => 'id_tipoafectacionigv ASC',
            'limit' => $limit,
            'offset' => $offset
        ]);

        $lista_tipos_afectacion = [];
        foreach ($tipos_afectacion as $tipo_afectacion) {
            $lista_tipos_afectacion[] = [
                'id_tipoafectacionigv'  => $tipo_afectacion->id_tipoafectacionigv,
                'descripcion'           => $tipo_afectacion->descripcion
            ];
        }

        $resp['respuesta'] = 'ok';
        $resp['items'] = $lista_tipos_afectacion;
        return $resp;
    }

    public function getItemsCategoriaAction() {
        $this->view->disable();
        $request = $this->request;
    
        if ($request->isAjax() == true) {
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst([
                "idusuario = :idusuario:", 
                'bind' => ['idusuario' => $idusuario]
            ]);
    
            if (!$usuario) {
                $resp = [
                    'respuesta' => 'error',
                    'titulo' => 'Error en Usuario',
                    'mensaje' => 'Lo sentimos! Es posible que se haya cerrado la sesión... <a style="color: #1c81d1;" href="/session" target="_blank">Haz click aquí para ingresar de nuevo</a>'
                ];
                echo json_encode($resp);
                exit();
            }
    
            $contribuyente = Contribuyente::findFirst([
                "id_contribuyente = :id_contribuyente:", 
                'bind' => ['id_contribuyente' => $usuario->id_contribuyente]
            ]);
    
            if (!$contribuyente) {
                $resp = [
                    'respuesta' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => 'No existe la empresa que intenta configurar!!'
                ];
                echo json_encode($resp);
                exit();
            }
    
            // Capturar datos enviados desde el frontend
            $datapost = $this->request->getPost();
            $offset = isset($datapost['offset']) ? (int) $datapost['offset'] : 0;
            $limit = isset($datapost['limit']) ? (int) $datapost['limit'] : 500;
    
            $resp = $this->getItemsCategoria($usuario->id_contribuyente, $offset, $limit);
            echo json_encode($resp);
            exit();
        }
    }

    public function getItemsCuentabancoAction() {
        $this->view->disable();
        $request = $this->request;
    
        if ($request->isAjax() == true) {
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst([
                "idusuario = :idusuario:", 
                'bind' => ['idusuario' => $idusuario]
            ]);
    
            if (!$usuario) {
                $resp = [
                    'respuesta' => 'error',
                    'titulo' => 'Error en Usuario',
                    'mensaje' => 'Lo sentimos! Es posible que se haya cerrado la sesión... <a style="color: #1c81d1;" href="/session" target="_blank">Haz click aquí para ingresar de nuevo</a>'
                ];
                echo json_encode($resp);
                exit();
            }
    
            $contribuyente = Contribuyente::findFirst([
                "id_contribuyente = :id_contribuyente:", 
                'bind' => ['id_contribuyente' => $usuario->id_contribuyente]
            ]);
    
            if (!$contribuyente) {
                $resp = [
                    'respuesta' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => 'No existe la empresa que intenta configurar!!'
                ];
                echo json_encode($resp);
                exit();
            }
    
            // Capturar datos enviados desde el frontend
            $datapost = $this->request->getPost();
            $offset = isset($datapost['offset']) ? (int) $datapost['offset'] : 0;
            $limit = isset($datapost['limit']) ? (int) $datapost['limit'] : 500;
    
            $resp = $this->getItemsCuentabanco($usuario->id_contribuyente, $offset, $limit);
            echo json_encode($resp);
            exit();
        }
    }

    public function getItemsSunatcodigoubigeoAction() {
        $this->view->disable();
        $request = $this->request;
    
        if ($request->isAjax() == true) {
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst([
                "idusuario = :idusuario:", 
                'bind' => ['idusuario' => $idusuario]
            ]);
    
            if (!$usuario) {
                $resp = [
                    'respuesta' => 'error',
                    'titulo' => 'Error en Usuario',
                    'mensaje' => 'Lo sentimos! Es posible que se haya cerrado la sesión... <a style="color: #1c81d1;" href="/session" target="_blank">Haz click aquí para ingresar de nuevo</a>'
                ];
                echo json_encode($resp);
                exit();
            }
    
            // Capturar datos enviados desde el frontend
            $datapost = $this->request->getPost();
            $offset = isset($datapost['offset']) ? (int) $datapost['offset'] : 0;
            $limit = isset($datapost['limit']) ? (int) $datapost['limit'] : 500;
    
            $resp = $this->getItemsSunatcodigoubigeo($offset, $limit);
            echo json_encode($resp);
            exit();
        }
    }

    public function procesarVentaCarritoAction() {
        $this->view->disable();
        $request = $this->request;
    
        if ($request->isAjax() == true) {
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst([
                "idusuario = :idusuario:", 
                'bind' => ['idusuario' => $idusuario]
            ]);
    
            if (!$usuario) {
                $resp = [
                    'respuesta' => 'error',
                    'titulo' => 'Error en Usuario',
                    'mensaje' => 'Lo sentimos! Es posible que se haya cerrado la sesión... <a style="color: #1c81d1;" href="/session" target="_blank">Haz click aquí para ingresar de nuevo</a>'
                ];
                return $this->response->setJsonContent($resp);
            }

            $contribuyente = Contribuyente::findFirst([
                "id_contribuyente = :id_contribuyente:", 
                'bind' => ['id_contribuyente' => $usuario->id_contribuyente]
            ]);

            if (!$contribuyente) {
                $resp = [
                    'respuesta' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => 'No existe la empresa que intenta configurar!!'
                ];
                return $this->response->setJsonContent($resp);
            }

            $systempostvalidacion = new SystemposvalidacionController;

            // Obtener y decodificar JSON
            try {
                $jsonData = $this->request->getJsonRawBody(true);
                if (!$jsonData) {
                    throw new \Exception('Error al decodificar JSON');
                }
            } catch (\Exception $e) {
                $resp = [
                    'respuesta' => 'error',
                    'titulo'    => 'Error en Datos',
                    'mensaje'   => 'Error al procesar los datos de la venta: ' . $e->getMessage()
                ];

                return $this->response->setJsonContent($resp);
            }
            
            // Extraer y validar datos del carrito
            try {
                $carrito = $systempostvalidacion->extraerDatosCarrito($jsonData, $usuario, $contribuyente);
                $datos_adicionales = $systempostvalidacion->extraerDatosAdicionales($jsonData, $usuario, $contribuyente);
                $datapost = $systempostvalidacion->generar_datapost($carrito, $datos_adicionales, $contribuyente);
                $id_vendedor_asignado = $datos_adicionales['id_vendedor_asignado'];

                $usuario_vendedor = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente:", 'bind' => array('idusuario' => $id_vendedor_asignado, 'id_contribuyente' => $contribuyente->id_contribuyente)));
		
                $documentoelectronico = new DocumentoelectronicoController;
                $resp_proceso_venta = $documentoelectronico->procesar_documento_electronico($usuario_vendedor, $datapost);
                if($resp_proceso_venta['respuesta'] == 'error') {
                    return $this->response->setJsonContent($resp_proceso_venta);
                }

                $this->response->setStatusCode(200, 'OK');
                $resp = array(
                    'respuesta' 				=> 'ok',
                    'serie_comprobante'			=> isset($resp_proceso_venta['documento']['serie_comprobante']) ? $resp_proceso_venta['documento']['serie_comprobante'] : '',
                    'numero_comprobante'		=> $resp_proceso_venta['documento']['numero_comprobante'],
                    'id_tipodoc_electronico' 	=> $resp_proceso_venta['documento']['id_tipodoc_electronico'],
                    'mensaje'					=> $resp_proceso_venta['mensaje'],
                    'mensaje_sunat'				=> isset($resp_proceso_venta['repuesta_api']) ? $resp_proceso_venta['repuesta_api'] : '',
                    'url_relativa_a4' 			=> $resp_proceso_venta['url_relativa_a4'],
                    'url_relativa_ticket' 		=> $resp_proceso_venta['url_relativa_ticket'],
                    'url_relativa_xml' 			=> $resp_proceso_venta['url_relativa_xml'],
                    'url_relativa_xml_cdr' 		=> $resp_proceso_venta['url_relativa_xml_cdr'],
                    'id_carrito'				=> $carrito['id_carrito'],
                );

                if($id_vendedor_asignado != $usuario->idusuario) {
                    $new_log = new LogDocumento();
                    $new_log->id_contribuyente          = $contribuyente->id_contribuyente;
                    $new_log->id_tipodoc_electronico    = $resp_proceso_venta['documento']['id_tipodoc_electronico'];
                    $new_log->serie_comprobante         = $resp_proceso_venta['documento']['serie_comprobante'];
                    $new_log->numero_comprobante        = $resp_proceso_venta['documento']['numero_comprobante'];
                    $new_log->tipo_envio_sunat          = $contribuyente->tipo_envio_sunat;
                    $new_log->id_usuario                = $usuario->idusuario;
                    $new_log->descripcion               = "Documento generado por el usuario: " . $usuario->nombre . " " . $usuario->apellido . "(ID: ". $usuario->idusuario ."), y fué asignado al usuario: " . $usuario_vendedor->nombre . " " . $usuario_vendedor->apellido . "(ID: ". $usuario_vendedor->idusuario .")";
                    $new_log->tipo                      = 'asignacion_venta';
                    $new_log->fecha_registro            = date('Y-m-d H:i:s');
                    $resp_save                          = $new_log->save();
                }

            } catch (\Exception $e) {
                $resp = [
                    'respuesta' => 'error',
                    'titulo'    => 'Error en Datos',
                    'mensaje'   => $e->getMessage()
                ];

                return $this->response->setJsonContent($resp);
            }

            return $this->response->setJsonContent($resp);
        }
    }
}