<?php
class ApisunatController extends ControllerBase
{
    //v5.0
	public function getTipoCambioAction() {
		$this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) {
            $datapost = $this->request->getPost();
            $fecha_consulta = !isset($datapost['fecha'])?date('Y-m-d'):$datapost['fecha'];
            
            if (!(DateTime::createFromFormat('Y-m-d', $fecha_consulta) !== FALSE)) {
                $fecha_consulta = date('Y-m-d');
            }
            
            $tipo_cambiosunat = SunatTipodecambio::findFirst(array("CAST(fecha AS DATE) = CAST(:fecha: AS DATE)", 'bind' => array('fecha' => $fecha_consulta)));
            
            if(!$tipo_cambiosunat) {
                
                $resp_tipo_cambio = $this->get_tipo_cambio_facturalaya($fecha_consulta);
                if($resp_tipo_cambio['respuesta'] == 'error') {
                    $tipo_cambiosunat = SunatTipodecambio::findFirst(array("order" => "fecha DESC"));
                    if(!$tipo_cambiosunat) {
                        echo json_encode(array());
                        exit();
                    }
                    
                    $resp['respuesta'] = 'ok';
                    $resp['fecha'] = date("d/m/Y", strtotime($tipo_cambiosunat->fecha));
                    $resp['compra'] = $tipo_cambiosunat->compra;
                    $resp['venta'] = $tipo_cambiosunat->venta;

                    echo json_encode($resp);
                    exit();
                }

                $new_tipocambio = new SunatTipodecambio();
                $new_tipocambio->fecha = date('Y-m-d', strtotime($resp_tipo_cambio['fecha']));
                $new_tipocambio->compra = $resp_tipo_cambio['compra'];
                $new_tipocambio->venta = $resp_tipo_cambio['venta'];
                $new_tipocambio->moneda = 'USD';
                $new_tipocambio->save();

                $resp['respuesta'] = 'ok';
                $resp['fecha'] = date("d/m/Y", strtotime($resp_tipo_cambio['fecha']));
                $resp['compra'] = $resp_tipo_cambio['compra'];
                $resp['venta'] = $resp_tipo_cambio['venta'];

                echo json_encode($resp);
                exit();
            }

            $resp['respuesta'] = 'ok';
            $resp['fecha'] = date("d/m/Y", strtotime($tipo_cambiosunat->fecha));
            $resp['compra'] = $tipo_cambiosunat->compra;
            $resp['venta'] = $tipo_cambiosunat->venta;

            echo json_encode($resp);
            exit();
		}	
    }

    public function get_tipo_cambio_por_fecha($fecha_consulta) {
        $tipo_cambiosunat = SunatTipodecambio::findFirst(array("CAST(fecha AS DATE) = CAST(:fecha: AS DATE)", 'bind' => array('fecha' => $fecha_consulta)));
        if(!$tipo_cambiosunat) {
            
            $resp_tipo_cambio = $this->get_tipo_cambio_facturalaya($fecha_consulta);
            if($resp_tipo_cambio['respuesta'] == 'error') {
                $tipo_cambiosunat = SunatTipodecambio::findFirst(array("order" => "fecha DESC"));
                if(!$tipo_cambiosunat) {
                    $resp['respuesta'] = 'error';
                    return $resp;
                }
                
                $resp['respuesta'] = 'ok';
                $resp['fecha'] = date("d/m/Y", strtotime($tipo_cambiosunat->fecha));
                $resp['compra'] = $tipo_cambiosunat->compra;
                $resp['venta'] = $tipo_cambiosunat->venta;

                return $resp;
            }
            
            $resp['respuesta'] = 'ok';
            $resp['fecha'] = date("d/m/Y", strtotime($resp_tipo_cambio['fecha']));
            $resp['compra'] = $resp_tipo_cambio['compra'];
            $resp['venta'] = $resp_tipo_cambio['venta'];
            return $resp;
        }

        $resp['respuesta'] = 'ok';
        $resp['fecha'] = date("d/m/Y", strtotime($tipo_cambiosunat->fecha));
        $resp['compra'] = $tipo_cambiosunat->compra;
        $resp['venta'] = $tipo_cambiosunat->venta;

        return $resp;
    }
    
    //v5.0
    public function get_tipo_cambio_facturalaya($fecha) {
        $data['token'] = $this->token_user; //Tu Token
        $data['ruc_proveedor'] = $this->ruc_proveedor;
		$data['moneda'] = "USD";
		$data['fecha'] = $fecha;

		$ruta = 'https://facturalahoy.com/api/facturalaya/get_tipo_cambio';
		$data_json = json_encode($data);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $ruta);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$response  = curl_exec($ch);
        curl_close($ch);
        
        $tipo_cambio = json_decode($response, true);
		if ($tipo_cambio === null && json_last_error() !== JSON_ERROR_NONE) {
			$resp['respuesta'] = 'error';
			$resp['mensaje'] = json_last_error_msg();
			echo json_encode($resp);
			exit();
        }
        
        return $tipo_cambio;
    }

    public function getTipoCambioByDateAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) {
            $datapost = $this->request->getPost();
            $fecha_consulta = empty($datapost['fecha'])?date('Y-m-d'):$datapost['fecha'];
            if (!(DateTime::createFromFormat('Y-m-d', $fecha_consulta) !== FALSE)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Sin Datos!';
                $resp['mensaje'] = 'El tipo de cambio para la fecha: '.$fecha_consulta.' no se encuentra registrado.';
                echo json_encode($resp);
                exit();
            }

            $resp = $this->get_tipo_cambio($fecha_consulta);
            echo json_encode($resp);
            exit();
        }
    }

    public function get_tipo_cambio($fecha_consulta) {
        $fecha_inicial = $fecha_consulta;
        $fecha_consulta_2 = new DateTime($fecha_consulta);
        $fecha_minima = new DateTime("2009-01-01");

        if($fecha_consulta_2 < $fecha_minima) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Sin Datos!';
            $resp['mensaje'] = 'El tipo de cambio para la fecha: '.$fecha_consulta.' no se encuentra registrado.';
            return $resp;
        }
        
        for($i=0; $i<=10; $i++) {
            $tipo_cambiosunat = SunatTipodecambio::findFirst(array("CAST(fecha AS DATE) = CAST(:fecha: AS DATE)", 'bind' => array('fecha' => $fecha_consulta)));
            if($tipo_cambiosunat) {
                $resp['respuesta'] = 'ok';
                $resp['fecha'] = $fecha_consulta;
                $resp['compra'] = (float)$tipo_cambiosunat->compra;
                $resp['venta'] = (float)$tipo_cambiosunat->venta;
                return $resp;
            }
            $fecha_consulta = date('Y-m-d', strtotime('-1 day', strtotime($fecha_consulta)));
        }

        $resp['respuesta'] = 'ok';
        $resp['fecha'] = $fecha_consulta;
        $resp['compra'] = 3.984;
        $resp['venta'] = 3.981;
        return $resp;

        $resp['respuesta'] = 'error';
        $resp['titulo'] = 'Sin Datos!';
        $resp['mensaje'] = 'El tipo de cambio para la fecha: '.$fecha_inicial.' no se encuentra registrado.';
        return $resp;
    }

    public function actualizar_tabla_tipocambio($anio) {
        $curl = curl_init();

		curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://api.sunat.cloud/cambio/'.$anio,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'GET',
		CURLOPT_SSL_VERIFYHOST => false,
		CURLOPT_SSL_VERIFYPEER => false
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		$result = json_decode($response);

		foreach($result as $fecha => $data) {
			$fecha_tipocambio = date('Y-m-d', strtotime($fecha));
			$valor_compra = floatval($data->compra);
			$valor_venta = floatval($data->venta);
			
			$tipo_cambiosunat = SunatTipodecambio::findFirst(array("CAST(fecha AS DATE) = CAST(:fecha: AS DATE)", 'bind' => array('fecha' => $fecha)));
			
			if(!$tipo_cambiosunat) {
				
				if($valor_compra > 0 && $valor_venta > 0) {
					$new_tipocambio = new SunatTipodecambio();
					$new_tipocambio->fecha = $fecha_tipocambio;
					$new_tipocambio->compra = $valor_compra;
					$new_tipocambio->venta = $valor_venta;
					$resp_save = $new_tipocambio->save();
				}
			}
		}
    }
    
    public function getListaSugerenciasUbigeoAction() {
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
			
			$termino = str_replace('  ', ' ', trim($datapost['q']));
			$trozos = explode(" ", $termino); 
            $numero_trozos = count($trozos); 

            if($numero_trozos <= 1) {
                $termino = '%'.$termino.'%';
                $query = "select * from sunat_codigoubigeo where codigo_ubigeo like :termino or CONCAT(departamento, ' ', provincia, ' ', distrito) like :termino";
            } else {
                //https://desarrolloweb.com/articulos/2087.php
                $termino = "'".$termino."'";
                $query = "select * from sunat_codigoubigeo where MATCH (departamento, provincia, distrito) AGAINST (:termino)";
            }

            try {
                $sentencia = $this->db->prepare($query);
                $sentencia->bindParam(':termino', $termino, PDO::PARAM_STR);
                $sentencia->execute();
            } catch (Exception $e) {
                $this->saveLogger($e);
                echo 'Excepción capturada: ',  $e->getMessage(), "\n";
                exit();
            }
            
            $lista = array();
            while ($item = $sentencia->fetch()) {
                $fila = (object)$item;
                $lista[] = array('id' => $fila->codigo_ubigeo, 'text' => $fila->departamento.' - '.$fila->provincia.' - '.$fila->distrito);
            }

            if(count($lista) <= 0) {
                $lista[] = array('id' => '', 'text' => '');
            }
			
            $resp['items'] = $lista;
            echo json_encode($resp);
            exit();
		}
    }

	public function getListaUbigeosAction() {
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
			
			$lista = SunatCodigoubigeo::find();
			$resp['respuesta'] = 'ok';
			$resp['lista'] = $lista;
			echo json_encode($resp);
			exit();
		}
    }
    
    public function getListaCodprodSegmentosAction() {
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
			
            $lista = SunatCodprodsegmento::find();
			$resp['respuesta'] = 'ok';
			$resp['lista'] = $lista;
			echo json_encode($resp);
			exit();
		}
    }

    public function getListaCodprodCodigoproductoAction() {
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
			
			$termino = str_replace('  ', ' ', trim($datapost['q']));
			$trozos = explode(" ", $termino); 
            $numero_trozos = count($trozos); 

            if($numero_trozos <= 1) {
                $termino = '%'.$termino.'%';
                $query = "select * from sunat_codproducto where codigoproducto like :termino or descripcion like :termino";
            } else {
                //https://desarrolloweb.com/articulos/2087.php
                $termino = "'".$termino."'";
                $query = "select * from sunat_codproducto where MATCH (descripcion) AGAINST (:termino)";
            }

            try {
                $sentencia = $this->db->prepare($query);
                $sentencia->bindParam(':termino', $termino, PDO::PARAM_STR);
                $sentencia->execute();
            } catch (Exception $e) {
                $this->saveLogger($e);
                echo 'Excepción capturada: ',  $e->getMessage(), "\n";
                exit();
            }
            
			
			
            $lista = array();
            while ($item = $sentencia->fetch()) {
                $codigo = (object)$item;
                $lista[] = array('id' => $codigo->codigoproducto, 'text' => $codigo->descripcion);
            }

            if(count($lista) <= 0) {
                $lista[] = array('id' => '', 'text' => '');
            }
			
            $resp['items'] = $lista;
            echo json_encode($resp);
            exit();
		}
    }

	public function getListaTipoDocIdentidadAction() {
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
			
			$lista = SunatTipodocidentidad::find();
			$resp['respuesta'] = 'ok';
			$resp['lista'] = $lista;
			echo json_encode($resp);
			exit();
		}
	}
	public function getListaMonedasAction() {
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
			
			$lista = SunatMoneda::find();
			$resp['respuesta'] = 'ok';
			$resp['lista'] = $lista;
			echo json_encode($resp);
			exit();
		}
	}

	public function getUnidadesmedidaAction() {
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
			
			$lista = SunatUnidadmedida::find();
			$resp['respuesta'] = 'ok';
			$resp['lista'] = $lista;
			echo json_encode($resp);
			exit();
		}
	}

	public function getListaTipoafectacionigvAction() {
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
			
			$lista = SunatTipoafectacionigv::find("id_tipoafectacionigv <> 7152");
			$resp['respuesta'] = 'ok';
			$resp['lista'] = $lista;
			echo json_encode($resp);
			exit();
		}
	}

	public function getCodigodetraccionAction() {
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
			
			$lista = SunatCodigodetraccion::find();
			$resp['respuesta'] = 'ok';
			$resp['lista'] = $lista;
			echo json_encode($resp);
			exit();
		}
	}

	public function getSunatTiponotacreditoAction() {
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
			
			$lista = SunatTiponotacredito::find();
			$resp['respuesta'] = 'ok';
			$resp['lista'] = $lista;
			echo json_encode($resp);
			exit();
		}
	}

	public function getSunatTiponotadebitoAction() {
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
			
			$lista = SunatTiponotadebito::find();
			$resp['respuesta'] = 'ok';
			$resp['lista'] = $lista;
			echo json_encode($resp);
			exit();
		}
	}
}