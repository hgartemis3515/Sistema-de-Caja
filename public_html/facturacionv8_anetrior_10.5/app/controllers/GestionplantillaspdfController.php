<?php

class GestionplantillaspdfController extends ControllerBase
{
    public function indexAction() {
		$this->setTitle('Gestión de Plantillas PDF');
        $this->view->setTemplateAfter('template_new');
     
        $this->assets
        ->addCss($this->baseUri . "public/css/new_style.css");

        $this->assets
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/switchery.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/uniform.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/selects/select2.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/datatables.min.js")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/uploaders/fileinput/fileinput.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/media/cropper.min.js?i=v2")

			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js?i=v3")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js?i=v32")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js?i=v3")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/buttons.min.js?i=v3")

			
			->addJs($this->baseUri . "public/template_new/global_assets/js/demo_pages/datatables_extension_colvis.js")
			
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/ui/moment/moment.min.js")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/pickers/daterangepicker.js")
			->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js")

            ->addJs($this->baseUri . "public/template/assets/js/pages/components_thumbnails.js")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/media/fancybox.min.js")

            ->addJs($this->baseUri . "public/js/subir_imagen.js?i=v2")
            ->addJs($this->baseUri . "public/template_new/theme_1/js/app.js?i=v2")
            ->addJs($this->baseUri . "public/js/gestionplantillaspdf.js?i=".rand()); 

            $ubigeo = SunatCodigoubigeo::find();
            $this->view->ubigeo = $ubigeo;
    
            $tipo_identidad = SunatTipodocidentidad::find();
            $this->view->tipo_identidad = $tipo_identidad;
    }

    public function guardarPlantillaAction() {
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

            //Datos de Formulario
            $datapost = $this->request->getPost();
            $tipos_comprobantes = empty($datapost['tipos_documentos_ids'])?array():explode(',', $datapost['tipos_documentos_ids']);
            $id_contribuyente = intval($datapost['id_contribuyente']);
            $id_plantillapdf = intval($datapost['id_plantilla_pdf']);
            $nombre_plantilla = $datapost['nombre_plantilla'];
            $select_tamanio = $datapost['select_tamanio'];
            $select_tipo = $datapost['select_categoria'];
            $preview = $datapost['src_img_upload'];

            $tipos_comprobantes_validos = array('03', '01', '07', '08', '77', '88', '09', '31', '99');
            $tipos_comprobantes = array_filter($tipos_comprobantes);
			if(count($tipos_comprobantes) > 0) {
				foreach($tipos_comprobantes as $id_tipo_doc) {
					if(!in_array($id_tipo_doc, $tipos_comprobantes_validos)) {
						$resp['respuesta'] = 'error';
						$resp['titulo'] = 'Error Tipo Doc.';
						$resp['mensaje'] = 'El Tipo de Documento Seleccionado no es Válido!';
						echo json_encode($resp);
						exit();
					}
				}
			}

            if(!empty($id_contribuyente)) {
                $propietario_plantilla = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
                if(!$propietario_plantilla) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error';
                    $resp['mensaje'] = 'El ID del contribuyente ingresado no es válido';
                    echo json_encode($resp);
                    exit();
                }
            }

            if(empty($nombre_plantilla)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error Tipo Doc.';
                $resp['mensaje'] = 'Debes ingresar un nombre para la plantilla!';
                echo json_encode($resp);
                exit();
            }

            if(!in_array($select_tamanio, array('ticket', 'a4'))) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error Tamaño';
                $resp['mensaje'] = 'Error, no se reconoce el tamaño';
                echo json_encode($resp);
                exit();
            }

            if(!in_array($select_tipo, array('normal', 'personalizado'))) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error Tamaño';
                $resp['mensaje'] = 'Error, no se reconoce el tamaño';
                echo json_encode($resp);
                exit();
            }

            if(empty($preview)) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error Tamaño';
                $resp['mensaje'] = 'Debes ingresar el preview para la plantilla';
                echo json_encode($resp);
                exit();
            }

            $plantillapdf = Plantillapdf::findFirst(array("id_plantillapdf = :id_plantillapdf:", 'bind' => array('id_plantillapdf' => $id_plantillapdf)));
            if(!$plantillapdf) {
                $plantillapdf = new Plantillapdf();
            }

            $plantillapdf->nombre = $nombre_plantilla;
            $plantillapdf->tamanio = $select_tamanio;
            $plantillapdf->tipo = $select_tipo;
            $plantillapdf->ids_tipodocelectronico = json_encode($tipos_comprobantes);
            $plantillapdf->id_contribuyente = (empty($id_contribuyente))?null:$id_contribuyente;
            $plantillapdf->estado = 'activo';
            $plantillapdf->preview = $preview;

            try {
                if(!$plantillapdf->save()) {
                    $msg = '';
                    foreach ($plantillapdf->getMessages() as $message) {
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
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en BD';
                $resp['mensaje'] = 'Obtenemos el siguiente error: '.$e->getMessage();
                echo json_encode($resp);
                exit();
            }

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = 'Perfecto!';
            $resp['mensaje'] = 'Hemos guardado correctamente la plantilla';
            echo json_encode($resp);
            exit();
        }
    }
    
   public function getListaPlantillaspdfAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $auth = $this->session->get('authv8');
            $idusuario = $auth['idusuario'];
            $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
            if(!$usuario) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error en Código';
                $msj['mensaje'] = 'Lo sentimos! Debes generar un código, no se permiten campos vacíos.';
                echo json_encode($msj);
                exit();
            }

            $plantillas = Plantillapdf::find(array("estado = :estado:", 'bind' => array('estado' => 'activo')));
            $array_lista = array();
            foreach($plantillas as $item) {
                $opciones = '<ul class="icons-list text-center">
                                <li class="dropdown">
                                    <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li><a onclick="editar_plantilla('.$item->id_plantillapdf.')" data-idplantillapdf="'.$item->id_plantillapdf.'" href="javascript:void(0)"><i class="icon-pencil4"></i> Editar</a></li>
                                        <li><a onclick="eliminar_plantilla('.$item->id_plantillapdf.')" data-idplantillapdf="'.$item->id_plantillapdf.'" href="javascript:void(0)"><i class="icon-user-cancel"></i> Eliminar</a></li>
                                    </ul>
                                </li>
                            </ul>';

                $tipo_cpe = json_decode($item->ids_tipodocelectronico);
                $html_tipo_docs = '';
                foreach($tipo_cpe as $id_documento) {
                    if($id_documento == '01') {
                        $html_tipo_docs = $html_tipo_docs.'<p><img title="FACTURAS" src="/facturacionv8/img/factura.svg" style="width: 22px;"> Facturas </p>';
                    } else if($id_documento == '03') {
                        $html_tipo_docs = $html_tipo_docs.'<p><img title="BOLETAS" src="/facturacionv8/img/boleta.svg" style="width: 22px;"> Boletas </p>';
                    } else if($id_documento == '07') {
                        $html_tipo_docs = $html_tipo_docs.'<p><img title="NOTAS DE CRÉDITO" src="/facturacionv8/img/nota_credito.svg" style="width: 22px;"> Nota Crédito </p>';
                    } else if($id_documento == '08') {
                        $html_tipo_docs = $html_tipo_docs.'<p><img title="NOTAS DE DÉBITO" src="/facturacionv8/img/nota_debito.svg" style="width: 22px;"> Nota Débito </p>';
                    } else if($id_documento == '09') {
                        $html_tipo_docs = $html_tipo_docs.'<p><img title="GUÍA REMISIÓN" src="/facturacionv8/img/svg/guia_remision.svg" style="width: 22px;"> Guía Remisión </p>';
                    } else if($id_documento == '77') {
                        $html_tipo_docs = $html_tipo_docs.'<p><img title="NOTA DE VENTA" src="/facturacionv8/img/nota_venta.svg" style="width: 22px;"> Nota Venta </p>';
                    } else if($id_documento == '88') {
                        $html_tipo_docs = $html_tipo_docs.'<p><img title="COTIZACIÓN" src="/facturacionv8/img/cotizacion.svg" style="width: 22px;"> Cotización </p>';
                    }
                }

                if($item->tamanio == 'ticket') {
                    $imagen = "<img src='$item->preview' alt='' class='image-preview' width='100px' height='250px'>";
                } else {
                    $imagen = "<img src='$item->preview' alt='' class='image-preview' width='100px' height='140px'>";
                }

                $array_lista[] = array(
                    $item->id_plantillapdf,
                    $item->nombre.'<br />'."<strong>".strtoupper($item->tamanio)."</strong>",
                    $imagen,
                    $html_tipo_docs,
                    $opciones
                );
            }

            $resp['respuesta'] = 'ok';
            $resp['lista'] = $array_lista;
            echo json_encode($resp);
            exit();
        }
    }

    public function getDataPlantillapdfAction(){
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
           
            $datapost = $this->request->getPost();
            $id_plantillapdf = !isset($datapost['idplantillapdf'])?0:intval($datapost['idplantillapdf']) + 0;
          
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

            $plantillapdf = Plantillapdf::findFirst(array("id_plantillapdf = :id_plantillapdf: and estado = 'activo'", 'bind' => array('id_plantillapdf' => $id_plantillapdf)));
            if(!$plantillapdf) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error!';
                $resp['mensaje'] = 'Lo sentimos! No hemos conseguido la plantilla, intenta nuevamente';
                echo json_encode($resp);
                exit();

            }

            $resp['respuesta'] = 'ok';
            $resp['plantillapdf'] = $plantillapdf;
            $resp['tipo_docs_validos'] = json_decode($plantillapdf->ids_tipodocelectronico);
            echo json_encode($resp);
            exit();

        }
    }

    public function eliminarPlantillapdfAction(){
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $id_plantillapdf = !isset($datapost['idplantillapdf'])?0:intval($datapost['idplantillapdf']) + 0;
         
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

            $plantillapdf = Plantillapdf::findFirst(array("id_plantillapdf = :id_plantillapdf: and estado = 'activo'", 'bind' => array('id_plantillapdf' => $id_plantillapdf)));
            if(!$plantillapdf) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error!';
                $resp['mensaje'] = 'Lo sentimos! No hemos conseguido la plantilla, intenta nuevamente';
                echo json_encode($resp);
                exit();

            }
            $plantillapdf->estado = 'inactivo';

            try {
                if(!$plantillapdf->save()) {
                    $msg = '';
                    foreach ($plantillapdf->getMessages() as $message) {
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
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en BD';
                $resp['mensaje'] = 'Obtenemos el siguiente error: '.$e->getMessage();
                echo json_encode($resp);
                exit();
            }

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = '¡Excelente!';
            $resp['mensaje'] = 'La plantilla  se ha eliminado correctamente!';
            echo json_encode($resp);
            exit();


        }
    }
}