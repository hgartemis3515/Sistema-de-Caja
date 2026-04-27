<?php
//require $_SERVER["DOCUMENT_ROOT"]."/facturacionv8/apis/phpspreadsheet/vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
class ImportacionproductosController extends ControllerBase
{
	public function indexAction() {

	}

	public function importarProductosAction() {
		ini_set('memory_limit','2048M');
        ini_set('max_execution_time', 940);

		$this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $resp = $this->subir_productos($this->request->getPost());
            echo json_encode($resp);
            exit();
        }
	}

	public function esUnCodigoValido(string $input): bool {
		// Permite letras (incluyendo Ñ/ñ), dígitos, punto, guión bajo, barra vertical, slash, asterisco y guión.
		$pattern = '/^[a-zA-ZñÑ0-9._|\/*\-]+$/u';
		return preg_match($pattern, $input) === 1;
	}
	
	public function subir_productos($datapost) {
		$auth = $this->session->get('authv8');
		$idusuario = $auth['idusuario'];
		$usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
		if(!$usuario) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error en Usuario';
			$resp['mensaje'] = 'Lo sentimos! Es posible que se haya cerrado la sessión, <a style="color: #1c81d1;" href="/session" target="_blank">haz click aquí para ingresar de nuevo</a>, luego de iniciar sessión, puedes regresar a esta pantalla y recargar la página y continuar...';
			return $resp;
		}

		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
		if(!$contribuyente) { 
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'No existe la empresa que intenta configurar!!';
			return $resp;
		}
		
		//Datos de Formulario
		$confirmacion = !isset($datapost['confirmacion'])?'no':$datapost['confirmacion'];
		$idsucursal = !isset($datapost['select_sucursal'])?0:intval($datapost['select_sucursal']) + 0;

		if(!isset($_FILES['archivo'])) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Debes Agregar un Archivo!';
			return $resp;
		}
		
		$allowedFileType = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
		if(!in_array($_FILES["archivo"]["type"], $allowedFileType)){
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'No es un archivo válido!';
			return $resp;
		}

		if($confirmacion != 'si') {
			$resp['respuesta'] = 'ok';
			$resp['titulo'] = 'Necesitamos tu Confirmación!';
			$resp['mensaje'] = '<strong>Necesitamos tu confirmación para proceder con la importación de todos los productos ¿Deseas Continuar?, Recuerda que una vez importados ya no podrás eliminar los productos!</strong>';
			return $resp;
		}

		$herramientas = new HerramientasController;
		
		$nombre_temporal = 'temp'.uniqid().$_FILES['archivo']['name'];
		$rutaArchivo = $_SERVER["DOCUMENT_ROOT"]."/facturacionv8/apis/apiexcel/temporales/".$nombre_temporal;
		move_uploaded_file($_FILES['archivo']['tmp_name'], $rutaArchivo);
		
		$documento = IOFactory::load($rutaArchivo);
		$totalDeHojas = $documento->getSheetCount();

		$hojaActual = $documento->getSheet(0); //extraemos datos de la primera hoja solamente
		$numeroMayorDeFila = $hojaActual->getHighestRow(); // Numérico
		$numeroMayorDeColumna = 21;

		if($numeroMayorDeFila >= 2501) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Solo se permite subir un máximo de 500 productos por archivo!';
			return $resp;
		}
		
		$array_code_prod = array();
		$array_nuevos_productos = array();
		# Iterar filas con ciclo for e índices
		for ($indiceFila = 2; $indiceFila <= $numeroMayorDeFila; $indiceFila++) {
			$n = $indiceFila - 1;
			$codigo_categoria =$hojaActual->getCell("A".$indiceFila)->getCalculatedValue();
			$codigo_producto = !empty($hojaActual->getCell("B".$indiceFila)->getValue()) ? trim($hojaActual->getCell("B".$indiceFila)->getValue()) : '';
			$nombre_producto = trim($hojaActual->getCell("C".$indiceFila)->getCalculatedValue());
			$codigo_afectacion = $hojaActual->getCell("D".$indiceFila)->getCalculatedValue();
			$id_unidad_medida = $hojaActual->getCell("E".$indiceFila)->getCalculatedValue();
			$codigo_moneda = trim($hojaActual->getCell("F".$indiceFila)->getCalculatedValue());
			$precio = floatval($hojaActual->getCell("G".$indiceFila)->getCalculatedValue()) + 0;
			$stock_actual = floatval($hojaActual->getCell("H".$indiceFila)->getCalculatedValue()) + 0;
			$stock_minimo = floatval($hojaActual->getCell("I".$indiceFila)->getCalculatedValue()) + 0;
			$detalle = $hojaActual->getCell("J".$indiceFila)->getCalculatedValue();
			$precio_compra = floatval($hojaActual->getCell("K".$indiceFila)->getCalculatedValue()) + 0;
			$id_sucursal = intval($hojaActual->getCell("L".$indiceFila)->getCalculatedValue()) + 0;
			$fecha_vencimiento = (!empty($hojaActual->getCell("M".$indiceFila)->getCalculatedValue()))?date("Y-m-d", strtotime($hojaActual->getCell("M".$indiceFila)->getCalculatedValue())):'';
			$marca = empty($hojaActual->getCell("N".$indiceFila)->getCalculatedValue())?null:$hojaActual->getCell("N".$indiceFila)->getCalculatedValue();
			$multiprecio = !empty($hojaActual->getCell("O".$indiceFila)->getCalculatedValue()) ? strtolower($hojaActual->getCell("O".$indiceFila)->getCalculatedValue()) : '';
			$multiprecio1_nombre = $hojaActual->getCell("P".$indiceFila)->getCalculatedValue();
			$multiprecio1_valor = floatval($hojaActual->getCell("Q".$indiceFila)->getCalculatedValue()) + 0;
			$multiprecio2_nombre = $hojaActual->getCell("R".$indiceFila)->getCalculatedValue();
			$multiprecio2_valor = floatval($hojaActual->getCell("S".$indiceFila)->getCalculatedValue()) + 0;
			$multiprecio3_nombre = $hojaActual->getCell("T".$indiceFila)->getCalculatedValue();
			$multiprecio3_valor = floatval($hojaActual->getCell("U".$indiceFila)->getCalculatedValue()) + 0;
			$multiprecio4_nombre = $hojaActual->getCell("V".$indiceFila)->getCalculatedValue();
			$multiprecio4_valor = floatval($hojaActual->getCell("W".$indiceFila)->getCalculatedValue()) + 0;
			$peso = floatval($hojaActual->getCell("X".$indiceFila)->getCalculatedValue()) + 0;

			if(empty($codigo_categoria) && empty($codigo_producto) && empty($nombre_producto) && empty($codigo_afectacion) && empty($id_unidad_medida) && empty($codigo_moneda) && empty($precio) && empty($stock_actual) && empty($stock_minimo) && empty($detalle) && empty($precio_compra) && empty($id_sucursal) && empty($multiprecio) && empty($multiprecio1_nombre) && empty($multiprecio1_valor) && empty($multiprecio2_nombre) && empty($multiprecio2_valor) && empty($multiprecio3_nombre) && empty($multiprecio3_valor) && empty($fecha_vencimiento) && empty($marca)) {
				break;
			}

			if(!empty($codigo_categoria) && !$this->esUnCodigoValido($codigo_categoria)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'En la línea N° '.($n + 1).', se ha ingresado un código de categoría inválido!, no debe contener símbolos especiales (valor encontrado: '.$codigo_categoria.')';
				return $resp;
			}

			if(!empty($codigo_producto) && !$this->esUnCodigoValido($codigo_producto)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'En la línea N° '.($n + 1).', se ha ingresado un código de producto inválido!, no debe contener símbolos especiales (valor encontrado: '.$codigo_producto.')';
				return $resp;
			}

			//echo "$codigo_categoria||$codigo_producto||$nombre_producto||$codigo_afectacion||$id_unidad_medida||$codigo_moneda||$precio||$stock_actual||$stock_minimo||$detalle||$precio_compra||$id_sucursal||$multiprecio||$multiprecio1_nombre||$multiprecio1_valor||$multiprecio2_nombre||$multiprecio2_valor||$multiprecio3_nombre||$multiprecio3_valor||$fecha_vencimiento||$marca";
			$idcategoria = null;
			if(!empty($codigo_categoria)) {
				$categoria = Categoria::findFirst(array("codigo = :codigo: and id_contribuyente = :id_contribuyente:", 'bind' => array('codigo' => $codigo_categoria, 'id_contribuyente' => $usuario->id_contribuyente)));
				if(!$categoria){
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error';
					$resp['mensaje'] = 'En la línea N° '.($n + 1).', se ha ingresado un código ('.$codigo_categoria.') de categoría que no existe actualmente!, primero debes registrar la categoría con el código ingresado';
					return $resp;
				}

				$idcategoria = $categoria->idcategoria;
			}

			$product_con_codigoactual = Producto::findFirst(array("id_contribuyente = :id_contribuyente: and UPPER(codigo) = :codigo: and idsucursal = :idsucursal: and estado = 'activo'", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'codigo' => $codigo_producto, 'idsucursal' => $idsucursal)));
			if($product_con_codigoactual) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'En la línea N° '.($n + 1).', en la columna CODIGO PRODUCTO, se ha ingresado un código que ya pertenece a otro producto registrado en el sistema, recuerda que cada código debe ser único por cada producto!';
				return $resp;
			}

			if(in_array($codigo_producto, $array_code_prod, true)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'El código de producto: '.$codigo_producto.' se encuentra repetido en el listado que deseas enviar. La línea donde se repite es la N° '.($n + 1).', en la columna CODIGO PRODUCTO, ';
				return $resp;
			}

			$array_code_prod[] = $codigo_producto;

			$tipoafectacionigv = SunatTipoafectacionigv::findFirst(array("id_tipoafectacionigv = :id_tipoafectacionigv:", 'bind' => array('id_tipoafectacionigv' => $codigo_afectacion)));
			if(!$tipoafectacionigv) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'En la línea N° '.($n + 1).', en la columna CODIGO - TIPO IGV, se ha ingresado un código inválido! (valor encontrado: '.$codigo_afectacion.')';
				return $resp;
			}
			
			$unidadmedida = SunatUnidadmedida::findFirst(array("idunidad = :idunidad:", 'bind' => array('idunidad' => $id_unidad_medida)));
			if(!$unidadmedida){
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'En la línea N° '.($n + 1).', en la columna CODIGO - UNIDAD MEDIDA, se ha ingresado un código inválido! Código Ingresado: '.$id_unidad_medida;
				return $resp;
			}

			$sql_moneda = SunatMoneda::findFirst(array("id_codigomoneda = :id_codigomoneda:", 'bind' => array('id_codigomoneda' => $codigo_moneda)));
			if(!$sql_moneda){
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'En la línea N° '.($n + 1).', en la columna MONEDA, se ha ingresado un código inválido!';
				return $resp;
			}

			$sucursal = Sucursal::findFirst(array("idsucursal = :idsucursal: and id_contribuyente = :id_contribuyente:", 'bind' => array('idsucursal' => $id_sucursal, 'id_contribuyente' => $usuario->id_contribuyente)));
			if(!$sucursal) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'En la línea N° '.($n + 1).', un código de sucursal inválido!. (valor recibido: '.$id_sucursal.')';
				return $resp;
			}

			if(empty($nombre_producto)) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'En la línea N° '.($n + 1).', en la columna NOMBRE, hemos detectado un producto que no lleva nombre, recuerda que todos los productos deben tener un nombre!';
				return $resp;
			}

			if($precio < 0) {
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = 'En la línea N° '.($n + 1).', en la columna PRECIO, se ha detectado un valor inválido ('.$precio.'), recuerda que cada producto debe tener un precio mayor a cero';
				return $resp;
			}

			if($precio_compra < 0) {
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

			$lista_precios = array();
			if($multiprecio == 'si') {
				if(!empty($multiprecio1_nombre) && !empty($multiprecio1_valor)) {
					$lista_precios[] = $multiprecio1_nombre.'|'.$multiprecio1_valor;
				}

				if(!empty($multiprecio2_nombre) && !empty($multiprecio2_valor)) {
					$lista_precios[] = $multiprecio2_nombre.'|'.$multiprecio2_valor;
				}

				if(!empty($multiprecio3_nombre) && !empty($multiprecio3_valor)) {
					$lista_precios[] = $multiprecio3_nombre.'|'.$multiprecio3_valor;
				}

				if(!empty($multiprecio4_nombre) && !empty($multiprecio4_valor)) {
					$lista_precios[] = $multiprecio4_nombre.'|'.$multiprecio4_valor;
				}
			}
			
			$array_nuevos_productos[] = array(
				"codigo_categoria"		=> $codigo_categoria,
				"id_categoria"			=> $idcategoria,
				"codigo_producto"		=> $codigo_producto,
				"nombre_producto"		=> $nombre_producto,
				"codigo_afectacion"		=> $codigo_afectacion,
				"id_unidad_medida"		=> $id_unidad_medida,
				"codigo_unidad_medida"	=> $unidadmedida->codigo,
				"codigo_moneda"			=> $codigo_moneda,
				"precio"				=> $precio,
				"stock_actual"			=> $stock_actual,
				"stock_minimo"			=> $stock_minimo,
				"detalle"				=> $detalle,
				"precio_compra"			=> $precio_compra,
				"id_sucursal"			=> $id_sucursal,
				"multiprecio"			=> $multiprecio,
				"multiprecio1_nombre"	=> $multiprecio1_nombre,
				"multiprecio1_valor"	=> $multiprecio1_valor,
				"multiprecio2_nombre"	=> $multiprecio2_nombre,
				"multiprecio2_valor"	=> $multiprecio2_valor,
				"multiprecio3_nombre"	=> $multiprecio3_nombre,
				"multiprecio3_valor"	=> $multiprecio3_valor,
				"fecha_vencimiento"		=> $fecha_vencimiento,
				"marca"					=> $marca,
				"lista_precios"			=> $lista_precios,
				"peso"					=> $peso
			);
		}
		
		$this->db->begin();
		$fecha_registro = date('Y-m-d H:i:s');
		$n = -1;
		$num_decimales = 2;
		if($contribuyente->num_decimales > 2) {
			$num_decimales = $contribuyente->num_decimales;
		}
		$num_productos = 0;
		$num_servicios = 0;
		$detalle_movimiento = array();
		foreach($array_nuevos_productos as $item) {
			$item = (object)$item;
			$n++;
			$product = new Producto();
			$product->fecha_registro =  $fecha_registro;
			$product->id_contribuyente = $usuario->id_contribuyente;
			$product->codigo = $item->codigo_producto;
			$product->id_unidad_medida = $item->id_unidad_medida;
			$product->id_tipoafectacionigv = $item->codigo_afectacion;
			$product->nombre = trim($item->nombre_producto);
			$product->id_cod_moneda = $item->codigo_moneda;

			if($item->codigo_afectacion == 10) {
				$product->valor_sin_igv = round($item->precio/1.18, $num_decimales); //$valor_sin_igv
				$product->valor_con_igv = $item->precio;
			} else {
				$product->valor_sin_igv = $item->precio; //$valor_sin_igv
				$product->valor_con_igv = round($item->precio*1.18, $num_decimales);
			}

			if(intval($item->id_unidad_medida) != 20) {
				$resp_porcentajes = $herramientas->get_porcentajes_ganancia($item->precio_compra, $item->precio, 0);
				$product->porcentaje_pventa = $resp_porcentajes['porcentaje_maximo'];
				$product->porcentaje_pminimo = $resp_porcentajes['porcentaje_minimo'];
			}
			
			$product->nota = $item->detalle;
			$product->stock = $item->stock_actual;
			$product->stock_minimo = $item->stock_minimo;
			$product->estado = 'activo';
			$product->idsucursal = $item->id_sucursal;
			$product->precio_compra = $item->precio_compra;
			$product->tipo_cambio_sunat = 3.981;
			$product->multi_precio = $item->multiprecio;
			$product->marca = empty($item->marca)?null:$item->marca;
			$product->fecha_vencimiento = (!empty($item->fecha_vencimiento))?date("Y-m-d", strtotime($item->fecha_vencimiento)):null;
			$product->id_categoria = $item->id_categoria;
			$product->peso = $item->peso;

			try {
				if(!$product->save()) {
					$this->db->rollback();
					$msg = '';
					foreach ($product->getMessages() as $message) {
						$msg = $msg.$message."</br>\n";
					}
					$resp['respuesta'] = 'error';
					$resp['titulo'] = 'Error en BD';
					$resp['mensaje'] = 'Obtenemos el siguiente error: '.$msg.'<br /><strong>NO se ha realizado ningún cambio en la base de datos!</strong> En la línea N° '.($n + 1).', '.$msg;
					return $resp;
				}
			} catch (Exception $e) {
                $this->saveLogger($e);
				$this->db->rollback();
				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error en BD';
				$resp['mensaje'] = 'Obtenemos el siguiente error: '.$e->getMessage().'<br /><strong>NO se ha realizado ningún cambio en la base de datos!</strong> En la línea N° '.($n + 1).', '.$e->getMessage();
				return $resp;
			}

			if($item->codigo_unidad_medida == 'ZZ') {
				$num_servicios++;
			} else {
				$num_productos++;
			}
			
			if($item->codigo_unidad_medida != 'ZZ') {
				if($item->stock_actual > 0) {
					$detalle_movimiento[] = array(
						'cantidad'          => $item->stock_actual,
						'costo_unitario'    => $product->precio_compra,
						'tipo_kardex'       => 'inventario_inicial',
						'id_codigomoneda'	=> $product->id_cod_moneda,

						'o_id_u_medida'     => $product->id_unidad_medida,
						'o_u_medida'        => $item->codigo_unidad_medida,
						'o_precio'          => $product->valor_con_igv,
						'o_id_afectigv'     => $product->id_tipoafectacionigv,
						'o_tipo_unidad'     => 'UND',
						'o_id_presentacion' => '',
						'o_cod_prod'        => $product->codigo,
						'o_id_prod'         => $product->idproducto,
						'o_nom_prod'        => $product->nombre
					);
				}
			}

			if($item->multiprecio == 'si') {
				foreach($item->lista_precios as $item_precio) {
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
		}

		if(count($detalle_movimiento) > 0) {
			$movimiento_almacen = new ProductomovimientosController;
			$data['id_contribuyente']           = $product->id_contribuyente;
			$data['id_sucursal']                = $product->idsucursal;
			$data['id_usuario']                 = $usuario->idusuario;
			$data['tipo_envio_sunat']           = $contribuyente->tipo_envio_sunat;
			$data['id_tipo_movimiento']         = 'in'; //ingreso
			$data['nota']                       = 'Importación de productos';
			$data['detalle_movimiento']			= $detalle_movimiento;
	
			$resp_reg_movimiento = $movimiento_almacen->registrar_movimiento($usuario, $data);
			if($resp_reg_movimiento['respuesta'] == 'error') {
				$this->db->rollback();
				return $resp_reg_movimiento;
			}
		}

		unlink($rutaArchivo);

		$log = new Log();
		$log->tabla = "producto";
		$log->accion = "importacion_productos";

		if($num_productos > 0 && $num_servicios > 0) {
			$log->descripcion = "Se importaron un total de $num_productos productos y $num_servicios servicios.";
		} else if($num_productos > 0 && $num_servicios <= 0) {
			if($num_productos == 1) {
				$log->descripcion = "Se ha importado un producto.";
			} else {
				$log->descripcion = "Se ha importado un total de $num_productos productos.";
			}
		} else if($num_productos <= 0 && $num_servicios > 0) {
			if($num_servicios == 1) {
				$log->descripcion = "Se ha importado un servicio.";
			} else {
				$log->descripcion = "Se ha importado un total de $num_servicios servicios.";
			}
		} else {
			$log->descripcion = "Se importaron un total de ".count($array_nuevos_productos)." productos";
		}
		
		$log->fecha_registro = date('Y-m-d H:i:s');
		$log->idusuario = $usuario->idusuario;
		$log->id_contribuyente = $usuario->id_contribuyente;
		$log->num_total_importados = count($array_nuevos_productos);
		$log->tipo_envio_sunat = $contribuyente->tipo_envio_sunat;
		$resp_log = $log->save();
		
		$this->db->commit();
		//$this->db->rollback();
		$resp['respuesta'] = 'ok';
		$resp['titulo'] = 'Genial!';
		$resp['mensaje'] = 'Se importaron '.count($array_nuevos_productos).' productos correctamente!';
		return $resp;
	}

	public function registrar_ingreso_kardex($data) {
		$tipo_kardex = 'entrada';
		$stock = 0;
		$costo_unitario_promedio = 0;
		$costo_total = 0;
		$stock_valorizado = 0;
	
		if($tipo_kardex == 'entrada') {
			$stock = empty($data['cantidad_entrada'])?0:$data['cantidad_entrada'];
		} elseif ($tipo_kardex == 'salida') {
			$stock = empty($data['cantidad_salida'])?0:$data['cantidad_salida'];
			$stock = (-1)*$stock;
		}

		$costo_unitario = empty($data['costo_unitario'])?0:$data['costo_unitario']; //Siempre ingresaremos un costo unitario con IGV
		$costo_total = $stock*$costo_unitario;
		$stock_valorizado = $stock*$costo_unitario;
		//$costo_unitario_promedio = round($costo_total/$stock);
		if($stock == 0) {
			$costo_unitario_promedio = 0;
		} else {
			$costo_unitario_promedio = round($costo_total/$stock, 2);
		}

		$new_kardex = new Kardex();
		$new_kardex->id_contribuyente = $data['id_contribuyente'];
		$new_kardex->idsucursal = $data['idsucursal'];
		$new_kardex->idproducto = $data['idproducto'];
		$new_kardex->idusuario = $data['idusuario'];
		$new_kardex->tipo_envio_sunat = $data['tipo_envio_sunat'];
		$new_kardex->tipo_kardex = $data['tipo_kardex'];
		$new_kardex->estado_kardex = 'activo';
		$new_kardex->cantidad_entrada = empty($data['cantidad_entrada'])?0:$data['cantidad_entrada'];
		$new_kardex->cantidad_salida = empty($data['cantidad_salida'])?0:$data['cantidad_salida'];
		$new_kardex->stock = $stock;
		$new_kardex->costo_unitario = $costo_unitario;
		$new_kardex->costo_unitario_promedio = $costo_unitario_promedio;
		$new_kardex->stock_valorizado = $stock_valorizado;
		$new_kardex->costo_total = $costo_total;
		$new_kardex->detalle = empty($data['detalle'])?'':$data['detalle'];
		$new_kardex->fecha_registro = empty($data['fecha_registro'])?date('Y-m-d H:i:s'):$data['fecha_registro'];;
		$new_kardex->docref_id_contribuyente = empty($data['docref_id_contribuyente'])?null:$data['docref_id_contribuyente'];
		$new_kardex->docref_id_tipodoc_electronico = empty($data['docref_id_tipodoc_electronico'])?null:$data['docref_id_tipodoc_electronico'];
		$new_kardex->docref_serie_comprobante = empty($data['docref_serie_comprobante'])?null:$data['docref_serie_comprobante'];
		$new_kardex->docref_numero_comprobante = empty($data['docref_numero_comprobante'])?null:$data['docref_numero_comprobante'];
		$new_kardex->docref_tipo_envio_sunat = empty($data['docref_tipo_envio_sunat'])?null:$data['docref_tipo_envio_sunat'];
		$new_kardex->id_compra = empty($data['id_compra'])?null:$data['id_compra'];

		try {
			if(!$new_kardex->save()) {
				$msg = '';
				foreach ($new_kardex->getMessages() as $message) {
					$msg = $msg.$message."</br>\n";
				}

				$resp['respuesta'] = 'error';
				$resp['titulo'] = 'Error';
				$resp['mensaje'] = $msg;
				return $resp;
			}
		} catch (Exception $e) {
            $this->saveLogger($e);
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = $e->getMessage();
			return $resp;
		}

		$resp['respuesta'] = 'ok';
		$resp['titulo'] = 'Kardex Generado';
		$resp['mensaje'] = 'Se ha registrado correctamente el kardex';
		return $resp;
	}
}
?>