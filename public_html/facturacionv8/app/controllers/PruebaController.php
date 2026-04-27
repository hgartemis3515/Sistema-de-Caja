<?php
require $_SERVER["DOCUMENT_ROOT"]."/facturacionv8/apis/snappypdf/vendor/autoload.php";
use Knp\Snappy\Pdf;
include($_SERVER["DOCUMENT_ROOT"]."/facturacionv8/apis/apicaptcha/anticaptcha.php");
include($_SERVER["DOCUMENT_ROOT"]."/facturacionv8/apis/apicaptcha/nocaptchaproxyless.php");
class PruebaController extends ControllerBase
{
	
	public function get_array_data() {
		
	}

	public function prueba_rutaAction(){
		$this->view->disable();
		echo "prueba de ruta";
		exit();
	}

	public function pruebaRuta2Action(){
		$this->view->disable();
		echo "prueba de ruta 2";
		exit();
	}

	public function getHtmlPlantillaA44Action() {
		$this->view->disable();
		echo "plantilla A4";
		exit();
	}

	public function getHtmlPlantillaA444Action() {
		$this->view->disable();
		echo "plantilla A444";
		exit();
	}

	public function emparejar_stocks_desde_kardexAction($id_contribuyente = 0) {
		$id_contribuyente = intval($id_contribuyente);
		if($id_contribuyente <= 0) {
			echo "no válido";
			exit();
		}

		$query = "SELECT DISTINCT idproducto FROM `kardex` where id_contribuyente = $id_contribuyente and tipo_envio_sunat = 'produccion'";
		$sentencia = $this->db->prepare($query);
		$sentencia->execute();
		$lista_errores = array();
		$lista_ok = array();
		while ($fila = $sentencia->fetch()) {
			$fila = (object)$fila;
			$producto = Producto::findFirst(array("idproducto = :idproducto: and id_contribuyente = :id_contribuyente:", 'bind' => array('idproducto' => $fila->idproducto, 'id_contribuyente' => $id_contribuyente)));
			if($producto) {
				$item_kardex = Kardex::findFirst(array('idproducto = :idproducto: and tipo_envio_sunat = :tipo_envio_sunat: and id_contribuyente = :id_contribuyente:', 'bind' => array('idproducto' => $fila->idproducto, 'tipo_envio_sunat' => 'produccion', 'id_contribuyente' => $id_contribuyente), "order" => "id_kardex DESC"));
				
				if($item_kardex) { 
					if($item_kardex->stock != $producto->stock) {
						$producto->stock = $item_kardex->stock;
						if(!$producto->save()) {
							$msg = '';
							$lista_errores[] = $fila->idproducto;
						} else {
							$lista_ok[] = $fila->idproducto;
						}
					}
				} else {
					
				}
			}
		}

		echo "terminó||=> ".json_encode($lista_errores).' <=||'.json_encode($lista_ok);
		exit();
	}

	public function emparejar_stocksAction() {

		echo "-";
		exit();
		
		$query = "SELECT DISTINCT detalle.id_producto, doc.id_contribuyente, doc.id_tipodocumento, doc.numero_comprobante, doc.modalidad FROM `doc_no_oficial` doc INNER JOIN detalle_docnooficial detalle ON (doc.id_contribuyente = detalle.id_contribuyente and doc.id_tipodocumento = detalle.id_tipodocumento and doc.numero_comprobante = detalle.numero_comprobante and doc.modalidad = detalle.modalidad) where doc.id_tipodocumento = '77' and doc.modalidad = 'produccion' and doc.total = 0 and doc.tipo='traslado' and doc.fecha_comprobante > '2022-06-28' and detalle.unidad_medida <> 'ZZ'";
		$sentencia = $this->db->prepare($query);
		$sentencia->execute();
		$lista_errores = array();
		while ($fila = $sentencia->fetch()) {
			$fila = (object)$fila;
			$id_producto = $fila->id_producto;
			$id_contribuyente = $fila->id_contribuyente;
			$producto = Producto::findFirst(array("idproducto = :idproducto: and id_contribuyente = :id_contribuyente:", 'bind' => array('idproducto' => $id_producto, 'id_contribuyente' => $id_contribuyente)));

			if($producto) {
				$item_kardex = Kardex::findFirst(array('idproducto = :idproducto: and tipo_envio_sunat = :tipo_envio_sunat: and id_contribuyente = :id_contribuyente:', 'bind' => array('idproducto' => $id_producto, 'tipo_envio_sunat' => $fila->modalidad, 'id_contribuyente' => $id_contribuyente), "order" => "id_kardex DESC"));
				
				if($item_kardex) {
					if($item_kardex->stock != $producto->stock) {
						$producto->stock = $item_kardex->stock;
						if(!$producto->save()) {
							$msg = '';
							$lista_errores[] = $id_producto;
						}
					}
				} else {
					
				}
			}
		}

		echo "terminó||".json_encode($lista_errores);
		exit();
	}

	public function copiarProductosAction() {
		//ini_set('memory_limit','350M');
        //ini_set('max_execution_time', 600);

		//INICIO: CÓDIGO PARA COPIAR PRODUCTO DE UNA SUCURSAL A OTRA CON TODO Y PRESENTACIONES
		$this->view->disable();
		exit();
		
		/* DATOS DE LA CUENTA ORIGEN */
		$id_contribuyente_origen = 7372;
		$id_sucursal_origen = 7366;
		/***************************/

		/* DATOS DE LA CUENTA DESTINO */
		$id_contribuyente_destino = 8283;
		$id_sucursal_destino = 8245;
		/*****************************/
		
		$lista_productos_origen = Producto::find(array("id_contribuyente = :id_contribuyente: and idsucursal = :idsucursal: and estado = 'activo'", 'bind' => array('id_contribuyente' => $id_contribuyente_origen, 'idsucursal' => $id_sucursal_origen)));

		$this->db->begin();
		$n_productos = 0;
		$n_presentaciones = 0;
		$n_productos_copiados = 0;
		$n_presentaciones_copiadas = 0;
		$n_multiprecio = 0;

		foreach($lista_productos_origen as $producto_origen) {
		    $producto_destino = Producto::findFirst(array("id_contribuyente = :id_contribuyente: and idsucursal = :idsucursal: and codigo = :codigo: and estado = 'activo'", 'bind' => array('id_contribuyente' => $id_contribuyente_destino, 'idsucursal' => $id_sucursal_destino, 'codigo' => $producto_origen->codigo)));

			$n_productos++;

			//extraemos el id de la categoría
			$id_categoria_destino = null;
			if(!empty($producto_origen->id_categoria)) {
				$categoria_origen = Categoria::findFirst(array("id_contribuyente = :id_contribuyente: and idcategoria = :idcategoria:", 'bind' => array('idcategoria' => $producto_origen->id_categoria, 'id_contribuyente' => $id_contribuyente_origen)));
				if($categoria_origen) {
					$categoria_destino = Categoria::findFirst(array("id_contribuyente = :id_contribuyente: and codigo = :codigo:", 'bind' => array('id_contribuyente' => $id_contribuyente_destino, 'codigo' => $categoria_origen->codigo)));
					if(!$categoria_destino) {
						$categoria_destino = new Categoria();
						$categoria_destino->codigo = $categoria_origen->codigo;
						$categoria_destino->nombre = $categoria_origen->nombre;
						$categoria_destino->descripcion = $categoria_origen->descripcion;
						$categoria_destino->id_contribuyente = $id_contribuyente_destino;
						$categoria_destino->id_codigoproducto = $categoria_origen->id_codigoproducto;
						$categoria_destino->fecha_registro = date('Y-m-d H:i:s');
						$categoria_destino->estado = $categoria_origen->estado;
						$categoria_destino->codigo_cuenta_contable = $categoria_origen->codigo_cuenta_contable;
						$categoria_destino->codigo_centro_costo = $categoria_origen->codigo_centro_costo;
						$categoria_destino->codigo_presupuesto = $categoria_origen->codigo_presupuesto;
						if(!$categoria_destino->save()) {
							$this->db->rollback();
							$msg = '';
							foreach ($categoria_destino->getMessages() as $message) {
								$msg = $msg.$message."</br>\n";
							}
		
							echo $msg;
							exit();
						}
					}
					
					$id_categoria_destino = $categoria_destino->idcategoria;
				}
			}

		    if(!$producto_destino) {
				
				$producto_destino = new Producto();
				$producto_destino->id_contribuyente = $id_contribuyente_destino;
				$producto_destino->idsucursal = $id_sucursal_destino;

				$producto_destino->codigo = $producto_origen->codigo;
				$producto_destino->id_unidad_medida = $producto_origen->id_unidad_medida;
				$producto_destino->id_cod_detraccion = $producto_origen->id_cod_detraccion;
				$producto_destino->id_tipoafectacionigv = $producto_origen->id_tipoafectacionigv;
				$producto_destino->id_categoria = $id_categoria_destino; //observación, debe crearse la categoría en el destino
				$producto_destino->nombre = $producto_origen->nombre;
				$producto_destino->id_cod_moneda = $producto_origen->id_cod_moneda;
				$producto_destino->precio_compra = $producto_origen->precio_compra;
				$producto_destino->valor_sin_igv = $producto_origen->valor_sin_igv;
				$producto_destino->valor_con_igv = $producto_origen->valor_con_igv;
				$producto_destino->nota = $producto_origen->nota;
				$producto_destino->foto = $producto_origen->foto;
				$producto_destino->stock = 0;//$producto_origen->stock;
				$producto_destino->stock_minimo = $producto_origen->stock_minimo;
				$producto_destino->fecha_registro = date('Y-m-d H:i:s');
				$producto_destino->precio_venta_minimo = $producto_origen->precio_venta_minimo;
				$producto_destino->estado = $producto_origen->estado;
				$producto_destino->tipo_cambio_sunat = $producto_origen->tipo_cambio_sunat;
				$producto_destino->multi_precio = $producto_origen->multi_precio; //si, no
				$producto_destino->fecha_vencimiento = $producto_origen->fecha_vencimiento;
				$producto_destino->marca = $producto_origen->marca;
				$producto_destino->afecto_icbper = $producto_origen->afecto_icbper;
				$producto_destino->codigos_presentaciones = $producto_origen->codigos_presentaciones; //verificar los códigos de las presentaciones.
				
				if(!$producto_destino->save()) {
					$this->db->rollback();
					$msg = '';
					foreach ($producto_destino->getMessages() as $message) {
						$msg = $msg.$message."</br>\n";
					}

					echo $msg;
					exit();
				}

				$n_productos_copiados++;
			} else {
				/*
				if($producto_destino->estado == 'inactivo') {
					$producto_destino->codigo = $producto_origen->codigo;
					$producto_destino->id_unidad_medida = $producto_origen->id_unidad_medida;
					$producto_destino->id_cod_detraccion = $producto_origen->id_cod_detraccion;
					$producto_destino->id_tipoafectacionigv = $producto_origen->id_tipoafectacionigv;
					$producto_destino->id_categoria = $id_categoria_destino; //observación, debe crearse la categoría en el destino
					$producto_destino->nombre = $producto_origen->nombre;
					$producto_destino->id_cod_moneda = $producto_origen->id_cod_moneda;
					$producto_destino->precio_compra = $producto_origen->precio_compra;
					$producto_destino->valor_sin_igv = $producto_origen->valor_sin_igv;
					$producto_destino->valor_con_igv = $producto_origen->valor_con_igv;
					$producto_destino->nota = $producto_origen->nota;
					$producto_destino->foto = $producto_origen->foto;
					$producto_destino->stock = 0;//$producto_origen->stock;
					$producto_destino->stock_minimo = $producto_origen->stock_minimo;
					$producto_destino->fecha_registro = date('Y-m-d H:i:s');
					$producto_destino->precio_venta_minimo = $producto_origen->precio_venta_minimo;
					$producto_destino->estado = $producto_origen->estado;
					$producto_destino->tipo_cambio_sunat = $producto_origen->tipo_cambio_sunat;
					$producto_destino->multi_precio = $producto_origen->multi_precio; //si, no
					$producto_destino->fecha_vencimiento = $producto_origen->fecha_vencimiento;
					$producto_destino->marca = $producto_origen->marca;
					$producto_destino->afecto_icbper = $producto_origen->afecto_icbper;
					$producto_destino->codigos_presentaciones = $producto_origen->codigos_presentaciones; //verificar los códigos de las presentaciones.
					
					if(!$producto_destino->save()) {
						$this->db->rollback();
						$msg = '';
						foreach ($producto_destino->getMessages() as $message) {
							$msg = $msg.$message."</br>\n";
						}
	
						echo $msg;
						exit();
					}
	
					$n_productos_copiados++;
				}
				*/
			}

			$id_producto_destino = $producto_destino->idproducto;

			if(!empty($producto_origen->codigos_presentaciones)) {
				$bd_lista_presentaciones_origen = ProductoPresentacion::find(array("idproducto = :idproducto:", 'bind' => array('idproducto' => $producto_origen->idproducto)));

				foreach($bd_lista_presentaciones_origen as $presentacion_origen) {
					$n_presentaciones++;

					$presentacion_destino = ProductoPresentacion::findFirst(array("codigo = :codigo: and idunidad = :idunidad: and idproducto = :idproducto:", 'bind' => array('codigo' => $presentacion_origen->codigo, 'idunidad' => $presentacion_origen->idunidad, 'idproducto' => $id_producto_destino)));
	
					if(!$presentacion_destino) {
						$presentacion_destino = new ProductoPresentacion();
						
						$presentacion_destino->idproducto = $id_producto_destino;
						$presentacion_destino->nombre = $presentacion_origen->nombre;
						$presentacion_destino->codigo = $presentacion_origen->codigo;
						$presentacion_destino->idunidad = $presentacion_origen->idunidad;
						$presentacion_destino->idunidad_base = $presentacion_origen->idunidad_base;
						$presentacion_destino->precio_con_igv = $presentacion_origen->precio_con_igv;
						$presentacion_destino->precio_sin_igv = $presentacion_origen->precio_sin_igv;
						$presentacion_destino->cantidad_und_base = $presentacion_origen->cantidad_und_base;
						$presentacion_destino->estado = $presentacion_origen->estado;
						$presentacion_destino->fecha_registro = date('Y-m-d H:i:s');
		
						if(!$presentacion_destino->save()) {
							$this->db->rollback();
							$msg = '';
							foreach ($presentacion_destino->getMessages() as $message) {
								$msg = $msg.$message."</br>\n";
							}
							echo $msg;
							exit();
						}

						$n_presentaciones_copiadas++;
					}
				}
			}

			if($producto_origen->multi_precio == 'si') {
				$multiprecios_origen = ProductoListaprecio::find("idproducto=".$producto_origen->idproducto);
				foreach($multiprecios_origen as $multiprecio_origen) {
					$multiprecio_destino = ProductoListaprecio::findFirst(array("idproducto = :idproducto: and nombre = :nombre: and precio = :precio:", 'bind' => array('idproducto' => $id_producto_destino, 'nombre' => $multiprecio_origen->nombre, 'precio' => $multiprecio_origen->precio)));
					if(!$multiprecio_destino) {
						$multiprecio_destino = new ProductoListaprecio();
						$multiprecio_destino->idproducto = $id_producto_destino;
						$multiprecio_destino->nombre = $multiprecio_origen->nombre;
						$multiprecio_destino->precio = $multiprecio_origen->precio;
						$multiprecio_destino->estado = $multiprecio_origen->estado;
	
						if(!$multiprecio_destino->save()) {
							$this->db->rollback();
							$msg = '';
							foreach ($multiprecio_destino->getMessages() as $message) {
								$msg = $msg.$message."</br>\n";
							}
							echo $msg;
							exit();
						}

						$n_multiprecio++;
					}
				}
			}
		}

		$this->db->commit();
		echo "
		id_contribuyente_origen 	= $id_contribuyente_origen \n <br />
		id_sucursal_origen 			= $id_sucursal_origen \n\n <br /><br />

		id_contribuyente_destino 	= $id_contribuyente_destino \n <br />
		id_sucursal_destino 		= $id_sucursal_destino \n <br /><br />

		n_productos 				= $n_productos \n <br />
		n_productos_copiados 		= $n_productos_copiados \n <br />

		n_presentaciones 			= $n_presentaciones \n <br />
		n_presentaciones_copiadas 	= $n_presentaciones_copiadas \n <br /><br />
		n_multiprecio 				= $n_multiprecio \n <br />
		";
		exit();
		//FIN: CÓDIGO PARA COPIAR PRODUCTO DE UNA SUCURSAL A OTRA CON TODO Y PRESENTACIONES así como multiprecios
	}

	public function cleanCurrency($value) {
		// Eliminar símbolos de moneda y otros caracteres no deseados
		$number = preg_replace('/[^0-9\.\-]/', '', $value);
	
		// Convertir a número flotante si tiene decimales, o entero si no los tiene
		if (strpos($number, '.') !== false) {
			return (float)$number;
		} else {
			return (int)$number;
		}
	}

	public function parametrosAction($id, $nombre) {
		$this->view->disable();
		echo "hola => $id, $nombre";
		exit();
	}

    public function indexAction() {
		$this->view->disable();
		echo "hola 5 => ".custom_money_format(789);
		exit();

		$texto = "  AR G- 78 9 45 6";
		echo preg_replace('/\s+/', '', $texto);
		exit();

		echo $this->cleanCurrency('S/. 1,000.00').'||'.$this->cleanCurrency('S/.1,000.00').'||'.$this->cleanCurrency('S/. 1.000,36');
		exit();



		$idusuario = 1;
		$usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));

		$gestion_usuarios = new GestionuserController;
		$resp = $gestion_usuarios->verificar_permisos($usuario, 'permisos_para_registros', 'opt_registro_documentos', 'anulacion_nota_venta');
		var_dump($resp);
		exit();


		$resp = SunatCodigotipopercepcion::find();
		echo json_encode($resp);
		exit();
	}

	public function limpia_espacios($cadena)
	{
		$cadena = trim($cadena);
		$cadena = preg_replace('[\s+]', "", $cadena);
		$cadena2 = '';
		for ($i = 0; $i < strlen($cadena); $i++) {
			if (is_numeric($cadena[$i])) {
				$cadena2 .= $cadena[$i];
			}
		}
		return $cadena2;
	}

	public function pruebaPermisosUsuarioAction() {
		$idusuario = 1;
		$usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            
		if(!$usuario) {
			echo "no existe";
			exit();
		}

		$gestion_usuarios = new GestionuserController;
		$resp = $gestion_usuarios->verificar_permisos($usuario, 'permisos_otros', 'opt_otros_costocompra');
		echo $resp;
		exit();
	}

	public function validar_stockAction() {
		$id_contribuyente = 4084;
		$id_sucursal = 3947;
		$productos = Producto::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo' and idsucursal = :idsucursal:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'idsucursal' => $id_sucursal)));

	    foreach($productos as $producto) {
	        $producto_save = Producto::findFirst(array("idproducto = :idproducto:", 'bind' => array('idproducto' => $producto->idproducto)));
	        $save_kardex = Kardex::findFirst(array("idproducto = :idproducto:", 'bind' => array('idproducto' => $producto->idproducto), "order" => "id_kardex DESC"));
	        if($save_kardex) {
	            if($save_kardex->stock != $producto_save->stock) {
    	            $producto_save->stock = $save_kardex->stock;
    	            if(!$producto_save->save()) {
            			$msg = '';
            			foreach ($producto_save->getMessages() as $message) {
            				$msg = $msg.$message."</br>\n";
            			}
            
            			$resp['respuesta'] = 'error';
            			$resp['titulo'] = 'Error';
            			$resp['mensaje'] = $msg;
            			echo json_encode($resp);
            			exit();
            		}
    	        }
	        }
	    }
	    
	    echo "Se actualizaron los stocks";
	    exit();
	}
}