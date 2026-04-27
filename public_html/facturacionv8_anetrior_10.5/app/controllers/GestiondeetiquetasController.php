<?php
class GestiondeetiquetasController extends ControllerBase {
	public function guardarEtiquetaAction() {
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
                $resp['titulo'] = 'Error en Usuario';
                $resp['mensaje'] = 'Debes iniciar sesión.';
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

            $nombre_etiqueta = trim($datapost['nombre']);
            if(empty($nombre_etiqueta)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes ingresar el nombre de la etiqueta.';
                echo json_encode($resp);
                exit();
            }

            $color_etiqueta = $datapost['color'];
            if(empty($color_etiqueta)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'Debes seleccionar un color para la etiqueta.';
                echo json_encode($resp);
                exit();
            }

            $colores_validos = array('primary', 'success', 'info', 'danger');
            if(!in_array($color_etiqueta, $colores_validos)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El color no se permite!';
                echo json_encode($resp);
                exit();
            }

            $codigo_etiqueta = isset($datapost['codigo'])?$datapost['codigo']:null;
            $detalle_etiqueta = isset($datapost['detalle'])?$datapost['detalle']:null;

            $etiqueta_guardada = Etiqueta::findFirst(array("id_contribuyente = :id_contribuyente: and nombre = :nombre:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'nombre' => $nombre_etiqueta)));
            if($etiqueta_guardada) {
                if($etiqueta_guardada->estado == 'inactivo') {
                    $etiqueta_guardada->estado = 'activo';
                    try {
                        if(!$etiqueta_guardada->save()) {
                            $msg = '';
                            foreach ($etiqueta_guardada->getMessages() as $message) {
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
                    $resp['titulo'] = 'OK';
                    $resp['etiqueta'] = $etiqueta_guardada;
                    $resp['mensaje'] = 'La etiqueta se habilitó correctamente!.';
                    echo json_encode($resp);
                    exit();
                } else {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'No se permite guardar dos o más etiquetas con el mismo nombre';
                    echo json_encode($resp);
                    exit();
                }
            }

            $new_etiqueta = new Etiqueta();
            $new_etiqueta->id_contribuyente = $contribuyente->id_contribuyente;
            $new_etiqueta->idusuario = $usuario->idusuario;
            $new_etiqueta->nombre = $nombre_etiqueta;
            $new_etiqueta->fecha_registro = date("Y-m-d H:i:s");
            $new_etiqueta->estado = 'activo';
            $new_etiqueta->color = $color_etiqueta;
            $new_etiqueta->codigo = $codigo_etiqueta;
            $new_etiqueta->detalle = $detalle_etiqueta;
            $new_etiqueta->tipo = 'normal';

            try {
                if(!$new_etiqueta->save()) {
                    $msg = '';
                    foreach ($new_etiqueta->getMessages() as $message) {
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
            $resp['titulo'] = 'OK';
            $resp['etiqueta'] = $new_etiqueta;
            $resp['mensaje'] = 'La etiqueta se registró correctamente!.';
            echo json_encode($resp);
            exit();
        }
    }

    public function getListaEtiquetasAction() {
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
                $resp['titulo'] = 'Error en Usuario';
                $resp['mensaje'] = 'Debes iniciar sesión.';
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
            
            $resp = $this->get_lista_etiquetas($usuario->id_contribuyente);
            echo json_encode($resp);
            exit();
        }
    }

    public function get_lista_etiquetas($id_contribuyente, $id_tipodocumento = '', $serie_comprobante = '', $numero_comprobante = '', $tipo_envio_sunat = '') {

        $array_tags_seleccionados = array();
        if(!empty($id_tipodocumento)) {
            $cpe = array('01', '03', '07', '08', '09', '31');
            $doc_no_oficial = array('77', '88');
            if(in_array($id_tipodocumento, $cpe)) {
                if(!empty($serie_comprobante) && !empty($numero_comprobante) && !empty($tipo_envio_sunat)) {
                    $array_tags_seleccionados = $this->get_etiquetas_documento($id_contribuyente, $id_tipodocumento, $serie_comprobante, $numero_comprobante, $tipo_envio_sunat)['etiquetas_activas'];
                }
            }

            if(in_array($id_tipodocumento, $doc_no_oficial)) {
                if(!empty($numero_comprobante) && !empty($tipo_envio_sunat)) {
                    $array_tags_seleccionados = $this->get_etiquetas_documento($id_contribuyente, $id_tipodocumento, '', $numero_comprobante, $tipo_envio_sunat)['etiquetas_activas'];
                }
            }
        }

        $lista_etiquetas = array();
        $etiquetas = Etiqueta::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $id_contribuyente)));
        foreach($etiquetas as $etiqueta) {

            $lista_etiquetas[] = array(
                'id_etiqueta'   => $etiqueta->id_etiqueta,
                'nombre'    => $etiqueta->nombre,
                'color'     => $etiqueta->color,
                'codigo'    => $etiqueta->codigo,
                'detalle'   => $etiqueta->detalle,
                'tipo'      => $etiqueta->tipo,
                'seleccionado' => in_array($etiqueta->id_etiqueta, $array_tags_seleccionados)?'si':'no'
            );
        }

        $resp['respuesta'] = 'ok';
        $resp['lista'] = $lista_etiquetas;
        return $resp;
    }

    public function get_etiquetas_documento($id_contribuyente, $id_tipodoc_electronico, $serie_comprobante, $numero_comprobante, $estado_envio_sunat) {
        //echo "$id_contribuyente, $id_tipodoc_electronico, $serie_comprobante, $numero_comprobante, $estado_envio_sunat";
        //exit();
        //-----INICIO ETIQUETAS-------------------
        //https://www.gyrocode.com/articles/jquery-datatables-how-to-expand-collapse-all-child-rows/
        $etiquetas_html = '';
        $array_doc_no_oficial = array('77', '88');
        if(!in_array($id_tipodoc_electronico, $array_doc_no_oficial)) {
            $etiquetas_documento = Etiquetaxdocumento::find(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat: and estado = :estado:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'estado' => 'activo', 'tipo_envio_sunat' => $estado_envio_sunat)));
        } else {
            $etiquetas_documento = Etiquetaxdocumento::find(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat: and estado = :estado:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'numero_comprobante' => $numero_comprobante, 'estado' => 'activo', 'tipo_envio_sunat' => $estado_envio_sunat)));
        }

        $array_ids_etiquetas = array();
        $id_btn_etiquetas = "$id_contribuyente-$id_tipodoc_electronico-$serie_comprobante-$numero_comprobante";
        foreach($etiquetas_documento as $etiqueta_asignada) {
            $array_ids_etiquetas[] = $etiqueta_asignada->id_etiqueta;
            $etiqueta_documento = Etiqueta::findFirst(array("id_contribuyente = :id_contribuyente: and id_etiqueta = :id_etiqueta: and estado = 'activo'", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_etiqueta' => $etiqueta_asignada->id_etiqueta)));
            if($etiqueta_documento) {
                $id_etiqueta_doc = "tag_in_table-$etiqueta_asignada->id_etiqueta-$id_contribuyente-$id_tipodoc_electronico-$serie_comprobante-$numero_comprobante";
                $etiquetas_html = $etiquetas_html.'<span id="'.$id_etiqueta_doc.'" data-idetiqueta="'.$etiqueta_asignada->id_etiqueta.'" class="array_etiquetas-'.$id_btn_etiquetas.' label label-'.$etiqueta_documento->color.' label-roundless"><i class="icon-price-tag2" style="font-size:10px;"></i> '.$etiqueta_documento->nombre.'</span>';
            }
        }

        $etiquetas_html = $etiquetas_html.'
            <span id="btn_addtag-'.$id_btn_etiquetas.'" class="label add-item label-rounded label-icon"  onclick="etiquetar_documento('.$id_contribuyente.', '."'".$id_tipodoc_electronico."'".', '."'".$serie_comprobante."'".', '.$numero_comprobante.', '."'".implode(",", $array_ids_etiquetas)."'".')">
                <i class="icon-plus3" style="font-size:10px;"></i> Agregar/Quitar
            </span>';
        
        $menu_etiqueta = '<li><a href="javascript:void(0)" onclick="etiquetar_documento('.$id_contribuyente.', '."'".$id_tipodoc_electronico."'".', '."'".$serie_comprobante."'".', '.$numero_comprobante.', '."'".implode(",", $array_ids_etiquetas)."'".')"><img src="/facturacionv8/img/etiqueta.png" style="width: 20px;"> Etiquetar</a></li>';
        //----------FIN ETIQUETAS-------------------

        $resp['respuesta'] = 'ok';
        $resp['etiquetas_html'] = $etiquetas_html;
        $resp['menu_etiqueta'] = '';//$menu_etiqueta;
        $resp['etiquetas_activas'] = $array_ids_etiquetas;
        return $resp;
    }

    public function eliminarEtiquetaAction() {
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
                $resp['titulo'] = 'Error en Usuario';
                $resp['mensaje'] = 'Debes iniciar sesión.';
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

            $id_etiqueta = intval($datapost['id_etiqueta']) + 0;
            $etiqueta = Etiqueta::findFirst(array("id_contribuyente = :id_contribuyente: and id_etiqueta = :id_etiqueta:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'id_etiqueta' => $id_etiqueta)));
            if($etiqueta) {
                $etiqueta->estado = 'inactivo';

                try {
                    if(!$etiqueta->save()) {
                        $msg = '';
                        foreach ($etiqueta->getMessages() as $message) {
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
            }

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'OK';
            $resp['mensaje'] = 'La etiqueta se eliminó correctamente!.';
            echo json_encode($resp);
            exit();
        }
    }

    public function asignarEtiquetaxdocumentoAction() {
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
                $resp['titulo'] = 'Error en Usuario';
                $resp['mensaje'] = 'Debes iniciar sesión.';
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

            $id_etiqueta = intval($datapost['id_etiqueta']) + 0;
            $id_contribuyente = $contribuyente->id_contribuyente;
            $tipo_documento = $datapost['tipo_documento'];
            $serie_documento = $datapost['serie_documento'];
            $correlativo_documento = intval($datapost['correlativo_documento']) + 0;
            $tipo_envio_sunat = $contribuyente->tipo_envio_sunat;
            $valor_activo = ($datapost['activo']=='si')?'si':'no';

            //echo "id_etiqueta: $id_etiqueta || id_contribuyente: $id_contribuyente || tipo_documento: $tipo_documento || serie_documento: $serie_documento || correlativo_documento: $correlativo_documento || tipo_envio_sunat: $tipo_envio_sunat || valor_activo: $valor_activo";
            //exit();

            $etiqueta = Etiqueta::findFirst(array("id_contribuyente = :id_contribuyente: and id_etiqueta = :id_etiqueta: and estado = 'activo'", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente, 'id_etiqueta' => $id_etiqueta)));
            if(!$etiqueta) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'No Existe la Etiqueta';
                return $resp;
            }

            $cpe = array('01', '03', '07', '08', '09');
            $doc_no_oficial = array('77', '88');

            if(in_array($tipo_documento, $cpe)) {
                $documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $tipo_documento, 'serie_comprobante' => $serie_documento, 'numero_comprobante' => $correlativo_documento , 'tipo_envio_sunat' => $tipo_envio_sunat)));
            } else if($tipo_documento == '31') {
                $documento = GuiaTransportista::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $tipo_documento, 'serie_comprobante' => $serie_documento, 'numero_comprobante' => $correlativo_documento , 'tipo_envio_sunat' => $tipo_envio_sunat)));
            } else if($tipo_documento == '77') {
                $documento = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad: and estado_documento = 'activo'", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodocumento' => $tipo_documento, 'numero_comprobante' => $correlativo_documento, 'modalidad' => $tipo_envio_sunat)));
                $serie_documento = 'NV01';
            } else if($tipo_documento == '88') {
                $documento = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad: and estado_documento = 'activo'", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodocumento' => $tipo_documento, 'numero_comprobante' => $correlativo_documento, 'modalidad' => $tipo_envio_sunat)));
                $serie_documento = 'C001';
            } else {
                $documento = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad: and estado_documento = 'activo'", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodocumento' => $tipo_documento, 'numero_comprobante' => $correlativo_documento, 'modalidad' => $tipo_envio_sunat)));
                $serie_documento = '000';
            }

            if(!$documento) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El documento que intenta etiquetar no existe';
                echo json_encode($resp);
                exit();
            }

            $etiquetaxdocumento = Etiquetaxdocumento::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat: and id_etiqueta = :id_etiqueta:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $tipo_documento, 'serie_comprobante' => $serie_documento, 'numero_comprobante' => $correlativo_documento, 'tipo_envio_sunat' => $tipo_envio_sunat, 'id_etiqueta' => $id_etiqueta)));

            if(!$etiquetaxdocumento) {
                $etiquetaxdocumento = new Etiquetaxdocumento();
                $etiquetaxdocumento->id_contribuyente = $id_contribuyente;
                $etiquetaxdocumento->id_tipodoc_electronico = $tipo_documento;
                $etiquetaxdocumento->serie_comprobante = $serie_documento;
                $etiquetaxdocumento->numero_comprobante = $correlativo_documento;
                $etiquetaxdocumento->tipo_envio_sunat = $tipo_envio_sunat;
                $etiquetaxdocumento->id_etiqueta = $id_etiqueta;
                $etiquetaxdocumento->fecha_registro = date("Y-m-d H:i:s");
                $etiquetaxdocumento->idusuario = $usuario->idusuario;
            }

            //aquí podríamos agregar un log para las etiquetas
            if($valor_activo == 'si') {
                $etiquetaxdocumento->estado = 'activo';
            } else {
                $etiquetaxdocumento->estado = 'inactivo';
                $etiquetaxdocumento->fecha_inactivo = date("Y-m-d H:i:s");
            }
            
            if($etiquetaxdocumento) {
                try {
                    if(!$etiquetaxdocumento->save()) {
                        $msg = '';
                        foreach ($etiquetaxdocumento->getMessages() as $message) {
                            $msg = $msg.$message."</br>\n";
                        }
        
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = 'Error al guardar la etiqueta '.$msg;
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
            }

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'OK';
            $resp['mensaje'] = 'Se guardó correctamente el estado de la etiqueta';
            $resp['etiqueta'] = $etiquetaxdocumento;
            echo json_encode($resp);
            exit();
        }
    }

    public function asignar_grupo_etiquetas_documento($id_contribuyente, $idusuario, $id_tipodocumento = '', $serie_comprobante = '', $numero_comprobante = '', $tipo_envio_sunat = '', $array_etiquetas = array()) {
        $array_doc_no_oficial = array('77', '88');
        $cpe = array('01', '03', '07', '08', '09');

        if(in_array($id_tipodocumento, $cpe)) {
            $documento = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodocumento, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante , 'tipo_envio_sunat' => $tipo_envio_sunat)));
        } else if($id_tipodocumento == '77') {
            $documento = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad: and estado_documento = 'activo'", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodocumento' => $id_tipodocumento, 'numero_comprobante' => $numero_comprobante, 'modalidad' => $tipo_envio_sunat)));
            $serie_comprobante = 'NV01';
        } else if($id_tipodocumento == '88') {
            $documento = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad: and estado_documento = 'activo'", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodocumento' => $id_tipodocumento, 'numero_comprobante' => $numero_comprobante, 'modalidad' => $tipo_envio_sunat)));
            $serie_comprobante = 'C001';
        } else {
            $documento = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad: and estado_documento = 'activo'", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodocumento' => $id_tipodocumento, 'numero_comprobante' => $numero_comprobante, 'modalidad' => $tipo_envio_sunat)));
            $serie_comprobante = '000';
        }
        
        if(!in_array($id_tipodocumento, $array_doc_no_oficial)) {
            $etiquetas_documento = Etiquetaxdocumento::find(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat: and estado = :estado:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodocumento, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'estado' => 'activo', 'tipo_envio_sunat' => $tipo_envio_sunat)));
        } else {
            $etiquetas_documento = Etiquetaxdocumento::find(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat: and estado = :estado:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodocumento, 'numero_comprobante' => $numero_comprobante, 'estado' => 'activo', 'tipo_envio_sunat' => $tipo_envio_sunat)));
        }

        $resp = $this->validar_existencia_etiquetas($id_contribuyente, $array_etiquetas);
        if($resp['respuesta'] == 'error') {
            echo json_encode($resp);
            exit();
        }

        $this->db->begin();
        $array_etiquetas_actuales = array();
        foreach($etiquetas_documento as $etiquetaxdocumento) {
            $array_etiquetas_actuales[] = $etiquetaxdocumento->id_etiqueta;
        }

        $array_etiquetas_nuevas = $array_etiquetas;

        $valores_en_comun = array_intersect($array_etiquetas_actuales, $array_etiquetas_nuevas);
		$etiquetas_nuevas = array_values(array_diff($array_etiquetas_nuevas, $valores_en_comun));
		$etiquetas_para_eliminar = array_values(array_diff($array_etiquetas_actuales, $valores_en_comun));

        foreach($etiquetas_para_eliminar as $id_etiqueta_eliminar) {
            $id_etiqueta_eliminar = intval($id_etiqueta_eliminar) + 0;
            if($id_etiqueta_eliminar > 0) {
                if(in_array($id_tipodocumento, $cpe)) {
                    $etiquetaxdocumento_bd = Etiquetaxdocumento::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat: and id_etiqueta = :id_etiqueta:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodocumento, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat, 'id_etiqueta' => $id_etiqueta_eliminar)));
                } else {
                    $etiquetaxdocumento_bd = Etiquetaxdocumento::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat: and id_etiqueta = :id_etiqueta:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodocumento, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat, 'id_etiqueta' => $id_etiqueta_eliminar)));
                }
                
                if($etiquetaxdocumento_bd) {
                    $etiquetaxdocumento_bd->estado = 'inactivo';
                    $etiquetaxdocumento_bd->fecha_inactivo = date("Y-m-d H:i:s");
                    
                    try {
                        if(!$etiquetaxdocumento_bd->save()) {
                            $this->db->rollback();
        
                            $msg = '';
                            foreach ($etiquetaxdocumento_bd->getMessages() as $message) {
                                $msg = $msg.$message."</br>\n";
                            }
            
                            $resp['respuesta'] = 'error';
                            $resp['titulo'] = 'Error';
                            $resp['mensaje'] = 'Error al guardar la etiqueta '.$msg;
                            return $resp;
                        }
                    } catch (Exception $e) {
                        $this->saveLogger($e);
                        $this->db->rollback();
        
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = $e->getMessage();
                        return $resp;
                    }
                }
            }
        }

        foreach($etiquetas_nuevas as $id_etiqueta_activada) {
            $id_etiqueta_activada = intval($id_etiqueta_activada) + 0;
            if($id_etiqueta_activada > 0) {
                if(in_array($id_tipodocumento, $cpe)) {
                    $etiquetaxdocumento_bd = Etiquetaxdocumento::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat: and id_etiqueta = :id_etiqueta:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodocumento, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat, 'id_etiqueta' => $id_etiqueta_activada)));
                } else {
                    $etiquetaxdocumento_bd = Etiquetaxdocumento::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat: and id_etiqueta = :id_etiqueta:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodocumento, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat, 'id_etiqueta' => $id_etiqueta_activada)));
                }
    
                if($etiquetaxdocumento_bd) {
                    $etiquetaxdocumento_bd->estado = 'activo';
                    $etiquetaxdocumento_bd->idusuario = $idusuario;
                } else {
                    $etiquetaxdocumento_bd = new Etiquetaxdocumento();
                    $etiquetaxdocumento_bd->id_contribuyente = $id_contribuyente;
                    $etiquetaxdocumento_bd->id_tipodoc_electronico = $id_tipodocumento;
                    $etiquetaxdocumento_bd->serie_comprobante = $serie_comprobante;
                    $etiquetaxdocumento_bd->numero_comprobante = $numero_comprobante;
                    $etiquetaxdocumento_bd->tipo_envio_sunat = $tipo_envio_sunat;
                    $etiquetaxdocumento_bd->id_etiqueta = $id_etiqueta_activada;
                    $etiquetaxdocumento_bd->fecha_registro = date("Y-m-d H:i:s");
                    $etiquetaxdocumento_bd->idusuario = $idusuario;
                    $etiquetaxdocumento_bd->estado = 'activo';
                }
                
                try {
                    if(!$etiquetaxdocumento_bd->save()) {
                        $this->db->rollback();
        
                        $msg = '';
                        foreach ($etiquetaxdocumento_bd->getMessages() as $message) {
                            $msg = $msg.$message."</br>\n";
                        }
        
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error';
                        $resp['mensaje'] = 'Error al guardar la etiqueta '.$msg;
                        return $resp;
                    }
                } catch (Exception $e) {
                    $this->saveLogger($e);
                    $this->db->rollback();
        
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = $e->getMessage();
                    return $resp;
                }
            }
        }
        
        $this->db->commit();
        $resp['respuesta'] = 'ok';
        return $resp;
    }

    public function validar_existencia_etiquetas($id_contribuyente, $array_etiquetas) {
        if(!is_array($array_etiquetas)) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['titulo'] = 'error_array';
            $resp['mensaje'] = 'El array de etiquetas es inválido';
            return $resp;
        }

        foreach($array_etiquetas as $id_etiqueta) {
            $id_etiqueta = intval($id_etiqueta) + 0;
            if($id_etiqueta > 0) {
                $etiqueta = Etiqueta::findFirst(array("id_contribuyente = :id_contribuyente: and id_etiqueta = :id_etiqueta:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_etiqueta' => $id_etiqueta)));
                if(!$etiqueta) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['titulo'] = 'error_id_etiqueta';
                    $resp['mensaje'] = 'No Existe la Etiqueta';
                    return $resp;
                }
            }
        }

        $resp['respuesta'] = 'ok';
        return $resp;
    }
}
?>