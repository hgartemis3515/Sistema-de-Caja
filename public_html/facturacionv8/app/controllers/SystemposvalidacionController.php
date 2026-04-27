<?php
class SystemposvalidacionController extends ControllerBase
{
	public function extraerDatosCarrito(array $jsonData, $usuario, $contribuyente): array {
        if (!isset($jsonData['carrito'])) {
            throw new \Exception('No se encontraron datos del carrito');
        }

        $carritoJson = $jsonData['carrito'];

        $carrito = [
            'id_carrito'        => $carritoJson['id_carrito'] ?? throw new \Exception('ID de carrito no encontrado'),
            'id_contribuyente'  => $carritoJson['id_contribuyente'] ?? throw new \Exception('ID de contribuyente no encontrado'),
            'id_usuario'        => $carritoJson['id_usuario'] ?? throw new \Exception('ID de usuario no encontrado'),
            'idsucursal'        => $carritoJson['idsucursal'] ?? throw new \Exception('ID de sucursal no encontrado'),
            'timestamp'         => $carritoJson['timestamp'] ?? throw new \Exception('Timestamp no encontrado'),
            'estado'            => $carritoJson['estado'] ?? throw new \Exception('Estado no encontrado'),
            'moneda'            => $carritoJson['moneda'] ?? throw new \Exception('Moneda no encontrada'),
            'tipo_cambio'       => $carritoJson['tipo_cambio'] ?? throw new \Exception('Tipo de cambio no encontrado'),
            'factor_igv'        => $carritoJson['factor_igv'] ?? throw new \Exception('Factor IGV no encontrado'),
            'tipo_descuento'    => $carritoJson['tipo_descuento'] ?? '',
            'valor_descuento'   => $carritoJson['valor_descuento'] ?? 0,
            'num_orden'         => $carritoJson['num_orden'] ?? '',
            'num_placa'         => $carritoJson['num_placa'] ?? '',
            'num_guia'          => $carritoJson['num_guia'] ?? '',
            'id_tipo_cpe'       => $carritoJson['id_tipodoc_electronico'] ?? throw new \Exception('Debes seleccionar un tipo de doucumento'),
            'id_carrito'        => $carritoJson['id_carrito'] ?? throw new \Exception('ID de carrito no encontrado'),
        ];

        if($carrito['valor_descuento'] < 0) {
            throw new \Exception('El valor del descuento no puede ser negativo');
        }

        if($carrito['valor_descuento'] > 0 && $carrito['tipo_descuento'] == '') {
            throw new \Exception('Debes seleccionar un tipo de descuento');
        }

        if($carrito['valor_descuento'] > 0 && $carrito['tipo_descuento'] != 'monto' && $carrito['tipo_descuento'] != 'porcentaje') {
            throw new \Exception('El tipo de descuento seleccionado no es válido');
        }

        if($carrito['tipo_descuento'] == 'porcentaje' && ($carrito['valor_descuento'] < 0 || $carrito['valor_descuento'] > 100)) {
            throw new \Exception('El valor del descuento por porcentaje debe estar entre 0 y 100');
        }
        
        if($carrito['id_contribuyente'] != $contribuyente->id_contribuyente) {
            throw new \Exception('El ID de contribuyente no coincide con el contribuyente del usuario');
        }

        if($carrito['id_usuario'] != $usuario->idusuario) {
            throw new \Exception('El ID de usuario no coincide con el usuario');
        }

        $carrito['id_usuario'] = intval($carrito['id_usuario']);

        $sucursal = Sucursal::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $contribuyente->id_contribuyente)));
        if(!$sucursal) {
            throw new \Exception('No se encontró la sucursal seleccionada');
        }

        $moneda = SunatMoneda::findFirst(array("id_codigomoneda = :id_codigomoneda:", 'bind' => array('id_codigomoneda' => $carrito['moneda'])));
        if(!$moneda) {
            throw new \Exception('No se encontró la moneda seleccionada');
        }

        if($carrito['tipo_cambio'] <= 0) {
            throw new \Exception('El tipo de cambio debe ser mayor a cero');
        }

        $valores_validos_factor_igv = $this->get_valores_validos_factor_igv();
        if (!in_array(intval($carrito['factor_igv']), $valores_validos_factor_igv)) {
            throw new \Exception('El Factor IGV Debería ser uno de los siguientes valores: '.implode(', ', $valores_validos_factor_igv));
        }
        
        if(empty($carrito['id_tipo_cpe'])) {
            throw new \Exception('El tipo de documento seleccionado no es válido');
        }

        $tipo_cpe = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $carrito['id_tipo_cpe'])));
        if(!$tipo_cpe) {
            throw new \Exception('El tipo de documento seleccionado no es válido');
        }
        

        $cliente = $this->extraerDatosCliente($carritoJson, $usuario, $contribuyente);
        $items = $this->extraerItems($carritoJson, $usuario, $contribuyente, $carrito['idsucursal']);
        $paymentSplits = $this->extraerPaymentSplits($carritoJson, $usuario, $contribuyente);

        return [
            'id_carrito'        => $carrito['id_carrito'],
            'id_contribuyente'  => $carrito['id_contribuyente'],
            'id_usuario'        => $carrito['id_usuario'],
            'idsucursal'        => $carrito['idsucursal'],
            'timestamp'         => $carrito['timestamp'],
            'tipo_descuento'    => $carrito['tipo_descuento'],
            'valor_descuento'   => $carrito['valor_descuento'],
            'estado'            => $carrito['estado'],
            'moneda'            => $carrito['moneda'],
            'tipo_cambio'       => $carrito['tipo_cambio'],
            'factor_igv'        => $carrito['factor_igv'],
            'num_orden'         => $carrito['num_orden'],
            'num_placa'         => $carrito['num_placa'],
            'num_guia'          => $carrito['num_guia'],
            'id_tipo_cpe'       => $carrito['id_tipo_cpe'],
            'cliente'           => $cliente,
            'items'             => $items,
            'payment_splits'    => $paymentSplits
        ];
    }

    public function extraerDatosCliente(array $carrito, $usuario, $contribuyente): array {
        if (!isset($carrito['cliente'])) {
            $clienteJson = [
                'idcliente'             => "",
                'id_tipodocidentidad'   => "1",
                'num_doc'               => "",
                'razon_social'          => "",
                'direccion'             => "",
                'id_codigoubigeo'       => ""
            ];
            return $clienteJson;
        }

        $cliente = $carrito['cliente'];

        $clienteJson = [
            'idcliente'             => $cliente['idcliente'] ?? throw new \Exception('ID de cliente no encontrado'),
            'id_tipodocidentidad'   => $cliente['id_tipodocidentidad'] ?? throw new \Exception('Tipo de documento no encontrado'),
            'num_doc'               => $cliente['num_doc'] ?? throw new \Exception('Número de documento no encontrado'),
            'razon_social'          => $cliente['razon_social'] ?? throw new \Exception('Razón social no encontrada'),
            'direccion'             => $cliente['direccion'] ?? "",
            'id_codigoubigeo'       => $cliente['id_codigoubigeo'] ?? ""
        ];

        $tipo_documento = SunatTipodocidentidad::findFirst(array("id_tipodocidentidad = :id_tipodocidentidad:", 'bind' => array('id_tipodocidentidad' => $clienteJson['id_tipodocidentidad'])));
        if(!$tipo_documento) {
            throw new \Exception('El Tipo de Documento para el Cliente no es válido');
        }

        if(isset($clienteJson['id_codigoubigeo']) && !empty($clienteJson['id_codigoubigeo'])) {
            $ubigeo = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $clienteJson['id_codigoubigeo'])));
            if(!$ubigeo) {
                throw new \Exception('El Código de Ubigeo para el Cliente no es válido');
            }
        }

        if($clienteJson['num_doc'] == "") {
            throw new \Exception('El número de documento del cliente no puede estar vacío');
        }

        if($clienteJson['razon_social'] == "") {
            throw new \Exception('La razón social del cliente no puede estar vacía');
        }

        $clienteJson['idcliente'] = intval($clienteJson['idcliente']);
        if($clienteJson['idcliente'] != 0) {
            $cliente = Cliente::findFirst(array("id_contribuyente = :id_contribuyente: and idcliente = :idcliente:", 'bind' => array('id_contribuyente' => $contribuyente->id_contribuyente, 'idcliente' => $clienteJson['idcliente'])));
            if(!$cliente) {
                throw new \Exception('El cliente seleccionado no existe o no está disponible');
            }
        }

        return $clienteJson;
    }

    public function extraerItems(array $carrito, $usuario, $contribuyente, $idsucursal): array {
        if (!isset($carrito['items']) || !is_array($carrito['items'])) {
            throw new \Exception('No se encontraron items en el carrito');
        }

        $items = [];
        $n = 0;
        foreach ($carrito['items'] as $itemJson) {
            $n++;
            $item = [
                'idproducto'            => $itemJson['idproducto'] ?? throw new \Exception('ID de producto no encontrado'),
                'codigo'                => $itemJson['codigo'] ?? throw new \Exception('Código de producto no encontrado'),
                'nombre'                => $itemJson['nombre'] ?? throw new \Exception('Nombre de producto no encontrado'),
                'cantidad'              => $itemJson['cantidad'] ?? throw new \Exception('Cantidad no encontrada'),
                'precio_venta'          => $itemJson['precio_venta'] ?? throw new \Exception('Precio de venta no encontrado'),
                'total'                 => $itemJson['total'] ?? throw new \Exception('Total no encontrado'),
                'id_unidad_medida'      => $itemJson['id_unidad_medida'] ?? throw new \Exception('Unidad de medida no encontrada'),
                'id_tipoafectacionigv'  => $itemJson['id_tipoafectacionigv'] ?? throw new \Exception('Tipo de afectación IGV no encontrado'),
                'tipo_unidad'           => $itemJson['tipo_unidad'] ?? throw new \Exception('Tipo de unidad no encontrado'),
                'id_presentacion'       => $itemJson['id_presentacion'] ?? throw new \Exception('Presentación no encontrada'), //verificar si cambia cuándo es presentación, o unidad_base
                'afecto_icbper'         => $itemJson['afecto_icbper'] ?? "no"
            ];

            $producto = Producto::findFirst(array("id_contribuyente = :id_contribuyente: and idsucursal = :idsucursal: and idproducto = :idproducto:", 'bind' => array('id_contribuyente' => $contribuyente->id_contribuyente, 'idsucursal' => $idsucursal, 'idproducto' => $item['idproducto'])));
            if(!$producto) {
                throw new \Exception("El item N° $n no se encontró en la base de datos");
            }

            if($item['tipo_unidad'] == 'unidad_base') {
                $unidad_medida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $item['id_unidad_medida'])));
                if(!$unidad_medida) {
                    throw new \Exception("El producto llamado ".$item['nombre']." no tiene una unidad de medida válida");
                }
            } else {
                $presentacion = ProductoPresentacion::findFirst(array("idproducto = :idproducto: and id_presentacion = :id_presentacion:", 'bind' => array('idproducto' => $item['idproducto'], 'id_presentacion' => $item['id_unidad_medida'])));
                if(!$presentacion) {
                    throw new \Exception("El producto llamado ".$item['nombre']." no tiene una presentación válida");
                }
            }

            $tipo_unidad_aceptado = array('unidad_base', 'presentacion');
            if(!in_array($item['tipo_unidad'], $tipo_unidad_aceptado)) {
                throw new \Exception("El tipo de unidad del producto ".$item['nombre']." no es válido");
            }

            $tipo_afectacion_igv = SunatTipoafectacionigv::findFirst(array("id_tipoafectacionigv = :id_tipoafectacionigv:", 'bind' => array('id_tipoafectacionigv' => $item['id_tipoafectacionigv'])));
            if(!$tipo_afectacion_igv) {
                throw new \Exception("El producto llamado ".$item['nombre']." no tiene un tipo de afectación IGV válido");
            }

            if($item['cantidad'] <= 0) {
                throw new \Exception("La cantidad del producto ".$item['nombre']." debe ser mayor a cero");
            }

            if($item['precio_venta'] <= 0) {
                throw new \Exception("El precio de venta del producto ".$item['nombre']." debe ser mayor a cero");
            }

            if($item['afecto_icbper'] != "si" && $item['afecto_icbper'] != "no") {
                throw new \Exception("El campo 'afecto_icbper' del producto ".$item['nombre']." no es válido");
            }
            
            $items[] = $item;
        }

        return $items;
    }

    public function extraerDatosAdicionales(array $jsonData, $usuario, $contribuyente): array {
        if (!isset($jsonData['datosAdicionales'])) {
            throw new \Exception('No se encontraron datos adicionales');
        }

        $adicionales = $jsonData['datosAdicionales'];

        $datosAdicionales = [
            'id_usuario'            => $adicionales['id_usuario'] ?? '',
            'id_contribuyente'      => $adicionales['id_contribuyente'] ?? '',
            'factor_igv_sunat'      => $adicionales['factor_igv_sunat'] ?? '',
            'impuesto_icbper'       => $adicionales['impuesto_icbper'] ?? '',
            'num_decimales'         => $adicionales['num_decimales'] ?? '',
            'tipo_cambio'           => $adicionales['tipo_cambio'] ?? '',
            'id_vendedor_asignado'  => $adicionales['id_vendedor_asignado'] ?? '',
        ];

        $vendedor = Usuario::findFirst(array("id_contribuyente = :id_contribuyente: and idusuario = :idusuario: and estado = 'activo'", 'bind' => array('idusuario' => $datosAdicionales['id_vendedor_asignado'], 'id_contribuyente' => $contribuyente->id_contribuyente)));
        if(!$vendedor) {
            throw new \Exception('El vendedor asignado no existe o no está disponible');
        }

        return $datosAdicionales;
    }

    public function extraerPaymentSplits(array $carrito, $usuario, $contribuyente): array {
        if (!isset($carrito['paymentSplits']) || !is_array($carrito['paymentSplits'])) {
            throw new \Exception('No se encontraron métodos de pago en el carrito');
        }
    
        $paymentSplits = [];
        $tipos_pago_validos = ['contado', 'credito', 'transferencia', 'tarjeta_credito'];
        $fecha_actual = date('Y-m-d');
    
        foreach ($carrito['paymentSplits'] as $split) {
            $bloque_id = ($split['id'] ?? -1) + 1; // Para mensajes de error
            
            // Validaciones comunes para todos los tipos
            if (!isset($split['tipo'])) {
                throw new \Exception("El método de pago del bloque {$bloque_id} no tiene un tipo definido");
            }
    
            if (!in_array($split['tipo'], $tipos_pago_validos)) {
                throw new \Exception("El tipo de pago '{$split['tipo']}' del bloque {$bloque_id} no es válido");
            }
    
            // Validar montoEfectivo
            if (!isset($split['montoEfectivo'])) {
                throw new \Exception("El bloque de pago {$bloque_id} no tiene un monto definido");
            }
    
            $monto = filter_var($split['montoEfectivo'], FILTER_VALIDATE_FLOAT);
            if ($monto === false || $monto <= 0) {
                throw new \Exception("El monto del bloque {$bloque_id} debe ser mayor a 0");
            }
    
            // Validar decimales
            if (strlen(substr(strrchr($monto, "."), 1)) > 2) {
                throw new \Exception("El monto del bloque {$bloque_id} no debe tener más de 2 decimales");
            }
    
            // Validar condicionPagoId
            if (!isset($split['condicionPagoId']) || empty($split['condicionPagoId'])) {
                throw new \Exception("El bloque de pago {$bloque_id} no tiene una condición de pago definida");
            }
    
            $condicionPago = Condiciondepago::findFirst([
                "id_contribuyente = :id_contribuyente: AND id_condicionpago = :id_condicionpago:",
                'bind' => [
                    'id_contribuyente' => $contribuyente->id_contribuyente,
                    'id_condicionpago' => intval($split['condicionPagoId'])
                ]
            ]);
    
            if (!$condicionPago) {
                throw new \Exception("La condición de pago del bloque {$bloque_id} no existe o no está disponible ".$split['condicionPagoId']." || ".$contribuyente->id_contribuyente);
            }
    
            // Validaciones específicas por tipo
            switch ($split['tipo']) {

                case 'credito':
                    $this->validarPagoCredito($split, $bloque_id, $fecha_actual);
                    break;
                    
                case 'transferencia':
                    $this->validarPagoTransferencia($split, $bloque_id, $contribuyente);
                    break;
                    
                case 'tarjeta_credito':
                    $this->validarPagoTarjeta($split, $bloque_id);
                    break;
            }
    
            // Construir array de retorno
            $paymentSplit = [
                'id'                => $split['id'],
                'tipo'              => $split['tipo'],
                'id_condicionpago'  => intval($split['condicionPagoId']),
                'total'             => $monto
            ];
    
            // Agregar campos adicionales según el tipo
            if ($split['tipo'] === 'credito') {
                $paymentSplit['fecha_vencimiento'] = $split['fechaVencimiento'];
            }

            if($split['tipo'] === 'contado') {
                $paymentSplit['cpago_nrooperacion'] = '';
                $paymentSplit['fechadeposito'] = '';
            }
    
            if ($split['tipo'] === 'transferencia') {
                $paymentSplit['idbanco'] = intval($split['bancoId']);
                if (isset($split['numOpe'])) $paymentSplit['cpago_nrooperacion'] = $split['numOpe'];
                if (isset($split['fechaTransf'])) $paymentSplit['fechadeposito'] = $split['fechaTransf'];
            }
    
            if ($split['tipo'] === 'tarjeta_credito') {
                if (isset($split['bancoId'])) $paymentSplit['idbanco'] = intval($split['bancoId']);
                if (isset($split['numOpe'])) $paymentSplit['cpago_nrooperacion'] = $split['numOpe'];
                if (isset($split['fechaTransf'])) $paymentSplit['fechadeposito'] = $split['fechaTransf'];
            }
    
            $paymentSplits[] = $paymentSplit;
        }
    
        return $paymentSplits;
    }
    
    private function validarPagoCredito(array $split, int $bloque_id, string $fecha_actual): void {
        if (!isset($split['fechaVencimiento']) || empty($split['fechaVencimiento'])) {
            throw new \Exception("El bloque de pago {$bloque_id} de tipo crédito debe incluir una fecha de vencimiento");
        }
    
        $fecha_vencimiento = DateTime::createFromFormat('Y-m-d', $split['fechaVencimiento']);
        if (!$fecha_vencimiento) {
            throw new \Exception("La fecha de vencimiento del bloque {$bloque_id} no tiene un formato válido (YYYY-MM-DD)");
        }
    
        if ($fecha_vencimiento->format('Y-m-d') <= $fecha_actual) {
            throw new \Exception("La fecha de vencimiento del bloque {$bloque_id} debe ser posterior a la fecha actual");
        }
    }
    
    private function validarPagoTransferencia(array $split, int $bloque_id, $contribuyente): void {
        if (!isset($split['bancoId']) || empty($split['bancoId'])) {
            throw new \Exception("El bloque de pago {$bloque_id} de tipo transferencia debe incluir un banco");
        }
    
        $banco = Cuentabanco::findFirst([
            "id_contribuyente = :id_contribuyente: AND id_cuentabanco = :id_cuentabanco:",
            'bind' => [
                'id_contribuyente' => $contribuyente->id_contribuyente,
                'id_cuentabanco' => intval($split['bancoId'])
            ]
        ]);
    
        if (!$banco) {
            throw new \Exception("El banco seleccionado en el bloque {$bloque_id} no existe o no está disponible");
        }
    
        if (isset($split['fechaTransf']) && !empty($split['fechaTransf'])) {
            $fecha_transf = DateTime::createFromFormat('Y-m-d', $split['fechaTransf']);
            if (!$fecha_transf) {
                throw new \Exception("La fecha de transferencia del bloque {$bloque_id} no tiene un formato válido (YYYY-MM-DD)");
            }
        }
    }
    
    private function validarPagoTarjeta(array $split, int $bloque_id): void {
        
    }

    public function getArrayItems($carrito, $contribuyente) {
        $idTipoAfectacionGravado = array(10); // Incluye IGV y suma al total de la venta
        $idTipoAfectacionGratuito = array(11, 12, 13, 14, 15, 16, 31, 32, 33, 34, 35, 36); // No incluyen IGV y no suman al total de la venta
        $idTipoAfectacionExonerado = array(20); // No incluye IGV pero suma al total de la venta
        $idTipoAfectacionInafecto = array(30); // No incluye IGV pero suma al total de la venta
        $idTipoAfectacionExportacion = array(40); // No incluye IGV pero suma al total de la venta

        $items = $carrito['items'];
        $lista_items = array();
        foreach($items as $item) {
            $producto = Producto::findFirst(array("id_contribuyente = :id_contribuyente: and idproducto = :idproducto:", 'bind' => array('id_contribuyente' => $contribuyente->id_contribuyente, 'idproducto' => $item['idproducto'])));
            if(!$producto) {
                throw new \Exception("El producto llamado ".$item['nombre']." no se encontró en la base de datos");
            }

            if($item['tipo_unidad'] == 'unidad_base') {
                $unidad_medida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $item['id_unidad_medida'])));
                if(!$unidad_medida) {
                    throw new \Exception("El producto llamado ".$item['nombre']." no tiene una unidad de medida válida");
                }

                $idunidadmedida = "UND-".$item['id_unidad_medida'];
                $unidadmedida_nombre = $unidad_medida->nombre;
                $tipo_unidad = 'unidad';
            } else {
                $presentacion = ProductoPresentacion::findFirst(array("idproducto = :idproducto: and id_presentacion = :id_presentacion:", 'bind' => array('idproducto' => $item['idproducto'], 'id_presentacion' => $item['id_unidad_medida'])));
                if(!$presentacion) {
                    throw new \Exception("El producto llamado ".$item['nombre']." no tiene una presentación válida");
                }

                $idunidadmedida = "PRE-".$item['id_unidad_medida'];
                $unidadmedida_nombre = $presentacion->nombre;
                $tipo_unidad = 'presentacion';
            }
            
            $afectacionIgv = SunatTipoafectacionigv::findFirst(array("id_tipoafectacionigv = :id_tipoafectacionigv:", 'bind' => array('id_tipoafectacionigv' => $item['id_tipoafectacionigv'])));
            if(!$afectacionIgv) {
                throw new \Exception("El producto llamado ".$item['nombre']." no tiene un tipo de afectación IGV válido");
            }

            $id_tipoafectacionigv = intval($item['id_tipoafectacionigv']);
            $precio = floatval($item['precio_venta']);
            $cantidad = floatval($item['cantidad']);

            $factor_igv = floatval($carrito['factor_igv'] / 100);
            $precio_sin_igv = $precio / (1 + $factor_igv);
            $importe = round($precio * $cantidad, 2);
            $subtotal = round($importe / (1 + $factor_igv), 2);
            $igv = round($importe - $subtotal, 2);

            if(!in_array($id_tipoafectacionigv, $idTipoAfectacionGravado)) {
                $precio_sin_igv = $precio;
                $importe = round($precio * $cantidad, 2);
                $subtotal = $importe;
                $igv = 0;
            }

            $subtotal_icbper = 0;
            if($item['afecto_icbper'] == 'si') {
                $anio_actual =  date("Y");
                $icbper = SunatIcbper::findFirst(array("anio = :anio:", 'bind' => array('anio' => $anio_actual)));
                if(!$icbper) {
                    $impuesto_icbper = '0.5';
                } else {
                    $impuesto_icbper = $icbper->monto;
                }

                $subtotal_icbper = round($cantidad * $impuesto_icbper, 2);
            }

            $item = array(
                "item_detraccion_codigo"    =>  "",
                "item_detraccion_porcentaje" =>  "",

                "afecto_icbper"             =>  $item['afecto_icbper'],
                "cantidad"                  =>  $cantidad,
                "codigo"                    =>  $item['codigo'],
                "descripcion"               =>  $item['nombre'],
                "id_cod_moneda"             =>  $carrito['moneda'],
                "id_tipoafectacionigv"      =>  $item['id_tipoafectacionigv'],
                "idarticulo"                =>  $item['idproducto'],
                "idpresentacion"            =>  ($item['tipo_unidad'] == 'presentacion') ? $item['id_unidad_medida'] : '',
                "idunidadmedida"            =>  $idunidadmedida,
                "tipo_unidad"               =>  $tipo_unidad,
                "unidadmedida"              =>  $unidadmedida_nombre,
                "precio"                    =>  $precio,
                "p_unit_sin_igv"            =>  $precio_sin_igv,
                "factor_igv_sunat"          =>  $factor_igv,
                "igv"                       =>  $igv,
                "importe"                   =>  $importe,
                "subtotal"                  =>  $subtotal,
                "subtotal_icbper"           =>  $subtotal_icbper,
            );

            //En el descuento por item, podríamos simplemente guardar el monto de descuento por item en un campo aparte, y el resto de datos tratarlos de la misma forma que los otros items. (y sería un descuento al precio del item, aunque creo que en esta parte se debe hacer el descuento al importe)

            $lista_items[] = $item;
        }

        return $lista_items;
    }

    public function getTotalesCpe($carrito, $items) {
        try {
            $idTipoAfectacionGravado = array(10); // Incluye IGV y suma al total de la venta
            $idTipoAfectacionGratuito = array(11, 12, 13, 14, 15, 16, 31, 32, 33, 34, 35, 36); // No incluyen IGV y no suman al total de la venta
            $idTipoAfectacionExonerado = array(20); // No incluye IGV pero suma al total de la venta
            $idTipoAfectacionInafecto = array(30); // No incluye IGV pero suma al total de la venta
            $idTipoAfectacionExportacion = array(40); // No incluye IGV pero suma al total de la venta

            $factor_igv = floatval($carrito['factor_igv']) / 100;

            $descuento_comprobante = 0;
            $descuento_porcentaje = 0;
            $descuento_total = 0;

            $total_importe_gravado = 0;
            $total_importe_exonerado = 0;
            $total_importe_gratuito = 0;
            $total_importe_inafecto = 0;
            $total_importe_exportacion = 0;
            $subtotal_ventas = 0;

            $total_icbper = 0;

            foreach($items as $item) {
                if(in_array($item['id_tipoafectacionigv'], $idTipoAfectacionGravado)) {
                    $subtotal_ventas += floatval($item['importe']);
                    $total_importe_gravado += floatval($item['importe']);
                } else if(in_array($item['id_tipoafectacionigv'], $idTipoAfectacionExonerado)) {
                    $subtotal_ventas += floatval($item['importe']);
                    $total_importe_exonerado += floatval($item['importe']);
                } else if(in_array($item['id_tipoafectacionigv'], $idTipoAfectacionInafecto)) {
                    $subtotal_ventas += floatval($item['importe']);
                    $total_importe_inafecto += floatval($item['importe']);
                } else if(in_array($item['id_tipoafectacionigv'], $idTipoAfectacionExportacion)) {
                    $subtotal_ventas += floatval($item['importe']);
                    $total_importe_exportacion += floatval($item['importe']);
                } else if(in_array($item['id_tipoafectacionigv'], $idTipoAfectacionGratuito)) {
                    $total_importe_gratuito += floatval($item['importe']);
                }

                $total_icbper += floatval($item['subtotal_icbper']);
            }

            $dataDescuento = $this->getDataDescuento($factor_igv, $carrito['tipo_descuento'], $carrito['valor_descuento'], $subtotal_ventas);
            $descuento_factor = $dataDescuento['descuento_factor'];
            $descuento_monto = $dataDescuento['descuento_monto'];

            $monto_gravado = $total_importe_gravado/(1 + $factor_igv);
            $total_igv = $total_importe_gravado - $monto_gravado;
            
            $monto_inafecto = $total_importe_inafecto;
            $monto_exonerado = $total_importe_exonerado;
            $monto_gratuito = $total_importe_gratuito;
            $monto_exportacion = $total_importe_exportacion;

            $descuento_monto_sin_igv= 0;
            $descuento_monto_inc_igv = 0;

            if($descuento_factor > 0) {
                $descuento_base_gravado = ($monto_gravado*$descuento_factor)/100;
                $descuento_base_inafecto = ($monto_inafecto*$descuento_factor)/100;
                $descuento_base_exonerado = ($monto_exonerado*$descuento_factor)/100;
                $descuento_base_exportacion = ($monto_exportacion*$descuento_factor)/100;

                $monto_gravado = $monto_gravado - $descuento_base_gravado;
                $monto_inafecto = $monto_inafecto - $descuento_base_inafecto;
                $monto_exonerado = $monto_exonerado - $descuento_base_exonerado;
                $monto_exportacion = $monto_exportacion - $descuento_base_exportacion;

                $total_igv = round($monto_gravado * $factor_igv, 2);

                $descuento_monto_sin_igv = $descuento_base_gravado + $descuento_base_inafecto + $descuento_base_exonerado + $descuento_base_exportacion;
            }
            
            $total_otros_cargos = isset($carrito['otros_cargos'])?floatval($carrito['otros_cargos']):0;
            $total_a_pagar = $monto_gravado + $monto_inafecto + $monto_exonerado + $monto_exportacion + $total_otros_cargos + $total_icbper + $total_igv;

            $convertir_numero_letras = new NumeroALetras();
            $total_a_pagar_letras = ($carrito['moneda'] == 'USD')?$convertir_numero_letras->convert(floatval($total_a_pagar) , 'dólares'):$convertir_numero_letras->convert(floatval($total_a_pagar) , 'soles');
            
            return [
                'subtotal_ventas'               => round($subtotal_ventas, 2),
                'monto_gravado'                 => round($monto_gravado, 2),
                'monto_inafecto'                => round($monto_inafecto, 2),
                'monto_exonerado'               => round($monto_exonerado, 2),
                'monto_gratuito'                => round($monto_gratuito, 2),
                'monto_exportacion'             => round($monto_exportacion, 2),
                'total_igv'                     => round($total_igv, 2),
                'total_otros_cargos'            => round($total_otros_cargos, 2),
                'total_icbper'                  => round($total_icbper, 2),
                'total_a_pagar'                 => round($total_a_pagar, 2),
                'descuento_monto_inc_igv'       => round($descuento_monto, 2),
                'descuento_monto_sin_igv'       => round($descuento_monto_sin_igv, 2),
                'descuento_factor'              => round($descuento_factor, 5),
                'total_a_pagar_letras'          => $total_a_pagar_letras
            ];
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getDataDescuento($factor_igv, $tipo, $valor, $subtotal_ventas) {
        $descuento_factor = 0;
        $descuento_monto = 0;
        $valor = floatval($valor);
        $subtotal_ventas = floatval($subtotal_ventas);

        if($tipo == 'porcentaje') {
            if($valor > 0) {
                $descuento_factor = $valor;
                $descuento_monto = round($subtotal_ventas * ($descuento_factor / 100), 2);   
            }
        } else if($tipo == 'monto') {
            if($valor > 0) {
                $descuento_factor = round(($valor / $subtotal_ventas) * 100, 5);
            }
        }

        return [
            'descuento_factor' => $descuento_factor,
            'descuento_monto' => $descuento_monto
        ];
    }

    public function organizarPago(array $payment_splits, $contribuyente): array {
        $result = [
            'tipo_pago' => 'unico',
            'total_transferencia' => 0,
            'total_contado' => 0,
            'total_tarjeta_credito' => 0,
            'total_credito' => 0,
            'data_cuotas' => [],
            'total_pagos' => 0,
            'payment_splits_pagado' => [],
        ];

        // Agrupar pagos por tipo
        $pagos_por_tipo = [];
        foreach ($payment_splits as $payment) {
            $pagos_por_tipo[$payment['tipo']][] = $payment;
        }

        // Calcular totales por tipo
        $payment_splits_pagado = array();
        foreach ($payment_splits as $payment) {
            switch ($payment['tipo']) {
                case 'transferencia':
                    $payment_splits_pagado[] = $payment;
                    $result['total_transferencia'] += $payment['total'];
                    break;
                case 'contado':
                    $payment_splits_pagado[] = $payment;
                    $result['total_contado'] += $payment['total'];
                    break;
                case 'tarjeta_credito':
                    $payment_splits_pagado[] = $payment;
                    $result['total_tarjeta_credito'] += $payment['total'];
                    break;
                case 'credito':
                    $result['total_credito'] += $payment['total'];
                    break;
            }
        }

        $result['payment_splits_pagado'] = $payment_splits_pagado;

        // Determinar si el pago es dividido o único
        $tipos_de_pago_usados = array_filter(
            ['transferencia', 'contado', 'tarjeta_credito'],
            fn($tipo) => isset($pagos_por_tipo[$tipo]) && count($pagos_por_tipo[$tipo]) > 0
        );

        if (count($tipos_de_pago_usados) > 1 || 
            (count($tipos_de_pago_usados) === 1 && count($pagos_por_tipo[reset($tipos_de_pago_usados)]) > 1)) {
            $result['tipo_pago'] = 'dividido';
        }

        // Procesar pagos a crédito si existen
        $result['data_cuotas'] = array();
        if (isset($pagos_por_tipo['credito'])) {
            $cuotas = $pagos_por_tipo['credito'];
            
            // Validar fechas futuras
            $fecha_actual = new DateTime();
            foreach ($cuotas as $cuota) {
                $fecha_vencimiento = new DateTime($cuota['fecha_vencimiento']);
                if ($fecha_vencimiento <= $fecha_actual) {
                    throw new Exception("La fecha de vencimiento debe ser futura: " . $cuota['fecha_vencimiento']);
                }
            }

            // Verificar fechas duplicadas
            $fechas_vencimiento = array_map(fn($cuota) => $cuota['fecha_vencimiento'], $cuotas);
            if (count($fechas_vencimiento) !== count(array_unique($fechas_vencimiento))) {
                throw new Exception("No se permiten fechas de vencimiento duplicadas");
            }

            // Ordenar cuotas por fecha de vencimiento (más próxima primero)
            usort($cuotas, function($a, $b) {
                return strtotime($a['fecha_vencimiento']) - strtotime($b['fecha_vencimiento']);
            });

            // Formatear cuotas
            $array_cuotas = array();
            foreach ($cuotas as $index => $cuota) {
                $fecha_formato = DateTime::createFromFormat('Y-m-d', $cuota['fecha_vencimiento'])
                    ->format('d/m/Y');
                
                $array_cuotas[] = [
                    'id_cuota' => $index + 1,
                    'monto_cuota' => $cuota['total'],
                    'id_condicionpago' => $cuota['id_condicionpago'],
                    'vencimiento_cuota' => $fecha_formato
                ];
            }

            $result['data_cuotas'] = array(
				"num_cuotas"			=> count($array_cuotas),
				"monto_total_cuotas"	=> $result['total_credito'],
				"cuotas"				=> $array_cuotas
			);
        }

        $result['num_cuotas'] = count($result['data_cuotas']);

        $total_pagos = $result['total_transferencia'] + $result['total_contado'] + $result['total_tarjeta_credito'] + $result['total_credito'];
        $result['total_pagos'] = $total_pagos;

        // Manejar modalidad seleccionada según tipo de pago
        if ($result['tipo_pago'] == 'unico') {
            // Para pago único, usar el primer pago del array
            $primer_pago = reset($payment_splits);
            $result['modalidad_seleccionada'] = [
                'id_condicionpago' => $primer_pago['id_condicionpago'] ?? null,
                'idbanco' => $primer_pago['idbanco'] ?? null,
                'tipo' => $primer_pago['tipo'] ?? null,
                'cpago_nrooperacion' => $primer_pago['cpago_nrooperacion'] ?? null,
                'fechadeposito' => $primer_pago['fechadeposito'] ?? null,
                'monto' => $primer_pago['total'] ?? null
            ];
        } else {
            // Para pago dividido
            if (isset($pagos_por_tipo['contado']) && !empty($pagos_por_tipo['contado'])) {
                // Si existe pago al contado, usar el primero
                $pago_contado = reset($pagos_por_tipo['contado']);
                $result['modalidad_seleccionada'] = [
                    'id_condicionpago' => $pago_contado['id_condicionpago'] ?? null,
                    'idbanco' => $pago_contado['idbanco'] ?? null,
                    'tipo' => 'contado',
                    'cpago_nrooperacion' => $pago_contado['cpago_nrooperacion'] ?? null,
                    'fechadeposito' => $pago_contado['fechadeposito'] ?? null,
                    'monto' => $total_pagos // Usar el total de todos los pagos
                ];
            } else {
                // Buscar condición de pago al contado en la base de datos
                //filstrar por id_contribuyente y tipo = contado
                $condicion_contado = Condiciondepago::findFirst([
                    "id_contribuyente = :id_contribuyente: AND tipo = 'contado' AND estado = 'activo'",
                    'bind' => ['id_contribuyente' => $contribuyente->id_contribuyente]
                ]);

                if (!$condicion_contado) {
                    // Crear nueva condición de pago al contado
                    $condicion_contado = new Condiciondepago();
                    $condicion_contado->id_contribuyente = $contribuyente->id_contribuyente;
                    $condicion_contado->tipo = 'contado';
                    $condicion_contado->estado = 'activo';
                    $condicion_contado->condicionpago = 'Al Contado';
                    try {
                        $condicion_contado->save();
                    } catch (\Exception $e) {
                        throw new \Exception("No se pudo crear la condición de pago al contado");
                    }

                    $id_condicionpago = $condicion_contado->id_condicionpago;
                } else {
                    $id_condicionpago = $condicion_contado->id_condicionpago;
                }

                $result['modalidad_seleccionada'] = [
                    'id_condicionpago' => $id_condicionpago,
                    'idbanco' => null,
                    'tipo' => 'contado',
                    'cpago_nrooperacion' => null,
                    'fechadeposito' => null,
                    'monto' => $total_pagos // Usar el total de todos los pagos
                ];
            }
        }

        return $result;
    }

    public function generar_datapost($carrito, $datos_adicionales, $contribuyente) {
        $tipo_operacion_docelectronico = '0101';

        $anio_actual =  date("Y");
        $icbper = SunatIcbper::findFirst(array("anio = :anio:", 'bind' => array('anio' => $anio_actual)));
        if(!$icbper) {
            $impuesto_icbper = '0.5';
        } else {
            $impuesto_icbper = $icbper->monto;
		}

        $cliente = $carrito['cliente'];
        $items = $this->getArrayItems($carrito, $contribuyente);
        $totales = $this->getTotalesCpe($carrito, $items);
        $modalidad_pago = $this->organizarPago($carrito['payment_splits'], $contribuyente);

        if($totales['total_a_pagar'] != $modalidad_pago['total_pagos']) {
            throw new \Exception("Verificamos que la suma de los pagos no es igual al total a pagar. (Total a Pagar: ".$totales['total_a_pagar']." - Total Pagado: ".$modalidad_pago['total_pagos'].")");
        }

        $fecha_deposito_transferencia = isset($modalidad_pago['modalidad_seleccionada']['fechadeposito']) 
        ? (function($fecha) {
            try {
                $date = new DateTime($fecha);
                return $date->format('d/m/Y'); // Formato día/mes/año
            } catch (Exception $e) {
                return ''; // Retorna vacío si la fecha no es válida
            }
        })($modalidad_pago['modalidad_seleccionada']['fechadeposito'])
        : '';

        $id_condicionpago = round(floatval($modalidad_pago['total_pagos']) - floatval($modalidad_pago['total_credito']), 2) <= 0 ? 0 : $modalidad_pago['modalidad_seleccionada']['id_condicionpago'];

        $datapost = array(
			//cabecera del documento
			"tipo_operacion_docelectronico" 	=> $tipo_operacion_docelectronico,
			"select_tipo_doc_electronico" 		=> $carrito['id_tipo_cpe'], //{03" => "boleta, 01" => "factura}
			"codmoneda_comprobante" 			=> $carrito['moneda'], //{PEN, USD}
			"select_sucursal" 					=> $carrito['idsucursal'], //debe existir en la base de datos
			"serie_comprobante" 				=> "-",
			"numero_comprobante" 				=> "-",
			"fecha_comprobante" 				=> date('d/m/Y'), // formato" => "dia/mes/año
			"fecha_vence_comprobante" 			=> date('d/m/Y'),
			"tipo_cambio_comprobante" 			=> $carrito['tipo_cambio'],
			"nro_placa_vehiculo" 				=> $carrito['num_placa'],
			"nro_orden" 						=> $carrito['num_orden'],
			"guia_remision_manual" 				=> $carrito['num_guia'],
			"select_usuario_vendedor" 			=> $datos_adicionales['id_vendedor_asignado'],
			"modalidad_envio_sunat" 			=> !isset($contribuyente->tipo_envio)?'':$contribuyente->tipo_envio,
			"doc_impuesto_icbper" 				=> $impuesto_icbper,

			//"enviar_a_sunat"					=> !isset($cabecera->enviar_a_sunat)?'si':$cabecera->enviar_a_sunat,

			//Data del Cliente
			"id_cliente_documento" 				=> isset($cliente['idcliente']) ? $cliente['idcliente'] : 0,
			"cliente_tipo_docidentidad" 		=> isset($cliente['id_tipodocidentidad']) ? $cliente['id_tipodocidentidad'] : '',
			"cliente_numerodocumento" 			=> isset($cliente['num_doc']) ? $cliente['num_doc'] : '',
			"cliente_nombre" 					=> isset($cliente['razon_social']) ? $cliente['razon_social'] : '',
			"cliente_direccion" 				=> isset($cliente['direccion']) ? $cliente['direccion'] : '',
            'select_codigoubigeo'               => isset($cliente['id_codigoubigeo']) ? $cliente['id_codigoubigeo'] : '',
			"numero_celular" 					=> '',
			"cliente_api_foto" 					=> '',
			"cliente_api_fecha_nac" 			=> '',
			"cliente_api_sexo" 					=> '',
			"cliente_email" 					=> isset($cliente['email']) ? $cliente['email'] : '',
			"opcion_envio_email" 				=> (isset($cliente['email']) && !empty($cliente['email'])) ? 'si' : 'no', 

			//cabecera del documento datos debajo del detalle
			"opcion_tipo_venta" 				=> ($modalidad_pago['num_cuotas'] > 0) ? 'credito' : 'contado',
			"confirmacion" 						=> 'si',
			"fecha_pago_comprobante" 			=> ($modalidad_pago['num_cuotas'] > 0) ? $modalidad_pago['data_cuotas']['cuotas'][0]['vencimiento_cuota'] : null,
			"txt_monto_adeudado" 				=> ($modalidad_pago['num_cuotas'] > 0) ? $modalidad_pago['total_credito'] : 0,
			"data_cuotas" 						=> ($modalidad_pago['num_cuotas'] > 0) ? json_encode($modalidad_pago['data_cuotas']) : null,
			"txt_pago_parcial" 					=> ($modalidad_pago['num_cuotas'] > 0) ? round(floatval($modalidad_pago['total_pagos']) - floatval($modalidad_pago['total_credito']), 2) : $modalidad_pago['total_pagos'],
			"condicionpago_comprobante" 		=> $id_condicionpago,
			"txt_numero_operacion" 				=> isset($modalidad_pago['modalidad_seleccionada']['cpago_nrooperacion']) ? $modalidad_pago['modalidad_seleccionada']['cpago_nrooperacion'] : '',
            "fecha_deposito" 					=> $fecha_deposito_transferencia,
            "select_cuenta_banco_deposito"      => isset($modalidad_pago['modalidad_seleccionada']['idbanco']) ? $modalidad_pago['modalidad_seleccionada']['idbanco'] : '',
			"observacion_documento" 			=> "",
			"tipo_docelectronico_modificar" 	=> "",
			"id_motivo_nota_credito" 			=> "",
			"id_motivo_nota_debito" 			=> "",

            "payment_splits"                    => $modalidad_pago['payment_splits_pagado'], // Pagos realizados
            "paymet_splits_tipo"                => $modalidad_pago['tipo_pago'],
		
			//opción de descuento 
			"opcion_tipo_descuento" 			=> ($totales['descuento_factor'] > 0)?'on':'',
			"txt_descuento_total" 				=> $totales['descuento_monto_sin_igv'],
			"txt_descuento_porcentaje" 			=> $totales['descuento_factor'],

			//totales documento
			"txt_sub_total_ventas" 				=> $totales['subtotal_ventas'],
			"txt_gravada_comprobante" 			=> $totales['monto_gravado'],
			"txt_exonerada_comprobante" 		=> $totales['monto_exonerado'],
			"txt_inafecta_comprobante" 			=> $totales['monto_inafecto'],
			"txt_exportacion_comprobante" 		=> $totales['monto_exportacion'],
			"txt_descuento_comprobante" 		=> $totales['descuento_monto_sin_igv'],
			"txt_igv_comprobante" 				=> $totales['total_igv'],
			"txt_gratuita_comprobante" 			=> $totales['monto_gratuito'],
			"txt_icbper_comprobante" 			=> $totales['total_icbper'],
			"txt_otros_cargos_comprobante" 		=> $totales['total_otros_cargos'],
			"txt_total_comprobante" 			=> $totales['total_a_pagar'],
			"txt_total_letras" 					=> $totales['total_a_pagar_letras'],
			"detalle" 							=> json_encode($items),

			//data extra no utilizada para facturas y boletas
			"total_recibido" 						=> "",
			"txt_otros_cargos_comprobante_input" 	=> 0,
			"tipo_percepcion" 						=> "",
			"monto_base_percepcion" 				=> "",
			"porcentaje_percepcion" 				=> "",
			"monto_percepcion" 						=> "",

			//Data de la detracción
			"detraccion_tipo_pago" 					=> '',
			"detraccion_id_numero_cuenta" 			=> '',
			"detraccion_codigo_bien" 				=> '',
			"porcentaje_detraccion" 				=> '',
			"monto_dolares_detraccion"				=> '',
			"monto_detraccion" 						=> '',
			"texto_detraccion" 						=> '',

			//data para la retención
			"select_aplica_retencion"				=> 'no',
			"porcentaje_retencion"					=> '',
			"base_imponible_retencion"				=> '',
			"monto_retencion"						=> '',

			"doc_guardado_id_tipodoc_electronico" 	=> "",
			"doc_guardado_serie_comprobante"		=> "",
			"doc_guardado_numero_comprobante" 		=> "",
			"tipo_doc_guardado" 					=> "",
			"numero_doc_guardado" 					=> "",
			"serie_doc_guardado" 					=> "",
			"doc_modifica_id_tipodoc_electronico" 	=> "",
			"doc_modifica_serie_comprobante" 		=> "",
			"doc_modifica_numero_comprobante" 		=> "",
			"flexdatalist-cliente_numerodocumento" 	=> "",
			"flexdatalist-cliente_nombre" 			=> "",

			"select_igv_sunat"						=> $carrito['factor_igv'],
			"origen_data"							=> isset($data['origen_data'])?$data['origen_data']:'POS',
		);
        
        return $datapost;
    }
}
?>