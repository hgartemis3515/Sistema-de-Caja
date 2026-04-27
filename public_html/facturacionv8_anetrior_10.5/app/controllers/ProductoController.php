<?php
//require $_SERVER["DOCUMENT_ROOT"]."/facturacionv8/apis/phpspreadsheet/vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ProductoController extends ControllerBase
{
	public function indexAction($idproducto = 0) {
		$this->setTitle('Agregar Producto');
        $this->view->setTemplateAfter('template_new'); 

        $this->assets
        ->addCss($this->baseUri . "public/css/new_style.css");
        $this->assets 
        
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/switchery.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/uniform.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/selects/select2.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/datatables.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/uploaders/fileinput/fileinput.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/extensions/buttons.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/extensions/buttons.min.js?i=v2")
        ->addJs($this->baseUri . "public/extras/editor193/js/dataTables.editor.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/core/app.js?i=v2")
        ->addJs($this->baseUri . "public/js/general.js?j=".rand()) 
        ->addJs($this->baseUri . "public/js/apisunat.js?i=v3")
        ->addJs($this->baseUri . "public/js/producto.js?i=".rand());
        
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

        $codigosunat = SunatCodigoproducto::find();
        $this->view->codigosunat = $codigosunat;
        $this->view->idproducto = $idproducto;

        return $this->response->redirect('producto/listaproductos');
    }

    public function actualizarProductosAction() {
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

            //Datos de Formulario
            $datapost = $this->request->getPost();
            $confirmacion = !isset($datapost['confirmacion'])?'no':$datapost['confirmacion'];
            $idsucursal = !isset($datapost['select_sucursal'])?0:intval($datapost['select_sucursal']) + 0;
            $opcion_actualizacion_total = !isset($datapost['opcion_actualizacion_total'])?'no':($datapost['opcion_actualizacion_total'] == 'on'?'si':'no');
            $opcion_actualizacion_total = ($opcion_actualizacion_total == 'si')?'si':'no';
            
            if(!isset($_FILES['archivo'])) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes Agregar un Archivo!';
                echo json_encode($resp);
                exit();
            }
            
            //https://phppot.com/php/import-excel-file-into-mysql-database-using-php/
            $allowedFileType = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
            if(!in_array($_FILES["archivo"]["type"], $allowedFileType)){
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No es un archivo válido!';
                echo json_encode($resp);
                exit();
            }

            $nombre_temporal = 'temp'.uniqid().$_FILES['archivo']['name'];
            $targetPath = $_SERVER["DOCUMENT_ROOT"]."/facturacionv8/apis/apiexcel/temporales/".$nombre_temporal;

            try {
                move_uploaded_file($_FILES['archivo']['tmp_name'], $targetPath);
            } catch (Exception $e) {
                $this->saveLogger($e);
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No hemos logrado leer el archivo';
                //$resp['mensaje'] = $e->getMessage();
                echo json_encode($resp);
                exit();
            }

            try {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                $spreadsheet = $reader->load($targetPath);
                $spreadsheet->getSheet(0);
                $filas = $spreadsheet->getActiveSheet()->toArray();
                $resp_actualizacion = $this->iniciar_proceso_actualizacion($filas, $contribuyente, $usuario, $confirmacion, $opcion_actualizacion_total);
            } catch (Exception $e) {
                $this->saveLogger($e);
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No hemos logrado leer el archivo';
                //$resp['mensaje'] = $e->getMessage();
                echo json_encode($resp);
                exit();
            }
            echo json_encode($resp_actualizacion);
            unlink($targetPath);
            exit();
        }
    }

    public function iniciar_proceso_actualizacion($Reader, $contribuyente, $usuario, $confirmacion, $opcion_actualizacion_total = 'no') {

        if($confirmacion == 'si') {
            $this->db->begin();
        }

        $n = 0;
        $num_prod_sin_cate = 0;
        $array_code_prod = array();
        $gestion_usuarios = new GestionuserController;
        $herramientas = new HerramientasController;

        $lista_detalle_ingresos = array();
        $lista_detalle_salidas = array();

        foreach ($Reader as $row) {
            if($n > 0) {
                $lista_precios = array();
                $idsucursal = !isset($row[0])?0:intval($row[0]);
                $idproducto = !isset($row[1])?0:intval($row[1]);
                $codigoproducto = !isset($row[2])?0:$row[2];
                $id_categoria = !isset($row[3])?0:intval($row[3]);
                $nombre = !isset($row[4])?'':trim($row[4]);
                $id_unidadmedida = !isset($row[5])?0:intval($row[5]);

                if($id_unidadmedida <= 0) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'En la línea N° '.($n + 1).', el ID de Unidad de Medida es inválido!';
                    return $resp;
                }

                $id_afectacionigv = !isset($row[6])?0:intval($row[6]);
                $precio_venta = !isset($row[7])?0:(round(floatval(str_replace('S/', '', str_replace(',','.',$row[7]))), 2) + 0);
                $stock = !isset($row[8])?0:(round(floatval(str_replace(',','.',$row[8])), 2) + 0);
                $stock_minimo = !isset($row[9])?0:(round(floatval(str_replace(',','.',$row[9])), 2) + 0);
                $estado = !isset($row[10])?'activo':((trim($row[10]) == 'activo')?'activo':'inactivo');
                $nota = !isset($row[11])?'':trim($row[11]);
                $marca = !isset($row[12])?'':trim($row[12]);

                $precio_compra = !isset($row[13])?0:(round(floatval(str_replace(',','.',$row[13])), 4) + 0);

                $nombre_precio1 = isset($row[14])?$row[14]:'';
                $valor_precio1 = isset($row[15])?$row[15]:'';

                $nombre_precio2 = isset($row[16])?$row[16]:'';
                $valor_precio2 = isset($row[17])?$row[17]:'';

                $nombre_precio3 = isset($row[18])?$row[18]:'';
                $valor_precio3 = isset($row[19])?$row[19]:'';

                $nombre_precio4 = isset($row[20])?$row[20]:'';
                $valor_precio4 = isset($row[21])?$row[21]:'';

                $peso = isset($row[22])?floatval($row[22]):0;

                if(!empty($nombre_precio1) && !empty($valor_precio1)) {
                    $lista_precios[] = array(
                        'nombre'    => $nombre_precio1,
                        'precio'    => $valor_precio1
                    );
                }

                if(!empty($nombre_precio2) && !empty($valor_precio2)) {
                    $lista_precios[] = array(
                        'nombre'    => $nombre_precio2,
                        'precio'    => $valor_precio2
                    );
                }

                if(!empty($nombre_precio3) && !empty($valor_precio3)) {
                    $lista_precios[] = array(
                        'nombre'    => $nombre_precio3,
                        'precio'    => $valor_precio3
                    );
                }

                if(!empty($nombre_precio4) && !empty($valor_precio4)) {
                    $lista_precios[] = array(
                        'nombre'    => $nombre_precio4,
                        'precio'    => $valor_precio4
                    );
                }

                if($estado != 'activo' && $estado != 'inactivo') {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'En la línea N° '.($n + 1).', el estado es inválido. Recordar que el estado debe ser activo o inactivo para eliminar.';
                    return $resp;
                }

                if(empty($idsucursal) && empty($idproducto) && empty($codigoproducto)) {
                    break;
                }

                if($opcion_actualizacion_total == 'no') {
                    //si la actualización es una actualización individual entonces hay que validar la existencia del producto por el ID
                    if(!empty($idproducto)) {
                        $producto = Producto::findFirst(array("idproducto = :idproducto: and id_contribuyente = :id_contribuyente:", 'bind' => array('idproducto' => $idproducto, 'id_contribuyente' => $usuario->id_contribuyente)));
                        if(!$producto) {
                            $resp['respuesta'] = 'error';
                            $resp['titulo'] = 'Error en Producto';
                            $resp['mensaje'] = 'En la línea N° '.($n + 1).', se ha ingresado un ID DE PRODUCTO ('.$idproducto.') que no existe actualmente!';
                            return $resp;
                        }
                    } else {
                        $producto = Producto::findFirst(array("codigo = :codigo: and idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('codigo' => $codigoproducto, 'idsucursal' => $idsucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
                        if(!$producto) {
                            $resp['respuesta'] = 'error';
                            $resp['titulo'] = 'Error en Producto';
                            $resp['mensaje'] = 'En la línea N° '.($n + 1).', se ha ingresado un CÓDIGO DE PRODUCTO ('.$codigoproducto.') que no existe actualmente!';
                            return $resp;
                        }
                    }
                } else {
                    $producto = Producto::findFirst(array("codigo = :codigo: and id_contribuyente = :id_contribuyente:", 'bind' => array('codigo' => $codigoproducto, 'id_contribuyente' => $usuario->id_contribuyente)));
                    if(!$producto) {
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error en Producto';
                        $resp['mensaje'] = 'En la línea N° '.($n + 1).', se ha ingresado un CODIGO DE PRODUCTO (<strong>'.$codigoproducto.'</strong>) que no existe actualmente!';
                        return $resp;
                    }
                }
                
                if($usuario->id_rol != 1 && $usuario->id_rol != 2 && $usuario->id_rol != 3 && $usuario->id_rol != 5) {
                    if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_productos') != 'acceso_total') {
                        if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_productos') != 'todas_las_sucursales') {
                            if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_productos') == 'prohibir') {
                                $resp['respuesta'] = 'error';
                                $resp['titulo'] = 'Error';
                                $resp['mensaje'] = 'Se ha restringido la Edición y/o Actualización de Productos en su Cuenta!';
                                return $resp;
                            }

                            if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_productos') == 'sucursal_asignada') {
                                if($usuario->idsucursal != $idsucursal) {
                                    $resp['respuesta'] = 'error';
                                    $resp['titulo'] = 'Error';
                                    $resp['mensaje'] = 'No Tiene Permisos para Registrar y/o Actualizar Productos en la sucursal con ID: '.$idsucursal;
                                    return $resp;
                                }
                            }
                        }
                    }
                }

                if($estado == 'activo') {
                    if($id_categoria == '') {
                        $num_prod_sin_cate++;
                    } else {
                        $categoria = Categoria::findFirst(array("idcategoria = :idcategoria: and id_contribuyente = :id_contribuyente:", 'bind' => array('idcategoria' => $id_categoria, 'id_contribuyente' => $usuario->id_contribuyente)));
                        if(!$categoria){
                            $categoria = Categoria::findFirst(array("codigo = :codigo: and id_contribuyente = :id_contribuyente:", 'bind' => array('codigo' => $id_categoria, 'id_contribuyente' => $usuario->id_contribuyente)));
                            if(!$categoria) {
                                $resp['respuesta'] = 'error';
                                $resp['titulo'] = 'Error';
                                $resp['mensaje'] = 'En la línea N° '.($n + 1).', se ha ingresado un ID DE CATEGORÍA ('.$id_categoria.') que no existe actualmente!';
                                return $resp;
                            }
                        } 
                    }

                    /*
                    if(empty($nombre)) {
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error en Producto';
                        $resp['mensaje'] = 'En la línea N° '.($n + 1).', se ha ingresado un NOMBRE DE PRODUCTO inválido!';
                        return $resp;
                    }
                    */

                    if(!empty($id_unidadmedida)) {
                        $unidadmedida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $id_unidadmedida)));
                        if(!$unidadmedida) {
                            $resp['respuesta'] = 'error';
                            $resp['titulo'] = 'Error';
                            $resp['mensaje'] = 'En la línea N° '.($n + 1).', se ha ingresado un ID DE UNIDAD ('.$id_unidadmedida.') que no existe actualmente!';
                            return $resp;
                        }
                    } else {
                        $unidadmedida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $producto->id_unidad_medida)));
                        if(!$unidadmedida) {
                            $resp['respuesta'] = 'error';
                            $resp['titulo'] = 'Error';
                            $resp['mensaje'] = 'En la línea N° '.($n + 1).', se ha ingresado un producto donde su unidad de medida está errada!';
                            return $resp;
                        }
                    }

                    if(!empty($id_afectacionigv)) {
                        $tipoafectacionigv = SunatTipoafectacionigv::findFirst(array("id_tipoafectacionigv = :id_tipoafectacionigv:", 'bind' => array('id_tipoafectacionigv' => $id_afectacionigv)));
                        if(!$tipoafectacionigv) {
                            $resp['respuesta'] = 'error';
                            $resp['titulo'] = 'Error';
                            $resp['mensaje'] = 'En la línea N° '.($n + 1).', se ha ingresado un ID DE AFECTACIÓN IGV ('.$id_unidadmedida.') que no existe actualmente!';
                            return $resp;
                        }
                    } else {
                        $tipoafectacionigv = SunatTipoafectacionigv::findFirst(array("id_tipoafectacionigv = :id_tipoafectacionigv:", 'bind' => array('id_tipoafectacionigv' => $producto->id_tipoafectacionigv)));
                        if(!$tipoafectacionigv) {
                            $resp['respuesta'] = 'error';
                            $resp['titulo'] = 'Error';
                            $resp['mensaje'] = 'En la línea N° '.($n + 1).', se ha ingresado un ID DE AFECTACIÓN IGV ('.$producto->id_tipoafectacionigv.') que no existe actualmente!';
                            return $resp;
                        }
                        $id_afectacionigv = $producto->id_tipoafectacionigv;
                    }
                    
                    if($precio_venta < 0) {
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = 'En la línea N° '.($n + 1).', El precio debería ser mayor a cero';
                        return $resp;
                    }

                    $num_decimales = 2;
                    if($contribuyente->num_decimales > 2) {
                        $num_decimales = $contribuyente->num_decimales;
                    }

                    $precio_inc_igv = 0;
                    $precio_sin_igv = 0;
                    if(!empty($precio_venta)) {
                        if($id_afectacionigv == 10 || $id_afectacionigv == 7152) {
                            $precio_inc_igv = round($precio_venta, $num_decimales);
                            $precio_sin_igv = round($precio_venta/1.18, $num_decimales);
                        } else {
                            $precio_inc_igv = round($precio_venta*1.18, $num_decimales);
                            $precio_sin_igv = $precio_venta;
                        }
                    }

                    if($unidadmedida->codigo != 'ZZ') {
                        if($stock < 0) {
                            $resp['respuesta'] = 'error';
                            $resp['titulo'] = 'Error';
                            $resp['mensaje'] = 'En la línea N° '.($n + 1).', El stock debería ser mayor a cero';
                            return $resp;
                        }

                        if($stock_minimo < 0) {
                            $resp['respuesta'] = 'error';
                            $resp['titulo'] = 'Error';
                            $resp['mensaje'] = 'En la línea N° '.($n + 1).', El stock_minimo debería ser mayor a cero';
                            return $resp;
                        }
                    }   
                }
                
                if($confirmacion == 'si' && $estado == 'activo') {
                    if($opcion_actualizacion_total == 'no') {
                        if(!empty($idproducto)) {
                            $lista_productos = Producto::find(array("idproducto = :idproducto: and id_contribuyente = :id_contribuyente:", 'bind' => array('idproducto' => $idproducto, 'id_contribuyente' => $usuario->id_contribuyente)));
                        } else {
                            $lista_productos = Producto::find(array("codigo = :codigo: and idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('codigo' => $codigoproducto, 'idsucursal' => $idsucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
                        }
                    } else {
                        $lista_productos = Producto::find(array("codigo = :codigo: and id_contribuyente = :id_contribuyente:", 'bind' => array('codigo' => $codigoproducto, 'id_contribuyente' => $usuario->id_contribuyente)));
                    }

                    foreach($lista_productos as $item_producto) {
                        $producto = Producto::findFirst(array("idproducto = :idproducto: and id_contribuyente = :id_contribuyente:", 'bind' => array('idproducto' => $item_producto->idproducto, 'id_contribuyente' => $usuario->id_contribuyente)));
                    
                        //ACTUALIZACIÓN DE LISTA DE PRECIOS
                        $lista_precios_anterior = ProductoListaprecio::find("idproducto=".$producto->idproducto);
                        foreach($lista_precios_anterior as $item_precio_anterior) {
                            $precio_anterior = ProductoListaprecio::findFirst(array("idproducto = :idproducto: and idprecio = :idprecio:", 'bind' => array('idproducto' => $producto->idproducto, 'idprecio' => $item_precio_anterior->idprecio)));
                            $precio_anterior->estado = 'inactivo';

                            try {
                                if(!$precio_anterior->save()) {
                                    $this->db->rollback();
                                    $msg = '';
                                    foreach ($precio_anterior->getMessages() as $message) {
                                        $msg = $msg.$message."</br>\n";
                                    }
                                    $resp['respuesta'] = 'error';
                                    $resp['titulo'] = 'Error';
                                    $resp['mensaje'] = 'Obtenemos el siguiente error: '.$msg;
                                    return $resp;
                                }
                            } catch (Exception $e) {
                                $this->saveLogger($e);
                                $this->db->rollback();
                                $resp['respuesta'] = 'error';
                                $resp['titulo'] = 'Error';
                                $resp['mensaje'] = 'Obtenemos el siguiente error: '.$e->getMessage();
                                return $resp;
                            }
                        }

                        foreach($lista_precios as $item_precio_nuevo) {
                            $item_precio = ProductoListaprecio::findFirst(array("idproducto = :idproducto: and nombre = :nombre: and precio = :precio:", 'bind' => array('idproducto' => $producto->idproducto, 'nombre' => $item_precio_nuevo['nombre'], 'precio' => $item_precio_nuevo['precio'])));
                            if(!$item_precio) {
                                $item_precio = new ProductoListaprecio();
                                $item_precio->idproducto = $producto->idproducto;
                                $item_precio->nombre = $item_precio_nuevo['nombre'];
                                $item_precio->precio = $item_precio_nuevo['precio'];
                                $item_precio->estado = 'activo';
                            } else {
                                $item_precio->estado = 'activo';
                            }

                            try {
                                if(!$item_precio->save()) {
                                    $this->db->rollback();
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
                                $this->db->rollback();
                                $resp['respuesta'] = 'error';
                                $resp['titulo'] = 'Error';
                                $resp['mensaje'] = 'Obtenemos el siguiente error: '.$e->getMessage();
                                return $resp;
                            }
                        }
                        //------------- FIN ACTUALIZACIÓN MULTIPRECIO ----------------------


                        if(!empty($id_categoria)) {
                            $producto->id_categoria = intval($id_categoria) + 0;
                        }
                        
                        if(!empty($nombre)) {
                            $producto->nombre = $nombre;
                        }
                        
                        if(!empty($id_unidadmedida)) {
                            $producto->id_unidad_medida = $id_unidadmedida;
                        }
                        
                        if(!empty($id_afectacionigv)) {
                            $producto->id_tipoafectacionigv = $id_afectacionigv;
                        }
                        
                        if(!empty($precio_venta)) {
                            $producto->valor_con_igv = $precio_inc_igv;
                            $producto->valor_sin_igv = $precio_sin_igv;
                        }
                        
                        if(!empty($nota)) {
                            $producto->nota = $nota;
                        }
                        
                        if(!empty($marca)) {
                            $producto->marca = $marca;
                        }

                        if($precio_compra > 0) {
                            $producto->precio_compra = $precio_compra;
                        }

                        $producto->peso = $peso;

                        if($opcion_actualizacion_total == 'no') {
                            //estos datos únicamente se cambian cuando la actualización no es para todos los productos de todas las sucursales
                            $producto->stock_minimo = $stock_minimo;
                            $stock_actual = $producto->stock;
                            $stock_real = $stock;

                            if($unidadmedida->codigo != 'ZZ') {
                                $producto->stock = $stock;
                            }
                        }

                        if($unidadmedida->codigo != 'ZZ') {
                            $resp_porcentajes = $herramientas->get_porcentajes_ganancia($producto->precio_compra, $producto->valor_con_igv, $producto->precio_venta_minimo);
                            $producto->porcentaje_pventa = $resp_porcentajes['porcentaje_maximo'];
                            $producto->porcentaje_pminimo = $resp_porcentajes['porcentaje_minimo'];
                        }

                        try {
                            if(!$producto->save()) {
                                $this->db->rollback();
                                $msg = '';
                                foreach ($producto->getMessages() as $message) {
                                    $msg = $msg.$message."</br>\n";
                                }
            
                                $resp['respuesta'] = 'error';
                                $resp['titulo'] = 'Error';
                                $resp['mensaje'] = $msg;
                                return $resp;
                            }
                        } catch (Exception $e) {
                            $this->saveLogger($e);
                            $this->db->rollback();
                            $resp['respuesta'] = 'error';
                            $resp['titulo'] = 'Error';
                            $resp['mensaje'] = 'Obtenemos el siguiente error: '.$e->getMessage();
                            return $resp;
                        }
                        
                        //el stock únicamente se actualiza cuando la actualización no es para todas las sucursales.
                        if($opcion_actualizacion_total == 'no') {
                            if($unidadmedida->codigo != 'ZZ') {
                                if($stock_real > $stock_actual) {
                                    $diferencia_stock = $stock_real - $stock_actual;

                                    $lista_detalle_ingresos[$producto->idsucursal][] = array(
                                        'cantidad'          => $diferencia_stock,
                                        'costo_unitario'    => $producto->precio_compra,
                                        'tipo_kardex'       => 'ingreso_sindoc',
                                        'id_codigomoneda'	=> $producto->id_cod_moneda,
                
                                        'o_id_u_medida'     => $producto->id_unidad_medida,
                                        'o_u_medida'        => $unidadmedida->codigo,
                                        'o_precio'          => $producto->valor_con_igv,
                                        'o_id_afectigv'     => $producto->id_tipoafectacionigv,
                                        'o_tipo_unidad'     => 'UND',
                                        'o_id_presentacion' => '',
                                        'o_cod_prod'        => $producto->codigo,
                                        'o_id_prod'         => $producto->idproducto,
                                        'o_nom_prod'        => $producto->nombre
                                    );
                                }

                                if($stock_actual > $stock_real) {
                                    //se debe hacer una salida para igual al stock real
                                    $diferencia_stock = $stock_actual - $stock_real;
                                    $lista_detalle_salidas[$producto->idsucursal][] = array(
                                        'cantidad'          => $diferencia_stock,
                                        'costo_unitario'    => $producto->precio_compra,
                                        'tipo_kardex'       => 'salida_sindoc',
                                        'id_codigomoneda'	=> $producto->id_cod_moneda,
                
                                        'o_id_u_medida'     => $producto->id_unidad_medida,
                                        'o_u_medida'        => $unidadmedida->codigo,
                                        'o_precio'          => $producto->valor_con_igv,
                                        'o_id_afectigv'     => $producto->id_tipoafectacionigv,
                                        'o_tipo_unidad'     => 'UND',
                                        'o_id_presentacion' => '',
                                        'o_cod_prod'        => $producto->codigo,
                                        'o_id_prod'         => $producto->idproducto,
                                        'o_nom_prod'        => $producto->nombre
                                    );
                                    /*
                                    $resp_salida = $this->registrar_movimiento_kardex($producto, $contribuyente, $usuario, $diferencia_stock, 'salida', 'Salida Generada por Actualización Masiva de Productos', $unidadmedida, $tipoafectacionigv);
                                    if($resp_salida['respuesta'] == 'error') {
                                        $this->db->rollback();
                                        return $resp_salida;
                                    }
                                    */
                                }
                            }
                        }

                    }
                }
                
                if($confirmacion == 'si' && $estado == 'inactivo') {
                    $producto->estado = 'inactivo';
                    
                    try {
                        if(!$producto->save()) {
                            $this->db->rollback();
                            $msg = '';
                            foreach ($producto->getMessages() as $message) {
                                $msg = $msg.$message."</br>\n";
                            }
                            $resp['respuesta'] = 'error';
                            $resp['titulo'] = 'Error';
                            $resp['mensaje'] = $msg;
                            return $resp;
                        }
                    } catch (Exception $e) {
                        $this->saveLogger($e);
                        $this->db->rollback();
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = 'Obtenemos el siguiente error: '.$e->getMessage();
                        return $resp;
                    }
                }
            }
            
            $n++;
        }

        $movimiento_almacen = new ProductomovimientosController;

        if(count($lista_detalle_ingresos) > 0) {
            $data['id_contribuyente']           = $producto->id_contribuyente;
            $data['id_usuario']                 = $usuario->idusuario;
            $data['tipo_envio_sunat']           = $contribuyente->tipo_envio_sunat;
            $data['id_tipo_movimiento']         = 'in'; //ingreso
            $data['nota']                       = 'Entrada por Actualización masiva';

            $detalle_movimiento = array();
            foreach($lista_detalle_ingresos as $idsucursal_mov_ingreso => $detalle_movimiento) {
                $data['id_sucursal'] = $idsucursal_mov_ingreso;//modificamos el id de la sucursal según la lista de entradas: Esto porque al momento de crear el movimiento de la entrada debería ser por sucursal
                $data['detalle_movimiento']	= $detalle_movimiento;
                $resp_reg_ingreso = $movimiento_almacen->registrar_movimiento($usuario, $data);
                if($resp_reg_ingreso['respuesta'] == 'error') {
                    $this->db->rollback();
                    return $resp_reg_ingreso;
                }
            }
        }

        if(count($lista_detalle_salidas) > 0) {    
            $data['id_contribuyente']           = $producto->id_contribuyente;
            $data['id_usuario']                 = $usuario->idusuario;
            $data['tipo_envio_sunat']           = $contribuyente->tipo_envio_sunat;
            $data['id_tipo_movimiento']         = 'sa'; //salida
            $data['nota']                       = 'Salida por actualización masiva';

            $detalle_movimiento = array();
            foreach($lista_detalle_salidas as $idsucursal_mov_salida => $detalle_movimiento) {
                $data['id_sucursal'] = $idsucursal_mov_salida; //modificamos el id de la sucursal según la lista de salidas: Esto porque al momento de crear el movimiento de la salida debería ser por sucursal
                $data['detalle_movimiento']	= $detalle_movimiento;
                $resp_reg_salida = $movimiento_almacen->registrar_movimiento($usuario, $data);
                if($resp_reg_salida['respuesta'] == 'error') {
                    $this->db->rollback();
                    return $resp_reg_salida;
                }
            }
        }

        if ($confirmacion == 'si') {
            $this->db->commit();
            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Actualización Completa';
            $resp['mensaje'] = 'Hemos Completado la Actualización Correctamente!';
            return $resp;
        }
        
        $resp['respuesta'] = 'ok';
        $resp['titulo'] = 'Revisión Completa';
        $resp['mensaje'] = 'Hemos Revisado sus Datos y no hemos encontrado errores. ¿Realmente desea Actualizar sus Productos? Recuerde que ya no podrá deshacerse los cambios!';
        return $resp;
    }

    public function registrar_movimiento_kardex($producto, $contribuyente, $usuario, $cantidad, $tipo, $nota, $unidad_medida, $tipoafectacionigv) {
        if($tipo != 'salida' && $tipo != 'entrada') {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'No se reconoce la acción para registrar en el kardex.';
            return $resp;
        }
        
        $fecha_registro = date('Y-m-d H:i:s');
        if($tipo == 'entrada') {
            $movimiento_almacen = new ProductomovimientosController;
            $data['id_contribuyente']           = $producto->id_contribuyente;
            $data['id_sucursal']                = $producto->idsucursal;
            $data['id_usuario']                 = $usuario->idusuario;
            $data['tipo_envio_sunat']           = $contribuyente->tipo_envio_sunat;
            $data['id_tipo_movimiento']         = 'in'; //ingreso
            $data['nota']                       = $nota;
            $data['destino_id_sucursal']        = '';
            $data['destino_id_contribuyente']   = '';

            $data['detalle_movimiento'][] = array(
                'cantidad'          => $cantidad,
                'costo_unitario'    => round($producto->precio_compra, 2),
                'tipo_kardex'       => 'ingreso_sindoc',
                'id_codigomoneda'   => $producto->id_cod_moneda,

                'o_id_u_medida'     => $producto->id_unidad_medida,
                'o_u_medida'        => $unidad_medida->codigo,
                'o_precio'          => $producto->valor_con_igv,
                'o_id_afectigv'     => $producto->id_tipoafectacionigv,
                'o_tipo_unidad'     => 'UND',
                'o_id_presentacion' => '',
                'o_cod_prod'        => $producto->codigo,
                'o_id_prod'         => $producto->idproducto,
                'o_nom_prod'        => $producto->nombre
            );

            $resp_reg_movimiento = $movimiento_almacen->registrar_movimiento($usuario, $data);
            if($resp_reg_movimiento['respuesta'] == 'error') {
                return $resp_reg_movimiento;
            }
            
            $resp_kardex = $resp_reg_movimiento['resp_kardex'];
            $producto->costo_promedio = $resp_kardex['costo_unitario_promedio'];
            $producto->stock = $producto->stock + $cantidad;
            
        } else if($tipo == 'salida') {
            $movimiento_almacen = new ProductomovimientosController;
            $data['id_contribuyente']           = $producto->id_contribuyente;
            $data['id_sucursal']                = $producto->idsucursal;
            $data['id_usuario']                 = $usuario->idusuario;
            $data['tipo_envio_sunat']           = $contribuyente->tipo_envio_sunat;
            $data['id_tipo_movimiento']         = 'sa'; //salida
            $data['nota']                       = $nota;
            $data['destino_id_sucursal']        = '';
            $data['destino_id_contribuyente']   = '';

            $data['detalle_movimiento'][] = array(
                'cantidad'          => $cantidad,
                'costo_unitario'    => round($producto->precio_compra, 2),
                'tipo_kardex'       => 'salida_sindoc',
                'id_codigomoneda'   => $producto->id_cod_moneda,

                'o_id_u_medida'     => $producto->id_unidad_medida,
                'o_u_medida'        => $unidad_medida->codigo,
                'o_precio'          => $producto->valor_con_igv,
                'o_id_afectigv'     => $producto->id_tipoafectacionigv,
                'o_tipo_unidad'     => 'UND',
                'o_id_presentacion' => '',
                'o_cod_prod'        => $producto->codigo,
                'o_id_prod'         => $producto->idproducto,
                'o_nom_prod'        => $producto->nombre
            );

            $resp_reg_movimiento = $movimiento_almacen->registrar_movimiento($usuario, $data);
            if($resp_reg_movimiento['respuesta'] == 'error') {
                return $resp_reg_movimiento;
            }
            
            $resp_kardex = $resp_reg_movimiento['resp_kardex'];
            $producto->costo_promedio = $resp_kardex['costo_unitario_promedio'];
            $producto->stock = $producto->stock - $cantidad;
        } 

        try {
            if(!$producto->save()) {
                $msg = '';
                foreach ($producto->getMessages() as $message) {
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
            $resp['mensaje'] = 'No hemos logrado actualizar el Stock por el siguiente error: '.$e->getMessage();
            return $resp;
        }

        $resp['respuesta'] = 'ok';
        return $resp;
    }

    public function importarProductosAction()
    {
        ini_set('memory_limit','2048M');
        ini_set('max_execution_time', 940);

        //require $_SERVER["DOCUMENT_ROOT"]."/facturacionv8/apis/apiexcel/";
        //require $_SERVER["DOCUMENT_ROOT"]."/facturacionv8/apis/apiexcel/php-excel-reader/excel_reader2.php";
        //require $_SERVER["DOCUMENT_ROOT"]."/facturacionv8/apis/apiexcel/SpreadsheetReader.php";
        
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $importacionproductos = new ImportacionproductosController;
            $resp = $importacionproductos->subir_productos($this->request->getPost());
            echo json_encode($resp);
            exit();
        }
    }

    public function verificar_filas_excel($Reader, $usuario) {
        $n = 0;
        $num_prod_sin_cate = 0;
        $array_code_prod = array();
        $herramientas = new HerramientasController;
        foreach ($Reader as $row) {
            if($n > 0) {
                $categoria_codigo = !isset($row[0])?'':strtoupper($row[0]);
                $producto_codigo = !isset($row[1])?'':strtoupper($row[1]);
                $producto_nombre = !isset($row[2])?'':$row[2];
                $id_tipoafectacionigv = !isset($row[3])?'':intval($row[3]);
                $id_unidad_medida = !isset($row[4])?'':intval($row[4]);
                $id_cod_moneda = !isset($row[5])?'':strtoupper($row[5]);
                $precio_con_igv = !isset($row[6])?0:(round(floatval(str_replace('S/', '', str_replace(',','.',$row[6]))), 2) + 0);
                $stock_actual = !isset($row[7])?0:(round(floatval(str_replace(',','.',$row[7])), 2) + 0);
                $stock_minimo = !isset($row[8])?0:(round(floatval(str_replace(',','.',$row[8])), 2) + 0);
                $detalle = !isset($row[9])?'':$row[9];
                $precio_compra = !isset($row[10])?0:(round(floatval(str_replace('S/', '', str_replace(',','.',$row[10]))), 2) + 0);
                $idsucursal = !isset($row[11])?'':intval($row[11]);

                $fecha_vencimiento = !isset($row[19])?'':$row[19];
                $marca_producto = !isset($row[20])?'':$row[20];

                if(!empty($fecha_vencimiento)) {
                    if($herramientas->validar_fecha_formato($fecha_vencimiento, 'd-m-Y')) {
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = 'En la línea N° '.($n + 1).', la fecha no cumple con el formato (el formato debe ser día-mes-año, por ejemplo 07-11-2020 o también 17-04-2020';
                        return $resp;
                    }
                }


                if(empty($categoria_codigo) && empty($producto_codigo) && empty($producto_nombre) && empty($id_tipoafectacionigv) && empty($id_unidad_medida) && empty($id_cod_moneda) && empty($precio_con_igv) && empty($stock_actual) && empty($stock_minimo) && empty($detalle)) {
                    break;
                }
                
                if($categoria_codigo == '') {
                    $num_prod_sin_cate++;
                } else {
                    $categoria = Categoria::findFirst(array("UPPER(codigo) = :codigo: and id_contribuyente = :id_contribuyente:", 'bind' => array('codigo' => $categoria_codigo, 'id_contribuyente' => $usuario->id_contribuyente)));
                    if(!$categoria){
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = 'En la línea N° '.($n + 1).', se ha ingresado un código ('.$categoria_codigo.') de categoría que no existe actualmente!, primero debes registrar la categoría con el código ingresado';
                        return $resp;
                    } 
                }

                $tipoafectacionigv = SunatTipoafectacionigv::findFirst(array("id_tipoafectacionigv = :id_tipoafectacionigv:", 'bind' => array('id_tipoafectacionigv' => $id_tipoafectacionigv)));
                if(!$tipoafectacionigv) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'En la línea N° '.($n + 1).', en la columna CODIGO - TIPO IGV, se ha ingresado un código inválido!';
                    return $resp;
                }

                $unidadmedida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $id_unidad_medida)));
                if(!$unidadmedida){
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'En la línea N° '.($n + 1).', en la columna CODIGO - UNIDAD MEDIDA, se ha ingresado un código inválido! Código Ingresado: '.$id_unidad_medida;
                    return $resp;
                }

                $product_con_codigoactual = Producto::findFirst(array("id_contribuyente = :id_contribuyente: and UPPER(codigo) = :codigo: and idsucursal = :idsucursal: and estado = 'activo'", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'codigo' => $producto_codigo, 'idsucursal' => $idsucursal)));
                if($product_con_codigoactual) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'En la línea N° '.($n + 1).', en la columna CODIGO PRODUCTO, se ha ingresado un código que ya pertenece a otro producto registrado en el sistema, recuerda que cada código debe ser único por cada producto!';
                    return $resp;
                }

                if(in_array($producto_codigo, $array_code_prod)) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'El código de producto: '.$producto_codigo.' se encuentra repetido en el listado que deseas enviar. La línea donde se repite es la N° '.($n + 1).', en la columna CODIGO PRODUCTO, ';
                    return $resp;
                }

                $array_code_prod[] = $producto_codigo;

                $sql_moneda = SunatMoneda::findFirst(array("id_codigomoneda = :id_codigomoneda:", 'bind' => array('id_codigomoneda' => $id_cod_moneda)));
                if(!$sql_moneda){
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'En la línea N° '.($n + 1).', en la columna MONEDA, se ha ingresado un código inválido!';
                    return $resp;
                }

                if(empty($producto_nombre)) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'En la línea N° '.($n + 1).', en la columna NOMBRE, hemos detectado un producto que no lleva nombre, recuerda que todos los productos deben tener un nombre!';
                    return $resp;
                }

                if($precio_con_igv < 0) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'En la línea N° '.($n + 1).', en la columna PRECIO, se ha detectado un valor inválido ('.$precio_con_igv.'), recuerda que cada producto debe tener un precio mayor a cero';
                    return $resp;
                }

                if($stock_actual < 0) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'En la línea N° '.($n + 1).', el stock es igual o menor a cero, debe ser un número mayor a cero';
                    return $resp;
                }

                if($stock_minimo < 0) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'En la línea N° '.($n + 1).', el stock mínimo debe ser cero o mayor a cero.';
                    return $resp;
                } 

                $sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $idsucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
                if(!$sucursal) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'En la línea N° '.($n + 1).', un código de sucursal inválido!.';
                    return $resp;
                }
                
            }

            if($n > 1000) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Recuerda que el número máximo de productos a importar es de 1000 productos...';
                return $resp;
            }

            $n++;
        }

        $texto_categorias = '';
        if($num_prod_sin_cate > 0) {
            $texto_categorias = '<br />Hemos detectado que existen '.$num_prod_sin_cate.' productos que no llevan categorías';
        }

        $resp['respuesta'] = 'ok';
        $resp['texto_categorias'] = $texto_categorias;
        $resp['num_prod_sin_cate'] = $num_prod_sin_cate;
        return $resp;
    }


    public function insertAction()
    {
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
                $resp['mensaje'] = 'No se encuentra la empresa al que pertenece el usuario';
                echo json_encode($resp);
                exit();
			}

            $herramientas = new HerramientasController;

            //Datos de Formulario
            $datapost = $this->request->getPost();
            $idproducto = !isset($datapost['idproducto'])?0:intval($datapost['idproducto']) + 0;
            $codigo = !isset($datapost['codigo'])?'':strtoupper($datapost['codigo']);
            $nombre_servicio = !isset($datapost['nombre_servicio'])?'':$datapost['nombre_servicio'];
            $id_tipoafectacionigv = !isset($datapost['producto_tipo_afect_igv'])?'':$datapost['producto_tipo_afect_igv'];
            $precio_compra = !isset($datapost['valor_de_compra'])?0:floatval($datapost['valor_de_compra']);
            $valor_con_igv = !isset($datapost['valor_con_igv'])?0:floatval($datapost['valor_con_igv']);
            $valor_sin_igv = !isset($datapost['valor_sin_igv'])?0:floatval($datapost['valor_sin_igv']);
            $id_categoria = !isset($datapost['id_categoria'])?0:intval($datapost['id_categoria']);
            $nota = !isset($datapost['nota_producto'])?'':$datapost['nota_producto'];
            $id_unidad_medida = !isset($datapost['id_unidad_medida'])?0:intval($datapost['id_unidad_medida']);
            if($id_unidad_medida <= 0) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Unidad de Medida';
                $resp['mensaje'] = 'Debes seleccionar una Unidad de Medida';
                echo json_encode($resp);
                exit();
            }
            $id_cod_moneda = !isset($datapost['id_cod_moneda'])?'':$datapost['id_cod_moneda'];
            $stock = !isset($datapost['stock'])?0:floatval($datapost['stock']);
            $stock_minimo = !isset($datapost['stock_minimo'])?0:floatval($datapost['stock_minimo']);
            $idsucursal = !isset($datapost['select_sucursal'])?0:intval($datapost['select_sucursal']) + 0;
            $tipo_cambio_producto = !isset($datapost['tipo_cambio_producto'])?1:floatval($datapost['tipo_cambio_producto']) + 0;
            $afecto_icbper = !isset($datapost['opcion_afecto_icbper'])?'no':$datapost['opcion_afecto_icbper'];
            $tienedetraccion = !isset($datapost['opcion_tienedetraccion'])?'no':$datapost['opcion_tienedetraccion'];
            $id_cod_detraccion = !isset($datapost['detraccion_codigo_bien'])?null:trim($datapost['detraccion_codigo_bien']);
            $imgprod_cuadradas = !isset($datapost['imgprod_cuadradas'])?'':trim($datapost['imgprod_cuadradas']);
            $imgprod_verticales = !isset($datapost['imgprod_verticales'])?'':trim($datapost['imgprod_verticales']);
            $imgprod_horizontales = !isset($datapost['imgprod_horizontales'])?'':trim($datapost['imgprod_horizontales']);

            $array_img_prod = array();
            $array_img_prod['cuadradas'] = ($imgprod_cuadradas != '')?array_map('trim', explode(',', $imgprod_cuadradas)):array();
            $array_img_prod['verticales'] = ($imgprod_verticales != '')?array_map('trim', explode(',', $imgprod_verticales)):array();
            $array_img_prod['horizontales'] = ($imgprod_horizontales != '')?array_map('trim', explode(',', $imgprod_horizontales)):array();

            $array_img_prod['cuadradas'] = array_unique($array_img_prod['cuadradas']);
            $array_img_prod['verticales'] = array_unique($array_img_prod['verticales']);
            $array_img_prod['horizontales'] = array_unique($array_img_prod['horizontales']);

            $presentaciones = isset($datapost['presentaciones'])?json_decode($datapost['presentaciones']):array();

            $peso = !isset($datapost['peso'])?0:floatval($datapost['peso']);
            
            /* EN CADA CATEGORÍA DE IMÁGENES NO DEBEN SER MAYOR A 10 IMÁGENES */
            /* El campo donde se guarda el json con las imágenes puede contener un máximo de 65535, y por tanto puede almacenar hasta 300 imágenes */
            if(count($array_img_prod['cuadradas']) > 10) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Imágenes';
                $resp['mensaje'] = 'Solamente se permite un máximo de 10 imágenes';
                echo json_encode($resp);
                exit();
            }

            if(count($array_img_prod['verticales']) > 10) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Imágenes';
                $resp['mensaje'] = 'Solamente se permite un máximo de 10 imágenes';
                echo json_encode($resp);
                exit();
            }

            if(count($array_img_prod['horizontales']) > 10) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en Imágenes';
                $resp['mensaje'] = 'Solamente se permite un máximo de 10 imágenes';
                echo json_encode($resp);
                exit();
            }
            /* --------------------------- */
            
            if($tienedetraccion != 'si') {
                $tienedetraccion = 'no';
                $id_cod_detraccion = null;
            } else {
                $codigo_detraccion = SunatCodigodetraccion::findFirst(array("id_cod_detraccion = :id_cod_detraccion:", 'bind' => array('id_cod_detraccion' => $id_cod_detraccion)));
                if(!$codigo_detraccion) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'Si el Producto Tiene Detracción, debes seleccionar el código de detracción!!';
                    echo json_encode($resp);
                    exit();
                }
            }

            if($afecto_icbper != 'si') {
                $afecto_icbper = 'no';
            }

            $opcion_lista_precio = !isset($datapost['opcion_lista_precio'])?'no':$datapost['opcion_lista_precio'];
            if($opcion_lista_precio != 'si') {
                $opcion_lista_precio = 'no';
            }

            $lista_precios = array();
            if($opcion_lista_precio == 'si') {
                $lista_precios = json_decode($datapost['json_listaprecios']);
            }

            $precio_venta_minimo = !isset($datapost['precio_venta_minimo'])?0:floatval($datapost['precio_venta_minimo']);
            
            if(empty($codigo)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes agregar o generar un código para el producto actual!';
                echo json_encode($resp);
                exit();
            }

            if($idproducto > 0) {
                $product_con_codigoactual = Producto::findFirst(array("idproducto <> :idproducto: and id_contribuyente = :id_contribuyente: and UPPER(codigo) = :codigo: and estado = 'activo' and idsucursal = :idsucursal:", 'bind' => array('idproducto' => $idproducto, 'id_contribuyente' => $usuario->id_contribuyente, 'codigo' => $codigo, 'idsucursal' => $idsucursal)));
            } else {
                $product_con_codigoactual = Producto::findFirst(array("id_contribuyente = :id_contribuyente: and UPPER(codigo) = :codigo: and estado = 'activo' and idsucursal = :idsucursal:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'codigo' => $codigo, 'idsucursal' => $idsucursal)));
            }

            if($stock_minimo < 0){
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos! El  Stock Mínimo no puede ser menor a cero';
                echo json_encode($resp);
                exit();
            }
            
            if($product_con_codigoactual) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El código actual ya está siendo utilizado, por favor, genere otro código o ingrese un nuevo código!';
                echo json_encode($resp);
                exit();
            }

            if(empty($nombre_servicio)){
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos! Debes ingresar un nombre del bien o servicio, no se permiten campos vacíos.';
                echo json_encode($resp);
                exit();
            }
            $tipoafectacionigv = SunatTipoafectacionigv::findFirst(array("id_tipoafectacionigv = :id_tipoafectacionigv:", 'bind' => array('id_tipoafectacionigv' => $id_tipoafectacionigv)));
            if(!$tipoafectacionigv) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Selecciona el Tipo de IGV.';
                echo json_encode($resp);
                exit();
            }
            if($valor_con_igv < 0){
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos! El Valor Unitario no puede ser menor a cero';
                echo json_encode($resp);
                exit();
            }
            if($valor_sin_igv < 0){
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos! El Valor Unitario no puede ser menor a cero';
                echo json_encode($resp);
                exit();
            }

            if($id_categoria > 0) {
                $sql_categoria = Categoria::findFirst(array("idcategoria = :idcategoria: and id_contribuyente = :id_contribuyente:", 'bind' => array('idcategoria' => $id_categoria, 'id_contribuyente' => $usuario->id_contribuyente)));
                if(!$sql_categoria){
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'Lo sentimos! esa categoría no pertenece a tu empresa.';
                    echo json_encode($resp);
                    exit();
                }
            }

            $unidad_medida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $id_unidad_medida)));
            if(!$unidad_medida){
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos!, esa medida no existe. Por favor selecciona una Unidad de Medida Válida';
                echo json_encode($resp);
                exit();
            }

            $sql_moneda = SunatMoneda::findFirst(array("id_codigomoneda = :id_codigomoneda:", 'bind' => array('id_codigomoneda' => $id_cod_moneda)));
            if(!$sql_moneda){
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El Tipo de Moneda Seleccionada No Existe. Por favor, selecciona un tipo de moneda Válido';
                echo json_encode($resp);
                exit();
            }

            if($sql_moneda->id_codigomoneda == 'USD') {
                if($tipo_cambio_producto <= 0) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'Debes ingresar el tipo de cambio, si estás registrando un producto en dólares debes ingresar el tipo de cambio';
                    echo json_encode($resp);
                    exit();
                }
            } else {
                $tipo_cambio_producto = 1;
            }

            if($idproducto <= 0) {
                $sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $idsucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
                if(!$sucursal) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'Debes Seleccionar el Almacén Donde Será Registrado el Producto!';
                    echo json_encode($resp);
                    exit();
                }
            }

            $gestion_usuarios = new GestionuserController;
            if($usuario->id_rol != 1 && $usuario->id_rol != 2 && $usuario->id_rol != 3 && $usuario->id_rol != 5) {
                if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_productos') != 'acceso_total') {
                    if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_productos') != 'todas_las_sucursales') {
                        if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_productos') == 'prohibir') {
                            $resp['respuesta'] = 'error';
                            $resp['titulo'] = 'Error';
                            $resp['mensaje'] = 'Se ha restringido la Edición y/o Actualización de Productos en su Cuenta!';
                            echo json_encode($resp);
                            exit();
                        }

                        if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_productos') == 'sucursal_asignada') {
                            if($usuario->idsucursal != $idsucursal) {
                                $resp['respuesta'] = 'error';
                                $resp['titulo'] = 'Error';
                                $resp['mensaje'] = 'No Tiene Permisos para Registrar y/o Actualizar Productos en la sucursal con ID: '.$idsucursal;
                                echo json_encode($resp);
                                exit();
                            }
                        }
                    }
                }
            }
            
            $this->db->begin();
            
            $product = Producto::findFirst(array("idproducto = :idproducto: and id_contribuyente = :id_contribuyente:", 'bind' => array('idproducto' => $idproducto, 'id_contribuyente' => $usuario->id_contribuyente)));
            $nuevo_registro = false;

            $fecha_registro = date('Y-m-d H:i:s');
            
            if(!$product) {
                $nuevo_registro = true;
                $product = new Producto();
                $product->fecha_registro =  $fecha_registro;
                $product->id_contribuyente = $usuario->id_contribuyente;
                $product->idsucursal = $idsucursal;

                if($unidad_medida->codigo != 'ZZ') {

                    if($stock < 0){
                        $this->db->rollback();
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = 'Lo sentimos! El Stock Actual no puede ser menor a cero';
                        echo json_encode($resp);
                        exit();
                    }

                    $product->stock = $stock;
                    $product->costo_promedio = $precio_compra;

                } else {
                    $product->stock = 0;
                    
                }
            }
            
            $num_decimales = 2;
            if($contribuyente->num_decimales > 2) {
                $num_decimales = $contribuyente->num_decimales;
            }

            if($tipoafectacionigv->id_tipoafectacionigv == 10 || $tipoafectacionigv->id_tipoafectacionigv == 7152) {
                $product->valor_sin_igv = round($valor_con_igv/1.18, $num_decimales); //$valor_sin_igv
                $product->valor_con_igv = $valor_con_igv;
            } else {
                $product->valor_sin_igv = $valor_con_igv; //$valor_sin_igv
                $product->valor_con_igv = round($valor_con_igv*1.18, $num_decimales);
            }
            
            $product->fecha_vencimiento = null;
            if($contribuyente->ver_fecha_vencimiento == 'si') {
                if($unidad_medida->codigo != 'ZZ') {
                    if(isset($datapost['txt_fecha_vencimiento']) && !empty($datapost['txt_fecha_vencimiento'])) {
                        $array_fecha_vencimiento = explode('/',!isset($datapost['txt_fecha_vencimiento'])?'':$datapost['txt_fecha_vencimiento']);
                        if(!isset($array_fecha_vencimiento[2]) || !isset($array_fecha_vencimiento[1]) || !isset($array_fecha_vencimiento[0])) {
                            $this->db->rollback();
                            $resp['respuesta'] = 'error';
                            $resp['titulo'] = 'Error';
                            $resp['mensaje'] = 'La fecha de vencimiento para el producto no es válida!';
                            echo json_encode($resp);
                            exit();
                        }
    
                        $fecha_vencimiento = $array_fecha_vencimiento[2].'-'.$array_fecha_vencimiento[1].'-'.$array_fecha_vencimiento[0];
                        if (!(DateTime::createFromFormat('Y-m-d', $fecha_vencimiento) !== FALSE)) {
                            $this->db->rollback();
                            $resp['respuesta'] = 'error';
                            $resp['titulo'] = 'Error';
                            $resp['mensaje'] = 'La fecha de vencimiento para el producto no es válida!';
                            echo json_encode($resp);
                            exit();
                        }
    
                        $product->fecha_vencimiento = $fecha_vencimiento;
                    }
                }
            }

            $product->marca = null;
            if($contribuyente->ver_marca == 'si') {
                if($unidad_medida->codigo != 'ZZ') {
                    if(!empty($datapost['txt_marca_producto'])) {
                        $product->marca = trim($datapost['txt_marca_producto']);
                    }
                }
            }
            
            $product->codigo = $codigo;
            $product->id_unidad_medida = $id_unidad_medida;
            $product->id_cod_detraccion = $id_cod_detraccion;
            $product->id_tipoafectacionigv = $id_tipoafectacionigv;
            $product->id_categoria = $id_categoria;
            $product->nombre = trim($nombre_servicio);
            $product->id_cod_moneda = $id_cod_moneda;
            $product->foto = json_encode($array_img_prod);
            if(!empty($nota)) {
                $product->nota = $nota; 
            }
            $product->stock_minimo = $stock_minimo;
            $product->tipo_cambio_sunat = $tipo_cambio_producto; 
            $product->precio_venta_minimo = $precio_venta_minimo;
            $product->multi_precio = $opcion_lista_precio;            

            if($unidad_medida->codigo != 'ZZ') {
                $product->afecto_icbper = $afecto_icbper;
                $product->precio_compra = $precio_compra;

                $resp_porcentajes = $herramientas->get_porcentajes_ganancia($precio_compra, $valor_con_igv, $precio_venta_minimo);
                $product->porcentaje_pventa = $resp_porcentajes['porcentaje_maximo'];
                $product->porcentaje_pminimo = $resp_porcentajes['porcentaje_minimo'];

            } else {
                $product->afecto_icbper = 'no';
                $product->precio_compra = 0;
            }
             
            $product->estado = 'activo';
            $product->peso = $peso;
            
            try {
                if(!$product->save()) {
                    $this->db->rollback();
                    $msg = '';
                    foreach ($product->getMessages() as $message) {
                        $msg = $msg.$message."</br>\n";
                    }
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error en BD';
                    $resp['mensaje'] = 'Obtenemos el siguiente error: '.$msg;
                    echo json_encode($resp);
                    exit();
                }
            } catch (Exception $e) {
                $this->saveLogger($e);
                $this->db->rollback();
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en BD';
                $resp['mensaje'] = 'Obtenemos el siguiente error: '.$e->getMessage();
                echo json_encode($resp);
                exit();
            }

            if($nuevo_registro) {
                if($unidad_medida->codigo != 'ZZ') {
                    if($stock > 0) {
                        $movimiento_almacen = new ProductomovimientosController;
                        $data['id_contribuyente']           = $product->id_contribuyente;
                        $data['id_sucursal']                = $product->idsucursal;
                        $data['id_usuario']                 = $usuario->idusuario;
                        $data['tipo_envio_sunat']           = $contribuyente->tipo_envio_sunat;
                        $data['id_tipo_movimiento']         = 'in'; //ingreso
                        $data['nota']                       = 'Registro de Producto';
                        $data['destino_id_sucursal']        = '';
                        $data['destino_id_contribuyente']   = '';

                        

                        $data['detalle_movimiento'][] = array(
                            'cantidad'          => $stock,
                            'costo_unitario'    => round($tipo_cambio_producto*$product->precio_compra, 2),
                            'tipo_kardex'       => 'inventario_inicial',
                            'id_codigomoneda'   => $id_cod_moneda,

                            'o_id_u_medida'     => $product->id_unidad_medida,
                            'o_u_medida'        => $unidad_medida->codigo,
                            'o_precio'          => $product->valor_con_igv,
                            'o_id_afectigv'     => $product->id_tipoafectacionigv,
                            'o_tipo_unidad'     => 'UND',
                            'o_id_presentacion' => '',
                            'o_cod_prod'        => $product->codigo,
                            'o_id_prod'         => $product->idproducto,
                            'o_nom_prod'        => $product->nombre,

                            'd_id_u_medida'     => null,
                            'd_u_medida'        => null,
                            'd_precio'          => null,
                            'd_id_afectigv'     => null,
                            'd_tipo_unidad'     => null,
                            'd_id_presentacion' => null,
                            'd_cod_prod'        => null,
                            'd_id_prod'         => null,
                            'd_nom_prod'        => null
                        );

                        $resp_reg_movimiento = $movimiento_almacen->registrar_movimiento($usuario, $data);
                        if($resp_reg_movimiento['respuesta'] == 'error') {
                            $this->db->rollback();
                            echo json_encode($resp_reg_movimiento);
                            exit();
                        }

                        $resp_kardex = $resp_reg_movimiento['resp_kardex'];
                        $product->costo_promedio = $resp_kardex['costo_unitario_promedio'];
                    }
                }
            }

            if($opcion_lista_precio == 'si') {
                $resp_lista_precios = $this->get_lista_precios($lista_precios, $contribuyente);
                if($resp_lista_precios['respuesta'] == 'error') {
                    $this->db->rollback();
                    echo json_encode($resp_lista_precios);
                    exit();
                }

                $lista_precios_anterior = ProductoListaprecio::find("idproducto=".$product->idproducto);
                foreach($lista_precios_anterior as $item_precio_anterior) {
                    $precio_anterior = ProductoListaprecio::findFirst(array("idproducto = :idproducto: and idprecio = :idprecio:", 'bind' => array('idproducto' => $product->idproducto, 'idprecio' => $item_precio_anterior->idprecio)));
                    $precio_anterior->estado = 'inactivo';

                    try {
                        if(!$precio_anterior->save()) {
                            $this->db->rollback();
                            $msg = '';
                            foreach ($precio_anterior->getMessages() as $message) {
                                $msg = $msg.$message."</br>\n";
                            }
                            $resp['respuesta'] = 'error';
                            $resp['titulo'] = 'Error';
                            $resp['mensaje'] = 'Obtenemos el siguiente error: '.$msg;
                            echo json_encode($resp);
                            exit();
                        }
                    } catch (Exception $e) {
                        $this->saveLogger($e);
                        $this->db->rollback();
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error en BD';
                        $resp['mensaje'] = 'Obtenemos el siguiente error: '.$e->getMessage();
                        echo json_encode($resp);
                        exit();
                    }
                }

                $lista_precios_unicos = $resp_lista_precios['lista_unicos'];
                
                foreach($lista_precios_unicos as $item_precio) {
                    $array_item_precio = explode('|', $item_precio);
                    $nombre_precio = $array_item_precio[0];
                    $monto_precio = $array_item_precio[1];

                    $item_precio = ProductoListaprecio::findFirst(array("idproducto = :idproducto: and nombre = :nombre: and precio = :precio:", 'bind' => array('idproducto' => $product->idproducto, 'nombre' => $nombre_precio, 'precio' => $monto_precio)));
                    if(!$item_precio) {
                        $item_precio = new ProductoListaprecio();
                        $item_precio->idproducto = $product->idproducto;
                        $item_precio->nombre = $nombre_precio;
                        $item_precio->precio = $monto_precio;
                        $item_precio->estado = 'activo';
                    } else {
                        $item_precio->estado = 'activo';
                    }

                    try {
                        if(!$item_precio->save()) {
                            $this->db->rollback();
                            $msg = '';
                            foreach ($item_precio->getMessages() as $message) {
                                $msg = $msg.$message."</br>\n";
                            }
                            $resp['respuesta'] = 'error';
                            $resp['titulo'] = 'Error';
                            $resp['mensaje'] = 'Obtenemos el siguiente error: '.$msg;
                            echo json_encode($resp);
                            exit();
                        }
                    } catch (Exception $e) {
                        $this->saveLogger($e);
                        $this->db->rollback();
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error en BD';
                        $resp['mensaje'] = 'Obtenemos el siguiente error: '.$e->getMessage();
                        echo json_encode($resp);
                        exit();
                    }
                }
            }

            $bd_lista_presentaciones = ProductoPresentacion::find(array("idproducto = :idproducto:", 'bind' => array('idproducto' => $product->idproducto)));
            foreach($bd_lista_presentaciones as $item_presentacion) {
                $bd_item_presentacion = ProductoPresentacion::findFirst(array("id_presentacion = :id_presentacion:", 'bind' => array('id_presentacion' => $item_presentacion->id_presentacion)));
                $bd_item_presentacion->estado = 'inactivo';
                $resp_save_bd = $bd_item_presentacion->save();
            }
    
            $array_lista_presentaciones_codigos = array();
            if(count($presentaciones) > 0) {
                foreach($presentaciones as $presentacion) {
                    $bd_presentacion = ProductoPresentacion::findFirst(array("idproducto = :idproducto: and idunidad = :idunidad: and nombre = :nombre:", 'bind' => array('idproducto' => $product->idproducto, 'idunidad' => $presentacion->id_unidad_presentacion, 'nombre' => $presentacion->nombre_presentacion)));
                    if(!$bd_presentacion) {
                        $bd_presentacion = new ProductoPresentacion();
                    }

                    if($product->id_tipoafectacionigv == 10 || $product->id_tipoafectacionigv == 7152) {
                        if($presentacion->precio_con_igv == '' || floatval($presentacion->precio_con_igv) < 0) {
                            $this->db->rollback();
                            $resp['respuesta'] = 'error';
                            $resp['titulo'] = 'Error';
                            $resp['mensaje'] = 'En el precio de presentación no debes ingresar números negativos o valores vacíos';
                            echo json_encode($resp);
                            exit();
                        }

                        $presentacion_valor_sin_igv = round($presentacion->precio_con_igv/1.18, $num_decimales); //$valor_sin_igv
                        $presentacion_valor_con_igv = $presentacion->precio_con_igv;
                    } else {
                        if($presentacion->precio_sin_igv == '' || floatval($presentacion->precio_sin_igv) < 0) {
                            $this->db->rollback();
                            $resp['respuesta'] = 'error';
                            $resp['titulo'] = 'Error';
                            $resp['mensaje'] = 'En el precio sin igv de presentación no debes ingresar números negativos o valores vacíos';
                            echo json_encode($resp);
                            exit();
                        }

                        $presentacion_valor_sin_igv = $presentacion->precio_sin_igv; //$valor_sin_igv
                        $presentacion_valor_con_igv = round($presentacion->precio_sin_igv*1.18, $num_decimales);
                    }

                    $presentacion->precio_minimo = empty($presentacion->precio_minimo)?0:$presentacion->precio_minimo;
                    if(floatval($presentacion->precio_minimo) < 0) {
                        $this->db->rollback();
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = 'En el precio mínimo de presentación no debes ingresar números negativos o valores vacíos';
                        echo json_encode($resp);
                        exit();
                    }

                    $unidad_base = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $presentacion->id_unidad_base)));
                    if(!$unidad_base) {
                        $this->db->rollback();
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = 'La unidad Base en el listado de presentaciones no existe o no es válido';
                        echo json_encode($resp);
                        exit();
                    }

                    $presentacion_unidad = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $presentacion->id_unidad_presentacion)));
                    if(!$presentacion_unidad) {
                        $this->db->rollback();
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = 'La unidad de la Presentación en el listado de presentaciones no existe o no es válido';
                        echo json_encode($resp);
                        exit();
                    }

                    if($presentacion->cantidad < 0) {
                        $this->db->rollback();
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = 'En la cantidad de presentación no debes ingresar números negativos';
                        echo json_encode($resp);
                        exit();
                    }

                    $bd_presentacion->idproducto = $product->idproducto;
                    $bd_presentacion->idunidad = intval($presentacion->id_unidad_presentacion);
                    $bd_presentacion->idunidad_base = intval($presentacion->id_unidad_base);
                    $bd_presentacion->precio_con_igv = $presentacion_valor_con_igv;
                    $bd_presentacion->precio_sin_igv = $presentacion_valor_sin_igv;
                    $bd_presentacion->precio_minimo = floatval($presentacion->precio_minimo) + 0;
                    $bd_presentacion->cantidad_und_base = $presentacion->cantidad + 0;
                    $bd_presentacion->nombre = $presentacion->nombre_presentacion;
                    $bd_presentacion->codigo = $presentacion->codigo;
                    $bd_presentacion->fecha_registro = $fecha_registro;
                    $bd_presentacion->estado = 'activo';

                    $array_lista_presentaciones_codigos[] = $bd_presentacion->codigo;

                    try {
                        if(!$bd_presentacion->save()) {
                            $this->db->rollback();
                            $msg = '';
                            foreach ($bd_presentacion->getMessages() as $message) {
                                $msg = $msg.$message."</br>\n";
                            }
                            $resp['respuesta'] = 'error';
                            $resp['titulo'] = 'Error';
                            $resp['mensaje'] = 'Obtenemos el siguiente error: '.$msg;
                            echo json_encode($resp);
                            exit();
                        }
                    } catch (Exception $e) {
                        $this->saveLogger($e);
                        $this->db->rollback();
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error en BD';
                        $resp['mensaje'] = 'Obtenemos el siguiente error: '.$e->getMessage();
                        echo json_encode($resp);
                        exit();
                    }
                }
            }

            $array_lista_codigos = array_unique($array_lista_presentaciones_codigos);
            $product->codigos_presentaciones = implode(',', $array_lista_codigos);

            try {
                if(!$product->save()) {
                    $this->db->rollback();
                    $msg = '';
                    foreach ($product->getMessages() as $message) {
                        $msg = $msg.$message."</br>\n";
                    }
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error en BD';
                    $resp['mensaje'] = 'Obtenemos el siguiente error: '.$msg;
                    echo json_encode($resp);
                    exit();
                }
            } catch (Exception $e) {
                $this->saveLogger($e);
                $this->db->rollback();
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en BD';
                $resp['mensaje'] = 'Obtenemos el siguiente error: '.$e->getMessage();
                echo json_encode($resp);
                exit();
            }
            
            $this->db->commit();
            $resp['respuesta'] = 'ok';
            $resp['titulo'] = '¡Excelente!';
            $resp['mensaje'] = 'El producto se ha guardado correctamente!';
            $resp['producto'] = $product;
            echo json_encode($resp);
            exit();
        }
    }

    public function get_lista_precios($lista_precios, $contribuyente) {
        if(!is_array($lista_precios)) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Haz Activado la opción de multiprecio, por tanto debes agregar una lista de precios!...';
			return $resp;
        }
        
        if(count($lista_precios) <= 0) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Haz Activado la opción de multiprecio, por tanto debes agregar una lista de precios!...';
			return $resp;
        }
        
        $num_decimales = 2;
		if($contribuyente->num_decimales > 2) {
			$num_decimales = $contribuyente->num_decimales;
        }
        
        $lista = array();
		$n = 0;
		foreach($lista_precios as $item) {
            if(empty($item->nombre) || empty($item->precio)) {
                continue;
            }

            if($item->nombre == 'Click para Cambiar' && $item->precio == '00') {
                continue;
            }

            $lista[] = $item->nombre.'|'.round(floatval($item->precio), $num_decimales);
        }

        $lista_unicos = array_unique($lista);

        if(count($lista_unicos) <= 0) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Haz Activado la opción de multiprecio, por tanto debes agregar una lista de precios!...';
			return $resp;
        }

        $resp['respuesta'] = 'ok';
        $resp['lista_unicos'] = $lista_unicos;
        return $resp;
    }

    public function getDataProductoAction()
    {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes iniciar sesión.';
                echo json_encode($resp);
                exit();
            }

            $herramientas = new HerramientasController;
            $gestion_usuarios = new GestionuserController;

            if($usuario->id_rol == 1 || $usuario->id_rol == 2 || $usuario->id_rol == 3) {
                $resp_permiso_costo_prod = true;
            } else {
                $resp_permiso_costo_prod = $gestion_usuarios->verificar_permisos($usuario, 'permisos_otros', 'opt_otros_costocompra');
            }

            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
			
			$idproducto = !isset($datapost['idproducto'])?0:intval($datapost['idproducto']) + 0;
			$producto = Producto::findFirst(array("idproducto = :idproducto: and id_contribuyente = :id_contribuyente:", 'bind' => array('idproducto' => $idproducto, 'id_contribuyente' => $usuario->id_contribuyente)));
            if(!$producto) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No existe el producto que seleccionaste';
                echo json_encode($resp);
                exit();
            }

            $simbolo_moneda = 'S/ ';
            if($producto->id_cod_moneda == 'USD') {
                $simbolo_moneda = '$ ';
            }

            $idcliente = !isset($datapost['idcliente'])?0:intval($datapost['idcliente']) + 0;
             
            $codigo_sunat = SunatCodproducto::findFirst(array("codigoproducto = :codigoproducto:", 'bind' => array('codigoproducto' => $producto->codigo)));
            $unidad_medida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $producto->id_unidad_medida)));

            $num_decimales = 2;
            if($contribuyente->num_decimales > 2) {
                $num_decimales = $contribuyente->num_decimales;
            }
            
            $producto->precio_compra = round($producto->precio_compra, $num_decimales);
            $producto->valor_sin_igv = round($producto->valor_sin_igv, $num_decimales);
            $producto->valor_con_igv = round($producto->valor_con_igv, $num_decimales);
            $resp_historial_costos = $this->get_historial_costos($producto->id_contribuyente, $producto->idproducto);
            $array_costos = $resp_historial_costos['array_precios'];
            if(count($array_costos) <= 0) {
                $html_costos =  $html_costos = '<ul class="list list-icons"><li><i class="icon-coins text-success position-left"></i> <span class="text-muted text-size-small mb-5">'.date("d-m-Y", strtotime($producto->fecha_registro)).'</span> - '.$producto->id_cod_moneda.' '.$producto->precio_compra.'</li></ul>';
            } else {
                $html_costos = $resp_historial_costos['html_costos'];
            }

            if($resp_permiso_costo_prod === true) {
                
            } else {
                $html_costos = '';
            }

            $lista_precios = ProductoListaprecio::find(array("idproducto = :idproducto: and estado = 'activo'", 'bind' => array('idproducto' => $producto->idproducto)));
            
            $lista_precios_array = array();
            foreach($lista_precios as $item_precio) {
                $lista_precios_array[] = array(
                    'nombre' => $item_precio->nombre,
                    'precio' => $item_precio->precio + 0,
                    'moneda' => $producto->id_cod_moneda
                );
            }

            $html_ultimo_precio = '';
            if($contribuyente->mostrar_uprecio_clieprod == 'si') {    
                if($idcliente > 0) {
                    $query = 'SELECT doc.idcliente, detalle.iddetalle, detalle.id_contribuyente, detalle.id_tipodoc_electronico, detalle.serie_comprobante, detalle.numero_comprobante, detalle.tipo_envio_sunat, detalle.cantidad, detalle.precio, detalle.id_producto, doc.fecha_comprobante, doc.fecha_registro, doc.id_codigomoneda, doc.tipo_cambio_sunat FROM detalle_doc detalle INNER JOIN doc_electronico doc on (detalle.id_contribuyente = doc.id_contribuyente and detalle.id_tipodoc_electronico = doc.id_tipodoc_electronico and detalle.serie_comprobante = doc.serie_comprobante and detalle.numero_comprobante = doc.numero_comprobante and detalle.tipo_envio_sunat = doc.tipo_envio_sunat) where doc.id_contribuyente = :id_contribuyente and (doc.id_tipodoc_electronico = "01" or doc.id_tipodoc_electronico = "03") and doc.idcliente = :idcliente and detalle.id_producto = :idproducto and detalle.tipo_envio_sunat = :tipo_envio_sunat ORDER BY doc.fecha_registro DESC LIMIT 1';

                    try {
                        $sentencia = $this->db->prepare($query);
                        $sentencia->bindParam(':id_contribuyente', $usuario->id_contribuyente, PDO::PARAM_INT);
                        $sentencia->bindParam(':idcliente', $idcliente, PDO::PARAM_INT);
                        $sentencia->bindParam(':idproducto', $producto->idproducto, PDO::PARAM_INT);
                        $sentencia->bindParam(':tipo_envio_sunat', $contribuyente->tipo_envio_sunat, PDO::PARAM_STR);
                        $sentencia->execute();
                    } catch (Exception $e) {
                        $this->saveLogger($e);
                        echo 'Excepción capturada: (getDataProductoAction) ',  $e->getMessage(), "\n";
                        exit();
                    }

                    $result = $sentencia->fetch();

                    if(isset($result['serie_comprobante']) && isset($result['numero_comprobante'])) {
                        $precio_ultimo = floatval($result['precio']) + 0;
                        
                        if($result['id_codigomoneda'] == 'USD') {
                            $simbolo_ultimo_precio = '$ ';
                        } else {
                            $simbolo_ultimo_precio = 'S/ ';
                        }

                        $html_ultimo_precio = '
                        <li><a href="javascript:void(0);" data-precio="'.$precio_ultimo.'" onclick="asignar_precio_de_lista('.$precio_ultimo.','."'".$result['id_codigomoneda']."'".')"><span class="badge badge-success pull-right">'.$simbolo_ultimo_precio.' '.$precio_ultimo.'</span> Último Precio Utilizado en '.$result['serie_comprobante'].'-'.$result['numero_comprobante'].'</a></li>
                        ';
                    }

                    if(count($lista_precios_array) > 0) {
                        $html_ultimo_precio = '<li class="divider"></li>'.$html_ultimo_precio;
                    }
                }
            }

            if($producto->id_tipoafectacionigv == "10" || $producto->id_tipoafectacionigv == "7152") {
                $precio_venta_original = $producto->valor_con_igv;
            } else {
                $precio_venta_original = $producto->valor_sin_igv;
            }

            /*
            if(count($lista_precios_array) > 0) {
                $html_ultimo_precio = $html_ultimo_precio.'
                <li><a href="javascript:void(0);" data-precio="'.$precio_venta_original.'" onclick="asignar_precio_de_lista('.$precio_venta_original.','."'".$producto->id_cod_moneda."'".')"><span class="badge badge-success pull-right">'.$simbolo_moneda.' '.$precio_venta_original.'</span> Precio de Venta</a></li>
                <li><a href="javascript:void(0);" data-precio="'.round($producto->precio_venta_minimo, $num_decimales).'" onclick="asignar_precio_de_lista('.round($producto->precio_venta_minimo, $num_decimales).','."'".$producto->id_cod_moneda."'".')"><span class="badge badge-success pull-right">'.$simbolo_moneda.' '.round($producto->precio_venta_minimo, $num_decimales).'</span> Precio Mínimo</a></li>
                ';
            }
            */

            $precio_venta_minimo = !empty($producto->precio_venta_minimo) ? $producto->precio_venta_minimo : 0;
            $html_ultimo_precio = $html_ultimo_precio.'
            <li id="vm_add_product_producto_precio_venta"><a href="javascript:void(0);" data-precio="'.$precio_venta_original.'" onclick="asignar_precio_de_lista('.$precio_venta_original.','."'".$producto->id_cod_moneda."'".')"><span class="badge badge-success pull-right">'.$simbolo_moneda.' '.$precio_venta_original.'</span> Precio de Venta</a></li>
            <li id="vm_add_product_producto_precio_venta_minimo"><a href="javascript:void(0);" data-precio="'.round($precio_venta_minimo, $num_decimales).'" onclick="asignar_precio_de_lista('.round($precio_venta_minimo, $num_decimales).','."'".$producto->id_cod_moneda."'".')"><span class="badge badge-success pull-right">'.$simbolo_moneda.' '.round($precio_venta_minimo, $num_decimales).'</span> Precio Mínimo</a></li>
            ';

            if($contribuyente->mostrar_codprod_pdfs == 'si') {
                $producto->nombre = $producto->codigo.' - '.$producto->nombre;
            }

            $detraccion = array();
            $detraccion['id_cod_detraccion'] = '';
            $detraccion['descripcion'] = '';
            $detraccion['porcentaje'] = '';
            
            if(!empty($producto->id_cod_detraccion)) {
                $codigodetraccion = SunatCodigodetraccion::findFirst(array("id_cod_detraccion = :id_cod_detraccion:", 'bind' => array('id_cod_detraccion' => $producto->id_cod_detraccion)));
                if($codigodetraccion) {
                    $detraccion['id_cod_detraccion'] = $codigodetraccion->id_cod_detraccion;
                    $detraccion['descripcion'] = $codigodetraccion->descripcion;
                    $detraccion['porcentaje'] = $codigodetraccion->porcentaje;
                }
            }

            $presentaciones = ProductoPresentacion::find(array("idproducto = :idproducto: and estado = 'activo'", 'bind' => array('idproducto' => $producto->idproducto)));
            $lista_presentaciones = array();
            foreach($presentaciones as $presentacion) {

                $unidad_presentacion = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $presentacion->idunidad)));
                $unidad_base = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $presentacion->idunidad_base)));

                $lista_presentaciones[] = array(
                    "row_identificadador"   => $presentacion->id_presentacion,
                    "codigo"    => $presentacion->codigo,
                    "nombre_presentacion"   => $presentacion->nombre,
                    "id_producto"   => $presentacion->idproducto,
                    "id_unidad_presentacion"   => $presentacion->idunidad,
                    "id_unidad_base"   => $presentacion->idunidad_base, //$('#producto_unidadmedida').val(),
                    "nombre_unidad_presentacion"   => $unidad_presentacion->nombre,
                    "cantidad"   => $presentacion->cantidad_und_base + 0,
                    "nombre_unidad_base"   => $unidad_base->nombre,
                    "precio_con_igv"   => $presentacion->precio_con_igv + 0,
                    "precio_sin_igv"   => $presentacion->precio_sin_igv + 0,
                    "id_presentacion"  => $presentacion->id_presentacion,
                    "id_cod_moneda"    => $producto->id_cod_moneda,
                    "precio_minimo"    => $presentacion->precio_minimo + 0
                );
            }
            
			$resp['respuesta'] = 'ok';
            $resp['producto'] = $producto;
            $imgs_producto = ($resp['producto']->foto != '')?json_decode($resp['producto']->foto, true):array();
            $imgs_producto_cuadradas = isset($imgs_producto['cuadradas'])?$imgs_producto['cuadradas']:array();
            $imgs_producto_verticales = isset($imgs_producto['verticales'])?$imgs_producto['verticales']:array();
            $imgs_producto_horizontales = isset($imgs_producto['horizontales'])?$imgs_producto['horizontales']:array();
            $imgs_producto_cuadradas = array_unique($imgs_producto_cuadradas);
            $imgs_producto_verticales = array_unique($imgs_producto_verticales);
            $imgs_producto_horizontales = array_unique($imgs_producto_horizontales);
            $imgs_producto['cuadradas'] = $imgs_producto_cuadradas;
            $imgs_producto['verticales'] = $imgs_producto_verticales;
            $imgs_producto['horizontales'] = $imgs_producto_horizontales;
            $resp['producto']->foto = json_encode($imgs_producto);
            $resp['codigosunat'] = $codigo_sunat;
            $resp['unidad_medida'] = $unidad_medida;
            $resp['restriccion_stock'] = $contribuyente->restriccion_stock;
            $resp['multi_almacen'] = $contribuyente->multi_almacen;
            $resp['html_costos'] = $html_costos;
            $resp['lista_precios'] = $lista_precios_array;
            $resp['html_ultimo_precio'] = $html_ultimo_precio;
            $resp['detraccion'] = $detraccion;
            $resp['presentaciones'] = $lista_presentaciones;
            $resp['fecha_vencimiento'] = empty($producto->fecha_vencimiento)?'':date("d/m/Y", strtotime($producto->fecha_vencimiento));
            $resp['porcentajes_ganancia'] = $herramientas->get_porcentajes_ganancia($producto->precio_compra, $producto->valor_con_igv, $producto->precio_venta_minimo);
			echo json_encode($resp);
			exit();
		}
    }

    public function get_historial_costos($id_contribuyente, $idproducto) {
        //SELECT dc.id_detalle, dc.id_compra, dc.id_unidad_medida, dc.precio, dc.precio_sin_igv, dc.id_tipoafectacionigv, c.id_contribuyente, c.fecha_registro, c.id_tipodoc_electronico FROM detalle_compra dc INNER JOIN compra c on dc.id_compra = c.id_compra where dc.id_producto = 11604 and c.id_contribuyente = 1 and c.id_tipodoc_electronico in ('01', '03', '77', '00') and c.estado = 'activo' ORDER BY c.fecha_registro DESC LIMIT 3

        /*
        $resp['respuesta'] = 'ok';
        $resp['html_costos'] = '<ul class="list list-icons"></ul>';
        $resp['array_precios'] = array();
        $resp['moneda_costo_promedio'] = '';
        $resp['costo_promedio'] = '';
        return $resp;
        */

        /*
        $query = "SELECT dc.id_detalle, dc.id_compra, dc.id_unidad_medida, dc.precio, c.id_codigomoneda, dc.precio_sin_igv, dc.id_tipoafectacionigv, c.id_contribuyente, c.fecha_registro, c.id_tipodoc_electronico, c.tipo_cambio_sunat FROM detalle_compra dc INNER JOIN compra c on dc.id_compra = c.id_compra where dc.id_producto = :id_producto and c.id_contribuyente = :id_contribuyente and c.id_tipodoc_electronico in ('01', '03', '77', '00') and c.estado = 'activo' ORDER BY c.fecha_registro DESC LIMIT 3";
        */

        $producto = Producto::findFirst(array("idproducto = :idproducto: and id_contribuyente = :id_contribuyente:", 'bind' => array('idproducto' => $idproducto, 'id_contribuyente' => $id_contribuyente)));

        $query = "
        SELECT * FROM (
            
            (SELECT dc.id_unidad_medida as id_unidad_medida, dc.precio as precio, c.id_codigomoneda as id_codigomoneda, dc.precio_sin_igv as precio_sin_igv, dc.id_tipoafectacionigv as id_tipoafectacionigv, c.fecha_registro as fecha_registro, c.tipo_cambio_sunat as tipo_cambio_sunat FROM detalle_compra dc INNER JOIN compra c on dc.id_compra = c.id_compra where dc.id_producto = :id_producto and c.id_contribuyente = :id_contribuyente and c.id_tipodoc_electronico in ('01', '03', '77', '00') and c.estado = 'activo' ORDER BY c.fecha_registro DESC LIMIT 3)
            
            UNION ALL
            
            (SELECT movdet.o_id_u_medida as id_unidad_medida, movdet.costo_unitario as precio, movdet.id_codigomoneda id_codigomoneda, 0 precio_sin_igv, movdet.o_id_afectigv id_tipoafectacionigv, mov.fecha_movimiento fecha_registro, 0 tipo_cambio_sunat FROM producto_movimiento_det movdet INNER JOIN producto_movimiento mov ON (movdet.id_contribuyente = mov.id_contribuyente and movdet.id_tipo_movimiento = mov.id_tipo_movimiento and movdet.serie = mov.serie and movdet.correlativo = mov.correlativo and movdet.tipo_envio_sunat = mov.tipo_envio_sunat) where movdet.id_contribuyente = :id_contribuyente and movdet.id_tipo_movimiento = 'in' and movdet.o_id_prod = :id_producto ORDER BY mov.fecha_movimiento DESC LIMIT 3)

        ) as tbl_costos ORDER BY fecha_registro DESC LIMIT 3
        ";

        try {
            $sentencia = $this->db->prepare($query);
            $sentencia->bindParam(':id_contribuyente', $id_contribuyente, PDO::PARAM_INT);
            $sentencia->bindParam(':id_producto', $idproducto, PDO::PARAM_INT);
            $sentencia->execute();
        } catch (Exception $e) {
            $this->saveLogger($e);
            echo 'Excepción capturada: (get_historial_costos) ',  $e->getMessage(), "\n";
            exit();
        }

        $html_costos = '<ul class="list list-icons">';
        $array_precios = array();
        while ($fila = $sentencia->fetch()) {
            $fila = (object)$fila;
            if($fila->id_tipoafectacionigv == 10 || $fila->id_tipoafectacionigv == 7152) {
                $precio_producto = $fila->precio + 0;
            } else {
                $precio_producto = $fila->precio_sin_igv + 0;
            }

            $texto_precio_compra = $fila->id_codigomoneda.' '.$precio_producto;
            if($fila->id_codigomoneda == 'USD') {
                $equivalente_soles = (round($precio_producto*$fila->tipo_cambio_sunat, 2)) > 0?' - S/. '.round($precio_producto*$fila->tipo_cambio_sunat, 2):'';
                $texto_precio_compra = $fila->id_codigomoneda.' '.$precio_producto.$equivalente_soles;
            }

            $html_costos = $html_costos.'<li><i class="icon-coins text-success position-left"></i> <span class="text-muted text-size-small mb-5">'.date("d-m-Y", strtotime($fila->fecha_registro)).'</span> - '.$texto_precio_compra.'</li>';
            $array_precios[] = $precio_producto;
        }

        $html_costos = $html_costos.'<li><i class="icon-coins text-success position-left"></i> <span class="text-muted text-size-small mb-5">Costo en Almacén:</span> '.$producto->id_cod_moneda.' '.($producto->precio_compra + 0).'</li>';

        $moneda_costo_promedio = '';
        $costo_promedio = '';
        $html_costo_promedio = '';
        
        /*
        $save_kardex = Kardex::findFirst(array("idproducto = :idproducto:", 'bind' => array('idproducto' => $idproducto), "order" => "id_kardex DESC"));
        if(!$save_kardex) {
            $moneda_costo_promedio = '';
            $costo_promedio = '';
            $html_costo_promedio = '';
        } else {
            $moneda_costo_promedio = $save_kardex->id_cod_moneda;
            $costo_promedio = $save_kardex->costo_unitario_promedio;
            $html_costos = $html_costos.'
            <li class="list-group-divider"></li>
            <li>
                <i class="icon-checkmark-circle text-success position-left"></i>
                PROMEDIO (kardex) - '.$save_kardex->id_cod_moneda.' '.$save_kardex->costo_unitario_promedio.'
            </li>
            ';
        }
        */

        $html_costos = $html_costos.'</ul>';
        $resp['respuesta'] = 'ok';
        $resp['html_costos'] = $html_costos;
        $resp['array_precios'] = $array_precios;
        $resp['moneda_costo_promedio'] = $moneda_costo_promedio;
        $resp['costo_promedio'] = $costo_promedio;
        return $resp;
    }

    public function getListaProductosAction(){
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
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

            $datapost = $this->request->getPost();
            $idsucursal = !isset($datapost['idsucursal'])?0:abs(intval($datapost['idsucursal']));
            if($idsucursal > 0) {
                $sucursal = Sucursal::findFirst(array("id_contribuyente = :id_contribuyente: and idsucursal = :idsucursal:", 'bind' => array('idsucursal' => $idsucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
                if(!$sucursal) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'No Existe el Almacén Seleccionado';
                    echo json_encode($resp);
                    exit(); 
                }
            }

            $select_tipo_item = !isset($datapost['select_tipo_item'])?0:abs(intval($datapost['select_tipo_item']));
            if(!in_array($select_tipo_item, array(0, 1, 2))) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El Tipo de Producto no es Válido';
                echo json_encode($resp);
                exit(); 
            }

            $resp = $this->get_lista_productos($usuario, $datapost, $idsucursal, $select_tipo_item);
            echo json_encode($resp);
            exit();
        }
    }

    public function get_lista_productos($usuario, $datapost, $idsucursal = 0, $select_tipo_item = 0) {
        $id_contribuyente = $usuario->id_contribuyente;
        $gestion_usuarios = new GestionuserController;

        $tipo_producto = '';

        if($select_tipo_item == 1) {
            $tipo_producto = " and id_unidad_medida <> 20 ";
        }

        if($select_tipo_item == 2) {
            $tipo_producto = " and id_unidad_medida = 20 ";
        }

        $sucursales_eliminadas = Sucursal::find(array("id_contribuyente = :id_contribuyente: and estado = 'inactivo'", 'bind' => array('id_contribuyente' => $id_contribuyente)));
        $ids_sucursales_eliminadas = array();
        foreach($sucursales_eliminadas as $sucursal_eliminada) {
            $ids_sucursales_eliminadas[] = $sucursal_eliminada->idsucursal;
        }

        $sql_sucursal_eliminadas = '';
        if(count($ids_sucursales_eliminadas) > 0) {
            $sql_sucursal_eliminadas = " and idsucursal not in (".implode(",", $ids_sucursales_eliminadas).") ";
        }

        if($idsucursal > 0) {
            $total_registros = count(Producto::find(array("id_contribuyente = :id_contribuyente: and idsucursal = :idsucursal: and estado = 'activo' ".$tipo_producto, 'bind' => array('id_contribuyente' => $id_contribuyente, 'idsucursal' => $idsucursal), 'columns' => 'idproducto', "order" => "idproducto DESC")));
        } else {
            $total_registros = count(Producto::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo' ".$tipo_producto.$sql_sucursal_eliminadas, 'bind' => array('id_contribuyente' => $id_contribuyente), 'columns' => 'idproducto', "order" => "idproducto DESC")));
        }
        
        $columnas = array( 
            //indice de la columna en datatable => nombre de la columna
            0 => 'p.idproducto',
            1 => 'p.idproducto',
            2 => 'p.codigo',

            3 => 'p.idsucursal',
            4 => 's.nombre',
            
            5 => 'p.id_categoria',
            6 => 'cate.nombre',

            7 => 'p.nombre',

            8 => 'p.id_unidad_medida',
            9 => 'unidad.nombre',

            10 => 'p.id_tipoafectacionigv',
            11 => 'stipoafec.descripcion',

            12 => 'p.costo_promedio',
            13 => 'p.stock',

            14 => 'p.precio_compra',
            15 => 'p.id_cod_moneda',
            16 => 'p.valor_con_igv',
            
            17 => 'nota',
            18 => 'p.stock',
            19 => 'p.stock_minimo',
            20 => 'p.afecto_icbper',
            21 => 'p.multi_precio',
            22 => 'p.fecha_vencimiento',
            23 => 'p.marca',
            24 => 'p.peso',
            25 => 'fecha_registro',
            26 => 'fecha_registro'
        );

        $query = "SELECT p.*, cate.nombre nombrecategoria, stipoafec.descripcion tipoafectacion, unidad.nombre nombreunidad, unidad.codigo codigounidad, unidad.simbolo simbolounidad, s.nombre nombresucursal FROM 
	                producto p 
                    LEFT JOIN categoria cate on p.id_categoria = cate.idcategoria 
                    INNER JOIN sunat_tipoafectacionigv stipoafec on p.id_tipoafectacionigv = stipoafec.id_tipoafectacionigv 
                    INNER JOIN sunat_unidadmedida unidad on p.id_unidad_medida = unidad.idunidad 
                    LEFT JOIN sucursal s on p.idsucursal = s.idsucursal WHERE p.id_contribuyente = ".$id_contribuyente." and p.estado = 'activo' ";

        if($idsucursal > 0) {
            $query = $query.' and p.idsucursal = '.$idsucursal.' ';
        }

        if($select_tipo_item == 1) {
            $query = $query." and p.id_unidad_medida <> 20 ";
        }

        if($select_tipo_item == 2) {
            $query = $query." and p.id_unidad_medida = 20 ";
        }

        if(count($ids_sucursales_eliminadas) > 0) {
            $query = $query." and p.idsucursal not in (".implode(",", $ids_sucursales_eliminadas).") ";
        }
        
        $termino = '';
        if(!empty($datapost['search']['value'])) {
            $termino = str_replace('  ', ' ', trim($datapost['search']['value']));
            $trozos = explode(" ", $termino); 
            $numero_trozos = count($trozos);
            $num_letras = strlen(preg_replace('/\s+/', '', $termino));

            if($numero_trozos <= 1) {
                $termino = '%'.$termino.'%';
				$query = $query." AND (cate.nombre like :termino or p.nombre like :termino or unidad.nombre like :termino or s.nombre like :termino or p.codigo like :termino) ";
			} else {				
				$termino = str_replace(" ", "+.*", $termino);
				$termino = str_replace(' ', '', $termino);
				$termino = "($termino)";
				
				$query = $query." AND (cate.nombre RLIKE :termino or p.nombre RLIKE :termino or unidad.nombre RLIKE :termino or s.nombre RLIKE :termino or p.codigo RLIKE :termino) ";
			}
        }

        $total_filas_filtradas = $this->get_num_rows_filtered_doc_sunat($query, $termino);
        if(intval($datapost['length']) > 0) {
            $query = $query." ORDER BY ".$columnas[$datapost['order'][0]['column']]." ".$datapost['order'][0]['dir']." LIMIT ".intval($datapost['start']).",".intval($datapost['length'])."";
        } else {
            $query = $query." ORDER BY ".$columnas[$datapost['order'][0]['column']]." ".$datapost['order'][0]['dir']." ";
        }
        

        try {
            $sentencia = $this->db->prepare($query);
            if(!empty($datapost['search']['value'])) {
                $sentencia->bindParam(':termino', $termino, PDO::PARAM_STR);
            }
            $sentencia->execute();
        } catch (Exception $e) {
            $this->saveLogger($e);
            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
            exit();
        }

        if($usuario->id_rol == 1 || $usuario->id_rol == 2 || $usuario->id_rol == 3) {
            $resp_permiso_costo_prod = true;
        } else {
            $resp_permiso_costo_prod = $gestion_usuarios->verificar_permisos($usuario, 'permisos_otros', 'opt_otros_costocompra');
        }
        
        $array_lista = array();
        $n = 0;
        while($item = $sentencia->fetch()) {
            $item = (object)$item;
            
            if(($item->stock - $item->stock_minimo) <= 7) {
                $text_stock = '<span title="Las Existencias Actuales Están Cerca del Stock Minimo" class="label bg-danger">'.($item->stock + 0).'</span>';
            } else {
                $text_stock = '<span class="label bg-success">'.($item->stock + 0).'</span>';
            }
            
            if(empty($item->id_categoria) || $item->id_categoria <= 0) {
                $nombre_categoria = 'Sin Categoría';
            } else {
                $nombre_categoria = $item->nombrecategoria;
            }

            if($item->id_tipoafectacionigv == 10 || $item->id_tipoafectacionigv == 7152) {
                $precio_producto = $item->valor_con_igv;
            } else {
                $precio_producto = $item->valor_sin_igv;
            }
            
            if($item->id_cod_moneda == 'PEN') {
                $moneda_costo_promedio = 'S/ ';
                $moneda_precio = 'S/ ';
            } else {
                $moneda_costo_promedio = '$ ';
                $moneda_precio = '$ ';
            }

            
            if(empty($item->costo_promedio)) {
                $costo_promedio = $this->get_costo_promedio($id_contribuyente, $item->idsucursal, $item->idproducto);
            } else {
                $costo_promedio = $item->costo_promedio;
            }


            //$moneda_costo_promedio = $item->id_cod_moneda;
            
            /*
            $save_kardex = Kardex::findFirst(array("idproducto = :idproducto:", 'bind' => array('idproducto' => $item->idproducto), "order" => "id_kardex DESC"));
            $costo_promedio = $item->precio_compra;
            if($save_kardex) {
                //$moneda_costo_promedio = $save_kardex->id_cod_moneda;
                if($save_kardex->id_cod_moneda == 'PEN') {
                    $moneda_costo_promedio = 'S/ ';
                } else {
                    $moneda_costo_promedio = '$ ';
                }
                $costo_promedio = $save_kardex->costo_unitario_promedio;
            }
            */

            $sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal:", 'bind' => array('idsucursal' => $item->idsucursal)));
            $fecha_actual_mes = strrev(date('m'));
            $fecha_actual_anio = strrev(date('Y'));
            $l1 = !empty($sucursal->sitio_web) ? urlencode($sucursal->sitio_web) : '';
            $l2 = urlencode(substr($item->nombre, 0, 24));
            $l3 = urlencode($fecha_actual_mes.' - '.$fecha_actual_anio);
            $l4 = urlencode($moneda_precio.' '.($precio_producto + 0));

            $opciones = '<ul class="icons-list text-center">
                <li class="dropdown">
                    <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="icon-menu9"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li><a onclick="edit_product('.$item->idproducto.')" data-idproducto="'.$item->idproducto.'" href="javascript:void(0)"><i class="icon-pencil4"></i> Editar</a></li>
                        <li><a onclick="edit_product('.$item->idproducto.', '."'".'copiar'."'".')" data-idproducto="'.$item->idproducto.'" href="javascript:void(0)"><i class="icon-copy3"></i> Copiar Producto</a></li>
                        <li><a onclick="eliminar_producto('.$item->idproducto.')" data-idcategoria="'.$item->idproducto.'" href="javascript:void(0)"><i class="icon-user-cancel"></i> Eliminar</a></li>
                        <li><a target="_blank" href="/facturacionv8/producto/imprimir_codigobarras/'.$item->codigo.'/?l1='.$l1.'&l2='.$l2.'&l3='.$l3.'"><i class="icon-barcode2"></i> Cod.Prod.</a></li>
                        <li><a target="_blank" href="/facturacionv8/producto/imprimir_codigobarras/'.$item->codigo.'/?l1='.$l1.'&l2='.$l2.'&l3='.$l4.'"><i class="icon-ticket"></i> Precio Prod.</a></li>
                        <li><a title="Click para ver Kardex" target="_blank" href="/facturacionv8/reportekardex/kardexdeproducto/'.$item->idproducto.'"><i class="icon-stack2"></i> Ver Kardex</a></li>
                        <li><a onclick="ver_stock_varias_sucursales('."'".$item->codigo."'".','.$item->idsucursal.')" data-idsucursal="'.$item->idsucursal.'" data-idproducto="'.$item->idproducto.'" data-codigoproducto="'.$item->codigo.'" href="javascript:void(0)"><i class="icon-copy3"></i> Ver en Otros Almacenes</a></li>
                    </ul>
                </li>
            </ul>';

            if(isset($datapost['mostrar_opt_menu']) && $datapost['mostrar_opt_menu'] == 'no') {
                $opciones = '';
            }

            if($resp_permiso_costo_prod === true) {
                $costopromedio = '<a title="Click para ver Kardex" target="_blank" href="/facturacionv8/reportekardex/kardexdeproducto/'.$item->idproducto.'">'.round($costo_promedio, 4).'</a>';
                $precio_compra = ($item->precio_compra + 0);
            } else {
                $costopromedio = 0; 
                $precio_compra = 0;
            }


             
            $array_lista[] = array(
                'DT_RowId'              => $item->idproducto,
                'fecha_registro'        => date("d-m-Y H:i:s", strtotime($item->fecha_registro)),
                'idproducto'            => $item->idproducto, //0  (visible)
                'codigo'                => $item->codigo,
                'codigo_array'          => "$item->codigo||$l1||$l2||$l3", //"<a href='/facturacionv8/producto/imprimir_codigobarras/$item->codigo/?l1=$l1&l2=$l2&l3=$l3' target='_blank'>".$item->codigo."</a>", //1
 
                'idsucursal'            => $item->idsucursal, //2
                'nombresucursal'        => $item->nombresucursal, //3  (visible)

                'id_categoria'          => $item->id_categoria, //4
                'nombre_categoria'      => $nombre_categoria, //5
                'nombreproducto'        => $item->nombre, //6

                'id_unidad_medida'      => $item->id_unidad_medida, //7
                'nombreunidad'          => $item->nombreunidad, //8  (visible)
                'id_tipoafectacionigv'  => $item->id_tipoafectacionigv, //9
                'tipoafectacion'        => $item->tipoafectacion, //10   (visible)

                'costopromedio'         => $costopromedio,
                'precio_compra'         => $precio_compra, //12 (visible)

                'id_cod_moneda'         => $item->id_cod_moneda, //13
                'precio_venta'          => ($precio_producto + 0), //14  (visible)
                'nota'                  => $item->nota, //15
                'stock'                 => $item->stock + 0, //16  (visible)

                'stock_valorizado'      => round($item->stock*$costo_promedio,2),

                'stock_minimo'          => $item->stock_minimo + 0, //17
                'afecto_icbper'         => $item->afecto_icbper, //18
                'multi_precio'          => $item->multi_precio, //19

                'fecha_vencimiento'     => (!empty($item->fecha_vencimiento))?date("Y-m-d", strtotime($item->fecha_vencimiento)):'', //20
                'marca'                 => $item->marca, //21

                'peso'                  => $item->peso,

                'opciones'              => $opciones //22  (visible)
            );
        }

        $json_data = array(
            "draw"            => intval($datapost['draw']),
            "recordsTotal"    => intval($total_registros),  //Número total de registros que cumplen con los criterios iniciales
            "recordsFiltered" => intval($total_filas_filtradas), //Total registros filtrados
            "data"            => $array_lista,   // total data array
            "numero_n"        => $n
        );

        return $json_data;
    }

    public function get_costo_promedio($id_contribuyente, $id_sucursal, $id_producto) {
        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));

        $producto = Producto::findFirst(array("idproducto = :idproducto: and id_contribuyente = :id_contribuyente: and idsucursal = :idsucursal:", 'bind' => array('idproducto' => $id_producto, 'id_contribuyente' => $id_contribuyente, 'idsucursal' => $id_sucursal)));

        $item_kardex = Kardex::findFirst(array('idproducto = :idproducto: and tipo_envio_sunat = :tipo_envio_sunat: and id_contribuyente = :id_contribuyente:', 'bind' => array('idproducto' => $id_producto, 'tipo_envio_sunat' => $contribuyente->tipo_envio_sunat, 'id_contribuyente' => $id_contribuyente), "order" => "id_kardex DESC"));

        $costo_promedio = 0;

        if($item_kardex) {
            if($item_kardex->stock != $producto->stock) {
                $producto->stock = $item_kardex->stock;
            }

            $producto->costo_promedio = $item_kardex->costo_unitario_promedio;

            $resp = $producto->save();

            $costo_promedio = $item_kardex->costo_unitario_promedio;
        }

        return $costo_promedio;
    }

    public function get_num_rows_filtered_doc_sunat($query, $termino_busqueda) {
        $query = "select count(idproducto) total_productos from ($query) as tblproductos;";
        
        try {
            $sentencia = $this->db->prepare($query);
            if(!empty($termino_busqueda)) {
                $sentencia->bindParam(':termino', $termino_busqueda, PDO::PARAM_STR);
            }
            $sentencia->execute();
        } catch (Exception $e) {
            $this->saveLogger($e);
            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
            exit();
        }

        $n = 0;
        while($fila = $sentencia->fetch()) {
            $fila = (object)$fila;
            $total = $fila->total_productos;
        }
        
        return $total;
    }

    public function updateCellAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $json_data = array(
                    "data"            => array(),   // total data array
                    "respuesta"       => 'error',
                    "titulo"          => 'Error en Usuario',
                    "mensaje"         => 'Lo sentimos! Es posible que se haya cerrado la sessión, <a style="color: #1c81d1;" href="/session" target="_blank">haz click aquí para ingresar de nuevo</a>, luego de iniciar sessión, puedes regresar a esta pantalla y recargar la página y continuar...'
                );
                echo json_encode($json_data);
                exit();
            }

            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            if(!$contribuyente) {
                $json_data = array(
                    "data"            => array(),   // total data array
                    "respuesta"       => 'error',
                    "titulo"          => 'Error en Usuario',
                    "mensaje"         => 'Lo sentimos! Es posible que se haya cerrado la sessión, <a style="color: #1c81d1;" href="/session" target="_blank">haz click aquí para ingresar de nuevo</a>, luego de iniciar sessión, puedes regresar a esta pantalla y recargar la página y continuar...'
                );
                echo json_encode($json_data);
                exit();
            }
            
            //Datos de formulario
            $datapost = $this->request->getPost();
            $data_editable = $datapost['data'];

            $this->db->begin();
            foreach($data_editable as $idproducto => $array_columna ) {
                //validar si el producto pertenece al contribuyente
                $producto = Producto::findFirst(array("idproducto = :idproducto: and id_contribuyente = :id_contribuyente:", 'bind' => array('idproducto' => $idproducto, 'id_contribuyente' => $usuario->id_contribuyente)));
                if(!$producto) {
                    $this->db->rollback();
                    $json_data = array(
                        "data"            => array(),   // total data array
                        "respuesta"       => 'error',
                        "titulo"          => 'Error en Producto',
                        "mensaje"         => 'No Existe el Producto que Intenta Editar'
                    );
                    echo json_encode($json_data);
                    exit();
                }

                $gestion_usuarios = new GestionuserController;
                if($usuario->id_rol != 1 && $usuario->id_rol != 2 && $usuario->id_rol != 3 && $usuario->id_rol != 5) {
                    if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_productos') != 'acceso_total') {
                        if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_productos') != 'todas_las_sucursales') {
                            if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_productos') == 'prohibir') {
                                $json_data = array(
                                    "data"            => array(),   // total data array
                                    "respuesta"       => 'error',
                                    "titulo"          => 'Error',
                                    "mensaje"         => 'Se ha restringido la Edición y/o Actualización de Productos en su Cuenta!'
                                );
                                echo json_encode($json_data);
                                exit();
                            }

                            if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_productos') == 'sucursal_asignada') {
                                if($usuario->idsucursal != $producto->idsucursal) {
                                    $json_data = array(
                                        "data"            => array(),   // total data array
                                        "respuesta"       => 'error',
                                        "titulo"          => 'Error',
                                        "mensaje"         => 'No Tiene Permisos para Registrar y/o Actualizar Productos en la sucursal con ID: '.$producto->idsucursal
                                    );
                                    echo json_encode($json_data);
                                    exit();
                                }
                            }
                        }
                    }
                }

                foreach($array_columna as $nombre_columna => $valor ) {
                    if($nombre_columna == 'codigo') {
                        $codigo = strtoupper(trim($valor));
                        $product_con_codigoactual = Producto::findFirst(array("idproducto <> :idproducto: and id_contribuyente = :id_contribuyente: and UPPER(codigo) = :codigo: and estado = 'activo' and idsucursal = :idsucursal:", 'bind' => array('idproducto' => $idproducto, 'id_contribuyente' => $usuario->id_contribuyente, 'codigo' => $codigo, 'idsucursal' => $producto->idsucursal)));

                        if($product_con_codigoactual) {
                            $this->db->rollback();
                            $json_data = array(
                                "data"            => array(),   // total data array
                                "respuesta"       => 'error',
                                "titulo"          => 'Error',
                                "mensaje"         => 'El código actual ya está siendo utilizado, por favor, genere otro código o ingrese un nuevo código!'
                            );
                            echo json_encode($json_data);
                            exit();
                        }

                        $producto->codigo = $codigo;
                        $resp_save = $this->guardar_producto_for_updaterow($producto);
                        if($resp_save['respuesta'] == 'error') {
                            $this->db->rollback();
                            echo json_encode($resp_save);
                            exit();
                        }
                    } else if($nombre_columna == 'nombreproducto') {
                        if(empty(trim($valor))) {
                            $this->db->rollback();
                            $json_data = array(
                                "data"            => array(),   // total data array
                                "respuesta"       => 'error',
                                "titulo"          => 'Error',
                                "mensaje"         => 'Debes Ingresar un Nombre para el Producto!'
                            );
                            echo json_encode($json_data);
                            exit();
                        }

                        //guardar producto
                        $producto->nombre = trim($valor);
                        $resp_save = $this->guardar_producto_for_updaterow($producto);
                        if($resp_save['respuesta'] == 'error') {
                            $this->db->rollback();
                            echo json_encode($resp_save);
                            exit();
                        }
                    } else if($nombre_columna == 'precio_venta') {
                        $precio_venta = floatval($valor) + 0;
                        if($precio_venta < 0) {
                            $this->db->rollback();
                            $json_data = array(
                                "data"            => array(),   // total data array
                                "respuesta"       => 'error',
                                "titulo"          => 'Error',
                                "mensaje"         => 'El precio de venta no puede ser menor a cero!'
                            );
                            echo json_encode($json_data);
                            exit();
                        }

                        $num_decimales = 2;
                        if($contribuyente->num_decimales > 2) {
                            $num_decimales = $contribuyente->num_decimales;
                        }

                        if($producto->id_tipoafectacionigv == 10 || $producto->id_tipoafectacionigv == 7152) {
                            $valor_sin_igv = round($precio_venta/1.18, $num_decimales); //$valor_sin_igv
                            $valor_con_igv = $precio_venta;
                        } else {
                            $valor_sin_igv = $precio_venta; //$valor_sin_igv
                            $valor_con_igv = round($precio_venta*1.18, $num_decimales);
                        }

                        $producto->valor_sin_igv = $valor_sin_igv;
                        $producto->valor_con_igv = $valor_con_igv;

                        //guardar producto
                        $resp_save = $this->guardar_producto_for_updaterow($producto);
                        if($resp_save['respuesta'] == 'error') {
                            $this->db->rollback();
                            echo json_encode($resp_save);
                            exit();
                        }
                    } else if($nombre_columna == 'precio_compra') {
                        $precio_compra = floatval($valor) + 0;
                        if($precio_compra < 0) {
                            $this->db->rollback();
                            $json_data = array(
                                "data"            => array(),   // total data array
                                "respuesta"       => 'error',
                                "titulo"          => 'Error',
                                "mensaje"         => 'El precio de compra no puede ser menor a cero!'
                            );
                            echo json_encode($json_data);
                            exit();
                        }

                        $producto->precio_compra = $precio_compra;
                        //guardar producto
                        $resp_save = $this->guardar_producto_for_updaterow($producto);
                        if($resp_save['respuesta'] == 'error') {
                            $this->db->rollback();
                            echo json_encode($resp_save);
                            exit();
                        }
                        
                    } else if($nombre_columna == 'nota') {
                        $producto->nota = $valor;

                        //guardar producto
                        $resp_save = $this->guardar_producto_for_updaterow($producto);
                        if($resp_save['respuesta'] == 'error') {
                            $this->db->rollback();
                            echo json_encode($resp_save);
                            exit();
                        }
                    } else if($nombre_columna == 'stock') {
                        $unidadmedida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $producto->id_unidad_medida)));
                        $tipoafectacionigv = SunatTipoafectacionigv::findFirst(array("id_tipoafectacionigv = :id_tipoafectacionigv:", 'bind' => array('id_tipoafectacionigv' => $producto->id_tipoafectacionigv)));

                        $stock = floatval($valor) + 0;
                        if($stock < 0) {
                            $this->db->rollback();
                            $json_data = array(
                                "data"            => array(),   // total data array
                                "respuesta"       => 'error',
                                "titulo"          => 'Error',
                                "mensaje"         => 'El stock no puede ser menor a cero!'
                            );
                            echo json_encode($json_data);
                            exit();
                        }

                        if($unidadmedida->codigo != 'ZZ') {
                            $stock_actual = $producto->stock;
                            $stock_real = $stock;

                            if($stock_real > $stock_actual) {
                                //registramos el stock real en el producto
                                $producto->stock = $stock;

                                try {
                                    if(!$producto->save()) {
                                        $this->db->rollback();
                                        $msg = '';
                                        foreach ($producto->getMessages() as $message) {
                                            $msg = $msg.$message."</br>\n";
                                        }

                                        $json_data = array(
                                            "data"            => array(),   // total data array
                                            "respuesta"       => 'error',
                                            "titulo"          => 'Error',
                                            "mensaje"         => $msg
                                        );

                                        echo json_encode($json_data);
                                        exit();
                                    }
                                } catch (Exception $e) {
                                    $this->saveLogger($e);
                                    $this->db->rollback();
                                    $json_data = array(
                                        "data"            => array(),   // total data array
                                        "respuesta"       => 'error',
                                        "titulo"          => 'Error',
                                        "mensaje"         => $e->getMessage()
                                    );
                                    echo json_encode($json_data);
                                    exit();
                                }
                                
                                //se debe hacer un ingreso con la diferencia, para igual al stock real
                                $diferencia_stock = $stock_real - $stock_actual;
                                $resp_ingreso = $this->registrar_movimiento_kardex($producto, $contribuyente, $usuario, $diferencia_stock, 'entrada', 'Entrada Generada por Actualización Inline (Lista Productos)', $unidadmedida, $tipoafectacionigv);
                                if($resp_ingreso['respuesta'] == 'error') {
                                    $this->db->rollback();
                                    $json_data = array(
                                        "data"            => array(),   // total data array
                                        "respuesta"       => 'error',
                                        "titulo"          => 'Error',
                                        "mensaje"         => $resp_ingreso['mensaje']
                                    );
                                    
                                    echo json_encode($json_data);
                                    exit();
                                }
                            }
    
                            if($stock_actual > $stock_real) {
                                //no se necesita actualizar el producto, ya que al crear la nota de venta de salida, el sistema automáticamente descuenta el stock
                                
                                //se debe hacer una salida para igual al stock real
                                $diferencia_stock = $stock_actual - $stock_real;
                                $resp_salida = $this->registrar_movimiento_kardex($producto, $contribuyente, $usuario, $diferencia_stock, 'salida', 'Salida Generada por Actualización Inline (Lista Productos)', $unidadmedida, $tipoafectacionigv);
                                if($resp_salida['respuesta'] == 'error') {
                                    $this->db->rollback();
                                    if($resp_ingreso['respuesta'] == 'error') {
                                        $this->db->rollback();
                                        $json_data = array(
                                            "data"            => array(),   // total data array
                                            "respuesta"       => 'error',
                                            "titulo"          => 'Error',
                                            "mensaje"         => $resp_salida['mensaje']
                                        );
                                        
                                        echo json_encode($json_data);
                                        exit();
                                    }
                                }
                            }
                        }
                    } else if($nombre_columna == 'stock_minimo') {
                        $stock_minimo = floatval($valor) + 0;
                        if($stock_minimo <= 0) {
                            $this->db->rollback();
                            $json_data = array(
                                "data"            => array(),   // total data array
                                "respuesta"       => 'error',
                                "titulo"          => 'Error',
                                "mensaje"         => 'El stock_minimo no puede ser menor a cero!'
                            );
                            echo json_encode($json_data);
                            exit();
                        }
                        $producto->stock_minimo = $stock_minimo;
                        //guardar producto
                        $resp_save = $this->guardar_producto_for_updaterow($producto);
                        if($resp_save['respuesta'] == 'error') {
                            $this->db->rollback();
                            echo json_encode($resp_save);
                            exit();
                        }
                    } else if($nombre_columna == 'afecto_icbper') {
                        $afecto_icbper = ($valor=='si')?'si':'no';
                        $producto->afecto_icbper = $afecto_icbper;

                        //guardar producto
                        $resp_save = $this->guardar_producto_for_updaterow($producto);
                        if($resp_save['respuesta'] == 'error') {
                            echo json_encode($resp_save);
                            exit();
                        }
                    } else if($nombre_columna == 'fecha_vencimiento') {
                        $fecha_vencimiento = $valor;

                        if (!(DateTime::createFromFormat('Y-m-d', $fecha_vencimiento) !== FALSE)) {
                            $fecha_vencimiento = null;
                            /*
                            $this->db->rollback();
                            $json_data = array(
                                "data"            => array(),   // total data array
                                "respuesta"       => 'error',
                                "titulo"          => 'Error',
                                "mensaje"         => 'La fecha de vencimiento para el producto no es válida!'
                            );
                            echo json_encode($json_data);
                            exit();
                            */
                        }
    
                        $producto->fecha_vencimiento = $fecha_vencimiento;

                        //guardar producto
                        $resp_save = $this->guardar_producto_for_updaterow($producto);
                        if($resp_save['respuesta'] == 'error') {
                            $this->db->rollback();
                            echo json_encode($resp_save);
                            exit();
                        }

                    } else if($nombre_columna == 'marca') {
                        if(empty(trim($valor))) {
                            $this->db->rollback();
                            $json_data = array(
                                "data"            => array(),   // total data array
                                "respuesta"       => 'error',
                                "titulo"          => 'Error',
                                "mensaje"         => 'Debes Ingresar un Nombre para el Producto!'
                            );
                            echo json_encode($json_data);
                            exit();
                        }

                        $producto->marca = $valor;

                        //guardar producto
                        $resp_save = $this->guardar_producto_for_updaterow($producto);
                        if($resp_save['respuesta'] == 'error') {
                            $this->db->rollback();
                            echo json_encode($resp_save);
                            exit();
                        }
                    } else {

                    }
                }
            }

            $this->db->commit();
            $array_lista[] = array();

            $json_data = array(
                "data"            => $array_lista,   // total data array
                "respuesta"       => 'ok',
                "mensaje"         => 'Se ha actualizado correctamente los campos'
            );
    
            echo json_encode($json_data);
            exit();
        }
    }

    public function guardar_producto_for_updaterow($producto) {

        try {
            if(!$producto->save()) {
                $msg = '';
                foreach ($producto->getMessages() as $message) {
                    $msg = $msg.$message."</br>\n";
                }

                $json_data = array(
                    "data"            => array(),   // total data array
                    "respuesta"       => 'error',
                    "titulo"          => 'Error',
                    "mensaje"         => $msg
                );

                return $json_data;
            }
        } catch (Exception $e) {
            $this->saveLogger($e);
            $json_data = array(
                "data"            => array(),   // total data array
                "respuesta"       => 'error',
                "titulo"          => 'Error',
                "mensaje"         => $e->getMessage()
            );

            return $json_data;
        }

        $json_data = array(
            "data"            => array(),   // total data array
            "respuesta"       => 'ok',
            "titulo"          => 'Actualización Completa',
            "mensaje"         => 'El Campo se ha Actualizado Correctamente!'
        );
        
        return $json_data;
    }

    public function getListaCategoriasAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            //Datos de formulario
            $datapost = $this->request->getPost();
            $idproducto = !isset($datapost['idproducto'])?0:intval($datapost['idproducto']) + 0;
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

            $categorias = Categoria::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            $resp['respuesta'] = 'ok';
            $resp['lista'] = $categorias;
            echo json_encode($resp);
            exit();
        }
    }

    public function eliminarProductoAction(){
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            //Datos de formulario
            $datapost = $this->request->getPost();
            $idproducto = !isset($datapost['idproducto'])?0:intval($datapost['idproducto']) + 0;
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
            /*
            $categoria = Categoria::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            if(!$categoria) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Lo sentimos!';
                $resp['mensaje'] = 'No puedes eliminar el producto de una categoría que no te pertenece!';
                echo json_encode($resp);
                exit();
            }
            */

            $producto = Producto::findFirst(array("idproducto = :idproducto: and id_contribuyente = :id_contribuyente:", 'bind' => array('idproducto' => $idproducto, 'id_contribuyente' => $usuario->id_contribuyente)));
            if(!$producto) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje']='Lo sentimos! El Producto seleccionado no existe!';
                echo json_encode($resp);
                exit();
            }

            if($usuario->id_rol != 1 && $usuario->id_rol != 2 && $usuario->id_rol != 3 && $usuario->id_rol != 5) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje']='Comunícate con un Administrador para Eliminar Productos!';
                echo json_encode($resp);
                exit();
            }
            
            $producto->estado = 'inactivo';
            
            try {
                if(!$producto->save()) {
                    $msg = '';
                    foreach ($producto->getMessages() as $message) {
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
            $resp['mensaje'] = 'Se desactivó correctamente el producto con id = '.$producto->idproducto;
            $resp['idproducto'] = $producto->idproducto;
            echo json_encode($resp);
            exit();
            
        }
    }

    public function listaproductosAction() {
		$this->setTitle('Lista de Productos');
        $this->view->setTemplateAfter('template_new'); 
        
        $this->assets
        ->addCss($this->baseUri . "public/extras/editor193/css/editor.jqueryui.css")
        ->addCss($this->baseUri . "public/extras/flexdatalist/jquery.flexdatalist.min.css")
        ->addCss($this->baseUri . "public/css/new_style.css");
        
        $this->assets
            ->addJs("https://unpkg.com/default-passive-events", false)
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/switchery.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/uniform.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/selects/select2.min.js")

            ->addJs($this->baseUri . "public/template/assets/js/plugins/uploaders/fileinput/fileinput.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/media/cropper.min.js?i=v2")

            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/datatables.min.js")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js?i=v3")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/buttons.min.js?i=v3")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/demo_pages/datatables_extension_colvis.js")
            ->addJs($this->baseUri . "public/extras/jqgrid/js/i18n/grid.locale-es.js?i=v2")
			->addJs($this->baseUri . "public/extras/jqgrid/js/jquery.jqGrid.min.js?i=v2")

            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/ui/moment/moment.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/pickers/daterangepicker.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/switch.min.js?i=v2")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/notifications/bootbox.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/notifications/sweet_alert.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/selects/select2.min.js")
            ->addJs($this->baseUri . "public/extras/editor193/js/dataTables.editor.min.js?i=v2")
            ->addJs($this->baseUri . "public/extras/flexdatalist/jquery.flexdatalist.js?i=v2")

            ->addJs($this->baseUri . "public/extras/editinplace/src/eip.js")

            ->addJs($this->baseUri . "public/template_new/theme_1/js/app.js")
            ->addJs($this->baseUri . "public/js/subir_imagen_producto.js?i=".rand())
            ->addJs($this->baseUri . "public/js/general.js?j=".rand()) 
            ->addJs($this->baseUri . "public/js/apisunat.js?i=v3")
            //->addJs($this->baseUri . "public/js/producto.js?i=".rand())
            ->addJs($this->baseUri . "public/js/producto/registrar_imagenes.js?i=".rand())
            ->addJs($this->baseUri . "public/js/producto/presentacion_producto.js?i=".rand())
            ->addJs($this->baseUri . "public/js/producto/movimientos_reportes.js?i=".rand())
            ->addJs($this->baseUri . "public/js/listaproductos.js?i=".rand())
            ->addJs($this->baseUri . "public/js/producto/transformacion_productos.js?i=".rand())
            ->addJs($this->baseUri . "public/js/producto/modal_ver_stock_varias_sucursales.js?i=".rand())
            ;

        $this->assets
			->addCss($this->baseUri . "public/extras/jqgrid/css/ui.jqgrid.css")
			->addCss($this->baseUri . "public/extras/jqgrid/css/custom.css");
        
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
            $resp['mensaje'] = 'No se encuentra la empresa al que pertenece el usuario';
            echo json_encode($resp);
            exit();
        }

        $sucursales = Sucursal::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
        
        $opciones_select = '';
        $opciones_select_2 = '';
        if($contribuyente->multi_almacen == 'si' || count($sucursales) == 1) {
            $opciones_select = '<option value="0">Mostrar Productos/Servicios en Todos los Almacenes</option>';
            foreach($sucursales as $sucursal) {
                if($sucursal->idsucursal == $usuario->idsucursal) {
                    $opciones_select = $opciones_select.'<option selected value="'.$sucursal->idsucursal.'">'.$sucursal->nombre.' (ID: '.$sucursal->idsucursal.')</option>';
                    $opciones_select_2 = $opciones_select_2.'<option selected value="'.$sucursal->idsucursal.'">'.$sucursal->nombre.' (ID: '.$sucursal->idsucursal.')</option>';
                } else {
                    $opciones_select = $opciones_select.'<option value="'.$sucursal->idsucursal.'">'.$sucursal->nombre.' (ID: '.$sucursal->idsucursal.')</option>';
                    $opciones_select_2 = $opciones_select_2.'<option value="'.$sucursal->idsucursal.'">'.$sucursal->nombre.' (ID: '.$sucursal->idsucursal.')</option>';
                }
            }
        } else {
            $opciones_select = '<option selected value="0">Mostrar Productos/Servicios en Todos los Almacenes</option>';
            foreach($sucursales as $sucursal) {
                $opciones_select = $opciones_select.'<option value="'.$sucursal->idsucursal.'">'.$sucursal->nombre.' (ID: '.$sucursal->idsucursal.')</option>';
                $opciones_select_2 = $opciones_select_2.'<option value="'.$sucursal->idsucursal.'">'.$sucursal->nombre.' (ID: '.$sucursal->idsucursal.')</option>';
            }
        }

        $this->view->sucursales = $sucursales;
        $this->view->opciones_select = $opciones_select;
        $this->view->opciones_select_2 = $opciones_select_2;
        $this->view->numero_decimales = $contribuyente->num_decimales;
        $this->view->precio_venta_minimo = $contribuyente->precio_venta_minimo;
        $this->view->bienes_detracciones = SunatCodigodetraccion::find();
        $this->view->contribuyente = $contribuyente;
    }



    public function registrarIngresoProductoAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
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
                $resp['mensaje'] = 'No se encuentra la empresa al que pertenece el usuario';
                echo json_encode($resp);
                exit();
			}
            
            //Datos de formulario
            $datapost = $this->request->getPost();
            $idproducto = !isset($datapost['idproducto'])?0:intval($datapost['idproducto']) + 0;
            $cantidad = !isset($datapost['cantidad'])?0:floatval($datapost['cantidad']) + 0;
            $nota = !isset($datapost['nota'])?'':$datapost['nota'];
            $idsucursal = !isset($datapost['select_sucursal'])?'':$datapost['select_sucursal'];
            $costo_unitario = !isset($datapost['costo_unitario'])?0:floatval($datapost['costo_unitario']) + 0;

            $producto = Producto::findFirst(array("id_contribuyente = :id_contribuyente: and idproducto = :idproducto: and estado = 'activo'", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'idproducto' => $idproducto)));

            if(!$producto) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El Producto que intenta modificar no existe!!';
                echo json_encode($resp);
                exit();
            }

            $gestion_usuarios = new GestionuserController;
            if($usuario->id_rol != 1 && $usuario->id_rol != 2 && $usuario->id_rol != 3 && $usuario->id_rol != 5) {
                if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_productos') != 'acceso_total') {
                    if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_productos') != 'todas_las_sucursales') {
                        if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_productos') == 'prohibir') {
                            $resp['respuesta'] = 'error';
                            $resp['titulo'] = 'Error';
                            $resp['mensaje'] = 'Se ha restringido la Edición y/o Actualización de Productos en su Cuenta!';
                            echo json_encode($resp);
                            exit();
                        }

                        if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_productos') == 'sucursal_asignada') {
                            if($usuario->idsucursal != $producto->idsucursal) {
                                $resp['respuesta'] = 'error';
                                $resp['titulo'] = 'Error';
                                $resp['mensaje'] = 'No Tiene Permisos para Registrar y/o Actualizar Productos en la sucursal con ID: '.$producto->idsucursal;
                                echo json_encode($resp);
                                exit();
                            }
                        }
                    }
                }
            }

            $dominio_principal = $this->get_parametros_iniciales()['url_domain'];
            $herramientas = new HerramientasController;

            if($cantidad <= 0) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'La cantidad a ingresar debe ser mayor a Cero!';
                echo json_encode($resp);
                exit();
            }

            $unidad_medida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $producto->id_unidad_medida)));
            if(!$unidad_medida){
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos!, esa medida no existe. Por favor selecciona una Unidad de Medida Válida';
                echo json_encode($resp);
                exit();
            }

            if($costo_unitario <= 0) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes ingresar el precio de compra para la mercadería que estás ingresando!';
                echo json_encode($resp);
                exit();
            }

            $sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $idsucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
            if(!$sucursal) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes Seleccionar el Almacén Donde Será Registrado el Producto!';
                echo json_encode($resp);
                exit();
            }

            $confirmado = empty($datapost['confirmado'])?'':$datapost['confirmado'];
			if($confirmado != 'si') {
				$resp['respuesta'] = 'ok';
				$resp['mensaje'] = '¿Realmente desea aumentar en '.$cantidad.', al stock actual?';
				echo json_encode($resp);
				exit();
            }
            
            $this->db->begin();

            if($producto->precio_compra != $costo_unitario) {
                if($costo_unitario > 0) {
                    $producto->precio_compra = $costo_unitario;
                }
            }
            
            $fecha_registro = date('Y-m-d H:i:s');
            $html_pdf = '';

            if($unidad_medida->codigo != 'ZZ') {
                if($cantidad > 0) {
                    $movimiento_almacen = new ProductomovimientosController;
                    $data['id_contribuyente']           = $producto->id_contribuyente;
                    $data['id_sucursal']                = $producto->idsucursal;
                    $data['id_usuario']                 = $usuario->idusuario;
                    $data['tipo_envio_sunat']           = $contribuyente->tipo_envio_sunat;
                    $data['id_tipo_movimiento']         = 'in'; //ingreso
                    $data['nota']                       = $nota;
                    $data['destino_id_sucursal']        = '';
                    $data['destino_id_contribuyente']   = '';

                    $data['detalle_movimiento'][] = array(
                        'cantidad'          => $cantidad,
                        'costo_unitario'    => round($costo_unitario, 2),
                        'tipo_kardex'       => 'ingreso_sindoc',
                        'id_codigomoneda'   => $producto->id_cod_moneda,

                        'o_id_u_medida'     => $producto->id_unidad_medida,
                        'o_u_medida'        => $unidad_medida->codigo,
                        'o_precio'          => $producto->valor_con_igv,
                        'o_id_afectigv'     => $producto->id_tipoafectacionigv,
                        'o_tipo_unidad'     => 'UND',
                        'o_id_presentacion' => '',
                        'o_cod_prod'        => $producto->codigo,
                        'o_id_prod'         => $producto->idproducto,
                        'o_nom_prod'        => $producto->nombre,

                        'd_id_u_medida'     => null,
                        'd_u_medida'        => null,
                        'd_precio'          => null,
                        'd_id_afectigv'     => null,
                        'd_tipo_unidad'     => null,
                        'd_id_presentacion' => null,
                        'd_cod_prod'        => null,
                        'd_id_prod'         => null,
                        'd_nom_prod'        => null
                    );

                    $resp_reg_movimiento = $movimiento_almacen->registrar_movimiento($usuario, $data);
                    if($resp_reg_movimiento['respuesta'] == 'error') {
                        $this->db->rollback();
                        echo json_encode($resp_reg_movimiento);
                        exit();
                    }

                    $existe_movimiento = isset($resp_reg_movimiento['producto_movimiento'])?true:false;

                    if($existe_movimiento) {
                        $producto_movimiento = $resp_reg_movimiento['producto_movimiento'];

                        $cadena_pdf_a4 = "$contribuyente->id_contribuyente||$producto_movimiento->id_tipo_movimiento||$producto_movimiento->serie||$producto_movimiento->correlativo||$producto_movimiento->tipo_envio_sunat||a4";
                        $cadena_pdf_ticket = "$contribuyente->id_contribuyente||$producto_movimiento->id_tipo_movimiento||$producto_movimiento->serie||$producto_movimiento->correlativo||$producto_movimiento->tipo_envio_sunat||ticket";

                        $cadena_pdf_a4_encriptada = $herramientas->encriptar($cadena_pdf_a4);
                        $cadena_pdf_ticket_encriptada = $herramientas->encriptar($cadena_pdf_ticket);

                        $url_a4 = 'https://'.$dominio_principal.'/facturacionv8/productomovimientos/print_pdf/?file='.urlencode($cadena_pdf_a4_encriptada);
                        $url_ticket = 'https://'.$dominio_principal.'/facturacionv8/productomovimientos/print_pdf/?file='.urlencode($cadena_pdf_ticket_encriptada);

                        $html_pdf = '<br /><br />'.'<a target="_blank" href="'.$url_a4.'"><img title="Formato A4" src="/facturacionv8/img/svg/pdf_cpe.svg" style="width: 30px; cursor: pointer;"></a>
                        <a target="_blank" href="'.$url_ticket.'"><img title="Formato Ticket" src="/facturacionv8/img/svg/ticket_cpe.svg" style="width: 30px; cursor: pointer;"></a>';
                    }
                    
                    $resp_kardex = $resp_reg_movimiento['resp_kardex'];
                    $producto->costo_promedio = $resp_kardex['costo_unitario_promedio'];
                    $producto->stock = $producto->stock + $cantidad;
                }
            }
            
            try {
                if(!$producto->save()) {
                    $this->db->rollback();
                    $msg = '';
                    foreach ($producto->getMessages() as $message) {
                        $msg = $msg.$message."</br>\n";
                    }
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error en BD';
                    $resp['mensaje'] = 'No hemos logrado actualizar el Stock';
                    echo json_encode($resp);
                    exit();
                }
            } catch (Exception $e) {
                $this->saveLogger($e);
                $this->db->rollback();
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en BD';
                $resp['mensaje'] = 'No hemos logrado actualizar el Stock '.$e->getMessage();
                echo json_encode($resp);
                exit();
            }

            $this->db->commit();
            $resp['respuesta'] = 'ok';
            $resp['titulo'] = '¡Excelente!';
            $resp['mensaje'] = 'El ingreso de productos se ha registrado de forma correcta!'.$html_pdf;
            $resp['producto'] = $producto;
            $resp['html_pdf'] = $html_pdf;
            echo json_encode($resp);
            exit();
        }
    }

    public function registrarSalidaProductoAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
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
                $resp['mensaje'] = 'No se encuentra la empresa al que pertenece el usuario';
                echo json_encode($resp);
                exit();
			}
            
            //Datos de formulario
            $datapost = $this->request->getPost();
            $idproducto = !isset($datapost['idproducto'])?0:intval($datapost['idproducto']) + 0;
            $cantidad = !isset($datapost['cantidad'])?0:floatval($datapost['cantidad']) + 0;
            $nota = !isset($datapost['nota'])?'':$datapost['nota'];
            $idsucursal = !isset($datapost['select_sucursal'])?'':$datapost['select_sucursal'];

            $producto = Producto::findFirst(array("id_contribuyente = :id_contribuyente: and idproducto = :idproducto: and estado = 'activo'", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'idproducto' => $idproducto)));

            if(!$producto) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El Producto que intenta modificar no existe!!';
                echo json_encode($resp);
                exit();
            }

            $gestion_usuarios = new GestionuserController;
            if($usuario->id_rol != 1 && $usuario->id_rol != 2 && $usuario->id_rol != 3 && $usuario->id_rol != 5) {
                if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_productos') != 'acceso_total') {
                    if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_productos') != 'todas_las_sucursales') {
                        if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_productos') == 'prohibir') {
                            $resp['respuesta'] = 'error';
                            $resp['titulo'] = 'Error';
                            $resp['mensaje'] = 'Se ha restringido la Edición y/o Actualización de Productos en su Cuenta!';
                            echo json_encode($resp);
                            exit();
                        }

                        if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_productos') == 'sucursal_asignada') {
                            if($usuario->idsucursal != $producto->idsucursal) {
                                $resp['respuesta'] = 'error';
                                $resp['titulo'] = 'Error';
                                $resp['mensaje'] = 'No Tiene Permisos para Registrar y/o Actualizar Productos en la sucursal con ID: '.$producto->idsucursal;
                                echo json_encode($resp);
                                exit();
                            }
                        }
                    }
                }
            }

            $dominio_principal = $this->get_parametros_iniciales()['url_domain'];
            $herramientas = new HerramientasController;

            if($cantidad <= 0) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'La cantidad a ingresar debe ser mayor a Cero!';
                echo json_encode($resp);
                exit();
            }

            $unidad_medida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $producto->id_unidad_medida)));
            if(!$unidad_medida){
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos!, esa medida no existe. Por favor selecciona una Unidad de Medida Válida';
                echo json_encode($resp);
                exit();
            }

            if($producto->stock - $cantidad < 0) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Usted intenta registrar una salida con una cantidad mayor a la que tiene actualmente en almacén, la resta no debe ser menor a cero';
                echo json_encode($resp);
                exit();
            }

            $sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $idsucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
            if(!$sucursal) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes Seleccionar el Almacén Donde Será Registrado el Producto!';
                echo json_encode($resp);
                exit();
            }

            $confirmado = empty($datapost['confirmado'])?'':$datapost['confirmado'];
			if($confirmado != 'si') {
				$resp['respuesta'] = 'ok';
				$resp['mensaje'] = '¿Realmente desea Disminuir en '.$cantidad.', al stock actual?';
				echo json_encode($resp);
				exit();
            }

            $tipoafectacionigv = SunatTipoafectacionigv::findFirst(array("id_tipoafectacionigv = :id_tipoafectacionigv:", 'bind' => array('id_tipoafectacionigv' => $producto->id_tipoafectacionigv)));
			if(!$tipoafectacionigv) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'El tipo de Afectación del IGV selecionado no es válido para el producto: '.$producto->nombre;
				return $resp;
			}
            
            $this->db->begin();

            $fecha_registro = date('Y-m-d H:i:s');

            $cliente = Cliente::findFirst(array("id_tipodocidentidad = :id_tipodocidentidad: and num_doc = :num_doc: and id_contribuyente = :id_contribuyente:", 'bind' => array('id_tipodocidentidad' => '6', 'num_doc' => $contribuyente->ruc, 'id_contribuyente' => $contribuyente->id_contribuyente)));
            if(!$cliente) {
                $cliente = new Cliente();
                $cliente->codigo = uniqid('CLIE_',true);
                $cliente->id_contribuyente = $usuario->id_contribuyente;
                $cliente->fecha_registro = date('Y-m-d H:i:s');
                $cliente->estado = 'activo';
                $cliente->id_tipodocidentidad = '6';
                $cliente->num_doc = $contribuyente->ruc;
                $cliente->email = $contribuyente->email;
                $cliente->razon_social = $contribuyente->razon_social;
                $cliente->direccion_fiscal = $contribuyente->direccion_fiscal;
                $cliente->id_cod_ubigeo = $contribuyente->codigo_ubigeo;

                try {
                    if(!$cliente->save()) {
                        $this->db->rollback();
                        $msg = '';
                        foreach ($cliente->getMessages() as $message) {
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
                    $this->db->rollback();
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'No hemos logrado registrar el Cliente '.$e->getMessage();
                    echo json_encode($resp);
                    exit();
                }
            }

            $html_pdf = '';
            
            $movimiento_almacen = new ProductomovimientosController;
            $data['id_contribuyente']           = $producto->id_contribuyente;
            $data['id_sucursal']                = $producto->idsucursal;
            $data['id_usuario']                 = $usuario->idusuario;
            $data['tipo_envio_sunat']           = $contribuyente->tipo_envio_sunat;
            $data['id_tipo_movimiento']         = 'sa'; //salida
            $data['nota']                       = $nota;
            $data['destino_id_sucursal']        = '';
            $data['destino_id_contribuyente']   = '';

            $data['detalle_movimiento'][] = array(
                'cantidad'          => $cantidad,
                'costo_unitario'    => round($producto->precio_compra, 2),
                'tipo_kardex'       => 'salida_sindoc',
                'id_codigomoneda'   => $producto->id_cod_moneda,

                'o_id_u_medida'     => $producto->id_unidad_medida,
                'o_u_medida'        => $unidad_medida->codigo,
                'o_precio'          => $producto->valor_con_igv,
                'o_id_afectigv'     => $producto->id_tipoafectacionigv,
                'o_tipo_unidad'     => 'UND',
                'o_id_presentacion' => '',
                'o_cod_prod'        => $producto->codigo,
                'o_id_prod'         => $producto->idproducto,
                'o_nom_prod'        => $producto->nombre,

                'd_id_u_medida'     => null,
                'd_u_medida'        => null,
                'd_precio'          => null,
                'd_id_afectigv'     => null,
                'd_tipo_unidad'     => null,
                'd_id_presentacion' => null,
                'd_cod_prod'        => null,
                'd_id_prod'         => null,
                'd_nom_prod'        => null
            );

            $resp_reg_movimiento = $movimiento_almacen->registrar_movimiento($usuario, $data);
            if($resp_reg_movimiento['respuesta'] == 'error') {
                $this->db->rollback();
                echo json_encode($resp_reg_movimiento);
                exit();
            }

            $existe_movimiento = isset($resp_reg_movimiento['producto_movimiento'])?true:false;

            if($existe_movimiento) {
                $producto_movimiento = $resp_reg_movimiento['producto_movimiento'];
                $cadena_pdf_a4 = "$contribuyente->id_contribuyente||$producto_movimiento->id_tipo_movimiento||$producto_movimiento->serie||$producto_movimiento->correlativo||$producto_movimiento->tipo_envio_sunat||a4";
                $cadena_pdf_ticket = "$contribuyente->id_contribuyente||$producto_movimiento->id_tipo_movimiento||$producto_movimiento->serie||$producto_movimiento->correlativo||$producto_movimiento->tipo_envio_sunat||ticket";
    
                $cadena_pdf_a4_encriptada = $herramientas->encriptar($cadena_pdf_a4);
                $cadena_pdf_ticket_encriptada = $herramientas->encriptar($cadena_pdf_ticket);
    
                $url_a4 = 'https://'.$dominio_principal.'/facturacionv8/productomovimientos/print_pdf/?file='.urlencode($cadena_pdf_a4_encriptada);
                $url_ticket = 'https://'.$dominio_principal.'/facturacionv8/productomovimientos/print_pdf/?file='.urlencode($cadena_pdf_ticket_encriptada);
    
                $html_pdf = '<br /><br />'.'<a target="_blank" href="'.$url_a4.'"><img title="Formato A4" src="/facturacionv8/img/svg/pdf_cpe.svg" style="width: 30px; cursor: pointer;"></a>
                <a target="_blank" href="'.$url_ticket.'"><img title="Formato Ticket" src="/facturacionv8/img/svg/ticket_cpe.svg" style="width: 30px; cursor: pointer;"></a>';
            }
                                    
            $resp_kardex = $resp_reg_movimiento['resp_kardex'];
            $producto->costo_promedio = $resp_kardex['costo_unitario_promedio'];
            $producto->stock = $producto->stock - $cantidad;

            try {
                if(!$producto->save()) {
                    $this->db->rollback();
                    $msg = '';
                    foreach ($producto->getMessages() as $message) {
                        $msg = $msg.$message."</br>\n";
                    }
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error en BD';
                    $resp['mensaje'] = 'No hemos logrado actualizar el Stock';
                    echo json_encode($resp);
                    exit();
                }
            } catch (Exception $e) {
                $this->saveLogger($e);
                $this->db->rollback();
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en BD';
                $resp['mensaje'] = 'No hemos logrado actualizar el Stock '.$e->getMessage();
                echo json_encode($resp);
                exit();
            }
            
            $this->db->commit();
            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Registro Correcto';
            $resp['mensaje'] = 'La Salida del Producto se ha Registrado Correctamente!'.$html_pdf;
            $resp['html_pdf'] = $html_pdf;
            echo json_encode($resp);
            exit();
        }
    }

    public function exportToExcelAction($idsucursal = 0) {
        
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=lista_productos.xls");
        ini_set('memory_limit','2048M');
        
        $this->view->disable();
        $auth = $this->session->get('authv8');
        $idusuario = $auth['idusuario'];
        $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
        if(!$usuario) {
            echo "Debes Iniciar Sesión";
            exit();
        }

        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
        if(!$contribuyente) {
            echo "El Contribuyente no Existe!";
            exit();
        }
        
        $query_sucursal = '';
        if(intval($idsucursal) > 0) {
            $sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $idsucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
            if(!$sucursal) {
                echo "La sucursal no existe!";
                exit();
            }

            $query_sucursal = " and producto.idsucursal = $idsucursal ";
        }
        
        $query = "SELECT producto.idproducto, producto.peso, producto.idsucursal, producto.costo_promedio, producto.codigo, producto.id_unidad_medida, producto.id_tipoafectacionigv, producto.id_categoria, categoria.codigo as codigo_categoria, producto.nombre, producto.id_cod_moneda, producto.valor_con_igv precio_inc_igv, producto.valor_sin_igv precio_sin_igv, producto.precio_venta_minimo, producto.nota, producto.stock, producto.stock_minimo, producto.afecto_icbper, producto.precio_compra, producto.idsucursal, producto.fecha_vencimiento FROM `producto` LEFT JOIN categoria on producto.id_categoria = categoria.idcategoria where producto.id_contribuyente = :id_contribuyente and producto.estado = 'activo' ".$query_sucursal." ORDER BY `producto`.`id_categoria` DESC";

        try {
            $sentencia = $this->db->prepare($query);
            $sentencia->bindParam(':id_contribuyente', $usuario->id_contribuyente, PDO::PARAM_INT);
            $sentencia->execute();
        } catch (Exception $e) {
            $this->saveLogger($e);
            echo "Error en la consulta: (exportToExcelAction) ".$e->getMessage();
            exit();
        }

        $html = '
        <meta charset="utf-8">
        <table style="width: 100%" border="1">
            <tr>
                <th>CODIGO CATEGORIA</th>
                <th>CODIGO PRODUCTO</th>
                <th>NOMBRE PRODUCTO</th>
                <th>CODIGO - TIPO DE IGV</th>
                <th>CODIGO - UNIDAD MEDIDA</th>
                <th>MONEDA</th>
                <th>PRECIO CON IGV</th>
                <th>PRECIO SIN IGV</th>
                <th>STOCK ACTUAL</th>
                <th>STOCK MINIMO</th>
                <th>DETALLE - MEMO</th>
                <th>PRECIO COMPRA</th>
                <th>ID ALMACEN/SUCURSAL</th>

                <th>COSTO PROMEDIO</th>
                <th>STOCK VALORIZADO</th>

                <th>MULTIPRECIO</th>
                <th>NOM.PREC.1</th>
                <th>VALOR.PREC.1</th>
                <th>NOM.PREC.2</th>
                <th>VALOR.PREC.2</th>
                <th>NOM.PREC.3</th>
                <th>VALOR.PREC.3</th>

                <th>FECHA VENCIMIENTO</th>
                <th>PESO</th>
            </tr>';

        $total_precio_inc_igv = 0;
        $total_precio_sin_igv = 0;
        $total_stock = 0;
        $total_stock_minimo = 0;
        $total_precio_compra = 0;
        $total_costo_promedio = 0;
        $total_stock_valorizado = 0;
        $total_precio1 = 0;
        $total_precio2 = 0;
        $total_precio3 = 0;
        
        while($fila = $sentencia->fetch()) {
            $item = (object)$fila;
            
            $moneda_simbolo = 'S/.';
            if($item->id_cod_moneda == 'PEN') {
                $moneda_simbolo = 'S/.';
            } else {
                $moneda_simbolo = '$';
            }


            $lista_precios = ProductoListaprecio::find(array("idproducto = :idproducto: and estado = 'activo'", 'bind' => array('idproducto' => $item->idproducto)));
            
            $lista_precios_array = array();
            $tiene_multiprecio = 'no';
            
            $nombre_precio1 = '';
            $monto_precio1 = 0;
            
            $nombre_precio2 = '';
            $monto_precio2 = 0;
            
            $nombre_precio3 = '';
            $monto_precio3 = 0;

            $n = 0;
            if(count($lista_precios) > 0) {
                $tiene_multiprecio = 'si';
                foreach($lista_precios as $item_precio) {
                    $n++;

                    if($n == 1) {
                        $nombre_precio1 = $item_precio->nombre;
                        $monto_multiprecio = floatval($item_precio->precio) + 0;
                        $monto_precio1 = $monto_multiprecio;
                    }

                    if($n == 2) {
                        $nombre_precio2 = $item_precio->nombre;
                        $monto_multiprecio = floatval($item_precio->precio) + 0;
                        $monto_precio2 = $monto_multiprecio;
                    }

                    if($n == 3) {
                        $nombre_precio3 = $item_precio->nombre;
                        $monto_multiprecio = floatval($item_precio->precio) + 0;
                        $monto_precio3 = $monto_multiprecio;
                    }
                }
            }

            if(empty($item->costo_promedio)) {
                $costo_promedio = $this->get_costo_promedio($usuario->id_contribuyente, $item->idsucursal, $item->idproducto);
            } else {
                $costo_promedio = $item->costo_promedio;
            }

            $stock_valorizado = round($costo_promedio*$item->stock, 2);
            
            $fecha_vencimiento = '';
            if(!empty($item->fecha_vencimiento)) {
                $fecha_vencimiento = date("d-m-Y", strtotime($item->fecha_vencimiento));
            }
            
            $row = "
            <tr>
                <td>$item->codigo_categoria</td>
                <td>$item->codigo</td>
                <td>$item->nombre</td>
                <td>$item->id_tipoafectacionigv</td>
                <td>$item->id_unidad_medida</td>
                <td>$item->id_cod_moneda</td>
                <td>$item->precio_inc_igv</td>
                <td>$item->precio_sin_igv</td>
                <td>$item->stock</td>
                <td>$item->stock_minimo</td>
                <td>$item->nota</td>
                <td>$item->precio_compra</td>
                <td>$item->idsucursal</td>

                <td>$costo_promedio</td>
                <td>$stock_valorizado</td>

                <td>$tiene_multiprecio</td>
                <td>$nombre_precio1</td>
                <td>$monto_precio1</td>
                <td>$nombre_precio2</td>
                <td>$monto_precio2</td>
                <td>$nombre_precio3</td>
                <td>$monto_precio3</td>

                <td>$fecha_vencimiento</td>
                <td>$item->peso</td>
            </tr>";

            $html = $html.$row;

            $total_precio_inc_igv += $item->precio_inc_igv;
            $total_precio_sin_igv += $item->precio_sin_igv;
            $total_stock += $item->stock;
            $total_stock_minimo += $item->stock_minimo;
            $total_precio_compra += $item->precio_compra;
            $total_costo_promedio += $costo_promedio;
            $total_stock_valorizado += $stock_valorizado;
            $total_precio1 += $monto_precio1;
            $total_precio2 += $monto_precio2;
            $total_precio3 += $monto_precio3;
        }

        //totales
        $row_totales = "
            <tr style='background: #FFD966;'>
                <td colspan='6'><b>TOTALES</b></td>
                <td><b>$total_precio_inc_igv</b></td>
                <td><b>$total_precio_sin_igv</b></td>
                <td><b>$total_stock</b></td>
                <td><b>$total_stock_minimo</b></td>
                <td></td>
                <td><b>$total_precio_compra</b></td>
                <td></td>

                <td><b>$total_costo_promedio</b></td>
                <td><b>$total_stock_valorizado</b></td>

                <td></td>
                <td></td>
                <td><b>$total_precio1</b></td>
                <td></td>
                <td><b>$total_precio2</b></td>
                <td></td>
                <td><b>$total_precio3</b></td>
                <td></td>
            </tr>
        ";

        $html = $html.$row_totales;
        $html = $html.'</table>';

        echo $html;
        exit();
    }

    public function descargarPlantillaImportacionAction() {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0)->setTitle("Lista Productos");
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'CODIGO CATEGORIA')
            ->setCellValue('B1', 'CODIGO PRODUCTO')
            ->setCellValue('C1', 'NOMBRE PRODUCTO')
            ->setCellValue('D1', 'CODIGO - TIPO DE IGV')
            ->setCellValue('E1', 'CODIGO - UNIDAD MEDIDA')
            ->setCellValue('F1', 'MONEDA')
            ->setCellValue('G1', 'PRECIO VENTA')
            ->setCellValue('H1', 'STOCK ACTUAL')
            ->setCellValue('I1', 'STOCK MINIMO')
            ->setCellValue('J1', 'DETALLE - MEMO')
            ->setCellValue('K1', 'PRECIO COMPRA')
            ->setCellValue('L1', 'ID ALMACEN/SUCURSAL')
            ->setCellValue('M1', 'FECHA_VENCIMIENTO - DIA-MES-AÑO')
            ->setCellValue('N1', 'MARCA')
            ->setCellValue('O1', 'MULTIPRECIO')
            ->setCellValue('P1', 'NOMBRE PRECIO 1')
            ->setCellValue('Q1', 'VALOR PRECIO 1')
            ->setCellValue('R1', 'NOMBRE PRECIO 2')
            ->setCellValue('S1', 'VALOR PRECIO 2')
            ->setCellValue('T1', 'NOMBRE PRECIO 3')
            ->setCellValue('U1', 'VALOR PRECIO 3')
            ->setCellValue('V1', 'NOMBRE PRECIO 4')
            ->setCellValue('W1', 'NOMBRE PRECIO 4')
            ->setCellValue('X1', 'PESO EN kGM')
            ;

        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('D')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('E')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('F')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('G')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('H')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('I')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('J')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('K')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('L')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('M')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('N')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('O')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('P')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('Q')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('R')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('S')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('T')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('U')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('V')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('W')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('X')->setAutoSize(true);

        $spreadsheet->getActiveSheet()->getStyle('A1:X1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('01468D');
        $spreadsheet->getActiveSheet()->getStyle('A1:X1')->getFont()->getColor()->setARGB('ffffff');
        $spreadsheet->getActiveSheet()->getStyle('A1:X1')->getFont()->setBold(true);

        $this->agregar_lista_unidades($spreadsheet, 1, 'Lista Unidades');
        $this->agregar_lista_afectaciones($spreadsheet, 2, 'Lista Afectaciones');

        $spreadsheet->setActiveSheetIndex(0);

        $nombre_archivo = 'Plantilla de Importación';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nombre_archivo.'.xlsx"');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit();
    }

    public function exportToUpdateAction($idsucursal = 0) {
        /*
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=lista_productos.xls");
        ini_set('memory_limit','2048M');
        */
        
        $this->view->disable();
        $auth = $this->session->get('authv8');
        $idusuario = $auth['idusuario'];
        $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
        if(!$usuario) {
            echo "Debes Iniciar Sesión";
            exit();
        }

        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
        if(!$contribuyente) {
            echo "El Contribuyente no Existe!";
            exit();
        }

        $idsucursal = !isset($idsucursal)?0:intval($idsucursal) + 0;
        $sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $idsucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
        if(!$sucursal) {
            echo "La Sucursal No Existe!";
            exit();
        }
        
        $query = "SELECT producto.idproducto, producto.codigo, producto.marca, producto.id_unidad_medida, producto.estado, producto.id_tipoafectacionigv, producto.id_categoria, categoria.codigo as codigo_categoria, producto.nombre, producto.id_cod_moneda, producto.valor_con_igv precio_inc_igv, producto.valor_sin_igv precio_sin_igv, producto.precio_venta_minimo, producto.nota, producto.stock, producto.stock_minimo, producto.afecto_icbper, producto.precio_compra, producto.idsucursal FROM `producto` LEFT JOIN categoria on producto.id_categoria = categoria.idcategoria where producto.id_contribuyente = :id_contribuyente and producto.estado = 'activo' and producto.idsucursal = :idsucursal ORDER BY `producto`.`id_categoria` DESC";
        
        try {
            $sentencia = $this->db->prepare($query);
            $sentencia->bindParam(':id_contribuyente', $usuario->id_contribuyente, PDO::PARAM_INT);
            $sentencia->bindParam(':idsucursal', $idsucursal, PDO::PARAM_INT);
            $sentencia->execute();
        } catch (Exception $e) {
            $this->saveLogger($e);
            echo "Error en la consulta: (exportToUpdateAction) ".$e->getMessage();
            exit();
        }

        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0)->setTitle("Lista Productos");
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'IDSUCURSAL')
            ->setCellValue('B1', 'IDPRODUCTO')
            ->setCellValue('C1', 'CODIGOPROD')
            ->setCellValue('D1', 'IDCATEGORIA')
            ->setCellValue('E1', 'NOMBRE/DESCRIPCIÓN')
            ->setCellValue('F1', 'ID_UNIDADMEDIDA')
            ->setCellValue('G1', 'AFECTACION IGV')
            ->setCellValue('H1', 'PRECIO VENTA')
            ->setCellValue('I1', 'STOCK')
            ->setCellValue('J1', 'STOCK MINIMO')
            ->setCellValue('K1', 'ESTADO')
            ->setCellValue('L1', 'NOTA')
            ->setCellValue('M1', 'MARCA')
            ->setCellValue('N1', 'PRECIO COMPRA')
            ->setCellValue('O1', 'NOMBRE PRECIO 1')
            ->setCellValue('P1', 'PRECIO 1')
            ->setCellValue('Q1', 'NOMBRE PRECIO 2')
            ->setCellValue('R1', 'PRECIO 2')
            ->setCellValue('S1', 'NOMBRE PRECIO 3')
            ->setCellValue('T1', 'PRECIO 3')
            ->setCellValue('U1', 'NOMBRE PRECIO 4')
            ->setCellValue('V1', 'PRECIO 4')
            ->setCellValue('W1', 'PESO EN KGM')
            ;

        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('D')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('E')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('F')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('G')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('H')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('I')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('J')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('K')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('L')->setAutoSize(true);
        
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('M')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('N')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('O')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('P')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('Q')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('R')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('S')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('T')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('U')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('V')->setAutoSize(true);

        $spreadsheet->getActiveSheet()->getStyle('A1:W1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('01468D');
        $spreadsheet->getActiveSheet()->getStyle('A1:W1')->getFont()->getColor()->setARGB('ffffff');
        $spreadsheet->getActiveSheet()->getStyle('A1:V1')->getFont()->setBold(true);
        
        $n = 1;
        while($fila = $sentencia->fetch()) {
            $item = (object)$fila;
            $n++;

            if($item->id_tipoafectacionigv == 10 || $item->id_tipoafectacionigv == 7152) {
                $precio_producto = $item->precio_inc_igv + 0;
            } else {
                $precio_producto = $item->precio_sin_igv + 0;
            }

            $lista_multiprecio = ProductoListaprecio::find(array("idproducto = :idproducto: and estado = :estado:", 'bind' => array('idproducto' => $item->idproducto, 'estado' => 'activo')));
            $lista_precios = array();
            foreach($lista_multiprecio as $item_multiprecio) {
                $lista_precios[] = array(
                    'nombre'    => $item_multiprecio->nombre,
                    'precio'    => $item_multiprecio->precio
                );
            }

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValueExplicit('A'.$n, $item->idsucursal, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('B'.$n, $item->idproducto, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('C'.$n, $item->codigo, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('D'.$n, (empty($item->id_categoria))?'':$item->id_categoria, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('E'.$n, $item->nombre, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('F'.$n, $item->id_unidad_medida, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('G'.$n, $item->id_tipoafectacionigv, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('H'.$n, $precio_producto, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('I'.$n, $item->stock, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('J'.$n, $item->stock_minimo, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('K'.$n, $item->estado, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('L'.$n, $item->nota, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('M'.$n, $item->marca, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)

                ->setCellValueExplicit('N'.$n, floatval($item->precio_compra) + 0, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)

                ->setCellValueExplicit('O'.$n, isset($lista_precios[0])?$lista_precios[0]['nombre']:'', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('P'.$n, isset($lista_precios[0])?(floatval($lista_precios[0]['precio']) + 0):'', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('Q'.$n, isset($lista_precios[1])?$lista_precios[1]['nombre']:'', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('R'.$n, isset($lista_precios[1])?(floatval($lista_precios[1]['precio']) + 0):'', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('S'.$n, isset($lista_precios[2])?$lista_precios[2]['nombre']:'', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('T'.$n, isset($lista_precios[2])?(floatval($lista_precios[2]['precio']) + 0):'', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('U'.$n, isset($lista_precios[3])?$lista_precios[3]['nombre']:'', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('V'.$n, isset($lista_precios[3])?(floatval($lista_precios[3]['precio']) + 0):'', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
            ;
        }

        $this->agregar_lista_unidades($spreadsheet, 1, 'Lista Unidades');
        $this->agregar_lista_categorias($spreadsheet, 2, 'Categorías', $usuario->id_contribuyente);
        $this->agregar_lista_afectaciones($spreadsheet, 3, 'Lista Afectaciones');
        $this->agregar_lista_sucursales($spreadsheet, 4, 'Sucursales', $usuario->id_contribuyente);

        $spreadsheet->setActiveSheetIndex(0);

        $nombre_archivo = 'ListaProductos_'.str_replace(' ', '_', $sucursal->nombre);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nombre_archivo.'.xlsx"');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit();
    }

    public function agregar_lista_unidades(&$spreadsheet, $num_hoja, $nombre_hoja) {

        $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, $nombre_hoja);
        $spreadsheet->addSheet($myWorkSheet);
        //$spreadsheet->setActiveSheetIndex($num_hoja)->setTitle($nombre_hoja);
        $spreadsheet->setActiveSheetIndex($num_hoja)
            ->setCellValue('A1', 'ID_UNIDADMEDIDA')
            ->setCellValue('B1', 'NOMBRE')
            ->setCellValue('C1', 'SIMBOLO')
            ;
            
        $spreadsheet->setActiveSheetIndex($num_hoja)->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex($num_hoja)->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex($num_hoja)->getColumnDimension('C')->setAutoSize(true);

        $lista = SunatUnidadmedida::find();
        $n = 1;
        foreach($lista as $unidad) {
            $n++;
            $spreadsheet->setActiveSheetIndex($num_hoja)
                ->setCellValueExplicit('A'.$n, $unidad->idunidad, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('B'.$n, $unidad->nombre, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('C'.$n, $unidad->simbolo, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
            ;
        }

        $spreadsheet->getActiveSheet()->getStyle('A1:C1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('01468D');
        $spreadsheet->getActiveSheet()->getStyle('A1:C1')->getFont()->getColor()->setARGB('ffffff');
        $spreadsheet->getActiveSheet()->getStyle('A1:C1')->getFont()->setBold(true);
    }

    public function agregar_lista_categorias(&$spreadsheet, $num_hoja, $nombre_hoja, $id_contribuyente) {
        $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, $nombre_hoja);
        $spreadsheet->addSheet($myWorkSheet);
        //$spreadsheet->setActiveSheetIndex($num_hoja)->setTitle($nombre_hoja);
        $spreadsheet->setActiveSheetIndex($num_hoja)
            ->setCellValue('A1', 'IDCATEGORIA')
            ->setCellValue('B1', 'NOMBRE')
            ->setCellValue('C1', 'DESCRIPCION')
            ;
        
        $spreadsheet->setActiveSheetIndex($num_hoja)->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex($num_hoja)->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex($num_hoja)->getColumnDimension('C')->setAutoSize(true);

        $categorias = Categoria::find(array("id_contribuyente = :id_contribuyente: and estado = :estado:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'estado' => 'activo')));

        $n = 1;
        foreach($categorias as $categoria) {
            $n++;
            $spreadsheet->setActiveSheetIndex($num_hoja)
                ->setCellValueExplicit('A'.$n, $categoria->idcategoria, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('B'.$n, $categoria->nombre, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('C'.$n, $categoria->descripcion, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
            ;
        }

        $spreadsheet->getActiveSheet()->getStyle('A1:C1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('01468D');
        $spreadsheet->getActiveSheet()->getStyle('A1:C1')->getFont()->getColor()->setARGB('ffffff');
        $spreadsheet->getActiveSheet()->getStyle('A1:C1')->getFont()->setBold(true);
    }

    public function agregar_lista_afectaciones(&$spreadsheet, $num_hoja, $nombre_hoja) {
        $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, $nombre_hoja);
        $spreadsheet->addSheet($myWorkSheet);
        //$spreadsheet->setActiveSheetIndex($num_hoja)->setTitle($nombre_hoja);
        $spreadsheet->setActiveSheetIndex($num_hoja)
            ->setCellValue('A1', 'ID AFECTACION IGV')
            ->setCellValue('B1', 'DESCRIPCION')
            ;
            
        $spreadsheet->setActiveSheetIndex($num_hoja)->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex($num_hoja)->getColumnDimension('B')->setAutoSize(true);

        $lista = SunatTipoafectacionigv::find();
        $n = 1;
        foreach($lista as $item) {
            $n++;
            $spreadsheet->setActiveSheetIndex($num_hoja)
                ->setCellValueExplicit('A'.$n, $item->id_tipoafectacionigv, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('B'.$n, $item->descripcion, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
            ;
        }

        $spreadsheet->getActiveSheet()->getStyle('A1:B1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('01468D');
        $spreadsheet->getActiveSheet()->getStyle('A1:B1')->getFont()->getColor()->setARGB('ffffff');
        $spreadsheet->getActiveSheet()->getStyle('A1:B1')->getFont()->setBold(true);
    }

    public function agregar_lista_sucursales(&$spreadsheet, $num_hoja, $nombre_hoja, $id_contribuyente) {
        $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, $nombre_hoja);
        $spreadsheet->addSheet($myWorkSheet);
        //$spreadsheet->setActiveSheetIndex($num_hoja)->setTitle($nombre_hoja);
        $spreadsheet->setActiveSheetIndex($num_hoja)
            ->setCellValue('A1', 'IDSUCURSAL')
            ->setCellValue('B1', 'CODIGO')
            ->setCellValue('C1', 'NOMBRE')
            ;
        
        $spreadsheet->setActiveSheetIndex($num_hoja)->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex($num_hoja)->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex($num_hoja)->getColumnDimension('C')->setAutoSize(true);

        $sucursales = Sucursal::find(array("id_contribuyente = :id_contribuyente: and estado = :estado:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'estado' => 'activo')));

        $n = 1;
        foreach($sucursales as $item) {
            $n++;
            $spreadsheet->setActiveSheetIndex($num_hoja)
                ->setCellValueExplicit('A'.$n, $item->idsucursal, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('B'.$n, $item->codigo, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('C'.$n, $item->nombre, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
            ;
        }

        $spreadsheet->getActiveSheet()->getStyle('A1:C1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('01468D');
        $spreadsheet->getActiveSheet()->getStyle('A1:C1')->getFont()->getColor()->setARGB('ffffff');
        $spreadsheet->getActiveSheet()->getStyle('A1:C1')->getFont()->setBold(true);
    }
    
    public function iniciarTrasladoAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
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
                $resp['mensaje'] = 'No se encuentra la empresa al que pertenece el usuario';
                echo json_encode($resp);
                exit();
			}
            
            //Datos de formulario
            $datapost = $this->request->getPost();
            $idsucursal_origen =  !isset($datapost['select_almacen_origen'])?0:$datapost['select_almacen_origen'];
            $idsucursal_destino =  !isset($datapost['select_almacen_destino'])?0:$datapost['select_almacen_destino'];
            $nota = !isset($datapost['nota_traslado'])?'':$datapost['nota_traslado'];

            $sucursal_origen = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $idsucursal_origen, 'id_contribuyente' => $usuario->id_contribuyente)));
            if(!$sucursal_origen) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'La Sucursal de Origen no se Encuentra Registrada!';
                echo json_encode($resp);
                exit();
            }

            $sucursal_destino = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $idsucursal_destino, 'id_contribuyente' => $usuario->id_contribuyente)));
            if(!$sucursal_destino) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'La Sucursal de Destino no se encuentra registrada!';
                echo json_encode($resp);
                exit();
            }

            if($idsucursal_origen == $idsucursal_destino) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'La Sucursal de Origen y la Sucursal de Destino deben ser Diferentes!';
                echo json_encode($resp);
                exit();
            }

            $lista_productos = json_decode($datapost['detalle']);
            if(!is_array($lista_productos)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes Agregar al menos un item al documento electrónico...';
                return $resp;
            }
    
            if(count($lista_productos) <= 0) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes Agregar al menos un item al documento electrónico...';
                echo json_encode($resp);
                exit();
            }
            
            $num_decimales = 2;
            if($contribuyente->num_decimales > 2) {
                $num_decimales = $contribuyente->num_decimales;
            }

            $gestion_usuarios = new GestionuserController;
            if($usuario->id_rol != 1 && $usuario->id_rol != 2 && $usuario->id_rol != 3 && $usuario->id_rol != 5) {
                if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_productos') != 'acceso_total') {
                    if($gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_productos') != 'todas_las_sucursales') {
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = 'No tiene Permisos para Realizar Traslados';
                        echo json_encode($resp);
                        exit();
                    }
                }
            }

            $this->db->begin();

            $n = 0;
            foreach($lista_productos as $item) {
                $n++;
                $producto = Producto::findFirst(array("id_contribuyente = :id_contribuyente: and idproducto = :idproducto: and estado = 'activo' and idsucursal = :idsucursal:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'idproducto' => $item->idarticulo, 'idsucursal' => $idsucursal_origen)));

                if(!$producto) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['id_contribuyente'] = $usuario->id_contribuyente;
                    $resp['idarticulo'] = $item->idarticulo;
                    $resp['sucursal_origen'] = $idsucursal_origen;
                    $resp['mensaje'] = 'El Producto que intenta modificar no existe!! => ';
                    echo json_encode($resp);
                    exit();
                }

                if($item->cantidad <= 0) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'El producto: '.$item->descripcion.' tiene como cantidad CERO, las cantidades deben ser mayores a cero';
                    echo json_encode($resp);
                    exit();
                }

                $unidad_medida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $producto->id_unidad_medida)));
                if(!$unidad_medida){
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'Lo sentimos!, esa medida no existe. Por favor selecciona una Unidad de Medida Válida';
                    echo json_encode($resp);
                    exit();
                }

                $tipoafectacionigv = SunatTipoafectacionigv::findFirst(array("id_tipoafectacionigv = :id_tipoafectacionigv:", 'bind' => array('id_tipoafectacionigv' => $producto->id_tipoafectacionigv)));
                if(!$tipoafectacionigv) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'El tipo de Afectación del IGV selecionado no es válido para el producto: '.$item->descripcion;
                    echo json_encode($resp);
                    exit();
                }

                if($contribuyente->restriccion_stock == 'si') {
                    if(floatval($item->cantidad) > floatval($producto->stock)) {
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = 'El Item N° '.$n.' tiene como stock actual: '.$producto->stock.' '.$unidad_medida->nombre.', y usted intenta trasladar '.$item->cantidad.' '.$unidad_medida->nombre.', por tanto no podemos continuar ya que la restricción de stock está activa.';
                        echo json_encode($resp);
                        exit();
                    }
                }

                $detalle[] = array(
                    "item"          	=> $n,
                    "id_u_medida"   	=> $unidad_medida->idunidad,
                    "u_medida"      	=> $unidad_medida->codigo,
                    "precio"            => $producto->valor_con_igv,
                    "id_afectigv" 	    => $tipoafectacionigv->id_tipoafectacionigv, //id_tipoafectacionigv - CATALOGO No. 07
                    "cantidad"          => $item->cantidad,
                    "idproducto"        => $producto->idproducto,
                    "codigoproducto"	=> $producto->codigo,
                    "nombreproducto"   	=> $producto->nombre,
                    "id_codigomoneda"   => $producto->id_cod_moneda,
                    "costo_unitario"    => $producto->precio_compra
                );
            }

            $confirmado = empty($datapost['confirmado'])?'':$datapost['confirmado'];
			if($confirmado != 'si') {
				$resp['respuesta'] = 'ok';
				$resp['mensaje'] = '¿Realmente Desea continuar con el traslado de mercadería?';
				echo json_encode($resp);
				exit();
            }

            $dominio_principal = $this->get_parametros_iniciales()['url_domain'];
            $herramientas = new HerramientasController;
            
            //=================================================
            //REGISTRO DE TRASLADO
            //=================================================
            $data['id_contribuyente'] = $contribuyente->id_contribuyente;
            $data['id_sucursal'] = $idsucursal_origen;
            $data['id_usuario'] = $usuario->idusuario;
            $data['tipo_envio_sunat'] = $contribuyente->tipo_envio_sunat;
            $data['id_tipo_movimiento'] = 'ts';
            $data['nota'] = $nota;
           
            $data['destino_id_sucursal'] = $idsucursal_destino;
            $data['destino_id_contribuyente'] = $contribuyente->id_contribuyente;
            
            $lista_items = array();
            foreach($detalle as $item) {
                $item = (object)$item;
                $array_item = array();

                $array_item['cantidad']             = $item->cantidad;
                $array_item['costo_unitario']       = $item->costo_unitario;
                $array_item['id_codigomoneda']      = $item->id_codigomoneda;
    
                $array_item['o_id_u_medida']        = $item->id_u_medida;
                $array_item['o_u_medida']           = $item->u_medida;
                $array_item['o_precio']             = $item->precio;
                $array_item['o_id_afectigv']        = $item->id_afectigv;
                $array_item['o_tipo_unidad']        = 'UND';
                $array_item['o_id_presentacion']    = '';
                $array_item['o_cod_prod']           = $item->codigoproducto;
                $array_item['o_id_prod']            = $item->idproducto;
                $array_item['o_nom_prod']           = $item->nombreproducto;

                $lista_items[] = $array_item;
            }

            $data['detalle_movimiento'] = $lista_items;

            $traslado = new ProductomovimientosController;
            $resp_reg_movimiento = $traslado->registrar_movimiento($usuario, $data);
            if($resp_reg_movimiento['respuesta'] == 'error') {
                $this->db->rollback();
				echo json_encode($resp_reg_movimiento);
				exit();
            }

            $existe_movimiento = isset($resp_reg_movimiento['producto_movimiento'])?true:false;

            $html_pdf = '';
            if($existe_movimiento) {
                $producto_movimiento = $resp_reg_movimiento['producto_movimiento'];
                $cadena_pdf_a4 = "$contribuyente->id_contribuyente||$producto_movimiento->id_tipo_movimiento||$producto_movimiento->serie||$producto_movimiento->correlativo||$producto_movimiento->tipo_envio_sunat||a4";
                $cadena_pdf_ticket = "$contribuyente->id_contribuyente||$producto_movimiento->id_tipo_movimiento||$producto_movimiento->serie||$producto_movimiento->correlativo||$producto_movimiento->tipo_envio_sunat||ticket";
    
                $cadena_pdf_a4_encriptada = $herramientas->encriptar($cadena_pdf_a4);
                $cadena_pdf_ticket_encriptada = $herramientas->encriptar($cadena_pdf_ticket);
    
                $url_a4 = 'https://'.$dominio_principal.'/facturacionv8/productomovimientos/print_pdf/?file='.urlencode($cadena_pdf_a4_encriptada);
                $url_ticket = 'https://'.$dominio_principal.'/facturacionv8/productomovimientos/print_pdf/?file='.urlencode($cadena_pdf_ticket_encriptada);
    
                $html_pdf = '<br /><br />'.'<a target="_blank" href="'.$url_a4.'"><img title="Formato A4" src="/facturacionv8/img/svg/pdf_cpe.svg" style="width: 30px; cursor: pointer;"></a>
                <a target="_blank" href="'.$url_ticket.'"><img title="Formato Ticket" src="/facturacionv8/img/svg/ticket_cpe.svg" style="width: 30px; cursor: pointer;"></a>';
            }
            
            $this->db->commit();
            $resp_traslado['respuesta'] = 'ok';
            $resp_traslado['titulo'] = '¡Excelente!';
            $resp_traslado['mensaje'] = 'El Traslado de los Productos se Realizó con Éxito!'.$html_pdf;
            $resp_traslado['html_pdf'] = $html_pdf;
            echo json_encode($resp_traslado);
            exit();
        }
    }

    public function imprimirCodigobarrasAction($codigo_producto = '') {
        $this->setTitle('Código de Barras');
        $this->view->setTemplateAfter('vacio');

        $this->assets
            ->addJs("https://cdn.jsdelivr.net/jsbarcode/3.3.20/JsBarcode.all.min.js", false)
            ->addJs($this->baseUri . "public/js/imprimir_codigobarras.js?i=".rand());

        $linea1 = isset($_GET['l1'])?$_GET['l1']:'';
        $linea2 = isset($_GET['l2'])?$_GET['l2']:'';
        $linea3 = isset($_GET['l3'])?$_GET['l3']:'';
        
        if($codigo_producto == '') {
            exit();
        }

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
            $resp['mensaje'] = 'No se encuentra la empresa al que pertenece el usuario';
            echo json_encode($resp);
            exit();
        }

        $producto = Producto::findFirst(array("id_contribuyente = :id_contribuyente: and codigo = :codigo: and estado = 'activo'", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'codigo' => $codigo_producto)));
        if(!$producto) {

        }
        if($producto) {
            $this->setTitle($producto->nombre.' - CODIGO: '.$producto->codigo);
        }

        //$this->view->producto = $producto;
        $this->view->codigo_producto = $codigo_producto;
        $this->view->l1 = $linea1;
        $this->view->l2 = $linea2;
        $this->view->l3 = $linea3;
    }

    public function getSugerenciasMarcasAction() {
		$this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes iniciar sesión.';
                echo json_encode($resp);
                exit();
			}

			$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            if(!$contribuyente) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos la empresa no existe!';
                echo json_encode($resp);
                exit();
			}

			$query = "SELECT DISTINCT marca FROM producto WHERE id_contribuyente = $contribuyente->id_contribuyente and marca is not null LIMIT 200";

			try {
                $sentencia = $this->db->prepare($query);
                $sentencia->execute();
            } catch (Exception $e) {
                $this->saveLogger($e);
                echo 'Excepción capturada: ',  $e->getMessage(), "\n";
                exit();
            }

            $n = 0;
            $array_lista = array();
            while($fila = $sentencia->fetch()) {
                $fila = (object)$fila;
                $array_lista[] = array('marca' => $fila->marca);
            }

            echo json_encode($array_lista);
            exit();
		}
    }

    public function iniciarProcesoTransformacionAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
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
                $resp['mensaje'] = 'No se encuentra la empresa al que pertenece el usuario';
                echo json_encode($resp);
                exit();
			}

            //Datos de formulario
            $datapost = $this->request->getPost();

            //DATOS DE LOS PRODUCTOS INICIALES
            $lista_prod_iniciales = json_decode($datapost['lista_prod_iniciales']);
            if(!is_array($lista_prod_iniciales)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes agregar al menos un producto a la lista inicial...';
                return $resp;
            }
    
            if(count($lista_prod_iniciales) <= 0) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes agregar al menos un producto a la lista inicial...';
                echo json_encode($resp);
                exit();
            }

            $lista_prod_finales = json_decode($datapost['lista_prod_finales']);
            if(!is_array($lista_prod_finales)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes agregar al menos un producto a la lista resultante...';
                return $resp;
            }
    
            if(count($lista_prod_finales) <= 0) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes agregar al menos un producto a la lista resultante...';
                echo json_encode($resp);
                exit();
            }
            
            $id_sucursal_origen = isset($datapost['select_aorigen_transformacion'])?intval($datapost['select_aorigen_transformacion']):0;
            $id_sucursal_destino = isset($datapost['select_adestino_transformacion'])?intval($datapost['select_adestino_transformacion']):0;
            $nota_transformacion = isset($datapost['nota_transformacion'])?$datapost['nota_transformacion']:'';
            $confirmado = !isset($datapost['confirmado'])?'no':$datapost['confirmado'];

            if($confirmado != 'si') {
                $resp['respuesta'] = 'ok';
                $resp['titulo'] = 'Confirmación';
                $resp['mensaje'] = '¿Realmente deseas iniciar el proceso de transformación?';
                echo json_encode($resp);
                exit();
            }

            $data['id_contribuyente'] = $contribuyente->id_contribuyente;
            $data['idusuario'] = $usuario->idusuario;
            $data['id_sucursal_origen'] = $id_sucursal_origen;
            $data['id_sucursal_destino'] = $id_sucursal_destino;
            $data['nota_transformacion'] = $nota_transformacion;
            $data['lista_prod_iniciales'] = $lista_prod_iniciales;
            $data['lista_prod_finales'] = $lista_prod_finales;

            $resp = $this->iniciar_proceso_transformacion($data);
            echo json_encode($resp);
            exit();            
        }
    }

    public function iniciar_proceso_transformacion($data) {
        $resp_validacion = $this->validar_datos_transformacion($data);
        if($resp_validacion['respuesta'] == 'error') {
            echo json_encode($resp_validacion);
            exit();
        }

        $id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:0;
        $idusuario = isset($data['idusuario'])?$data['idusuario']:0;
        $id_sucursal_origen = isset($data['id_sucursal_origen'])?$data['id_sucursal_origen']:0;
        $id_sucursal_destino = isset($data['id_sucursal_destino'])?$data['id_sucursal_destino']:0;
        $nota_transformacion = isset($data['nota_transformacion'])?$data['nota_transformacion']:'';
        $lista_prod_iniciales = $resp_validacion['lista_prod_iniciales'];
        $lista_prod_finales = $resp_validacion['lista_prod_finales'];
        $tipo_envio_sunat = $resp_validacion['tipo_envio_sunat'];
        $usuario = $resp_validacion['usuario'];

        $data_movimiento['id_contribuyente']           = $id_contribuyente;
        $data_movimiento['id_usuario']                 = $idusuario;
        $data_movimiento['tipo_envio_sunat']           = $tipo_envio_sunat;
        $data_movimiento['id_sucursal']                = $id_sucursal_origen;
        $data_movimiento['destino_id_sucursal']        = $id_sucursal_destino;
        $data_movimiento['destino_id_contribuyente']   = $id_contribuyente;
        $data_movimiento['id_tipo_movimiento']         = 'tr'; //transformacion
        $data_movimiento['nota']                       = empty($nota_transformacion)?'Transformación de Materiales':$nota_transformacion;

        $detalle_movimiento = array();
        
        foreach($lista_prod_iniciales as $item) {
            $item = (object)$item;

            $detalle_movimiento[] = array(
                'cantidad'          => $item->cantidad,
                'costo_unitario'    => $item->costo_unitario,
                'tipo_kardex'       => 'salida_sindoc',
                'id_codigomoneda'   => $item->id_codigomoneda,

                'o_id_u_medida'     => $item->id_u_medida,
                'o_u_medida'        => $item->u_medida,
                'o_precio'          => $item->precio,
                'o_id_afectigv'     => $item->id_afectigv,
                'o_tipo_unidad'     => 'UND',
                'o_id_presentacion' => '',
                'o_cod_prod'        => $item->codigoproducto,
                'o_id_prod'         => $item->idproducto,
                'o_nom_prod'        => $item->nombreproducto,

                'd_id_u_medida'     => null,
                'd_u_medida'        => null,
                'd_precio'          => null,
                'd_id_afectigv'     => null,
                'd_tipo_unidad'     => null,
                'd_id_presentacion' => null,
                'd_cod_prod'        => null,
                'd_id_prod'         => null,
                'd_nom_prod'        => null
            );
        }

        foreach($lista_prod_finales as $item) {
            $item = (object)$item;

            $detalle_movimiento[] = array(
                'cantidad'          => $item->cantidad,
                'costo_unitario'    => $item->costo_unitario,
                'tipo_kardex'       => 'ingreso_sindoc',
                'id_codigomoneda'   => $item->id_codigomoneda,

                'o_id_u_medida'     => null,
                'o_u_medida'        => null,
                'o_precio'          => null,
                'o_id_afectigv'     => null,
                'o_tipo_unidad'     => null,
                'o_id_presentacion' => null,
                'o_cod_prod'        => null,
                'o_id_prod'         => null,
                'o_nom_prod'        => null,

                'd_id_u_medida'     => $item->id_u_medida,
                'd_u_medida'        => $item->u_medida,
                'd_precio'          => $item->precio,
                'd_id_afectigv'     => $item->id_afectigv,
                'd_tipo_unidad'     => 'UND',
                'd_id_presentacion' => '',
                'd_cod_prod'        => $item->codigoproducto,
                'd_id_prod'         => $item->idproducto,
                'd_nom_prod'        => $item->nombreproducto,
            );
        }

        $data_movimiento['detalle_movimiento'] = $detalle_movimiento;

        $this->db->begin();

        $movimiento_almacen = new ProductomovimientosController;
        $resp_reg_movimiento = $movimiento_almacen->registrar_movimiento($usuario, $data_movimiento);
        if($resp_reg_movimiento['respuesta'] == 'error') {
            $this->db->rollback();
            return $resp_reg_movimiento;
        }

        $herramientas = new HerramientasController;
        $dominio_principal = $this->get_parametros_iniciales()['url_domain'];
        $url_a4 = '';
        $url_ticket = '';
        $html_pdf = '';

        $existe_movimiento = isset($resp_reg_movimiento['producto_movimiento'])?true:false;
        if($existe_movimiento) {
            $producto_movimiento = $resp_reg_movimiento['producto_movimiento'];

            $cadena_pdf_a4 = "$id_contribuyente||$producto_movimiento->id_tipo_movimiento||$producto_movimiento->serie||$producto_movimiento->correlativo||$producto_movimiento->tipo_envio_sunat||a4";
            $cadena_pdf_ticket = "$id_contribuyente||$producto_movimiento->id_tipo_movimiento||$producto_movimiento->serie||$producto_movimiento->correlativo||$producto_movimiento->tipo_envio_sunat||ticket";

            $cadena_pdf_a4_encriptada = $herramientas->encriptar($cadena_pdf_a4);
            $cadena_pdf_ticket_encriptada = $herramientas->encriptar($cadena_pdf_ticket);

            $url_a4 = 'https://'.$dominio_principal.'/facturacionv8/productomovimientos/print_pdf/?file='.urlencode($cadena_pdf_a4_encriptada);
            $url_ticket = 'https://'.$dominio_principal.'/facturacionv8/productomovimientos/print_pdf/?file='.urlencode($cadena_pdf_ticket_encriptada);

            $html_pdf = '<br /><br />'.'<a target="_blank" href="'.$url_a4.'"><img title="Formato A4" src="/facturacionv8/img/svg/pdf_cpe.svg" style="width: 30px; cursor: pointer;"></a>
            <a target="_blank" href="'.$url_ticket.'"><img title="Formato Ticket" src="/facturacionv8/img/svg/ticket_cpe.svg" style="width: 30px; cursor: pointer;"></a>';
        }

        $this->db->commit();
        $resp['respuesta'] = 'ok';
        $resp['titulo'] = 'Correcto';
        $resp['mensaje'] = 'El Proceso de Transformación se ha completado con éxito'.$html_pdf;
        $resp['url_a4'] = $url_a4;
        $resp['url_ticket'] = $url_ticket;
        $resp['html_pdf'] = $html_pdf;
        return $resp;
    }

    public function validar_datos_transformacion($data) {
        $id_contribuyente = isset($data['id_contribuyente'])?$data['id_contribuyente']:0;
        $idusuario = isset($data['idusuario'])?$data['idusuario']:0;
        $id_sucursal_origen = isset($data['id_sucursal_origen'])?$data['id_sucursal_origen']:0;
        $id_sucursal_destino = isset($data['id_sucursal_destino'])?$data['id_sucursal_destino']:0;
        $nota_transformacion = isset($data['nota_transformacion'])?$data['nota_transformacion']:'';
        $lista_prod_iniciales = isset($data['lista_prod_iniciales'])?$data['lista_prod_iniciales']:array();
        $lista_prod_finales = isset($data['lista_prod_finales'])?$data['lista_prod_finales']:array();

        $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
        if(!$usuario) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error en Usuario';
            $resp['mensaje'] = 'El Usuario no Existe';
            return $resp;
        }

        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
        if(!$contribuyente) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'No se encuentra la empresa al que pertenece el usuario';
            return $resp;
        }

        $sucursal_origen = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $id_sucursal_origen, 'id_contribuyente' => $id_contribuyente)));
        if(!$sucursal_origen) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'No Existe la Sucursal de Origen';
            return $resp;
        }

        $sucursal_destino = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $id_sucursal_destino, 'id_contribuyente' => $id_contribuyente)));
        if(!$sucursal_destino) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'No Existe la Sucursal de Destino';
            return $resp;
        }

        if(!is_array($lista_prod_iniciales)) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Debes agregar al menos un producto a la lista inicial...';
            return $resp;
        }

        if(count($lista_prod_iniciales) <= 0) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Debes agregar al menos un producto a la lista inicial...';
            return $resp;
        }

        if(!is_array($lista_prod_finales)) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Debes agregar al menos un producto a la lista resultante...';
            return $resp;
        }

        if(count($lista_prod_finales) <= 0) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Debes agregar al menos un producto a la lista resultante...';
            return $resp;
        }

        $lista_prod_iniciales_array = array();
        $n_iniciales = 0;
        foreach($lista_prod_iniciales as $item_prod) {
            $n_iniciales++;
            $producto = Producto::findFirst(array("id_contribuyente = :id_contribuyente: and idproducto = :idproducto: and estado = 'activo' and idsucursal = :idsucursal:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'idproducto' => $item_prod->idarticulo, 'idsucursal' => $sucursal_origen->idsucursal)));

            if(!$producto) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['id_contribuyente'] = $usuario->id_contribuyente;
                $resp['idarticulo'] = $item_prod->idarticulo;
                $resp['id_sucursal_origen'] = $id_sucursal_origen;
                $resp['mensaje'] = "El Producto N° $n_iniciales de la lista de materiales no existe, o no pertenece a la sucursal seleccionada: ".$sucursal_origen->nombre." (ID:$sucursal_origen->idsucursal)";
                return $resp;
            }

            if($item_prod->cantidad <= 0) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El producto: '.$item_prod->descripcion.' de la lista de materiales tiene como cantidad CERO, las cantidades deben ser mayores a cero';
                return $resp;
            }

            if($producto->id_unidad_medida == 20) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El producto: '.$item_prod->descripcion.' se ha registrado como SERVICIO, por tanto no se puede continuar';
                return $resp;
            }

            $unidad_medida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $producto->id_unidad_medida)));
            if(!$unidad_medida){
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos!, esa medida no existe. Por favor selecciona una Unidad de Medida Válida';
                return $resp;
            }

            $tipoafectacionigv = SunatTipoafectacionigv::findFirst(array("id_tipoafectacionigv = :id_tipoafectacionigv:", 'bind' => array('id_tipoafectacionigv' => $producto->id_tipoafectacionigv)));
            if(!$tipoafectacionigv) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El tipo de Afectación del IGV selecionado no es válido para el producto: '.$item_prod->descripcion;
                return $resp;
            }

            if($contribuyente->restriccion_stock == 'si') {
                if(floatval($item_prod->cantidad) > floatval($producto->stock)) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'El Item N° '.$n_iniciales.' tiene como stock actual: '.$producto->stock.' '.$unidad_medida->nombre.', y usted intenta transformar '.$item_prod->cantidad.' '.$unidad_medida->nombre.', por tanto no podemos continuar ya que la restricción de stock está activa...';
                    return $resp;
                }
            }

            $lista_prod_iniciales_array[] = array(
                "item"          	=> $n_iniciales,
                "id_u_medida"   	=> $unidad_medida->idunidad,
                "u_medida"      	=> $unidad_medida->codigo,
                "precio"            => $producto->valor_con_igv,
                "id_afectigv" 	    => $tipoafectacionigv->id_tipoafectacionigv, //id_tipoafectacionigv - CATALOGO No. 07
                "cantidad"          => $item_prod->cantidad,
                "idproducto"        => $producto->idproducto,
                "codigoproducto"	=> $producto->codigo,
                "nombreproducto"   	=> $producto->nombre,
                "id_codigomoneda"   => $producto->id_cod_moneda,
                "costo_unitario"    => $producto->precio_compra
            );
        }
        
        $lista_prod_finales_array = array();
        $n_finales = 0;
        foreach($lista_prod_finales as $item_prod) {
            $n_finales++;
            $producto = Producto::findFirst(array("id_contribuyente = :id_contribuyente: and idproducto = :idproducto: and estado = 'activo' and idsucursal = :idsucursal:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'idproducto' => $item_prod->idarticulo, 'idsucursal' => $sucursal_destino->idsucursal)));

            if(!$producto) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['id_contribuyente'] = $usuario->id_contribuyente;
                $resp['idarticulo'] = $item_prod->idarticulo;
                $resp['id_sucursal_destino'] = $id_sucursal_destino;
                $resp['mensaje'] = "El Producto N° $n_finales de la lista de materiales no existe, o no pertenece a la sucursal seleccionada: ".$sucursal_destino->nombre." (ID:$sucursal_destino->idsucursal)";
                return $resp;
            }

            if($item_prod->cantidad <= 0) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El producto: '.$item_prod->descripcion.' de la lista de materiales tiene como cantidad CERO, las cantidades deben ser mayores a cero';
                return $resp;
            }

            if($producto->id_unidad_medida == 20) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El producto: '.$item_prod->descripcion.' se ha registrado como SERVICIO, por tanto no se puede continuar';
                return $resp;
            }

            $unidad_medida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $producto->id_unidad_medida)));
            if(!$unidad_medida){
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Lo sentimos!, esa medida no existe. Por favor selecciona una Unidad de Medida Válida';
                return $resp;
            }

            $tipoafectacionigv = SunatTipoafectacionigv::findFirst(array("id_tipoafectacionigv = :id_tipoafectacionigv:", 'bind' => array('id_tipoafectacionigv' => $producto->id_tipoafectacionigv)));
            if(!$tipoafectacionigv) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El tipo de Afectación del IGV selecionado no es válido para el producto: '.$item_prod->descripcion;
                return $resp;
            }

            $lista_prod_finales_array[] = array(
                "item"          	=> $n_finales,
                "id_u_medida"   	=> $unidad_medida->idunidad,
                "u_medida"      	=> $unidad_medida->codigo,
                "precio"            => $producto->valor_con_igv,
                "id_afectigv" 	    => $tipoafectacionigv->id_tipoafectacionigv, //id_tipoafectacionigv - CATALOGO No. 07
                "cantidad"          => $item_prod->cantidad,
                "idproducto"        => $producto->idproducto,
                "codigoproducto"	=> $producto->codigo,
                "nombreproducto"   	=> $producto->nombre,
                "id_codigomoneda"   => $producto->id_cod_moneda,
                "costo_unitario"    => $producto->precio_compra
            );
        }

        $idproductos_finales = array_column($lista_prod_finales, 'idproducto');
        $idproductos_iniciales = array_column($lista_prod_iniciales, 'idproducto');
        $idproductos_repetidos = array_intersect($idproductos_finales, $idproductos_iniciales);

        if (!empty($idproductos_repetidos)) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'Encontramos que existen productos de la lista de materiales que se repiten en la lista final, esto no es lógico, ya que la transformación de productos implica que un producto A se transformará en un Producto B, nunca en el mismo producto.';
            return $resp;
        }

        $resp['respuesta'] = 'ok';
        $resp['data'] = $data;
        $resp['lista_prod_iniciales'] = $lista_prod_iniciales_array;
        $resp['lista_prod_finales'] = $lista_prod_finales_array;
        $resp['tipo_envio_sunat'] = $contribuyente->tipo_envio_sunat;
        $resp['contribuyente'] = $contribuyente;
        $resp['usuario'] = $usuario;
        return $resp;
    }

    public function getStockVariasSucursalesAction() {
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

            //Datos de Formulario
            $datapost = $this->request->getPost();
            $codigo_producto = isset($datapost['codigo_producto'])?$datapost['codigo_producto']:'';

            if($codigo_producto == '') {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes ingresar un código de producto';
                echo json_encode($resp);
                exit();
            }

            $query = "SELECT p.idsucursal, s.nombre nombre_sucursal, p.codigo, p.nombre nombre_producto, p.stock FROM producto p INNER JOIN sucursal s ON p.idsucursal = s.idsucursal where p.id_contribuyente = :id_contribuyente and p.codigo = :codigo and p.estado = 'activo'";

            try {
                $sentencia = $this->db->prepare($query);
                $sentencia->bindParam(':id_contribuyente', $usuario->id_contribuyente, PDO::PARAM_INT);
                $sentencia->bindParam(':codigo', $codigo_producto, PDO::PARAM_STR);
                $sentencia->execute();
            } catch (Exception $e) {
                $this->saveLogger($e);
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = $e->getMessage();
                echo json_encode($resp);
                exit();
            }

            $n = 0;
            $array_lista = array();
            while($fila = $sentencia->fetch()) {
                $fila = (object)$fila;
                $array_lista[] = array(
                    'idsucursal'        => $fila->idsucursal,
                    'nombre_sucursal'   => $fila->nombre_sucursal,
                    'codigo'            => $fila->codigo,
                    'nombre_producto'   => $fila->nombre_producto,
                    'stock'             => $fila->stock
                );
            }

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Stock de Productos';
            $resp['mensaje'] = 'Se encontraron '.count($array_lista).' productos con el código: '.$codigo_producto;
            $resp['lista'] = $array_lista;
            echo json_encode($resp);
            exit();
        }
    }
}
?>