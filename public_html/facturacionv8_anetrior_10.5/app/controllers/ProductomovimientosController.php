<?php
require $_SERVER["DOCUMENT_ROOT"]."/facturacionv8/apis/snappypdf/vendor/autoload.php";
require $_SERVER["DOCUMENT_ROOT"]."/facturacionv8/apis/qrlib/vendor/autoload.php";
use Knp\Snappy\Pdf;
class ProductomovimientosController extends ControllerBase
{
    public function procesar_movimiento($usuario, $data) {
        
    }

    //ESTA FUNCIÓN SIEMBRE DEBE LLAMAR UTILIZAR THIS->BD->BEGIN(), THIS->BD->ROOLLBACK(), THIS->BD->COMMIT()
    public function registrar_movimiento($usuario, $data) {
        $kardex = new KardexController;

        $resp_validar_cabecera = $this->validar_cabecera_movimiento($data);
        if($resp_validar_cabecera['respuesta'] == 'error') {
            return $resp_validar_cabecera;
        }

        $data = $resp_validar_cabecera['data'];

        $resp_validar_detalle = $this->validar_detalle_movimiento($data);
        if($resp_validar_detalle['respuesta'] == 'error') {
            return $resp_validar_detalle;
        }

        $detalle_movimiento = $resp_validar_detalle['lista_items'];

        $idusuario = $usuario->idusuario;

        $id_contribuyente_origen = $data['id_contribuyente'];
        $id_sucursal_origen = $data['id_sucursal'];

        $id_contribuyente_destino = $data['destino_id_contribuyente'];
        $id_sucursal_destino = $data['destino_id_sucursal'];

        $fecha_actual =  date('Y-m-d H:i:s');
        $producto_movimiento = new ProductoMovimiento();
        $producto_movimiento->id_contribuyente = $id_contribuyente_origen;
        $producto_movimiento->id_tipo_movimiento = $data['id_tipo_movimiento'];
        $producto_movimiento->serie = $data['serie'];
        $producto_movimiento->correlativo = $data['correlativo'];
        $producto_movimiento->tipo_envio_sunat = $data['tipo_envio_sunat'];
        $producto_movimiento->tipo = $data['tipo'];
        $producto_movimiento->id_sucursal = $id_sucursal_origen;
        $producto_movimiento->fecha_movimiento = $fecha_actual;
        $producto_movimiento->id_usuario = $data['id_usuario'];
        $producto_movimiento->nota = $data['nota'];
        $producto_movimiento->estado = 'activo';
        $producto_movimiento->destino_id_sucursal = $id_sucursal_destino;
        $producto_movimiento->destino_id_contribuyente = $id_contribuyente_destino;

        try {
            if(!$producto_movimiento->save()) {
                $msg = '';
                foreach ($producto_movimiento->getMessages() as $message) {
                    $msg = $msg.$message."</br>\n";
                }
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No es posible registrar el movimiento!';
                return $resp;
            }
        } catch (Exception $e) {
            $this->saveLogger($e);
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'No es posible registrar el movimiento! '.$e->getMessage();
            return $resp;
        }
    
        $n = 0;
        foreach($detalle_movimiento as $item) {
            $n++;
            $item = (object)$item;
            
            $detalle_movimiento = new ProductoMovimientoDet();
            $detalle_movimiento->id_contribuyente = $producto_movimiento->id_contribuyente;
            $detalle_movimiento->id_tipo_movimiento = $producto_movimiento->id_tipo_movimiento;
            $detalle_movimiento->serie = $producto_movimiento->serie;
            $detalle_movimiento->correlativo = $producto_movimiento->correlativo;
            $detalle_movimiento->tipo_envio_sunat = $producto_movimiento->tipo_envio_sunat;

            $detalle_movimiento->item = $n;
            $detalle_movimiento->cantidad = $item->cantidad;
            $detalle_movimiento->costo_unitario = $item->costo_unitario;
            $detalle_movimiento->id_codigomoneda = $item->id_codigomoneda;

            $detalle_movimiento->o_id_u_medida = $item->o_id_u_medida;
            $detalle_movimiento->o_u_medida = $item->o_u_medida;
            $detalle_movimiento->o_precio = $item->o_precio;
            $detalle_movimiento->o_id_afectigv = $item->o_id_afectigv;
            $detalle_movimiento->o_tipo_unidad = $item->o_tipo_unidad;
            $detalle_movimiento->o_id_presentacion = $item->o_id_presentacion;
            $detalle_movimiento->o_cod_prod = $item->o_cod_prod;
            $detalle_movimiento->o_id_prod = $item->o_id_prod;
            $detalle_movimiento->o_nom_prod = $item->o_nom_prod;

            $detalle_movimiento->d_id_u_medida = $item->d_id_u_medida;
            $detalle_movimiento->d_u_medida = $item->d_u_medida;
            $detalle_movimiento->d_precio = $item->d_precio;
            $detalle_movimiento->d_id_afectigv = $item->d_id_afectigv;
            $detalle_movimiento->d_tipo_unidad = $item->d_tipo_unidad;
            $detalle_movimiento->d_id_presentacion = $item->d_id_presentacion;
            $detalle_movimiento->d_cod_prod = $item->d_cod_prod;
            $detalle_movimiento->d_id_prod = $item->d_id_prod;
            $detalle_movimiento->d_nom_prod = $item->d_nom_prod;

            try {
                if(!$detalle_movimiento->save()) {
                    $msg = '';
                    foreach ($detalle_movimiento->getMessages() as $message) {
                        $msg = $msg.$message."</br>\n";
                    }
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'Obtenemos el siguiente error: '.$msg;
                    return $resp;
                }
            } catch (Exception $e) {
                $this->saveLogger($e);
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No es posible registrar el detalle del movimiento! '.$e->getMessage();
                return $resp;
            }
            
            if($producto_movimiento->id_tipo_movimiento == 'in') {
                //=====================================
                //INGRESO SIN DOCUMENTO (AUMENTA STOCK)
                //=====================================

                $producto_origen = Producto::findFirst(array("id_contribuyente = :id_contribuyente: and idproducto = :idproducto: and estado = 'activo' and idsucursal = :idsucursal:", 'bind' => array('id_contribuyente' => $producto_movimiento->id_contribuyente, 'idproducto' => $item->o_id_prod, 'idsucursal' => $id_sucursal_origen)));
                if(!$producto_origen) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'No existe el producto con ID: '.$item->o_id_prod;
                    return $resp;
                }

                /*Cremos un Registro en el Kardex*/
                $data_kardex['id_contribuyente'] = $producto_origen->id_contribuyente;
                $data_kardex['idsucursal'] = $producto_origen->idsucursal;
                $data_kardex['idproducto'] = $producto_origen->idproducto;
                $data_kardex['idusuario'] = $idusuario;
                $data_kardex['tipo_envio_sunat'] = $producto_movimiento->tipo_envio_sunat; //prueba, produccion
                $data_kardex['tipo_kardex'] = isset($item->tipo_kardex)?($item->tipo_kardex == 'inventario_inicial'?'inventario_inicial':'ingreso_sindoc'):'ingreso_sindoc'; //inventario_inicial, venta, compra, ingreso_sindoc, salida_sindoc	
                $data_kardex['estado_kardex'] = 'activo'; //activo, anulado
                $data_kardex['cantidad_entrada'] = $detalle_movimiento->cantidad;
                $data_kardex['costo_unitario'] = round($detalle_movimiento->costo_unitario, 2);
                $data_kardex['detalle'] = empty($producto_movimiento->nota)?'Ingreso Productos Sin DOC.':$producto_movimiento->nota;
                $data_kardex['fecha_registro'] = $fecha_actual;

                $data_kardex['docref_id_contribuyente'] = $producto_movimiento->id_contribuyente;
                $data_kardex['docref_id_tipodoc_electronico'] = $producto_movimiento->id_tipo_movimiento;
                $data_kardex['docref_serie_comprobante'] = $producto_movimiento->serie;
                $data_kardex['docref_numero_comprobante'] = $producto_movimiento->correlativo;
                $data_kardex['docref_tipo_envio_sunat'] = $producto_movimiento->tipo_envio_sunat;
                
                $resp_kardex = $kardex->registrar_en_kardex($data_kardex);
                if($resp_kardex['respuesta'] == 'error') {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error Kardex';
                    $resp['mensaje'] = 'No se logró guardar el movimiento en el kardex';
                    $resp['resp_kardex'] = $resp_kardex;
                    return $resp;
                }

                $resp['resp_kardex'] = $resp_kardex;

            } else if($producto_movimiento->id_tipo_movimiento == 'sa') {
                //=====================================
                //SALIDA SIN DOCUMENTO (DISMINUYE STOCK)
                //=====================================
                $producto_origen = Producto::findFirst(array("id_contribuyente = :id_contribuyente: and idproducto = :idproducto: and estado = 'activo' and idsucursal = :idsucursal:", 'bind' => array('id_contribuyente' => $producto_movimiento->id_contribuyente, 'idproducto' => $item->o_id_prod, 'idsucursal' => $id_sucursal_origen)));
                if(!$producto_origen) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'No existe el producto con ID: '.$item->o_id_prod;
                    return $resp;
                }

                /*Cremos un Registro en el Kardex*/
                $data_kardex['id_contribuyente'] = $producto_origen->id_contribuyente;
                $data_kardex['idsucursal'] = $producto_origen->idsucursal;
                $data_kardex['idproducto'] = $producto_origen->idproducto;
                $data_kardex['idusuario'] = $idusuario;
                $data_kardex['tipo_envio_sunat'] = $producto_movimiento->tipo_envio_sunat; //prueba, produccion
                $data_kardex['tipo_kardex'] = 'salida_sindoc'; //inventario_inicial, venta, compra, ingreso_sindoc, salida_sindoc	
                $data_kardex['estado_kardex'] = 'activo'; //activo, anulado
                $data_kardex['cantidad_salida'] = $detalle_movimiento->cantidad;
                $data_kardex['detalle'] = empty($producto_movimiento->nota)?'Ingreso Productos Sin DOC.':$producto_movimiento->nota;
                $data_kardex['fecha_registro'] = $fecha_actual;

                $data_kardex['docref_id_contribuyente'] = $producto_movimiento->id_contribuyente;
                $data_kardex['docref_id_tipodoc_electronico'] = $producto_movimiento->id_tipo_movimiento;
                $data_kardex['docref_serie_comprobante'] = $producto_movimiento->serie;
                $data_kardex['docref_numero_comprobante'] = $producto_movimiento->correlativo;
                $data_kardex['docref_tipo_envio_sunat'] = $producto_movimiento->tipo_envio_sunat;
                
                $resp_kardex = $kardex->registrar_en_kardex($data_kardex);
                if($resp_kardex['respuesta'] == 'error') {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error Kardex';
                    $resp['mensaje'] = 'No se logró guardar el movimiento en el kardex';
                    $resp['resp_kardex'] = $resp_kardex;
                    return $resp;
                }

                $resp['resp_kardex'] = $resp_kardex;

            } else if($producto_movimiento->id_tipo_movimiento == 'ts') {
                //==================================
                //registrar en el kardex una salida
                //==================================
                $producto_origen = Producto::findFirst(array("id_contribuyente = :id_contribuyente: and idproducto = :idproducto: and estado = 'activo' and idsucursal = :idsucursal:", 'bind' => array('id_contribuyente' => $producto_movimiento->id_contribuyente, 'idproducto' => $item->o_id_prod, 'idsucursal' => $id_sucursal_origen)));
                if(!$producto_origen) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'No existe el producto con ID: '.$item->o_id_prod;
                    return $resp;
                }
                
                $data_kardex_salida['id_contribuyente'] = $producto_origen->id_contribuyente;
                $data_kardex_salida['idsucursal'] = $producto_origen->idsucursal;
                $data_kardex_salida['idproducto'] = $producto_origen->idproducto;
                $data_kardex_salida['idusuario'] = $idusuario;
                $data_kardex_salida['tipo_envio_sunat'] = $producto_movimiento->tipo_envio_sunat; //prueba, produccion
                $data_kardex_salida['tipo_kardex'] = 'salida_sindoc'; //inventario_inicial, venta, compra, ingreso_sindoc, salida_sindoc	
                $data_kardex_salida['estado_kardex'] = 'activo'; //activo, anulado
                $data_kardex_salida['cantidad_salida'] = $detalle_movimiento->cantidad;
                $data_kardex_salida['detalle'] = empty($producto_movimiento->nota)?'Ingreso Productos Sin DOC.':$producto_movimiento->nota;
                $data_kardex_salida['fecha_registro'] = $fecha_actual;
                
                $resp_kardex_salida = $kardex->registrar_en_kardex($data_kardex_salida);
                if($resp_kardex_salida['respuesta'] == 'error') {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error Kardex';
                    $resp['mensaje'] = 'No se logró guardar el movimiento en el kardex';
                    $resp['resp_kardex'] = $resp_kardex_salida;
                    return $resp;
                }
                
                $producto_origen->costo_promedio = $resp_kardex_salida['costo_unitario_promedio'];
                $producto_origen->stock = $producto_origen->stock - $detalle_movimiento->cantidad;

                try {
                    if(!$producto_origen->save()) {
                        $msg = '';
                        foreach ($producto_origen->getMessages() as $message) {
                            $msg = $msg.$message."</br>\n";
                        }
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error en BD';
                        $resp['mensaje'] = 'No hemos logrado actualizar el Stock en el producto origen';
                        return $resp;
                    }
                } catch (Exception $e) {
                    $this->saveLogger($e);
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error en BD';
                    $resp['mensaje'] = 'No hemos logrado actualizar el Stock en el producto origen! '.$e->getMessage();
                    return $resp;
                }
                
                //==================================
                //registrar en el kardex una ingreso
                //==================================
                $producto_destino = Producto::findFirst(array("id_contribuyente = :id_contribuyente: and idproducto = :idproducto: and estado = 'activo' and idsucursal = :idsucursal:", 'bind' => array('id_contribuyente' => $producto_movimiento->id_contribuyente, 'idproducto' => $item->d_id_prod, 'idsucursal' => $id_sucursal_destino)));
                if(!$producto_destino) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'No existe el producto con ID: '.$item->d_id_prod;
                    return $resp;
                }
                
                $data_kardex_entrada['id_contribuyente'] = $producto_destino->id_contribuyente;
                $data_kardex_entrada['idsucursal'] = $producto_destino->idsucursal;
                $data_kardex_entrada['idproducto'] = $producto_destino->idproducto;
                $data_kardex_entrada['idusuario'] = $idusuario;
                $data_kardex_entrada['tipo_envio_sunat'] = $producto_movimiento->tipo_envio_sunat; //prueba, produccion
                $data_kardex_entrada['tipo_kardex'] = 'ingreso_sindoc'; //inventario_inicial, venta, compra, ingreso_sindoc, salida_sindoc	
                $data_kardex_entrada['estado_kardex'] = 'activo'; //activo, anulado
                $data_kardex_entrada['cantidad_entrada'] = $detalle_movimiento->cantidad;
                $data_kardex_entrada['costo_unitario'] = round($detalle_movimiento->costo_unitario, 2);
                $data_kardex_entrada['detalle'] = empty($producto_movimiento->nota)?'Ingreso Productos Sin DOC.':$producto_movimiento->nota;
                $data_kardex_entrada['fecha_registro'] = $fecha_actual;

                $data_kardex_entrada['docref_id_contribuyente'] = $producto_movimiento->id_contribuyente;
                $data_kardex_entrada['docref_id_tipodoc_electronico'] = $producto_movimiento->id_tipo_movimiento;
                $data_kardex_entrada['docref_serie_comprobante'] = $producto_movimiento->serie;
                $data_kardex_entrada['docref_numero_comprobante'] = $producto_movimiento->correlativo;
                $data_kardex_entrada['docref_tipo_envio_sunat'] = $producto_movimiento->tipo_envio_sunat;
                
                $resp_kardex_entrada = $kardex->registrar_en_kardex($data_kardex_entrada);
                if($resp_kardex_entrada['respuesta'] == 'error') {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error Kardex';
                    $resp['mensaje'] = 'No se logró guardar el movimiento en el kardex';
                    $resp['resp_kardex'] = $resp_kardex_entrada;
                    return $resp;
                }
                
                $producto_destino->costo_promedio = $resp_kardex_entrada['costo_unitario_promedio'];
                $producto_destino->stock = $producto_destino->stock + $detalle_movimiento->cantidad;

                try {
                    if(!$producto_destino->save()) {
                        $msg = '';
                        foreach ($producto_destino->getMessages() as $message) {
                            $msg = $msg.$message."</br>\n";
                        }
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error en BD';
                        $resp['mensaje'] = 'No hemos logrado actualizar el Stock en el producto destino';
                        return $resp;
                    }
                } catch (Exception $e) {
                    $this->saveLogger($e);
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error en BD';
                    $resp['mensaje'] = 'No hemos logrado actualizar el Stock en el producto destino! '.$e->getMessage();
                    return $resp;
                }

            } else if($producto_movimiento->id_tipo_movimiento == 'tr') {
                //transformación de productos
                $data_kardex_salida_tr = array();
                $data_kardex_ingreso = array();

                if($item->tipo_kardex == 'salida_sindoc') {
                    //=====================================
                    //IMPLICA DISMINUCIÓN DE STOCK (PRODUCTOS INICIALES)
                    //=====================================
                    $producto_inicial = Producto::findFirst(array("id_contribuyente = :id_contribuyente: and idproducto = :idproducto: and estado = 'activo' and idsucursal = :idsucursal:", 'bind' => array('id_contribuyente' => $producto_movimiento->id_contribuyente, 'idproducto' => $item->o_id_prod, 'idsucursal' => $id_sucursal_origen)));
                    if(!$producto_inicial) {
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = 'No existe el producto con ID: '.$item->o_id_prod;
                        return $resp;
                    }

                    /*Cremos un Registro en el Kardex*/
                    $data_kardex_salida_tr['id_contribuyente'] = $producto_inicial->id_contribuyente;
                    $data_kardex_salida_tr['idsucursal'] = $producto_inicial->idsucursal;
                    $data_kardex_salida_tr['idproducto'] = $producto_inicial->idproducto;
                    $data_kardex_salida_tr['idusuario'] = $idusuario;
                    $data_kardex_salida_tr['tipo_envio_sunat'] = $producto_movimiento->tipo_envio_sunat; //prueba, produccion
                    $data_kardex_salida_tr['tipo_kardex'] = 'salida_sindoc'; //inventario_inicial, venta, compra, ingreso_sindoc, salida_sindoc	
                    $data_kardex_salida_tr['estado_kardex'] = 'activo'; //activo, anulado
                    $data_kardex_salida_tr['cantidad_salida'] = $detalle_movimiento->cantidad;
                    $data_kardex_salida_tr['detalle'] = empty($producto_movimiento->nota)?'Ingreso Productos Sin DOC.':$producto_movimiento->nota;
                    $data_kardex_salida_tr['fecha_registro'] = $fecha_actual;

                    $data_kardex_salida_tr['docref_id_contribuyente'] = $producto_movimiento->id_contribuyente;
                    $data_kardex_salida_tr['docref_id_tipodoc_electronico'] = $producto_movimiento->id_tipo_movimiento;
                    $data_kardex_salida_tr['docref_serie_comprobante'] = $producto_movimiento->serie;
                    $data_kardex_salida_tr['docref_numero_comprobante'] = $producto_movimiento->correlativo;
                    $data_kardex_salida_tr['docref_tipo_envio_sunat'] = $producto_movimiento->tipo_envio_sunat;
                    
                    $resp_kardex_salida = $kardex->registrar_en_kardex($data_kardex_salida_tr);
                    if($resp_kardex_salida['respuesta'] == 'error') {
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error Kardex';
                        $resp['mensaje'] = 'No se logró guardar el movimiento en el kardex';
                        $resp['resp_kardex'] = $resp_kardex;
                        return $resp;
                    }

                    $resp['resp_kardex'] = $resp_kardex_salida;

                    $producto_inicial->costo_promedio = $resp_kardex_salida['costo_unitario_promedio'];
                    $producto_inicial->stock = $resp_kardex_salida['stock'];

                    try {
                        if(!$producto_inicial->save()) {
                            $msg = '';
                            foreach ($producto_inicial->getMessages() as $message) {
                                $msg = $msg.$message."</br>\n";
                            }
                            $resp['respuesta'] = 'error';
                            $resp['titulo'] = 'Error en BD';
                            $resp['mensaje'] = 'No hemos logrado actualizar el Stock en el producto destino';
                            return $resp;
                        }
                    } catch (Exception $e) {
                        $this->saveLogger($e);
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error en BD';
                        $resp['mensaje'] = 'No hemos logrado actualizar el Stock en el producto destino! '.$e->getMessage();
                        return $resp;
                    }
                    //-----------------------------------------------------
                } else {
                    //=====================================
                    //INGRESO SIN DOCUMENTO (AUMENTA STOCK) PRODUCTO FINIAL (O RESULTANTE)
                    //=====================================

                    $producto_resultante = Producto::findFirst(array("id_contribuyente = :id_contribuyente: and idproducto = :idproducto: and estado = 'activo' and idsucursal = :idsucursal:", 'bind' => array('id_contribuyente' => $producto_movimiento->id_contribuyente, 'idproducto' => $item->d_id_prod, 'idsucursal' => $id_sucursal_destino)));
                    if(!$producto_resultante) {
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = 'No existe el producto con ID: '.$item->d_id_prod;
                        return $resp;
                    }

                    /*Cremos un Registro en el Kardex*/
                    $data_kardex_ingreso['id_contribuyente'] = $producto_resultante->id_contribuyente;
                    $data_kardex_ingreso['idsucursal'] = $producto_resultante->idsucursal;
                    $data_kardex_ingreso['idproducto'] = $producto_resultante->idproducto;
                    $data_kardex_ingreso['idusuario'] = $idusuario;
                    $data_kardex_ingreso['tipo_envio_sunat'] = $producto_movimiento->tipo_envio_sunat; //prueba, produccion
                    $data_kardex_ingreso['tipo_kardex'] = 'ingreso_sindoc'; //inventario_inicial, venta, compra, ingreso_sindoc, salida_sindoc	
                    $data_kardex_ingreso['estado_kardex'] = 'activo'; //activo, anulado
                    $data_kardex_ingreso['cantidad_entrada'] = $detalle_movimiento->cantidad;
                    $data_kardex_ingreso['costo_unitario'] = round($detalle_movimiento->costo_unitario, 2);
                    $data_kardex_ingreso['detalle'] = empty($producto_movimiento->nota)?'Ingreso Productos Sin DOC.':$producto_movimiento->nota;
                    $data_kardex_ingreso['fecha_registro'] = $fecha_actual;

                    $data_kardex_ingreso['docref_id_contribuyente'] = $producto_movimiento->id_contribuyente;
                    $data_kardex_ingreso['docref_id_tipodoc_electronico'] = $producto_movimiento->id_tipo_movimiento;
                    $data_kardex_ingreso['docref_serie_comprobante'] = $producto_movimiento->serie;
                    $data_kardex_ingreso['docref_numero_comprobante'] = $producto_movimiento->correlativo;
                    $data_kardex_ingreso['docref_tipo_envio_sunat'] = $producto_movimiento->tipo_envio_sunat;
                    
                    $resp_kardex_ingreso = $kardex->registrar_en_kardex($data_kardex_ingreso);
                    if($resp_kardex_ingreso['respuesta'] == 'error') {
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error Kardex';
                        $resp['mensaje'] = 'No se logró guardar el movimiento en el kardex';
                        $resp['resp_kardex'] = $resp_kardex_ingreso;
                        return $resp;
                    }

                    $resp['resp_kardex'] = $resp_kardex_ingreso;

                    $producto_resultante->costo_promedio = $resp_kardex_ingreso['costo_unitario_promedio'];
                    $producto_resultante->stock = $resp_kardex_ingreso['stock'];

                    try {
                        if(!$producto_resultante->save()) {
                            $msg = '';
                            foreach ($producto_resultante->getMessages() as $message) {
                                $msg = $msg.$message."</br>\n";
                            }
                            $resp['respuesta'] = 'error';
                            $resp['titulo'] = 'Error en BD';
                            $resp['mensaje'] = 'No hemos logrado actualizar el Stock en el producto destino';
                            return $resp;
                        }
                    } catch (Exception $e) {
                        $this->saveLogger($e);
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error en BD';
                        $resp['mensaje'] = 'No hemos logrado actualizar el Stock en el producto destino! '.$e->getMessage();
                        return $resp;
                    }
                    //-----------------------------------------------------
                }
                
            } else {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en BD';
                $resp['mensaje'] = 'No se reconoce el tipo de movimiento';
                return $resp;
            }
        }

        $resp['respuesta'] = 'ok';
        $resp['producto_movimiento'] = $producto_movimiento;
        return $resp;
    }

    public function validar_cabecera_movimiento($data) {
        
        $id_contribuyente = $data['id_contribuyente'];
        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
        if(!$contribuyente) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Lo sentimos la empresa no existe!';
            return $resp;
        }

        $idsucursal_origen = isset($data['id_sucursal'])?$data['id_sucursal']:0;
        $sucursal_origen = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $idsucursal_origen, 'id_contribuyente' => $id_contribuyente)));
        if(!$sucursal_origen) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'La Sucursal con ID: '.$idsucursal_origen.' no existe';
            return $resp;
        }

        $idusuario = $data['id_usuario'];
        $id_contribuyente = $data['id_contribuyente'];
        $usuario = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente:", 'bind' => array('idusuario' => $idusuario, 'id_contribuyente' => $id_contribuyente)));
        if(!$usuario) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'El Usuario no Existe';
            return $resp;
        }

        $tipo_envio_sunat = $contribuyente->tipo_envio_sunat;
        $data['tipo_envio_sunat'] = $tipo_envio_sunat;

        $id_tipo_movimiento = $data['id_tipo_movimiento'];
        $resp_serie = $this->get_serie_movimiento($contribuyente, $usuario, $id_tipo_movimiento);
        if($resp_serie['respuesta'] == 'error') {
            return $resp_serie;
        }

        $data['serie'] = $resp_serie['serie'];

        $correlativo = $this->get_correlativo_movimiento($data['id_contribuyente'], $data['id_tipo_movimiento'], $data['serie'], $data['tipo_envio_sunat']);
        $data['correlativo'] = $correlativo;

        $nota = isset($data['nota'])?$data['nota']:'';
        $data['nota'] = $nota;

        if($id_tipo_movimiento == 'in') {
            $data['destino_id_sucursal'] = $idsucursal_origen;
            $data['destino_id_contribuyente'] = $id_contribuyente;
            $data['tipo'] = 'ingreso';
        } else if($id_tipo_movimiento == 'sa') {
            $data['destino_id_sucursal'] = $idsucursal_origen;
            $data['destino_id_contribuyente'] = $id_contribuyente;
            $data['tipo'] = 'salida';
        } else if($id_tipo_movimiento == 'ts') {
            $idsucursal_destino = isset($data['destino_id_sucursal'])?$data['destino_id_sucursal']:0;
            $id_contribuyente_destino = $id_contribuyente; //$data['destino_id_contribuyente'];

            if($idsucursal_origen == $idsucursal_destino) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'La Sucursal de destino no puede ser la misma sucursal de origen';
                return $resp;
            }

            $sucursal_destino = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $idsucursal_origen, 'id_contribuyente' => $id_contribuyente_destino)));
            if(!$sucursal_destino) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'La Sucursal de destino con ID: '.$idsucursal_destino.' no existe';
                return $resp;
            }

            $data['destino_id_sucursal'] = $idsucursal_destino;
            $data['destino_id_contribuyente'] = $id_contribuyente_destino;
            $data['tipo'] = 'traslado';
        } else if($id_tipo_movimiento == 'tr') {
            
            $data['tipo'] = 'transformacion';
        } else {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'No se Reconoce el Movimiento';
            return $resp;
        }

        $resp['respuesta'] = 'ok';
        $resp['data'] = $data;
        return $resp;
    }

    public function validar_detalle_movimiento($data) {
        $id_tipo_movimiento = $data['id_tipo_movimiento'];

        $id_contribuyente_origen = $data['id_contribuyente'];
        $id_sucursal_origen = $data['id_sucursal'];

        $id_contribuyente_destino = $data['destino_id_contribuyente'];
        $id_sucursal_destino = $data['destino_id_sucursal'];

        

        if(is_array($data['detalle_movimiento']) && count($data['detalle_movimiento']) > 0) { 

        } else {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Debes ingrear items al detalle del movimiento para continuar con el registro.';
            return $resp;
        }

        $detalle_movimiento = $data['detalle_movimiento'];

        $lista_items = array();

        if($id_tipo_movimiento == 'in' || $id_tipo_movimiento == 'sa') {

            foreach($detalle_movimiento as $item) {

                $cantidad = floatval($item['cantidad']) + 0;
                $id_producto_origen = intval($item['o_id_prod']) + 0;

                $producto_origen = Producto::findFirst(array("id_contribuyente = :id_contribuyente: and idproducto = :idproducto: and estado = 'activo' and idsucursal = :idsucursal:", 'bind' => array('id_contribuyente' => $id_contribuyente_origen, 'idproducto' => $id_producto_origen, 'idsucursal' => $id_sucursal_origen)));

                if(!$producto_origen) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'No se encuentra el producto (IdProd: '.$id_producto_origen.' y IdSucursal: '.$id_sucursal_origen.')';
                    return $resp;
                }

                $id_unidad_medida_origen = $producto_origen->id_unidad_medida;
                $unidad_medida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $id_unidad_medida_origen)));
                if(!$unidad_medida) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'No se encuentra la unidad de medida';
                    return $resp;
                }

                $id_codigomoneda = isset($item['id_codigomoneda'])?$item['id_codigomoneda']:null;
                if($id_codigomoneda != 'PEN' && $id_codigomoneda != 'USD') {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'El código de la moneda no se reconoce';
                    $resp['function'] = 'validar_detalle_movimiento';
                    return $resp;
                }

                $array_item = array();
                $array_item['cantidad'] = $cantidad;
                $array_item['costo_unitario'] = isset($item->costo_unitario)?(floatval($item->costo_unitario) > 0?floatval($item->costo_unitario):$producto_origen->precio_compra):$producto_origen->precio_compra;
                $array_item['tipo_kardex'] = isset($item['tipo_kardex'])?($item['tipo_kardex'] == 'inventario_inicial'?'inventario_inicial':'ingreso_sindoc'):'ingreso_sindoc';
                $array_item['id_codigomoneda'] = $id_codigomoneda;

                $array_item['o_id_u_medida'] = $id_unidad_medida_origen;
                $array_item['o_u_medida'] = $unidad_medida->codigo;
                $array_item['o_precio'] = $producto_origen->valor_con_igv;
                $array_item['o_id_afectigv'] = $producto_origen->id_tipoafectacionigv;
                $array_item['o_tipo_unidad'] = 'UND';
                $array_item['o_id_presentacion'] = '';
                $array_item['o_cod_prod'] = $producto_origen->codigo;
                $array_item['o_id_prod'] = $producto_origen->idproducto;
                $array_item['o_nom_prod'] = $producto_origen->nombre;

                $array_item['d_id_u_medida'] = null;
                $array_item['d_u_medida'] = null;
                $array_item['d_precio'] = null;
                $array_item['d_id_afectigv'] = null;
                $array_item['d_tipo_unidad'] = null;
                $array_item['d_id_presentacion'] = null;
                $array_item['d_cod_prod'] = null;
                $array_item['d_id_prod'] = null;
                $array_item['d_nom_prod'] = null;
                
                $lista_items[] = $array_item;
            }

            $resp['respuesta'] = 'ok';
            $resp['lista_items'] = $lista_items;
            return $resp;

        } else if($id_tipo_movimiento == 'ts') {

            foreach($detalle_movimiento as $item) {
                $cantidad = floatval($item['cantidad']) + 0;
                $id_producto_origen = intval($item['o_id_prod']) + 0;

                $codigo_producto_origen = $item['o_cod_prod'];
                $nombre_producto_origen = $item['o_nom_prod'];

                $producto_origen = Producto::findFirst(array("id_contribuyente = :id_contribuyente: and idproducto = :idproducto: and estado = 'activo' and idsucursal = :idsucursal:", 'bind' => array('id_contribuyente' => $id_contribuyente_origen, 'idproducto' => $id_producto_origen, 'idsucursal' => $id_sucursal_origen)));

                if(!$producto_origen) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'No se encuentra el producto origen';
                    return $resp;
                }

                $id_unidad_medida_origen = $producto_origen->id_unidad_medida;
                $unidad_medida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $id_unidad_medida_origen)));
                if(!$unidad_medida) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'No se encuentra la unidad de medida';
                    return $resp;
                }

                $id_codigomoneda = isset($item['id_codigomoneda'])?$item['id_codigomoneda']:null;
                if($id_codigomoneda != 'PEN' && $id_codigomoneda != 'USD') {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'El código de moneda es incorrecto';
                    return $resp;
                }

                $producto_destino = Producto::findFirst(array("id_contribuyente = :id_contribuyente: and nombre = :nombre: and estado = 'activo' and idsucursal = :idsucursal: and id_unidad_medida = :id_unidad_medida: and codigo = :codigo:", 'bind' => array('id_contribuyente' => $id_contribuyente_destino, 'nombre' => $nombre_producto_origen, 'idsucursal' => $id_sucursal_destino, 'id_unidad_medida' => $id_unidad_medida_origen, 'codigo' => $codigo_producto_origen)));

                if(!$producto_destino) {
                    $resp_copia_producto = $this->copiar_producto_a_sucursal_destino($id_contribuyente_origen, $id_sucursal_origen, $id_producto_origen, $id_sucursal_destino);
                    if($resp_copia_producto['respuesta'] == 'error') {
                        return $resp_copia_producto;
                    }
    
                    $producto_destino = $resp_copia_producto['producto_destino'];
                }

                $array_item = array();
                $array_item['cantidad'] = $cantidad;
                $array_item['costo_unitario'] = $producto_origen->precio_compra;
                $array_item['tipo_kardex'] = isset($item['tipo_kardex'])?($item['tipo_kardex'] == 'inventario_inicial'?'inventario_inicial':'ingreso_sindoc'):'ingreso_sindoc';
                $array_item['id_codigomoneda'] = $id_codigomoneda;

                $array_item['o_id_u_medida'] = $id_unidad_medida_origen;
                $array_item['o_u_medida'] = $unidad_medida->codigo;
                $array_item['o_precio'] = $producto_origen->valor_con_igv;
                $array_item['o_id_afectigv'] = $producto_origen->id_tipoafectacionigv;
                $array_item['o_tipo_unidad'] = 'UND';
                $array_item['o_id_presentacion'] = '';
                $array_item['o_cod_prod'] = $producto_origen->codigo;
                $array_item['o_id_prod'] = $producto_origen->idproducto;
                $array_item['o_nom_prod'] = $producto_origen->nombre;

                $array_item['d_id_u_medida'] = $id_unidad_medida_origen;
                $array_item['d_u_medida'] = $unidad_medida->codigo;
                $array_item['d_precio'] = $producto_destino->valor_con_igv;
                $array_item['d_id_afectigv'] = $producto_destino->id_tipoafectacionigv;
                $array_item['d_tipo_unidad'] = 'UND';
                $array_item['d_id_presentacion'] = '';
                $array_item['d_cod_prod'] = $producto_destino->codigo;
                $array_item['d_id_prod'] = $producto_destino->idproducto;
                $array_item['d_nom_prod'] = $producto_destino->nombre;
                
                $lista_items[] = $array_item;
            }

            $resp['respuesta'] = 'ok';
            $resp['lista_items'] = $lista_items;
            return $resp;
        } else if($id_tipo_movimiento == 'tr') {
            $lista_items = $detalle_movimiento;
            
            $resp['respuesta'] = 'ok';
            $resp['lista_items'] = $lista_items;
            return $resp;
        }

        $resp['respuesta'] = 'error';
        $resp['titulo'] = 'Error';
        $resp['mensaje'] = 'Error al validar el detalle';
        return $resp;
    }

    public function copiar_producto_a_sucursal_destino($id_contribuyente, $idsucursal_origen, $id_producto, $id_sucursal_destino) {
        $producto_origen = Producto::findFirst(array("id_contribuyente = :id_contribuyente: and idproducto = :idproducto: and estado = 'activo' and idsucursal = :idsucursal:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'idproducto' => $id_producto, 'idsucursal' => $idsucursal_origen)));
        if($producto_origen->id_unidad_medida <= 0) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'El producto origen no tiene una unidad de medida';
            return $resp;
        }
        
        $fecha_actual =  date('Y-m-d H:i:s');

        $producto_destino = new Producto();
        $producto_destino->id_contribuyente = $producto_origen->id_contribuyente;
        $producto_destino->idsucursal = $id_sucursal_destino;
        $producto_destino->codigo = $producto_origen->codigo;
        $producto_destino->id_unidad_medida = $producto_origen->id_unidad_medida;
        $producto_destino->id_cod_detraccion = $producto_origen->id_cod_detraccion;
        $producto_destino->id_tipoafectacionigv = $producto_origen->id_tipoafectacionigv;
        $producto_destino->id_categoria = $producto_origen->id_categoria;
        $producto_destino->nombre = $producto_origen->nombre;
        $producto_destino->id_cod_moneda = $producto_origen->id_cod_moneda;
        $producto_destino->precio_compra = $producto_origen->precio_compra;
        $producto_destino->valor_sin_igv = $producto_origen->valor_sin_igv;
        $producto_destino->valor_con_igv = $producto_origen->valor_con_igv;
        $producto_destino->nota = $producto_origen->nota;
        $producto_destino->foto = $producto_origen->foto;
        $producto_destino->stock = 0;
        $producto_destino->stock_minimo = $producto_origen->stock_minimo;
        $producto_destino->fecha_registro = $fecha_actual;
        $producto_destino->precio_venta_minimo = $producto_origen->precio_venta_minimo;
        $producto_destino->estado = $producto_origen->estado;
        $producto_destino->tipo_cambio_sunat = $producto_origen->tipo_cambio_sunat;
        $producto_destino->multi_precio = $producto_origen->multi_precio;

        try {
            if(!$producto_destino->save()) {
                $msg = '';
                foreach ($producto_destino->getMessages() as $message) {
                    $msg = $msg.$message."</br>\n";
                }
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en BD';
                $resp['mensaje'] = 'No hemos logrado actualizar el Stock';
                return $resp;
            }
        } catch (Exception $e) {
            $this->saveLogger($e);
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error en BD';
            $resp['mensaje'] = 'No hemos logrado actualizar el Stock! '.$e->getMessage();
            return $resp;
        }

        $lista_precios_origen = ProductoListaprecio::find("idproducto=".$producto_origen->idproducto);
        foreach($lista_precios_origen as $item_precio_origen) {
            $item_precio = new ProductoListaprecio();
            $item_precio->idproducto = $producto_destino->idproducto;
            $item_precio->nombre = $item_precio_origen->nombre;
            $item_precio->precio = $item_precio_origen->precio;
            $item_precio->estado = 'activo';

            try {
                if(!$item_precio->save()) {
                    $msg = '';
                    foreach ($item_precio->getMessages() as $message) {
                        $msg = $msg.$message."</br>\n";
                    }
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'Obtenemos el siguiente error: '.$msg;
                    return $resp;
                }
            } catch (Exception $e) {
                $this->saveLogger($e);
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No hemos logrado guardar el precio en la lista de precios! '.$e->getMessage();
                return $resp;
            }
        }

        $resp['respuesta'] = 'ok';
        $resp['producto_destino'] = $producto_destino;
        return $resp;
    }

    public function get_serie_movimiento($contribuyente, $usuario, $id_tipo_movimiento) {
        if($id_tipo_movimiento == 'in') {
            $resp['respuesta'] = 'ok';
            $resp['serie']= 'IN';
            return $resp;
        } else if($id_tipo_movimiento == 'sa') {
            $resp['respuesta'] = 'ok';
            $resp['serie']= 'SA';
            return $resp;
        } else if($id_tipo_movimiento == 'ts') {
            $resp['respuesta'] = 'ok';
            $resp['serie']= 'TS';
            return $resp;
        } else if($id_tipo_movimiento == 'tr') {
            $resp['respuesta'] = 'ok';
            $resp['serie']= 'TR';
            return $resp;
        }

        $resp['respuesta'] = 'error';
        $resp['titulo'] = 'Error';
        $resp['mensaje'] = 'No encontramos una serie válida para el movimiento a registrar';
        return $resp;
    }
    
    public function get_correlativo_movimiento($id_contribuyente, $id_tipo_movimiento, $serie, $tipo_envio_sunat) {
        $movimiento_almacen = ProductoMovimiento::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipo_movimiento = :id_tipo_movimiento: and serie = :serie: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipo_movimiento' => $id_tipo_movimiento, 'serie' => $serie, 'tipo_envio_sunat' => $tipo_envio_sunat), "order" => "correlativo DESC"));

		if(!$movimiento_almacen) {
			return 1;
		}
		
		$correlativo = intval($movimiento_almacen->correlativo) + 1;
		return $correlativo;
    }

    public function get_ingresos($data) {
        $herramientas = new HerramientasController;
        $datapost = $data['datapost'];
        $id_contribuyente = intval($data['id_contribuyente']) + 0;
        $id_sucursal = intval($data['id_sucursal']) + 0;
        $fecha_inicio = $data['fecha_inicio'];
        $fecha_fin = $data['fecha_fin'];
        $id_producto = isset($data['id_producto'])?intval($data['id_producto']) + 0:0;
        $tipo_envio_sunat = $data['tipo_envio_sunat'];

        $select_items = " mov.id_contribuyente, mov.id_tipo_movimiento, mov.serie, mov.correlativo, CONCAT(mov.serie,'-',mov.correlativo) as serie_correlativo, mov.tipo_envio_sunat, mov.tipo, mov.id_sucursal, mov.fecha_movimiento, mov.id_usuario, mov.nota, mov.estado, mov.destino_id_sucursal, mov.destino_id_contribuyente, det.o_id_prod, det.o_nom_prod, det.o_id_u_medida, det.o_cod_prod, det.d_id_prod, det.d_nom_prod, det.d_id_u_medida, det.cantidad ";

        $from = " producto_movimiento mov INNER JOIN producto_movimiento_det det ON (mov.id_contribuyente = det.id_contribuyente and mov.id_tipo_movimiento = det.id_tipo_movimiento and mov.serie = det.serie and mov.correlativo = det.correlativo and mov.tipo_envio_sunat = det.tipo_envio_sunat) ";

        $where = " det.id_tipo_movimiento = 'in' and det.tipo_envio_sunat = '$tipo_envio_sunat' and mov.estado = 'activo' and mov.id_contribuyente = $id_contribuyente and (CAST(mov.fecha_movimiento AS DATE) BETWEEN CAST(:fecha_inicio AS DATETIME) and CAST(:fecha_fin AS DATETIME)) ";

        $select_count = " count(DISTINCT det.id_movimiento_det) as total ";

        if($id_producto > 0) {
            $where = $where." and det.o_id_prod = $id_producto ";
        }

        if($id_sucursal > 0) {
            $where = $where." and mov.id_sucursal = $id_sucursal ";
        }
        
        $sql_count_items = "SELECT $select_count FROM $from WHERE $where";
        
        //Inicio: Total Registros
        try {
            $sentencia_count = $this->db->prepare($sql_count_items);
            $sentencia_count->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            $sentencia_count->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
            $sentencia_count->execute();
            $result_count = $sentencia_count->fetch();
            $total_registros = $result_count['total'];

        } catch (Exception $e) {
            $this->saveLogger($e);
            $msg_excepcion = $e->getMessage();
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['codigo'] = 'Error001';
            $resp['mensaje'] = 'Error al extraer la lista de movimientos de ingreso';
            return $resp;
        }
        //fin: Total Registros

        $columnas = array(
            0 => 'mov.correlativo',
            1 => 'mov.tipo',
            2 => 'mov.id_sucursal',
            3 => 'mov.fecha_movimiento',
            4 => 'mov.nota',
            5 => 'det.o_id_prod',
            6 => 'det.o_cod_prod',
            7 => 'det.o_nom_prod',
            8 => 'det.cantidad',
            9 => 'mov.fecha_movimiento' //menu
        );

        if(!empty($datapost['search']['value'])) {
            $where = $where." AND (mov.serie like :termino or mov.correlativo like :termino or mov.tipo like :termino or mov.id_sucursal like :termino or mov.nota like :termino or det.o_id_prod like :termino or det.o_cod_prod like :termino or det.o_nom_prod like :termino) ";
        }

        //Inicio: Total registros filtrados
        $sql_count_items = "SELECT $select_count FROM $from WHERE $where ";
        try {
            $sentencia_filter = $this->db->prepare($sql_count_items);
            $sentencia_filter->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            $sentencia_filter->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
            if(!empty($datapost['search']['value'])) {
                $termino_busqueda = '%'.$datapost['search']['value'].'%';
                $sentencia_filter->bindParam(':termino', $termino_busqueda, PDO::PARAM_STR);
            }
            $sentencia_filter->execute();
            $result_filter= $sentencia_filter->fetch();
            $total_filas_filtradas = $result_filter['total'];
        } catch (Exception $e) {
            $this->saveLogger($e);
            $msg_excepcion = $e->getMessage();
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['codigo'] = 'Error002';
            $resp['mensaje'] = 'Error al extraer la lista de movimientos de entradas'.$msg_excepcion;
            return $resp;
        }
        //Fin: Total registros filtrados

        $sql_list_items = "SELECT $select_items FROM $from WHERE $where ";
        $sql_list_items = $sql_list_items." ORDER BY ".$columnas[$datapost['order'][0]['column']]." ".$datapost['order'][0]['dir']." LIMIT ".intval($datapost['start']).",".intval($datapost['length'])."";
        
        /*
        $sql_list_items = str_replace(':fecha_inicio', "'$fecha_inicio'", $sql_list_items);
        $sql_list_items = str_replace(':fecha_fin', "'$fecha_fin'", $sql_list_items);
        echo $sql_list_items;
        exit();
        */

        try {
            $sentencia_list_items = $this->db->prepare($sql_list_items);
            $sentencia_list_items->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            $sentencia_list_items->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
            if(!empty($datapost['search']['value'])) {
                $termino_busqueda = '%'.$datapost['search']['value'].'%';
                $sentencia_list_items->bindParam(':termino', $termino_busqueda, PDO::PARAM_STR);
            }
            $sentencia_list_items->execute();
        } catch (Exception $e) {
            $this->saveLogger($e);
            $msg_excepcion = $e->getMessage();
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['codigo'] = 'Error003';
            $resp['mensaje'] = 'Error al extraer la lista de movimientos de ingresos..'.$msg_excepcion;
            return $resp;
        }

        $dominio_principal = $this->get_parametros_iniciales()['url_domain'];
        
        $lista_item = array();
        $n = 0;
        while($fila = $sentencia_list_items->fetch()) {
            $item = (object)$fila;

            $cadena_pdf_a4 = "$id_contribuyente||$item->id_tipo_movimiento||$item->serie||$item->correlativo||$item->tipo_envio_sunat||a4";
            $cadena_pdf_ticket = "$id_contribuyente||$item->id_tipo_movimiento||$item->serie||$item->correlativo||$item->tipo_envio_sunat||ticket";
            
            $cadena_pdf_a4_encriptada = $herramientas->encriptar($cadena_pdf_a4);
            $cadena_pdf_ticket_encriptada = $herramientas->encriptar($cadena_pdf_ticket);

            $url_a4 = 'https://'.$dominio_principal.'/facturacionv8/productomovimientos/print_pdf/?file='.urlencode($cadena_pdf_a4_encriptada);
		    $url_ticket = 'https://'.$dominio_principal.'/facturacionv8/productomovimientos/print_pdf/?file='.urlencode($cadena_pdf_ticket_encriptada);

            $lista_item[] = array(
                /*'serie_correlativo'     => $item->serie_correlativo.'
                                            <a target="_blank" href="'.$url_a4.'"><img title="Formato A4" src="/facturacionv8/img/svg/pdf_cpe.svg" style="width: 30px; cursor: pointer;"></a>
                                            <a target="_blank" href="'.$url_ticket.'"><img title="Formato Ticket" src="/facturacionv8/img/svg/ticket_cpe.svg" style="width: 30px; cursor: pointer;"></a>
                                        ', */
                'serie_correlativo'     => $item->serie_correlativo, 
                'mov_tipo'              => $item->tipo,
                'mov_id_sucursal'       => $item->id_sucursal,
                'mov_fecha_movimiento'  => date("d-m-Y", strtotime($item->fecha_movimiento)),
                'mov_nota'              => $item->nota,
                'det_o_id_prod'         => $item->o_id_prod,
                'det_o_cod_prod'        => $item->o_cod_prod,
                'det_o_nom_prod'        => $item->o_nom_prod,
                'det_cantidad'          => $item->cantidad + 0,
                'opciones'              => '<ul class="icons-list">
                                                <li class="dropdown">
                                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                        <i class="icon-menu9"></i>
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                        <li><a target="_blank" href="'.$url_a4.'"><img title="Formato A4" src="/facturacionv8/img/svg/pdf_cpe.svg" style="width: 18px; cursor: pointer;"> Ver PDF - A4</a></li>
                                                        <li><a target="_blank" href="'.$url_ticket.'"><img title="Formato Ticket" src="/facturacionv8/img/svg/ticket_cpe.svg" style="width: 18px; cursor: pointer;"> Ver PDF - Ticket</a></li>
                                                    </ul> 
                                                </li>
                                            </ul>'
            );
        }

        $json_data = array(
            "draw"            => intval($datapost['draw']),
            "recordsTotal"    => intval($total_registros),  //Número total de registros que cumplen con los criterios iniciales
            "recordsFiltered" => $total_filas_filtradas, //Total registros filtrados
            "data"            => $lista_item,   // total data array
            "numero_n"        => $n
        );

        $resp['respuesta'] = 'ok';
        $resp['json_data'] = $json_data;

        return $resp;
    }

    public function get_salidas($data) {
        $herramientas = new HerramientasController;
        $datapost = $data['datapost'];
        $id_contribuyente = intval($data['id_contribuyente']) + 0;
        $id_sucursal = intval($data['id_sucursal']) + 0;
        $fecha_inicio = $data['fecha_inicio'];
        $fecha_fin = $data['fecha_fin'];
        $id_producto = isset($data['id_producto'])?intval($data['id_producto']) + 0:0;
        $tipo_envio_sunat = $data['tipo_envio_sunat'];

        $select_items = " mov.id_contribuyente, mov.id_tipo_movimiento, mov.serie, mov.correlativo, CONCAT(mov.serie,'-',mov.correlativo) as serie_correlativo, mov.tipo_envio_sunat, mov.tipo, mov.id_sucursal, mov.fecha_movimiento, mov.id_usuario, mov.nota, mov.estado, mov.destino_id_sucursal, mov.destino_id_contribuyente, det.o_id_prod, det.o_nom_prod, det.o_id_u_medida, det.o_cod_prod, det.d_id_prod, det.d_nom_prod, det.d_id_u_medida, det.cantidad ";

        $from = " producto_movimiento mov INNER JOIN producto_movimiento_det det ON (mov.id_contribuyente = det.id_contribuyente and mov.id_tipo_movimiento = det.id_tipo_movimiento and mov.serie = det.serie and mov.correlativo = det.correlativo and mov.tipo_envio_sunat = det.tipo_envio_sunat) ";

        $where = " det.id_tipo_movimiento = 'sa' and det.tipo_envio_sunat = '$tipo_envio_sunat' and mov.estado = 'activo' and mov.id_contribuyente = $id_contribuyente and (CAST(mov.fecha_movimiento AS DATE) BETWEEN CAST(:fecha_inicio AS DATETIME) and CAST(:fecha_fin AS DATETIME)) ";

        $select_count = " count(DISTINCT det.id_movimiento_det) as total ";

        if($id_producto > 0) {
            $where = $where." and det.o_id_prod = $id_producto ";
        }

        if($id_sucursal > 0) {
            $where = $where." and mov.id_sucursal = $id_sucursal ";
        }
        
        $sql_count_items = "SELECT $select_count FROM $from WHERE $where";
        
        //Inicio: Total Registros
        try {
            $sentencia_count = $this->db->prepare($sql_count_items);
            $sentencia_count->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            $sentencia_count->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
            $sentencia_count->execute();
            $result_count = $sentencia_count->fetch();
            $total_registros = $result_count['total'];

        } catch (Exception $e) {
            $this->saveLogger($e);
            $msg_excepcion = $e->getMessage();
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['codigo'] = 'Error001';
            $resp['mensaje'] = 'Error al extraer la lista de movimientos de salida';
            return $resp;
        }
        //fin: Total Registros

        $columnas = array(
            0 => 'mov.correlativo',
            1 => 'mov.tipo',
            2 => 'mov.id_sucursal',
            3 => 'mov.fecha_movimiento',
            4 => 'mov.nota',
            5 => 'det.o_id_prod',
            6 => 'det.o_cod_prod',
            7 => 'det.o_nom_prod',
            8 => 'det.cantidad',
            9 => 'mov.fecha_movimiento' //menu
        );

        if(!empty($datapost['search']['value'])) {
            $where = $where." AND (mov.serie like :termino or mov.correlativo like :termino or mov.tipo like :termino or mov.id_sucursal like :termino or mov.nota like :termino or det.o_id_prod like :termino or det.o_cod_prod like :termino or det.o_nom_prod like :termino) ";
        }

        //Inicio: Total registros filtrados
        $sql_count_items = "SELECT $select_count FROM $from WHERE $where ";
        try {
            $sentencia_filter = $this->db->prepare($sql_count_items);
            $sentencia_filter->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            $sentencia_filter->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
            if(!empty($datapost['search']['value'])) {
                $termino_busqueda = '%'.$datapost['search']['value'].'%';
                $sentencia_filter->bindParam(':termino', $termino_busqueda, PDO::PARAM_STR);
            }
            $sentencia_filter->execute();
            $result_filter= $sentencia_filter->fetch();
            $total_filas_filtradas = $result_filter['total'];
        } catch (Exception $e) {
            $this->saveLogger($e);
            $msg_excepcion = $e->getMessage();
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['codigo'] = 'Error002';
            $resp['mensaje'] = 'Error al extraer la lista de movimientos de salidas';
            return $resp;
        }
        //Fin: Total registros filtrados

        $sql_list_items = "SELECT $select_items FROM $from WHERE $where ";
        $sql_list_items = $sql_list_items." ORDER BY ".$columnas[$datapost['order'][0]['column']]." ".$datapost['order'][0]['dir']." LIMIT ".intval($datapost['start']).",".intval($datapost['length'])."";
        
        /*
        $sql_list_items = str_replace(':fecha_inicio', "'$fecha_inicio'", $sql_list_items);
        $sql_list_items = str_replace(':fecha_fin', "'$fecha_fin'", $sql_list_items);
        echo $sql_list_items;
        exit();
        */

        try {
            $sentencia_list_items = $this->db->prepare($sql_list_items);
            $sentencia_list_items->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            $sentencia_list_items->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
            if(!empty($datapost['search']['value'])) {
                $termino_busqueda = '%'.$datapost['search']['value'].'%';
                $sentencia_list_items->bindParam(':termino', $termino_busqueda, PDO::PARAM_STR);
            }
            $sentencia_list_items->execute();
        } catch (Exception $e) {
            $this->saveLogger($e);
            $msg_excepcion = $e->getMessage();
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['codigo'] = 'Error003';
            $resp['mensaje'] = 'Error al extraer la lista de movimientos de salidas..';
            return $resp;
        }
        
        $dominio_principal = $this->get_parametros_iniciales()['url_domain'];
        $lista_item = array();
        $n = 0;
        while($fila = $sentencia_list_items->fetch()) {
            $item = (object)$fila;

            $cadena_pdf_a4 = "$id_contribuyente||$item->id_tipo_movimiento||$item->serie||$item->correlativo||$item->tipo_envio_sunat||a4";
            $cadena_pdf_ticket = "$id_contribuyente||$item->id_tipo_movimiento||$item->serie||$item->correlativo||$item->tipo_envio_sunat||ticket";
            
            $cadena_pdf_a4_encriptada = $herramientas->encriptar($cadena_pdf_a4);
            $cadena_pdf_ticket_encriptada = $herramientas->encriptar($cadena_pdf_ticket);

            $url_a4 = 'https://'.$dominio_principal.'/facturacionv8/productomovimientos/print_pdf/?file='.urlencode($cadena_pdf_a4_encriptada);
		    $url_ticket = 'https://'.$dominio_principal.'/facturacionv8/productomovimientos/print_pdf/?file='.urlencode($cadena_pdf_ticket_encriptada);

            $lista_item[] = array(
                /*'serie_correlativo'     => $item->serie_correlativo.'
                                            <a target="_blank" href="'.$url_a4.'"><img title="Formato A4" src="/facturacionv8/img/svg/pdf_cpe.svg" style="width: 30px; cursor: pointer;"></a>
                                            <a target="_blank" href="'.$url_ticket.'"><img title="Formato Ticket" src="/facturacionv8/img/svg/ticket_cpe.svg" style="width: 30px; cursor: pointer;"></a>
                                        ', */
                'serie_correlativo'     => $item->serie_correlativo,
                'mov_tipo'              => $item->tipo,
                'mov_id_sucursal'       => $item->id_sucursal,
                'mov_fecha_movimiento'  => date("d-m-Y", strtotime($item->fecha_movimiento)),
                'mov_nota'              => $item->nota,
                'det_o_id_prod'         => $item->o_id_prod,
                'det_o_cod_prod'        => $item->o_cod_prod,
                'det_o_nom_prod'        => $item->o_nom_prod,
                'det_cantidad'          => $item->cantidad + 0,
                'opciones'              => '<ul class="icons-list">
                                                <li class="dropdown">
                                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                        <i class="icon-menu9"></i>
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                        <li><a target="_blank" href="'.$url_a4.'"><img title="Formato A4" src="/facturacionv8/img/svg/pdf_cpe.svg" style="width: 18px; cursor: pointer;"> Ver PDF - A4</a></li>
                                                        <li><a target="_blank" href="'.$url_ticket.'"><img title="Formato Ticket" src="/facturacionv8/img/svg/ticket_cpe.svg" style="width: 18px; cursor: pointer;"> Ver PDF - Ticket</a></li>
                                                    </ul>  
                                                </li>
                                            </ul>'
            );
        }

        $json_data = array(
            "draw"            => intval($datapost['draw']),
            "recordsTotal"    => intval($total_registros),  //Número total de registros que cumplen con los criterios iniciales
            "recordsFiltered" => $total_filas_filtradas, //Total registros filtrados
            "data"            => $lista_item,   // total data array
            "numero_n"        => $n
        );

        $resp['respuesta'] = 'ok';
        $resp['json_data'] = $json_data;

        return $resp;
    }

    public function get_lista_transformaciones_detalle($data) {
        $herramientas = new HerramientasController;
        $datapost = $data['datapost'];
        $id_contribuyente = intval($data['id_contribuyente']) + 0;
        $id_sucursal = intval($data['id_sucursal']) + 0;
        $fecha_inicio = $data['fecha_inicio'];
        $fecha_fin = $data['fecha_fin'];
        $tipo_envio_sunat = $data['tipo_envio_sunat'];
        $id_sucursal_destino = isset($data['id_sucursal_destino'])?intval($data['id_sucursal_destino']) + 0:0;

        $select_columns = "pm.id_contribuyente, pm.id_tipo_movimiento, pm.serie, pm.correlativo, pm.tipo_envio_sunat, pm.id_sucursal, pm.destino_id_sucursal, pm.id_usuario, pm.tipo, pm.nota, pm.fecha_movimiento, pd.id_movimiento_det, pd.cantidad, pd.costo_unitario, pd.id_codigomoneda, pd.o_id_u_medida, pd.o_u_medida, pd.o_precio, pd.o_id_afectigv, pd.o_tipo_unidad, pd.o_id_presentacion, pd.o_cod_prod, pd.o_id_prod, pd.o_nom_prod, pd.d_id_u_medida, pd.d_u_medida, pd.d_precio, pd.d_id_afectigv, pd.d_tipo_unidad, pd.d_id_presentacion, pd.d_cod_prod, pd.d_id_prod, pd.d_nom_prod";
        $from = "producto_movimiento pm INNER JOIN producto_movimiento_det pd ON (pm.id_contribuyente = pd.id_contribuyente and pm.id_tipo_movimiento = pd.id_tipo_movimiento and pm.serie = pd.serie and pm.correlativo = pd.correlativo and pm.tipo_envio_sunat = pd.tipo_envio_sunat)";
        $where = " pm.id_contribuyente = :id_contribuyente and pm.id_tipo_movimiento = 'tr' and pm.tipo = 'transformacion' and (CAST(pm.fecha_movimiento AS DATE) BETWEEN CAST(:fecha_inicio AS DATE) and CAST(:fecha_fin AS DATE)) and pm.tipo_envio_sunat = :tipo_envio_sunat ";
        
        if($id_sucursal > 0) {
            $where = $where." and pm.id_sucursal = $id_sucursal ";
        }

        if($id_sucursal_destino > 0) {
            $where = $where." and pm.destino_id_sucursal = $id_sucursal_destino ";
        }

        $sql_count_items = "SELECT count(DISTINCT pd.id_movimiento_det) as total FROM $from WHERE $where ;";

        //Inicio: Total Registros
        try {
            $sentencia_count = $this->db->prepare($sql_count_items);
            $sentencia_count->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
            $sentencia_count->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
            $sentencia_count->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            $sentencia_count->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
            $sentencia_count->execute();
            $result_count = $sentencia_count->fetch();
            $total_registros = $result_count['total'];
        } catch (Exception $e) {
            $this->saveLogger($e);
            $msg_excepcion = $e->getMessage();
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['codigo'] = 'Error001';
            $resp['mensaje'] = 'Error al extraer la lista de movimientos de transformación';
            return $resp;
        }
        //fin: Total Registros

        $columnas = array(
            0   => 'correlativo',
            1   => 'fecha_movimiento',
            2   => 'id_usuario', //nombre usuario
            3   => 'id_sucursal', //sucursal origen
            4   => 'id_sucursal', //sucursal origen: nombre de la sucursal
            5   => 'destino_id_sucursal', //sucursal destino
            6   => 'destino_id_sucursal', //sucursal destino: nombre de la sucursal
            7   => 'nota',
            
            8   => 'o_id_u_medida',
            9   => 'o_u_medida',
            10   => 'o_precio',
            11   => 'o_id_afectigv',
            12   => 'o_tipo_unidad',
            13   => 'o_id_presentacion',
            14   => 'o_cod_prod',
            15   => 'o_id_prod',
            16   => 'o_nom_prod',

            17   => 'd_id_u_medida',
            18   => 'd_u_medida',
            19   => 'd_precio',
            20   => 'd_id_afectigv',
            21   => 'd_tipo_unidad',
            22   => 'd_id_presentacion',
            23   => 'd_cod_prod',
            24   => 'd_id_prod',
            25   => 'd_nom_prod',

            26   => 'id_codigomoneda',
            27   => 'id_codigomoneda'
        );

        if(!empty($datapost['search']['value'])) {
            $where = $where." AND (pm.serie like :termino or pm.correlativo like :termino or pm.tipo like :termino or pm.id_sucursal like :termino or pm.nota like :termino or pd.o_nom_prod like :termino ) ";
        }

        //Inicio: Total registros filtrados
        $sql_count_items = "SELECT count(DISTINCT pd.id_movimiento_det) as total FROM $from WHERE $where ;";
        try {
            $sentencia_filter = $this->db->prepare($sql_count_items);
            $sentencia_filter->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
            $sentencia_filter->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
            $sentencia_filter->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            $sentencia_filter->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
            if(!empty($datapost['search']['value'])) {
                $termino_busqueda = '%'.$datapost['search']['value'].'%';
                $sentencia_filter->bindParam(':termino', $termino_busqueda, PDO::PARAM_STR);
            }
            $sentencia_filter->execute();
            $result_filter= $sentencia_filter->fetch();
            $total_filas_filtradas = $result_filter['total'];
        } catch (Exception $e) {
            $this->saveLogger($e);
            $msg_excepcion = $e->getMessage();
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['codigo'] = 'Error002';
            $resp['mensaje'] = 'Error al extraer la lista de movimientos de traslados';
            return $resp;
        }
        //Fin: Total registros filtrados

        $sql_list_items = "SELECT $select_columns FROM $from WHERE $where ";
        $sql_list_items = $sql_list_items." ORDER BY ".$columnas[$datapost['order'][0]['column']]." ".$datapost['order'][0]['dir']." LIMIT ".intval($datapost['start']).",".intval($datapost['length'])."";

        try {
            $sentencia_list_items = $this->db->prepare($sql_list_items);
            $sentencia_list_items->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
            $sentencia_list_items->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
            $sentencia_list_items->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            $sentencia_list_items->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
            if(!empty($datapost['search']['value'])) {
                $termino_busqueda = '%'.$datapost['search']['value'].'%';
                $sentencia_list_items->bindParam(':termino', $termino_busqueda, PDO::PARAM_STR);
            }
            $sentencia_list_items->execute();
        } catch (Exception $e) {
            $this->saveLogger($e);
            $msg_excepcion = $e->getMessage();
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['codigo'] = 'Error003';
            $resp['mensaje'] = 'Error al extraer la lista de movimientos de salidas..';
            return $resp;
        }

        $dominio_principal = $this->get_parametros_iniciales()['url_domain'];
        $lista_item = array();
        $n = 0;
        while($fila = $sentencia_list_items->fetch()) {
            $item = (object)$fila;
            $cadena_pdf_a4 = "$id_contribuyente||$item->id_tipo_movimiento||$item->serie||$item->correlativo||$item->tipo_envio_sunat||a4";
            $cadena_pdf_ticket = "$id_contribuyente||$item->id_tipo_movimiento||$item->serie||$item->correlativo||$item->tipo_envio_sunat||ticket";
            
            $cadena_pdf_a4_encriptada = $herramientas->encriptar($cadena_pdf_a4);
            $cadena_pdf_ticket_encriptada = $herramientas->encriptar($cadena_pdf_ticket);

            $url_a4 = 'https://'.$dominio_principal.'/facturacionv8/productomovimientos/print_pdf/?file='.urlencode($cadena_pdf_a4_encriptada);
		    $url_ticket = 'https://'.$dominio_principal.'/facturacionv8/productomovimientos/print_pdf/?file='.urlencode($cadena_pdf_ticket_encriptada);

            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $item->id_usuario)));
            $nombre_usuario = '';
            if($usuario) {
                $nombre_usuario = $usuario->nombre.' '.$usuario->apellido." (ID: $usuario->idusuario)";
            }

            $info_sucursal_origen = '';
            $info_sucursal_destino = '';

            $sucursal_origen = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $item->id_sucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
            if($sucursal_origen) {
                $info_sucursal_origen = $sucursal_origen->nombre." (ID: $sucursal_origen->idsucursal)";
            }

            $sucursal_destino = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $item->destino_id_sucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
            if($sucursal_destino) {
                $info_sucursal_destino = $sucursal_destino->nombre." (ID: $sucursal_destino->idsucursal)";
            }

            $lista_item[] = array(
                'serie_correlativo'     => $item->serie.' - '.$item->correlativo, 
                'fecha_movimiento'      => date("d-m-Y / H:i A", strtotime($item->fecha_movimiento)),
                'usuario'               => $nombre_usuario,
                'id_sucursal'           => $item->id_sucursal,
                'info_sucursal_origen'  => $info_sucursal_origen,
                
                'id_sucursal_destino'   => $item->destino_id_sucursal,
                'info_sucursal_destino' => $info_sucursal_destino,
                'nota'                  => $item->nota,
                
                'cantidad'              => floatval($item->cantidad) + 0,
                'o_id_u_medida'         => $item->o_id_u_medida,
                'o_u_medida'            => $item->o_u_medida,
                'o_precio'              => $item->o_precio,
                'o_id_afectigv'         => $item->o_id_afectigv,
                'o_tipo_unidad'         => $item->o_tipo_unidad,
                'o_id_presentacion'     => $item->o_id_presentacion,
                'o_cod_prod'            => $item->o_cod_prod,
                'o_id_prod'             => $item->o_id_prod,
                'o_nom_prod'            => $item->o_nom_prod,

                'd_id_u_medida'         => $item->d_id_u_medida,
                'd_u_medida'            => $item->d_u_medida,
                'd_precio'              => $item->d_precio,
                'd_id_afectigv'         => $item->d_id_afectigv,
                'd_tipo_unidad'         => $item->d_tipo_unidad,
                'd_id_presentacion'     => $item->d_id_presentacion,
                'd_cod_prod'            => $item->d_cod_prod,
                'd_id_prod'             => $item->d_id_prod,
                'd_nom_prod'            => $item->d_nom_prod,

                'id_codigomoneda'       => $item->id_codigomoneda,

                'opciones'              => '<ul class="icons-list">
                                                <li class="dropdown">
                                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                        <i class="icon-menu9"></i>
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                        <li><a target="_blank" href="'.$url_a4.'"><img title="Formato A4" src="/facturacionv8/img/svg/pdf_cpe.svg" style="width: 18px; cursor: pointer;"> Ver PDF - A4</a></li>
                                                        <li style="display:none;"><a target="_blank" href="'.$url_ticket.'"><img title="Formato Ticket" src="/facturacionv8/img/svg/ticket_cpe.svg" style="width: 18px; cursor: pointer;"> Ver PDF - Ticket</a></li>
                                                    </ul> 
                                                </li>
                                            </ul>'
            );
        }

        $json_data = array(
            "draw"            => intval($datapost['draw']),
            "recordsTotal"    => intval($total_registros),  //Número total de registros que cumplen con los criterios iniciales
            "recordsFiltered" => $total_filas_filtradas, //Total registros filtrados
            "data"            => $lista_item,   // total data array
            "numero_n"        => $n
        );

        $resp['respuesta'] = 'ok';
        $resp['json_data'] = $json_data;

        return $resp;
    }

    public function get_lista_transformaciones($data) {
        $herramientas = new HerramientasController;
        $datapost = $data['datapost'];
        $id_contribuyente = intval($data['id_contribuyente']) + 0;
        $id_sucursal = intval($data['id_sucursal']) + 0;
        $fecha_inicio = $data['fecha_inicio'];
        $fecha_fin = $data['fecha_fin'];
        $tipo_envio_sunat = $data['tipo_envio_sunat'];
        $id_sucursal_destino = isset($data['id_sucursal_destino'])?intval($data['id_sucursal_destino']) + 0:0;

        $select_columns = " * ";
        $from = "producto_movimiento";
        $where = "id_contribuyente = :id_contribuyente and id_tipo_movimiento = 'tr' and tipo = 'transformacion' and (CAST(fecha_movimiento AS DATE) BETWEEN CAST(:fecha_inicio AS DATE) and CAST(:fecha_fin AS DATE)) and tipo_envio_sunat = :tipo_envio_sunat";

        if($id_sucursal > 0) {
            $where = $where." and id_sucursal = $id_sucursal ";
        }

        if($id_sucursal_destino > 0) {
            $where = $where." and destino_id_sucursal = $id_sucursal_destino ";
        }

        $sql_count_items = "SELECT COUNT(distinct id_contribuyente, id_tipo_movimiento, serie, correlativo, tipo_envio_sunat) as total FROM $from WHERE $where ;";
        
        /*
        $sql_count_items = str_replace(':id_contribuyente', $id_contribuyente, $sql_count_items);
        $sql_count_items = str_replace(':tipo_envio_sunat', "'".$tipo_envio_sunat."'", $sql_count_items);
        $sql_count_items = str_replace(':fecha_inicio', "'".$fecha_inicio."'", $sql_count_items);
        $sql_count_items = str_replace(':fecha_fin', "'".$fecha_fin."'", $sql_count_items);
        echo $sql_count_items;
        exit();
        */

        //Inicio: Total Registros
        try {
            $sentencia_count = $this->db->prepare($sql_count_items);
            $sentencia_count->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
            $sentencia_count->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
            $sentencia_count->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            $sentencia_count->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
            $sentencia_count->execute();
            $result_count = $sentencia_count->fetch();
            $total_registros = $result_count['total'];

        } catch (Exception $e) {
            $this->saveLogger($e);
            $msg_excepcion = $e->getMessage();
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['codigo'] = 'Error001';
            $resp['mensaje'] = 'Error al extraer la lista de movimientos de transformación';
            return $resp;
        }
        //fin: Total Registros
        
        $columnas = array(
            0   => 'correlativo',
            1   => 'fecha_movimiento',
            2   => 'id_usuario', //nombre usuario
            3   => 'id_sucursal', //sucursal origen
            4   => 'id_sucursal', //sucursal origen: nombre de la sucursal
            5   => 'destino_id_sucursal', //sucursal destino
            6   => 'destino_id_sucursal', //sucursal destino: nombre de la sucursal
            7   => 'nota',
            8   => 'correlativo'
        );

        if(!empty($datapost['search']['value'])) {
            $where = $where." AND (serie like :termino or correlativo like :termino or tipo like :termino or id_sucursal like :termino or nota like :termino) ";
        }

        //Inicio: Total registros filtrados
        $sql_count_items = "SELECT COUNT(distinct id_contribuyente, id_tipo_movimiento, serie, correlativo, tipo_envio_sunat) as total FROM $from WHERE $where ;";
        try {
            $sentencia_filter = $this->db->prepare($sql_count_items);
            $sentencia_filter->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
            $sentencia_filter->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
            $sentencia_filter->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            $sentencia_filter->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
            if(!empty($datapost['search']['value'])) {
                $termino_busqueda = '%'.$datapost['search']['value'].'%';
                $sentencia_filter->bindParam(':termino', $termino_busqueda, PDO::PARAM_STR);
            }
            $sentencia_filter->execute();
            $result_filter= $sentencia_filter->fetch();
            $total_filas_filtradas = $result_filter['total'];
        } catch (Exception $e) {
            $this->saveLogger($e);
            $msg_excepcion = $e->getMessage();
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['codigo'] = 'Error002';
            $resp['mensaje'] = 'Error al extraer la lista de movimientos de traslados';
            return $resp;
        }
        //Fin: Total registros filtrados

        $sql_list_items = "SELECT $select_columns FROM $from WHERE $where ";
        $sql_list_items = $sql_list_items." ORDER BY ".$columnas[$datapost['order'][0]['column']]." ".$datapost['order'][0]['dir']." LIMIT ".intval($datapost['start']).",".intval($datapost['length'])."";

        try {
            $sentencia_list_items = $this->db->prepare($sql_list_items);
            $sentencia_list_items->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
            $sentencia_list_items->bindParam(':tipo_envio_sunat', $tipo_envio_sunat, PDO::PARAM_STR);
            $sentencia_list_items->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            $sentencia_list_items->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
            if(!empty($datapost['search']['value'])) {
                $termino_busqueda = '%'.$datapost['search']['value'].'%';
                $sentencia_list_items->bindParam(':termino', $termino_busqueda, PDO::PARAM_STR);
            }
            $sentencia_list_items->execute();
        } catch (Exception $e) {
            $this->saveLogger($e);
            $msg_excepcion = $e->getMessage();
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['codigo'] = 'Error003';
            $resp['mensaje'] = 'Error al extraer la lista de movimientos de salidas..';
            return $resp;
        }

        $dominio_principal = $this->get_parametros_iniciales()['url_domain'];
        $lista_item = array();
        $n = 0;
        while($fila = $sentencia_list_items->fetch()) {
            $item = (object)$fila;
            
            $cadena_pdf_a4 = "$id_contribuyente||$item->id_tipo_movimiento||$item->serie||$item->correlativo||$item->tipo_envio_sunat||a4";
            $cadena_pdf_ticket = "$id_contribuyente||$item->id_tipo_movimiento||$item->serie||$item->correlativo||$item->tipo_envio_sunat||ticket";
            
            $cadena_pdf_a4_encriptada = $herramientas->encriptar($cadena_pdf_a4);
            $cadena_pdf_ticket_encriptada = $herramientas->encriptar($cadena_pdf_ticket);

            $url_a4 = 'https://'.$dominio_principal.'/facturacionv8/productomovimientos/print_pdf/?file='.urlencode($cadena_pdf_a4_encriptada);
		    $url_ticket = 'https://'.$dominio_principal.'/facturacionv8/productomovimientos/print_pdf/?file='.urlencode($cadena_pdf_ticket_encriptada);

            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $item->id_usuario)));
            $nombre_usuario = '';
            if($usuario) {
                $nombre_usuario = $usuario->nombre.' '.$usuario->apellido." (ID: $usuario->idusuario)";
            }

            $info_sucursal_origen = '';
            $info_sucursal_destino = '';

            $sucursal_origen = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $item->id_sucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
            if($sucursal_origen) {
                $info_sucursal_origen = $sucursal_origen->nombre." (ID: $sucursal_origen->idsucursal)";
            }

            $sucursal_destino = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $item->destino_id_sucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
            if($sucursal_destino) {
                $info_sucursal_destino = $sucursal_destino->nombre." (ID: $sucursal_destino->idsucursal)";
            }

            $lista_item[] = array(
                'serie_correlativo'     => $item->serie.' - '.$item->correlativo, 
                'fecha_movimiento'      => date("d-m-Y / H:i A", strtotime($item->fecha_movimiento)),
                'usuario'               => $nombre_usuario,
                'id_sucursal'           => $item->id_sucursal,
                'info_sucursal_origen'  => $info_sucursal_origen,
                
                'id_sucursal_destino'   => $item->destino_id_sucursal,
                'info_sucursal_destino' => $info_sucursal_destino,
                'nota'                  => $item->nota,
                

                'opciones'              => '<ul class="icons-list">
                                                <li class="dropdown">
                                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                        <i class="icon-menu9"></i>
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                        <li><a target="_blank" href="'.$url_a4.'"><img title="Formato A4" src="/facturacionv8/img/svg/pdf_cpe.svg" style="width: 18px; cursor: pointer;"> Ver PDF - A4</a></li>
                                                        <li style="display:none;"><a target="_blank" href="'.$url_ticket.'"><img title="Formato Ticket" src="/facturacionv8/img/svg/ticket_cpe.svg" style="width: 18px; cursor: pointer;"> Ver PDF - Ticket</a></li>
                                                    </ul> 
                                                </li>
                                            </ul>'
            );
        }

        $json_data = array(
            "draw"            => intval($datapost['draw']),
            "recordsTotal"    => intval($total_registros),  //Número total de registros que cumplen con los criterios iniciales
            "recordsFiltered" => $total_filas_filtradas, //Total registros filtrados
            "data"            => $lista_item,   // total data array
            "numero_n"        => $n
        );

        $resp['respuesta'] = 'ok';
        $resp['json_data'] = $json_data;

        return $resp;
    }

    public function get_traslados($data) {
        $herramientas = new HerramientasController;
        $datapost = $data['datapost'];
        $id_contribuyente = intval($data['id_contribuyente']) + 0;
        $id_sucursal = intval($data['id_sucursal']) + 0;
        $fecha_inicio = $data['fecha_inicio'];
        $fecha_fin = $data['fecha_fin'];
        $id_producto = isset($data['id_producto'])?intval($data['id_producto']) + 0:0;
        $tipo_envio_sunat = $data['tipo_envio_sunat'];
        $id_sucursal_destino = isset($data['id_sucursal_destino'])?intval($data['id_sucursal_destino']) + 0:0;

        $select_items = " mov.id_usuario, mov.id_contribuyente, mov.id_tipo_movimiento, mov.serie, mov.correlativo, CONCAT(mov.serie,'-',mov.correlativo) as serie_correlativo, mov.tipo_envio_sunat, mov.tipo, mov.id_sucursal, mov.fecha_movimiento, mov.id_usuario, mov.nota, mov.estado, mov.destino_id_sucursal, mov.destino_id_contribuyente, det.o_id_prod, det.o_nom_prod, det.o_id_u_medida, det.o_cod_prod, det.d_id_prod, det.d_cod_prod, det.d_nom_prod, det.d_id_u_medida, det.cantidad ";

        $from = " producto_movimiento mov INNER JOIN producto_movimiento_det det ON (mov.id_contribuyente = det.id_contribuyente and mov.id_tipo_movimiento = det.id_tipo_movimiento and mov.serie = det.serie and mov.correlativo = det.correlativo and mov.tipo_envio_sunat = det.tipo_envio_sunat) ";

        $where = " det.id_tipo_movimiento = 'ts' and det.tipo_envio_sunat = '$tipo_envio_sunat' and mov.estado = 'activo' and mov.id_contribuyente = $id_contribuyente and (CAST(mov.fecha_movimiento AS DATE) BETWEEN CAST(:fecha_inicio AS DATE) and CAST(:fecha_fin AS DATE)) ";

        $select_count = " count(DISTINCT det.id_movimiento_det) as total ";

        if($id_producto > 0) {
            $where = $where." and det.o_id_prod = $id_producto ";
        }

        if($id_sucursal > 0) {
            $where = $where." and mov.id_sucursal = $id_sucursal ";
        }

        if($id_sucursal_destino > 0) {
            $where = $where." and mov.destino_id_sucursal = $id_sucursal_destino ";
        }
        
        $sql_count_items = "SELECT $select_count FROM $from WHERE $where";
        
        //Inicio: Total Registros
        try {
            $sentencia_count = $this->db->prepare($sql_count_items);
            $sentencia_count->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            $sentencia_count->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
            $sentencia_count->execute();
            $result_count = $sentencia_count->fetch();
            $total_registros = $result_count['total'];

        } catch (Exception $e) {
            $this->saveLogger($e);
            $msg_excepcion = $e->getMessage();
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['codigo'] = 'Error001';
            $resp['mensaje'] = 'Error al extraer la lista de movimientos de salida';
            return $resp;
        }
        //fin: Total Registros

        $columnas = array(
            0   => 'mov.correlativo',
            1   => 'mov.tipo',
            2   => 'mov.id_sucursal',
            3   => 'mov.fecha_movimiento',
            4   => 'mov.nota',

            5   => 'det.o_id_prod',
            6   => 'det.o_cod_prod',
            7   => 'det.o_nom_prod',

            8   => 'det.cantidad',

            9   => 'det.d_id_prod',
            10  => 'det.d_cod_prod',
            11  => 'det.d_nom_prod',

            12  => 'mov.fecha_movimiento' //menu
        );

        if(!empty($datapost['search']['value'])) {
            $where = $where." AND (mov.serie like :termino or mov.correlativo like :termino or mov.tipo like :termino or mov.id_sucursal like :termino or mov.nota like :termino or det.o_id_prod like :termino or det.o_cod_prod like :termino or det.o_nom_prod like :termino) ";
        }

        //Inicio: Total registros filtrados
        $sql_count_items = "SELECT $select_count FROM $from WHERE $where ";
        try {
            $sentencia_filter = $this->db->prepare($sql_count_items);
            $sentencia_filter->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            $sentencia_filter->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
            if(!empty($datapost['search']['value'])) {
                $termino_busqueda = '%'.$datapost['search']['value'].'%';
                $sentencia_filter->bindParam(':termino', $termino_busqueda, PDO::PARAM_STR);
            }
            $sentencia_filter->execute();
            $result_filter= $sentencia_filter->fetch();
            $total_filas_filtradas = $result_filter['total'];
        } catch (Exception $e) {
            $this->saveLogger($e);
            $msg_excepcion = $e->getMessage();
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['codigo'] = 'Error002';
            $resp['mensaje'] = 'Error al extraer la lista de movimientos de traslados';
            return $resp;
        }
        //Fin: Total registros filtrados

        $sql_list_items = "SELECT $select_items FROM $from WHERE $where ";
        $sql_list_items = $sql_list_items." ORDER BY ".$columnas[$datapost['order'][0]['column']]." ".$datapost['order'][0]['dir']." LIMIT ".intval($datapost['start']).",".intval($datapost['length'])."";
        
        /*
        $sql_list_items = str_replace(':fecha_inicio', "'$fecha_inicio'", $sql_list_items);
        $sql_list_items = str_replace(':fecha_fin', "'$fecha_fin'", $sql_list_items);
        echo $sql_list_items;
        exit();
        */

        try {
            $sentencia_list_items = $this->db->prepare($sql_list_items);
            $sentencia_list_items->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            $sentencia_list_items->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
            if(!empty($datapost['search']['value'])) {
                $termino_busqueda = '%'.$datapost['search']['value'].'%';
                $sentencia_list_items->bindParam(':termino', $termino_busqueda, PDO::PARAM_STR);
            }
            $sentencia_list_items->execute();
        } catch (Exception $e) {
            $this->saveLogger($e);
            $msg_excepcion = $e->getMessage();
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['codigo'] = 'Error003';
            $resp['mensaje'] = 'Error al extraer la lista de movimientos de salidas..';
            return $resp;
        }
        
        $dominio_principal = $this->get_parametros_iniciales()['url_domain'];
        $lista_item = array();
        $n = 0;
        while($fila = $sentencia_list_items->fetch()) {
            $item = (object)$fila;

            $cadena_pdf_a4 = "$id_contribuyente||$item->id_tipo_movimiento||$item->serie||$item->correlativo||$item->tipo_envio_sunat||a4";
            $cadena_pdf_ticket = "$id_contribuyente||$item->id_tipo_movimiento||$item->serie||$item->correlativo||$item->tipo_envio_sunat||ticket";
            
            $cadena_pdf_a4_encriptada = $herramientas->encriptar($cadena_pdf_a4);
            $cadena_pdf_ticket_encriptada = $herramientas->encriptar($cadena_pdf_ticket);

            $url_a4 = 'https://'.$dominio_principal.'/facturacionv8/productomovimientos/print_pdf/?file='.urlencode($cadena_pdf_a4_encriptada);
		    $url_ticket = 'https://'.$dominio_principal.'/facturacionv8/productomovimientos/print_pdf/?file='.urlencode($cadena_pdf_ticket_encriptada);

            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $item->id_usuario)));
            $nombre_usuario = '';
            if($usuario) {
                $nombre_usuario = $usuario->nombre.' '.$usuario->apellido." (ID: $usuario->idusuario)";
            }

            $info_sucursal_origen = '';
            $info_sucursal_destino = '';

            $sucursal_origen = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $item->id_sucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
            if($sucursal_origen) {
                $info_sucursal_origen = $sucursal_origen->nombre." (ID: $sucursal_origen->idsucursal)";
            }

            $sucursal_destino = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $item->destino_id_sucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
            if($sucursal_destino) {
                $info_sucursal_destino = $sucursal_destino->nombre." (ID: $sucursal_destino->idsucursal)";
            }

            $lista_item[] = array(
                'serie_correlativo'     => $item->serie_correlativo, 
                'mov_tipo'              => $item->tipo,
                'mov_id_sucursal'       => $item->id_sucursal,
                'mov_fecha_movimiento'  => date("d-m-Y", strtotime($item->fecha_movimiento)),
                'mov_nota'              => $item->nota,

                'id_sucursal_origen'    => $item->id_sucursal,
                'info_sucursal_origen'  => $info_sucursal_origen,
                'det_o_id_prod'         => $item->o_id_prod,
                'det_o_cod_prod'        => $item->o_cod_prod,
                'det_o_nom_prod'        => $item->o_nom_prod,

                'det_cantidad'          => $item->cantidad + 0,

                'id_sucursal_destino'   => $item->destino_id_sucursal,
                'info_sucursal_destino' => $info_sucursal_destino,
                'det_d_id_prod'         => $item->d_id_prod,
                'det_d_cod_prod'        => $item->d_cod_prod,
                'det_d_nom_prod'        => $item->d_nom_prod,

                'usuario'               => $nombre_usuario,

                'opciones'              => '<ul class="icons-list">
                                                <li class="dropdown">
                                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                        <i class="icon-menu9"></i>
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                        <li><a target="_blank" href="'.$url_a4.'"><img title="Formato A4" src="/facturacionv8/img/svg/pdf_cpe.svg" style="width: 18px; cursor: pointer;"> Ver PDF - A4</a></li>
                                                        <li><a target="_blank" href="'.$url_ticket.'"><img title="Formato Ticket" src="/facturacionv8/img/svg/ticket_cpe.svg" style="width: 18px; cursor: pointer;"> Ver PDF - Ticket</a></li>
                                                    </ul> 
                                                </li>
                                            </ul>'
            );
        }

        $json_data = array(
            "draw"            => intval($datapost['draw']),
            "recordsTotal"    => intval($total_registros),  //Número total de registros que cumplen con los criterios iniciales
            "recordsFiltered" => $total_filas_filtradas, //Total registros filtrados
            "data"            => $lista_item,   // total data array
            "numero_n"        => $n
        );

        $resp['respuesta'] = 'ok';
        $resp['json_data'] = $json_data;

        return $resp;
    }

    public function getListaMovimientosAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            //Datos de sesion
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Usuario';
                $resp['mensaje'] = 'Lo sentimos! Es posible que se haya cerrado la sessión, <a style="color: #1c81d1;" href="/session" target="_blank">haz click aquí para ingresar de nuevo</a>, luego de iniciar sessión, puedes regresar a esta pantalla y recargar la página y continuar...';
                echo json_encode($resp);
                exit();
            }
            
            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            if(!$contribuyente) { 
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No existe la empresa que intenta configurar!!';
                echo json_encode($resp);
                exit();
            }

            $datapost = $this->request->getPost();
            $herramientas = new HerramientasController;

            $idsucursal = intval($datapost['id_sucursal']) + 0;
            $idproducto = isset($datapost['id_producto'])?intval($datapost['id_producto']) + 0:0;
            $fecha_inicio = empty($datapost['fecha_inicio'])?date("d/m/Y"):$datapost['fecha_inicio'];
			$fecha_fin = empty($datapost['fecha_fin'])?date("d/m/Y"):$datapost['fecha_fin'];

            $sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $idsucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
            if(!$sucursal) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'La sucursal seleccionada no existe!';
                echo json_encode($resp);
                exit();
            }

            if($idproducto > 0) {
                $producto = Producto::findFirst(array("idproducto = :idproducto: and id_contribuyente = :id_contribuyente: and idsucursal = :idsucursal:", 'bind' => array('idproducto' => $idproducto, 'id_contribuyente' => $usuario->id_contribuyente, 'idsucursal' => $idsucursal)));
                if(!$producto) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'El producto seleccionado no existe!';
                    echo json_encode($resp);
                    exit();
                }
            }
            
            $array_fecha_inicio = explode('/',!isset($datapost['fecha_inicio'])?date('d/m/Y'):$datapost['fecha_inicio']);
			$array_fecha_fin = explode('/',!isset($datapost['fecha_fin'])?date('d/m/Y'):$datapost['fecha_fin']);
            $fecha_inicio = $array_fecha_inicio[2].'-'.$array_fecha_inicio[1].'-'.$array_fecha_inicio[0];
			$fecha_fin = $array_fecha_fin[2].'-'.$array_fecha_fin[1].'-'.$array_fecha_fin[0];

			$herramientas = new HerramientasController;
			if(!$herramientas->validate_date($fecha_inicio)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en la Fecha de Inicio';
				$resp['mensaje'] = 'El formato de la fecha inicial ingresada no es válido!';
				echo json_encode($resp);
                exit();
			}

			if(!$herramientas->validate_date($fecha_fin)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en la Fecha de Término';
				$resp['mensaje'] = 'El formato de la fecha final ingresada no es válido!';
				echo json_encode($resp);
                exit();
			}

            $tipo = isset($datapost['tipo_movimiento'])?$datapost['tipo_movimiento']:'';
            if($tipo != 'ingreso' && $tipo != 'salida' && $tipo != 'traslado' && $tipo != 'lista_transformacion'  && $tipo != 'lista_detalle_transformacion') {
                $resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'El tipo de movimiento no se reconoce';
				echo json_encode($resp);
                exit();
            }
            
            $data['datapost'] = $datapost;
            $data['id_contribuyente'] = $usuario->id_contribuyente;
            $data['id_sucursal'] = $idsucursal;
            $data['fecha_inicio'] = $fecha_inicio;
            $data['fecha_fin'] = $fecha_fin;
            $data['id_producto'] = $idproducto;
            $data['tipo_envio_sunat'] = $contribuyente->tipo_envio_sunat;

            if($tipo == 'ingreso') {
                $resp = $this->get_ingresos($data);
            } else if($tipo == 'salida') {
                $resp = $this->get_salidas($data);
            } else if($tipo == 'traslado') {
                $id_sucursal_destino = intval($datapost['id_sucursal_destino']) + 0;
                if($id_sucursal_destino > 0) {
                    $sucursal_destino = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $id_sucursal_destino, 'id_contribuyente' => $usuario->id_contribuyente)));
                    if(!$sucursal_destino) {
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = 'La sucursal destino seleccionada no existe!';
                        echo json_encode($resp);
                        exit();
                    }

                    $data['id_sucursal_destino'] = $id_sucursal_destino;
                }

                $resp = $this->get_traslados($data);
            } else if($tipo == 'lista_transformacion') {
                $id_sucursal_destino = intval($datapost['id_sucursal_destino']) + 0;
                if($id_sucursal_destino > 0) {
                    $sucursal_destino = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $id_sucursal_destino, 'id_contribuyente' => $usuario->id_contribuyente)));
                    if(!$sucursal_destino) {
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = 'La sucursal destino seleccionada no existe!';
                        echo json_encode($resp);
                        exit();
                    }

                    $data['id_sucursal_destino'] = $id_sucursal_destino;
                }

                $resp = $this->get_lista_transformaciones($data);
            } else if($tipo == 'lista_detalle_transformacion') {
                $id_sucursal_destino = intval($datapost['id_sucursal_destino']) + 0;
                if($id_sucursal_destino > 0) {
                    $sucursal_destino = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $id_sucursal_destino, 'id_contribuyente' => $usuario->id_contribuyente)));
                    if(!$sucursal_destino) {
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = 'La sucursal destino seleccionada no existe!';
                        echo json_encode($resp);
                        exit();
                    }

                    $data['id_sucursal_destino'] = $id_sucursal_destino;
                }
                
                $resp = $this->get_lista_transformaciones_detalle($data);
            }
            
            if($resp['respuesta'] == 'error') {
                echo json_encode($resp);
                exit();
            }
            
            echo json_encode($resp['json_data']);
            exit();
        }
    }

    public function printPdfAction() {
        $cadena_cifrada = $_GET['file'];
        $herramientas = new HerramientasController;
		$cadena_decifrada = $herramientas->desencriptar($cadena_cifrada);

        $array_data = explode('||', $cadena_decifrada);
		$id_contribuyente = !isset($array_data[0])?'':$array_data[0];
		$id_tipo_movimiento = !isset($array_data[1])?'':$array_data[1];
		$serie = !isset($array_data[2])?'':$array_data[2];
		$correlativo = !isset($array_data[3])?'':$array_data[3];
		$tipo_envio_sunat = !isset($array_data[4])?'':$array_data[4];
        $tamanio = !isset($array_data[5])?'':$array_data[5];
        
        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			return $this->dispatcher->forward(array(
				"controller" => "errors",
				"action" => "show404"
			));
		}

        if(empty($contribuyente->logo_350)) {
			$style_logo = 'style="width: 100px; height: 100px"';
			$img_logo = str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->img_logo);
		} else {
			$style_logo = 'style="width: 320px;"';
			$img_logo = str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->logo_350);
		}

        if($tamanio != 'ticket' && $tamanio != 'a4') {
			return $this->dispatcher->forward(array(
				"controller" => "errors",
				"action" => "show404"
			));
		}

        if($id_tipo_movimiento != 'in' && $id_tipo_movimiento != 'sa' && $id_tipo_movimiento != 'ts' && $id_tipo_movimiento != 'tr') {
            return $this->dispatcher->forward(array(
				"controller" => "errors",
				"action" => "show404"
			));
        }

        $movimiento_almacen = ProductoMovimiento::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipo_movimiento = :id_tipo_movimiento: and serie = :serie: and correlativo = :correlativo: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipo_movimiento' => $id_tipo_movimiento, 'serie' => $serie, 'correlativo' => $correlativo, 'tipo_envio_sunat' => $tipo_envio_sunat)));

        if(!$movimiento_almacen) {
            return $this->dispatcher->forward(array(
				"controller" => "errors",
				"action" => "show404"
			));
        }

        $nombre_movimiento = '';
        if($movimiento_almacen->id_tipo_movimiento == 'in') {
            $nombre_movimiento = 'Registro de Ingreso';
        } else if($movimiento_almacen->id_tipo_movimiento == 'sa') {
            $nombre_movimiento = 'Registro de Salida';
        } else if($movimiento_almacen->id_tipo_movimiento == 'ts') {
            $nombre_movimiento = 'Registro de Traslado';
        } else if($movimiento_almacen->id_tipo_movimiento == 'tr') {
            $nombre_movimiento = 'Registro de Transformación';
        }

        $sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $movimiento_almacen->id_sucursal, 'id_contribuyente' => $id_contribuyente)));
        if(!$sucursal) {
            return $this->dispatcher->forward(array(
				"controller" => "errors",
				"action" => "show404"
			));
        }

        $info_sucursal_destino = '';
        if($movimiento_almacen->id_tipo_movimiento == 'ts' || $movimiento_almacen->id_tipo_movimiento == 'tr') {
            $sucursal_destino = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $movimiento_almacen->destino_id_sucursal, 'id_contribuyente' => $id_contribuyente)));
            if(!$sucursal_destino) {
                return $this->dispatcher->forward(array(
                    "controller" => "errors",
                    "action" => "show404"
                ));
            }
            $info_sucursal_destino = $sucursal_destino->nombre." (ID: $sucursal_destino->idsucursal)";
        }

        $usuario = Usuario::findFirst(array("idusuario = :idusuario: and id_contribuyente = :id_contribuyente:", 'bind' => array('idusuario' => $movimiento_almacen->id_usuario, 'id_contribuyente' => $id_contribuyente)));
        if(!$usuario) {
            return $this->dispatcher->forward(array(
				"controller" => "errors",
				"action" => "show404"
			));
        }

        $data['cabecera']  = array(
            'serie'             => $serie,
            'correlativo'       => $correlativo,
            'nombre_cpe'        => $nombre_movimiento,
            'fecha_registro'    => date("d-m-Y / H:i A",strtotime($movimiento_almacen->fecha_movimiento)),
            'sucursal'          => $sucursal->nombre." (ID: $sucursal->idsucursal)",
            'usuario'           => $usuario->nombre.' '.$usuario->apellido." (ID: $usuario->idusuario)",
            'estado_documento'  => $movimiento_almacen->estado,
            'nota'              => $movimiento_almacen->nota,
            'sucursal_destino'  => $info_sucursal_destino,
            'id_tipo_movimiento' => $movimiento_almacen->id_tipo_movimiento
        );

        $img_logo_cuadrado = empty($contribuyente->img_logo)?'':str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->img_logo);
		$img_logo_rectangular = empty($contribuyente->logo_350)?'':str_replace('/facturacionv8/herramientas/verimage/', '/facturacionv8/public/files/upload_user/', $contribuyente->logo_350);

        $ruta_base = $this->ruta_base_public_html;
		$img_logo_cuadrado = empty($img_logo_cuadrado)?'':$ruta_base.$img_logo_cuadrado;
		$img_logo_rectangular = empty($img_logo_rectangular)?'':$ruta_base.$img_logo_rectangular;

        $ubigeo_ubicacion = '';
		$ubigeo = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $sucursal->id_ubigeo)));
		if($ubigeo) {
			$ubigeo_ubicacion = $ubigeo->distrito.', '.$ubigeo->provincia.', '.$ubigeo->departamento;
		}

        $data['emisor']  = array(
            'razon_social' 			=> $contribuyente->razon_social,
			'nombre_comercial' 		=> $contribuyente->nombre_comercial,
			'direccion' 			=> $sucursal->direccion,
			'ubigeo' 				=> $ubigeo_ubicacion, //ubicación
			'telefono' 				=> $sucursal->telefono,
			'email' 				=> $sucursal->email,
			'sitio_web' 			=> $sucursal->sitio_web,
			'ruc' 					=> $contribuyente->ruc,
			'logo_rectangular'		=> $img_logo_rectangular,
			'logo_cuadrado' 		=> $img_logo_cuadrado,
			'tipo_envio_sunat'		=> $tipo_envio_sunat,
			'ruta_base'				=> $ruta_base
        );
        
        $lista_items = ProductoMovimientoDet::find(array("id_contribuyente = :id_contribuyente: and id_tipo_movimiento = :id_tipo_movimiento: and serie = :serie: and correlativo = :correlativo: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipo_movimiento' => $id_tipo_movimiento, 'serie' => $serie, 'correlativo' => $correlativo, 'tipo_envio_sunat' => $tipo_envio_sunat)));

        if($movimiento_almacen->id_tipo_movimiento != 'tr') {
            $n = 0;
            foreach($lista_items as $item) {
                $n++;
    
                $unidad_medida_sunat = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $item->o_id_u_medida)));
    
                $data['detalle'][] = array(
                    'nro_item'      => $n,
                    'codigo'        => $item->o_cod_prod,
                    'descripcion'   => $item->o_nom_prod,
                    'unidad_medida' => $unidad_medida_sunat->nombre,
                    'cantidad'      => $item->cantidad + 0
                );
            }
        }

        $data_patrocinador = $this->get_parametros_iniciales();
        $logo_patrocinador = 'https://'.$data_patrocinador['url_domain'].$data_patrocinador['logo_img_461'];
        
        $template_movimientos = new ProductomovimientospdfController;
        $html = '';
        if($movimiento_almacen->id_tipo_movimiento == 'in') {
            if($tamanio == 'a4') {
                $html = $template_movimientos->template_salida_entrada_a4_1($data);
            } else {
                $html = $template_movimientos->template_salida_entrada_ticket_1($data);
            }
        } else if($movimiento_almacen->id_tipo_movimiento == 'sa') {
            if($tamanio == 'a4') {
                $html = $template_movimientos->template_salida_entrada_a4_1($data);
            } else {
                $html = $template_movimientos->template_salida_entrada_ticket_1($data);
            }
        } else if($movimiento_almacen->id_tipo_movimiento == 'ts') {
            if($tamanio == 'a4') {
                $html = $template_movimientos->template_salida_entrada_a4_1($data);
            } else {
                $html = $template_movimientos->template_salida_entrada_ticket_1($data);
            }
        } else if($movimiento_almacen->id_tipo_movimiento == 'tr') {
            $data_transferencia['id_contribuyente']     = $id_contribuyente;
            $data_transferencia['id_tipo_movimiento']   = $id_tipo_movimiento;
            $data_transferencia['serie']                = $serie;
            $data_transferencia['correlativo']          = $correlativo;
            $data_transferencia['tipo_envio_sunat']     = $tipo_envio_sunat;
            $resp_lista_prod = $this->get_detalle_transformacion($data_transferencia);
            $data['lista_productos_inicial'] = $resp_lista_prod['lista_productos_inicial'];
            $data['lista_productos_finales'] = $resp_lista_prod['lista_productos_finales'];
            
            if($tamanio == 'a4') {
                $html = $template_movimientos->template_transformacion_a4_1($data);
            } else {
                $html = $template_movimientos->template_transformacion_ticket_1($data);
            }
        }

        $nombre_archivo = 'Registro_Movimiento_'.$serie.'-'.$correlativo.'.pdf';
		$snappy = new Pdf('/usr/local/bin/wkhtmltopdf');
        if(isset($this->snappy_zoom) && $this->snappy_zoom > 0) {
			$snappy->setOption('zoom', $this->snappy_zoom);
		}
		$snappy->setOption('enable-local-file-access', true);
		header('Content-Type: application/pdf');
		header('Content-Disposition: inline; filename="'.$nombre_archivo.'"');
        
        if($tamanio == 'ticket') {
            $html = $html.'
            <div style="margin-top: 25px; padding-left: 0; padding-right: 0; padding-top: 0; padding-bottom: 15px;">
                <div class="text-center">
                    <img src="'.$logo_patrocinador.'" style="width: 140px !important;" class="margin-0"><p class="margin-0" style="font-style: italic; font-size:12px;">Emitido por: <span class="font-weight-bold">'.$data_patrocinador['url_domain'].'</span></p>
                </div>
            </div>
            ';

            $snappy->setOption('margin-left', '1mm');
			$snappy->setOption('margin-right', '1mm');
			$snappy->setOption('margin-top', '2mm');
			$snappy->setOption('margin-bottom', '5mm');
            echo $snappy->getOutputFromHtml($html, array('page-height' =>  200,'page-width' => 78));
            exit();
        } else {
            $html_header = '
    		<!DOCTYPE html>
    		<html>
			<body style="margin: 0; padding-left: 0; padding-right: 0; padding-top: 15px; padding-bottom: 15px;">
    				<div class="text-center" style="height: 50px">
    					<p>hola</p>
    				</div>
    			</body>
    		</html>
    		';
			
			$html_footer = '
    		<!DOCTYPE html>
    		<html>
    			<style>
    				.text-center{
    					text-align: center;
    				}
    				.font-weight-bold{
    					font-weight: bold;
    				}
    				.margin-0{
    					margin: 0;
    					padding-left: 0;
						padding-right: 0;
						padding-top: 0;
						padding-bottom: 0;
    				}
    			</style>
    			<body style="margin: 0; padding-left: 0; padding-right: 0; padding-top: 0; padding-bottom: 15px;">
    				<div class="text-center">
    					<img src="'.$logo_patrocinador.'" style="width: 140px !important;" class="margin-0"><p class="margin-0" style="font-style: italic; font-size:12px;">Emitido por: <span class="font-weight-bold">'.$data_patrocinador['url_domain'].'</span></p>
    				</div>
    			</body>
    		</html>
    		';

            $snappy->setOption('header-html', $html_header);
			$snappy->setOption('footer-html', $html_footer);

			$snappy->setOption('margin-top', '2mm');
			$snappy->setOption('margin-left', '9mm');
			$snappy->setOption('margin-right', '9mm');
			
			echo $snappy->getOutputFromHtml($html);
            exit();
        }

        echo $html;
        exit();

    }

    public function get_detalle_transformacion($data) {
        $id_contribuyente   = $data['id_contribuyente'];
        $id_tipo_movimiento = $data['id_tipo_movimiento'];
        $serie              = $data['serie'];
        $correlativo        = $data['correlativo'];
        $tipo_envio_sunat   = $data['tipo_envio_sunat'];

        $lista_items = ProductoMovimientoDet::find(array("id_contribuyente = :id_contribuyente: and id_tipo_movimiento = :id_tipo_movimiento: and serie = :serie: and correlativo = :correlativo: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipo_movimiento' => $id_tipo_movimiento, 'serie' => $serie, 'correlativo' => $correlativo, 'tipo_envio_sunat' => $tipo_envio_sunat)));
        
        $lista_productos_inicial = array();
        $lista_productos_finales = array();
        foreach($lista_items as $item) {
            if(!empty($item->o_id_prod)) {
                $lista_productos_inicial[] = array(
                    'cantidad'          => $item->cantidad,
                    'costo_unitario'    => $item->costo_unitario,
                    'id_u_medida'       => $item->o_id_u_medida,
                    'u_medida'          => $item->o_u_medida,
                    'precio'            => $item->o_precio,
                    'id_afectigv'       => $item->o_id_afectigv,
                    'tipo_unidad'       => $item->o_tipo_unidad,
                    'id_presentacion'   => $item->o_id_presentacion,
                    'cod_prod'          => $item->o_cod_prod,
                    'id_prod'           => $item->o_id_prod,
                    'nom_prod'          => $item->o_nom_prod,
                    'id_codigomoneda'   => $item->id_codigomoneda,
                    'simbolo_moneda'    => ($item->id_codigomoneda == 'PEN')?'S/.':'USD'
                );
            } else {
                $lista_productos_finales[] = array(
                    'cantidad'          => $item->cantidad,
                    'costo_unitario'    => $item->costo_unitario,
                    'id_u_medida'       => $item->d_id_u_medida,
                    'u_medida'          => $item->d_u_medida,
                    'precio'            => $item->d_precio,
                    'id_afectigv'       => $item->d_id_afectigv,
                    'tipo_unidad'       => $item->d_tipo_unidad,
                    'id_presentacion'   => $item->d_id_presentacion,
                    'cod_prod'          => $item->d_cod_prod,
                    'id_prod'           => $item->d_id_prod,
                    'nom_prod'          => $item->d_nom_prod,
                    'id_codigomoneda'   => $item->id_codigomoneda,
                    'simbolo_moneda'    => ($item->id_codigomoneda == 'PEN')?'S/.':'USD'
                );
            }
        }

        $resp['respuesta'] = 'ok';
        $resp['lista_productos_inicial'] = $lista_productos_inicial;
        $resp['lista_productos_finales'] = $lista_productos_finales;
        return $resp;
    }
}

?>