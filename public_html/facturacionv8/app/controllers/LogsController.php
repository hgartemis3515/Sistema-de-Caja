<?php
class LogsController extends ControllerBase
{
	public function indexAction() {

	}

	public function log_configuracion_sistema($data) {
		$id_contribuyente = $data['id_contribuyente'];
		$idusuario = $data['idusuario'];
		$descripcion = $data['descripcion'];
		$tabla = isset($data['tabla'])? $data['tabla']:'';
		$columna = isset($data['columna'])? $data['columna']:'';

		try {
			$log = new LogConfigSistema();
			$log->id_contribuyente = $id_contribuyente;
			$log->idusuario = $idusuario;
			$log->fecha_registro = date('Y-m-d H:i:s');
			$log->descripcion = $descripcion;
			$log->tabla = $tabla;
			$log->columna = $columna;

			if(!$log->save()) {
                $this->db->rollback();
                $msg = '';
                foreach ($log->getMessages() as $message) {
                    $msg = $msg.$message."</br>\n";
                }
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = $msg;
                return $resp;
            }
		} catch (\Throwable $th) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = $th->getMessage();
			return $resp;
		}

		$resp['respuesta'] = 'ok';
		return $resp;	
	}

	public function log_compra($data) {
		$id_compra 				= $data['id_compra'];
		$id_contribuyente 		= $data['id_contribuyente'];
		$idsucursal 			= $data['idsucursal'];
		$id_tipodoc_electronico = $data['id_tipodoc_electronico'];
		$serie_comprobante 		= $data['serie_comprobante'];
		$numero_comprobante 	= $data['numero_comprobante'];
		$tipo_envio_sunat 		= $data['tipo_envio_sunat'];
		$id_usuario 			= $data['id_usuario'];
		$tipo_log 				= $data['tipo_log'];
		$id_proveedor 			= $data['id_proveedor'];
		$descripcion 			= isset($data['descripcion'])?$data['descripcion']:'';

		$usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $id_usuario)));

		if($tipo_log == 'anular_compra') {
			$log = new LogCompra();
			$log->id_compra 				= $id_compra;
			$log->id_contribuyente 			= $id_contribuyente;
			$log->idsucursal 				= $idsucursal;
			$log->id_tipodoc_electronico 	= $id_tipodoc_electronico;
			$log->serie_comprobante 		= $serie_comprobante;
			$log->numero_comprobante 		= $numero_comprobante;
			$log->tipo_envio_sunat 			= $tipo_envio_sunat;
			$log->id_proveedor				= $id_proveedor;
			$log->id_usuario 				= $id_usuario;
			$log->fecha_registro 			= date('Y-m-d H:i:s');
			$log->tipo 						= $tipo_log;
			$log->descripcion 				= (empty($descripcion))?"El Usuario ".$usuario->nombre.' '.$usuario->apellido.' (ID: '.$usuario->idusuario.') ha realizado una Anulación para el comprobante.':$descripcion;
			
			try {
				if(!$log->save()) {
					$msg = '';
					foreach ($log->getMessages() as $message) {
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
			return $resp;
		}
	}

	public function log_documento($data) {
		$id_contribuyente = $data['id_contribuyente'];
		$id_tipodoc_electronico = $data['id_tipodoc_electronico'];
		$serie_comprobante = $data['serie_comprobante'];
		$numero_comprobante = $data['numero_comprobante'];
		$tipo_envio_sunat = $data['tipo_envio_sunat'];
		$idusuario = $data['idusuario'];
		$tipo_log = $data['tipo_log'];
		$descripcion = isset($data['descripcion'])?$data['descripcion']:'';
		
		$array_cpe = array('01', '03', '07', '08', '09');
		
		if(in_array($id_tipodoc_electronico, $array_cpe)) {
			$documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));
		} else {
			if($id_tipodoc_electronico == '77') {
				$serie_comprobante = (empty($serie_comprobante))?'NV01':$serie_comprobante;
			} else {
				$serie_comprobante = (empty($serie_comprobante))?'COTI':$serie_comprobante;
			}
			
			$documento = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad: and estado_documento = 'activo'", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodocumento' => $id_tipodoc_electronico, 'numero_comprobante' => $numero_comprobante, 'modalidad' => $tipo_envio_sunat)));
		}

		if(!$documento) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'No Existe el Documento';
			$resp['mensaje'] = 'documento no encontrado';
			return $resp;
		}

		$usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
		
		if($tipo_log == 'comunicacion_baja') {
			$log = new LogDocumento();
			$log->id_contribuyente = $id_contribuyente;
			$log->id_tipodoc_electronico = $id_tipodoc_electronico;
			$log->serie_comprobante = $serie_comprobante;
			$log->numero_comprobante = $numero_comprobante;
			$log->tipo_envio_sunat = $tipo_envio_sunat;
			$log->idusuario = $idusuario;
			$log->fecha_registro = date('Y-m-d H:i:s');
			$log->tipo = $tipo_log;
			$log->descripcion = (empty($descripcion))?"El Usuario ".$usuario->nombre.' '.$usuario->apellido.' (ID: '.$usuario->idusuario.') ha realizado una comunicación de baja o anulación para el comprobante.':$descripcion;
			$log->save();

			$resp['respuesta'] = 'ok';
			return $resp;
		}

		if($tipo_log == 'anulacion') {
			$log = new LogDocumento();
			$log->id_contribuyente = $id_contribuyente;
			$log->id_tipodoc_electronico = $id_tipodoc_electronico;
			$log->serie_comprobante = $serie_comprobante;
			$log->numero_comprobante = $numero_comprobante;
			$log->tipo_envio_sunat = $tipo_envio_sunat;
			$log->idusuario = $idusuario;
			$log->fecha_registro = date('Y-m-d H:i:s');
			$log->tipo = $tipo_log;
			$log->descripcion = (empty($descripcion))?"El Usuario ".$usuario->nombre.' '.$usuario->apellido.' (ID: '.$usuario->idusuario.') ha realizado una comunicación de baja o anulación para el comprobante.':$descripcion;
			
			$log->save();

			$resp['respuesta'] = 'ok';
			return $resp;
		}

		$log = new LogDocumento();
		$log->id_contribuyente = $id_contribuyente;
		$log->id_tipodoc_electronico = $id_tipodoc_electronico;
		$log->serie_comprobante = $serie_comprobante;
		$log->numero_comprobante = $numero_comprobante;
		$log->tipo_envio_sunat = $tipo_envio_sunat;
		$log->idusuario = $idusuario;
		$log->fecha_registro = date('Y-m-d H:i:s');
		$log->tipo = $tipo_log;
		$log->descripcion = (empty($descripcion))?'':$descripcion;
		
		$log->save();

		$resp['respuesta'] = 'ok';
		return $resp;
	}
}
?>