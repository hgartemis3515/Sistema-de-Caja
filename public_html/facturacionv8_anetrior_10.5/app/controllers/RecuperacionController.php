<?php
class RecuperacionController extends ControllerBase
{
    public function indexAction() {
 
		exit();
		$this->leer_xml_restaurar_guias();

		exit();
		$productos = Producto::find(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => 2153)));
		foreach($productos as $producto) {
			$new_producto = new Producto();
			$new_producto->id_contribuyente = 2391;
			$new_producto->idsucursal = 2199;
			$new_producto->codigo = $producto->codigo;
			$new_producto->id_unidad_medida = $producto->id_unidad_medida;
			$new_producto->id_cod_detraccion = $producto->id_cod_detraccion;
			$new_producto->id_tipoafectacionigv = $producto->id_tipoafectacionigv;
			$new_producto->id_categoria = $producto->id_categoria;
			$new_producto->nombre = $producto->nombre;
			$new_producto->id_cod_moneda = $producto->id_cod_moneda;
			$new_producto->precio_compra = $producto->precio_compra;
			$new_producto->valor_sin_igv = $producto->valor_sin_igv;
			$new_producto->valor_con_igv = $producto->valor_con_igv;
			$new_producto->nota = $producto->nota;
			$new_producto->foto = $producto->foto;
			$new_producto->stock = 0;
			$new_producto->stock_minimo = $producto->stock_minimo;
			$new_producto->fecha_registro = $producto->fecha_registro;
			$new_producto->precio_venta_minimo = $producto->precio_venta_minimo;
			$new_producto->estado = $producto->estado;
			$new_producto->tipo_cambio_sunat = $producto->tipo_cambio_sunat;
			$new_producto->multi_precio = $producto->multi_precio;

			$resp = $new_producto->save();
		}

		echo "si llega";
		exit();
	}

	public function leer_xml_restaurar_guias() {
		$docxml = new DOMDocument();
		$ruta_base = "/home/humbertoguadalup/.files_contribuyente";

		$query = "select detalle_doc.id_contribuyente, detalle_doc.id_tipodoc_electronico, detalle_doc.serie_comprobante, detalle_doc.numero_comprobante, detalle_doc.tipo_envio_sunat, doc_electronico.name_xml, doc_electronico.name_xml_zip FROM detalle_doc INNER JOIN doc_electronico on (detalle_doc.id_contribuyente = doc_electronico.id_contribuyente and detalle_doc.id_tipodoc_electronico = doc_electronico.id_tipodoc_electronico and detalle_doc.serie_comprobante = doc_electronico.serie_comprobante and detalle_doc.numero_comprobante = doc_electronico.numero_comprobante and detalle_doc.tipo_envio_sunat = doc_electronico.tipo_envio_sunat) where detalle_doc.id_tipodoc_electronico = '09' and detalle_doc.tipo_envio_sunat = 'produccion' and detalle_doc.iddetalle >= 589571 and detalle_doc.iddetalle <= 589651 GROUP BY detalle_doc.id_contribuyente, detalle_doc.id_tipodoc_electronico, detalle_doc.serie_comprobante, detalle_doc.numero_comprobante, detalle_doc.tipo_envio_sunat";

		$this->db->begin();

		try {
			$sentencia = $this->db->prepare($query);
			$sentencia->execute();
		} catch (Exception $e) {
            $this->saveLogger($e);
			echo 'Excepción capturada: (leer_xml_restaurar_guias) ',  $e->getMessage(), "\n";
			exit();
		}

		$sin_xml = array();
		$n = 0;
		while ($fila = $sentencia->fetch()) {
			$n++;
			
			$fila = (object)$fila;
			$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $fila->id_contribuyente)));
			if(!$contribuyente) {
				$this->db->rollback();
				echo "no existe el contribuyente!";
				exit();
			}

			$ruta_base_xml = $ruta_base.'/'.$contribuyente->ruc.'/'.$contribuyente->ruta_xml_produccion.'/';

			if($fila->id_tipodoc_electronico == '01' || $fila->id_tipodoc_electronico == '03') {
				$carpeta_contenedora = $ruta_base_xml.'facturas_boletas/';
			} else if($fila->id_tipodoc_electronico == '07') {
				$carpeta_contenedora = $ruta_base_xml.'nota_credito/';
			} else if($fila->id_tipodoc_electronico == '08') {
				$carpeta_contenedora = $ruta_base_xml.'nota_debito/';
			} else if($fila->id_tipodoc_electronico == '09') {
				$carpeta_contenedora = $ruta_base_xml.'guias_remision/';
			} else {
				$this->db->rollback();
				echo "No se reconoce el tipo de documento!";
				exit();
			}
			
			$zip = new ZipArchive;
			if ($zip->open($carpeta_contenedora.$fila->name_xml_zip) === TRUE) {
				if($zip->extractTo($carpeta_contenedora, $fila->name_xml) === true) {

				} else {
					$this->db->rollback();
					echo "no se puede extraer uno de los zip";
					exit();
				}

				$zip->close();
			}

			$ruta_xml = $carpeta_contenedora.$fila->name_xml;
			
			if(!@$docxml->load($ruta_xml)) {
				$sin_xml[] = array(
					'id_contribuyente' => $fila->id_contribuyente,
					'id_tipodoc_electronico' => $fila->id_tipodoc_electronico,
					'serie_comprobante'	=> $fila->serie_comprobante,
					'numero_comprobante' => $fila->numero_comprobante,
					'tipo_envio_sunat' => $fila->tipo_envio_sunat
				);
				continue;
			}

			$items_invoice = $docxml->getElementsByTagName('DespatchLine');
			$_items = array();
			foreach($items_invoice as $item) {
				$cantidad = $item->getElementsByTagName('DeliveredQuantity')[0]->nodeValue;
				$name = $item->getElementsByTagName('Name')[0]->nodeValue;
				$num_item = $item->getElementsByTagName('ID')[0]->nodeValue;
				
				$_items[] = array(
					'id_contribuyente' => $fila->id_contribuyente,
					'id_tipodoc_electronico' => $fila->id_tipodoc_electronico,
					'serie_comprobante'	=> $fila->serie_comprobante,
					'numero_comprobante' => $fila->numero_comprobante,
					'tipo_envio_sunat' => $fila->tipo_envio_sunat,
					'cantidad'     => $cantidad,
					'name'		=> $name,
					'num_item' => $num_item
				);
			}
			
			$num_decimales = $contribuyente->num_decimales;
			
			foreach($_items as $item_xml) {
				$item_xml = (object)$item_xml;
				$detalle_oficial = DetalleDoc::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat: and item = :item:", 'bind' => array('id_contribuyente' => $item_xml->id_contribuyente, 'id_tipodoc_electronico' => $item_xml->id_tipodoc_electronico, 'serie_comprobante' => $item_xml->serie_comprobante, 'numero_comprobante' => $item_xml->numero_comprobante, 'tipo_envio_sunat' => $item_xml->tipo_envio_sunat, 'item' => $item_xml->num_item)));

				if(!$detalle_oficial) {
					echo 'no existe<br />';
				} else {
					$detalle_oficial->cantidad = round(floatval($item_xml->cantidad), $num_decimales) + 0;

					
					if(!$detalle_oficial->save()) {
						$this->db->rollback();
						$msg = '';
						foreach ($detalle_oficial->getMessages() as $message) {
							$msg = $msg.$message."</br>\n";
						}
						echo $msg;
					}
					
				}

				
			}

			unlink($ruta_xml);
		}

		$this->db->commit();
		echo "<br />Numero CPE: ".$n;
		exit();
	}

	public function tratando_recuperar_cpes() {
		$query = "SELECT * FROM `detalle_doc` where iddetalle > 589572 and cantidad = 1 and precio = 1 and precio_sin_igv = 1 and tipo_envio_sunat = 'produccion'";
		$sentencia = $this->db->prepare($query);
		$sentencia->execute();

		$n = 0;
		while ($fila = $sentencia->fetch()) {
			$fila = (object)$fila;
			$n++;
			
			$det_ofi = DetalleDoc::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $fila->id_contribuyente, 'id_tipodoc_electronico' => $fila->id_tipodoc_electronico, 'serie_comprobante' => $fila->serie_comprobante, 'numero_comprobante' => $fila->numero_comprobante, 'tipo_envio_sunat' => $fila->tipo_envio_sunat)));
			
			if($det_ofi) {
				$detalleoficial = DetalleDoc::findFirst(array("iddetalle = :iddetalle:", 'bind' => array('iddetalle' => $det_ofi->iddetalle)));

				$producto = Producto::findFirst(array("idproducto = :idproducto:", 'bind' => array('idproducto' => $det_ofi->id_producto)));
				if($producto) {
					$detalleoficial->precio = $producto->valor_con_igv;
					$detalleoficial->precio_sin_igv = $producto->valor_sin_igv;
					$detalleoficial->cantidad = round(floatval($detalleoficial->sub_total)/floatval($producto->valor_sin_igv), 4);
					$resp = $detalleoficial->save();
				} else {
					echo "<br >No se encontró detalle";
				}
			} else {
				echo "<br> NO SE ENCONTRÓ DETALLE";
			}
		}

		
		echo 'Total Procesados =>>>> '.$n;
		exit();
	}

	public function leer_xml_restaurar_cpe($inicial, $final) {
		$docxml = new DOMDocument();
		$ruta_base = "/home/humbertoguadalup/.files_contribuyente";

		$query = "select detalle_doc.id_contribuyente, detalle_doc.id_tipodoc_electronico, detalle_doc.serie_comprobante, detalle_doc.numero_comprobante, detalle_doc.tipo_envio_sunat, doc_electronico.name_xml, doc_electronico.name_xml_zip FROM detalle_doc INNER JOIN doc_electronico on (detalle_doc.id_contribuyente = doc_electronico.id_contribuyente and detalle_doc.id_tipodoc_electronico = doc_electronico.id_tipodoc_electronico and detalle_doc.serie_comprobante = doc_electronico.serie_comprobante and detalle_doc.numero_comprobante = doc_electronico.numero_comprobante and detalle_doc.tipo_envio_sunat = doc_electronico.tipo_envio_sunat) where iddetalle > 589572 and cantidad = 1 and precio = 1 and precio_sin_igv = 1 and detalle_doc.tipo_envio_sunat = 'produccion' GROUP BY detalle_doc.id_contribuyente, detalle_doc.id_tipodoc_electronico, detalle_doc.serie_comprobante, detalle_doc.numero_comprobante, detalle_doc.tipo_envio_sunat";

		$this->db->begin();

		$sentencia = $this->db->prepare($query);
		$sentencia->execute();
		$sin_xml = array();
		$n = 0;
		while ($fila = $sentencia->fetch()) {
			$n++;
			$fila = (object)$fila;
			$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $fila->id_contribuyente)));
			if(!$contribuyente) {
				$this->db->rollback();
				echo "no existe el contribuyente!";
				exit();
			}

			$ruta_base_xml = $ruta_base.'/'.$contribuyente->ruc.'/'.$contribuyente->ruta_xml_produccion.'/';

			if($fila->id_tipodoc_electronico == '01' || $fila->id_tipodoc_electronico == '03') {
				$carpeta_contenedora = $ruta_base_xml.'facturas_boletas/';
			} else if($fila->id_tipodoc_electronico == '07') {
				$carpeta_contenedora = $ruta_base_xml.'nota_credito/';
			} else if($fila->id_tipodoc_electronico == '08') {
				$carpeta_contenedora = $ruta_base_xml.'nota_debito/';
			} else if($fila->id_tipodoc_electronico == '09') {
				$carpeta_contenedora = $ruta_base_xml.'guias_remision/';
			} else {
				$this->db->rollback();
				echo "No se reconoce el tipo de documento!";
				exit();
			}
			
			$zip = new ZipArchive;
			if ($zip->open($carpeta_contenedora.$fila->name_xml_zip) === TRUE) {
				if($zip->extractTo($carpeta_contenedora, $fila->name_xml) === true) {

				} else {
					$this->db->rollback();
					echo "no se puede extraer uno de los zip";
					exit();
				}

				$zip->close();
			}

			$ruta_xml = $carpeta_contenedora.$fila->name_xml;
			
			if(!@$docxml->load($ruta_xml)) {
				$sin_xml[] = array(
					'id_contribuyente' => $fila->id_contribuyente,
					'id_tipodoc_electronico' => $fila->id_tipodoc_electronico,
					'serie_comprobante'	=> $fila->serie_comprobante,
					'numero_comprobante' => $fila->numero_comprobante,
					'tipo_envio_sunat' => $fila->tipo_envio_sunat
				);
				continue;
			}

			$items_invoice = $docxml->getElementsByTagName('InvoiceLine');
			$_items = array();
			foreach($items_invoice as $item) {
				$id_afectacion = $item->getElementsByTagName('TaxExemptionReasonCode')[0]->nodeValue;
				$descripcion = $item->getElementsByTagName('Description')[0]->nodeValue;
				$cantidad = $item->getElementsByTagName('InvoicedQuantity')[0]->nodeValue;
				$unidad_medida = $item->getElementsByTagName('InvoicedQuantity')[0]->getAttribute('unitCode');
				$taxable_amount = isset($item->getElementsByTagName('TaxableAmount')[0]->nodeValue)?$item->getElementsByTagName('TaxableAmount')[0]->nodeValue:0;
				$tax_amount = $item->getElementsByTagName('TaxAmount')[0]->nodeValue;
				$codigo_precio = isset($item->getElementsByTagName('PriceTypeCode')[0]->nodeValue)?$item->getElementsByTagName('PriceTypeCode')[0]->nodeValue:0;
				$precio_sin_igv = isset($item->getElementsByTagName('TaxSubtotal')[0]->getElementsByTagName('TaxableAmount')[0]->nodeValue)?$item->getElementsByTagName('TaxSubtotal')[0]->getElementsByTagName('TaxableAmount')[0]->nodeValue:0;
				$precio_con_igv = $item->getElementsByTagName('AlternativeConditionPrice')[0]->getElementsByTagName('PriceAmount')[0]->nodeValue;
				$codigo_producto = $item->getElementsByTagName('SellersItemIdentification')[0]->getElementsByTagName('ID')[0]->nodeValue;
				
				$_items[] = array(
					'id_contribuyente' => $fila->id_contribuyente,
					'id_tipodoc_electronico' => $fila->id_tipodoc_electronico,
					'serie_comprobante'	=> $fila->serie_comprobante,
					'numero_comprobante' => $fila->numero_comprobante,
					'tipo_envio_sunat' => $fila->tipo_envio_sunat,
					'cantidad'     => $cantidad,
					'und_med'      => $unidad_medida,
					'descripcion'  => $descripcion,
					'id_afectacion' => $id_afectacion,
					'precio_sin_igv'  => $precio_sin_igv,
					'precio_con_igv'  => $precio_con_igv,
					'id_producto' => $codigo_producto,
					'tax_amount'	=> $tax_amount
				);
			}
			
			$num_decimales = $contribuyente->num_decimales;
			
			foreach($_items as $item_xml) {
				$item_xml = (object)$item_xml;
				$detalle_oficial = DetalleDoc::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat: and id_producto = :id_producto: and igv = :igv:", 'bind' => array('id_contribuyente' => $item_xml->id_contribuyente, 'id_tipodoc_electronico' => $item_xml->id_tipodoc_electronico, 'serie_comprobante' => $item_xml->serie_comprobante, 'numero_comprobante' => $item_xml->numero_comprobante, 'tipo_envio_sunat' => $item_xml->tipo_envio_sunat, 'id_producto' => $item_xml->id_producto, 'igv' => floatval($item_xml->tax_amount))));

				if(!$detalle_oficial) {
					echo 'no existe<br />';
				}

				$detalle_oficial->cantidad = round(floatval($item_xml->cantidad), $num_decimales) + 0;
				$detalle_oficial->precio = round(floatval($item_xml->precio_con_igv), $num_decimales) + 0;
				$detalle_oficial->precio_sin_igv = round(floatval($item_xml->precio_sin_igv), $num_decimales) + 0;

				if(!$detalle_oficial->save()) {
					$this->db->rollback();
					$msg = '';
					foreach ($detalle_oficial->getMessages() as $message) {
						$msg = $msg.$message."</br>\n";
					}
					echo $msg;
				}
			}

			unlink($ruta_xml);
		}

		$this->db->commit();
		echo "<br />Numero CPE: ".$n;
		echo "<br />Inicio: ".$inicial;
		echo "<br />Fin: ".$final;
		echo "<br /><br />Nuevo Inicio: ".($final + 1);
		echo "<br />Nuevo Fin: ".($final + 200);
		echo "<br /><a target='_blank' href='https://arpsystem.com.pe/facturacionv8/recuperacion/index/".($final + 1)."/".($final + 200)."'>https://arpsystem.com.pe/facturacionv8/recuperacion/index/".($final + 1)."/".($final + 200)."</a>";
		exit();
	}

	public function restaurar_backup_detalle_doc() {
		ini_set('memory_limit','2048M');
		ini_set('max_execution_time', 1940);
		//$new_activacion = Usuarioxcursos::findFirst(array("idusuario = :idusuario: and idcurso = :idcurso:", 'bind' => array('idusuario' => $new_usuario->idusuario, 'idcurso' => $idcurso), "order" => "idcurso DESC"));
		$this->db->begin();
		$items_backup = DetalleDocBackup::find(array("iddetalle >= 600001 and iddetalle <= 650000", 'columns' => 'iddetalle, id_contribuyente, cantidad, precio, precio_sin_igv', "order" => "id_contribuyente ASC"));
		$n = 0;
		$ultimo_id = 0;
		foreach($items_backup as $item_backup) {
			$n++;
			$item_oficial = DetalleDoc::findFirst(array("iddetalle = :iddetalle:", 'bind' => array('iddetalle' => $item_backup->iddetalle)));
			$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $item_backup->id_contribuyente)));
			$num_decimales = $contribuyente->num_decimales;
			
			$item_oficial->cantidad = round(floatval($item_backup->cantidad), $num_decimales) + 0;
			$item_oficial->precio = round(floatval($item_backup->precio), $num_decimales) + 0;
			$item_oficial->precio_sin_igv = round(floatval($item_backup->precio_sin_igv), $num_decimales) + 0;

			if(!$item_oficial->save()) {
				$this->db->rollback();
				$msg = '';
				foreach ($item_oficial->getMessages() as $message) {
					$msg = $msg.$message."</br>\n";
				}
				echo $msg;
			}

			$ultimo_id = $item_backup->iddetalle;
		}
		$this->db->commit();
		echo $n.' =>> '.$ultimo_id;
		exit();
	}

	public function restaurar_backup_detalle_docnooficial() {
		//$new_activacion = Usuarioxcursos::findFirst(array("idusuario = :idusuario: and idcurso = :idcurso:", 'bind' => array('idusuario' => $new_usuario->idusuario, 'idcurso' => $idcurso), "order" => "idcurso DESC"));
		$this->db->begin();
		$items_backup = DetalleDocnooficialBackup::find();
		$n = 0;
		$ultimo_id = 0;
		foreach($items_backup as $item_backup) {
			$n++;
			$item_oficial = DetalleDocnooficial::findFirst(array("iddetalle = :iddetalle:", 'bind' => array('iddetalle' => $item_backup->iddetalle)));
			$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $item_backup->id_contribuyente)));
			$num_decimales = $contribuyente->num_decimales;
			
			$item_oficial->cantidad = round(floatval($item_backup->cantidad), $num_decimales) + 0;
			$item_oficial->precio = round(floatval($item_backup->precio), $num_decimales) + 0;
			$item_oficial->precio_sin_igv = round(floatval($item_backup->precio_sin_igv), $num_decimales) + 0;

			if(!$item_oficial->save()) {
				$this->db->rollback();
				$msg = '';
				foreach ($item_oficial->getMessages() as $message) {
					$msg = $msg.$message."</br>\n";
				}
				echo $msg;
			}

			$ultimo_id = $item_backup->iddetalle;
		}
		$this->db->commit();
		echo $n.' =>> '.$ultimo_id;
		exit();
	}
}