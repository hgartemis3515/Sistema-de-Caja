<?php

class GestiondeproveedoresController extends ControllerBase
{
    public function indexAction() {
		$this->setTitle('Gestión de Proveedores');
        $this->view->setTemplateAfter('template_new');

        $this->assets
        ->addCss($this->baseUri . "public/css/new_style.css");

        $this->assets
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/switchery.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/styling/uniform.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/forms/selects/select2.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/datatables.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/tables/datatables/extensions/buttons.min.js?i=v2")
            ->addJs($this->baseUri . "public/extras/help-tour/js/jquery.iGuider.js")
            ->addJs($this->baseUri . "public/extras/help-tour/themes/material/iGuider-theme-material.js")
            ->addJs($this->baseUri . "public/extras/help-tour/localization/iGuider-es.js?i=3")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/ui/moment/moment.min.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/pickers/daterangepicker.js")
            ->addJs($this->baseUri . "public/template_new/global_assets/js/plugins/visualization/echarts/echarts.min.js")
            ->addJs($this->baseUri . "public/template_new/theme_1/js/app.js")
            ->addJs($this->baseUri . "public/js/general.js?i=".rand())
            ->addJs($this->baseUri . "public/js/gestionproveedores.js?i=".rand());     

            $ubigeo = SunatCodigoubigeo::find();
            $this->view->ubigeo = $ubigeo;
    
            $tipo_identidad = SunatTipodocidentidad::find();
            $this->view->tipo_identidad = $tipo_identidad;
    }

    public function insertAction(){
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $id_tipodocidentidad = !isset($datapost['type_document'])?'':$datapost['type_document'];
            $id_proveedor = !isset($datapost['idproveedor'])?0:intval($datapost['idproveedor']) + 0;
            $codigo = $datapost['codigo'];
            $num_doc = !isset($datapost['doc_id'])?'':$datapost['doc_id'];
            $razon_social = !isset($datapost['razon_social'])?'':$datapost['razon_social'];
            $direccion_fiscal = !isset($datapost['direccionfiscal'])?'':$datapost['direccionfiscal'];
            $email = !isset($datapost['email'])?'':$datapost['email'];
            $celular = !isset($datapost['celular'])?'':$datapost['celular'];
            $id_cod_ubigeo = !isset($datapost['ubigeo'])?'':$datapost['ubigeo'];
            $detalle_adicional = !isset($datapost['detalle_adicional'])?'':$datapost['detalle_adicional'];

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

            //Verificando campos 
            if(empty($codigo)){
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Lo sentimos! Debes generar un código, no se permiten campos vacíos.';
                echo json_encode($msj);
                exit();
            }

            $tipodocidentidad = SunatTipodocidentidad::findFirst(array("id_tipodocidentidad = :id_tipodocidentidad:", 'bind' => array('id_tipodocidentidad' => $id_tipodocidentidad)));
            if(!$tipodocidentidad){
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Lo siento, ese  tipo de Documento de Identidad no existe. Por favor selecciona uno Válido';
                echo json_encode($msj);
                exit();
            }

            if(empty($num_doc)){
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Lo sentimos! Debes escribir el Documento de Identidad, no se permiten campos vacíos.';
                echo json_encode($msj);
                exit();
            }
            if(empty($razon_social)){
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Lo sentimos! Debes escribir el Razón Social o Nombre Completo del proveedor,  no se permiten campos vacíos.';
                echo json_encode($msj);
                exit();
            } 

            //Consultas
            if($id_cod_ubigeo != '') {
                $sql_ubigeo = SunatCodigoubigeo::findFirst(array("codigo_ubigeo = :codigo_ubigeo:", 'bind' => array('codigo_ubigeo' => $id_cod_ubigeo)));
                if(!$sql_ubigeo){
                    $msj['respuesta'] = 'error';
                    $msj['titulo'] = 'Error';
                    $msj['mensaje'] = 'Lo siento, ese  código de Ubicación Geográfica no existe. Por favor selecciona un ubigeo Válido';
                    echo json_encode($msj);
                    exit();
                }
            }
            $nuevo_registro = false;

            $proveedor = Proveedor::findFirst(array("id_proveedor = :id_proveedor: and id_contribuyente = :id_contribuyente:", 'bind' => array('id_proveedor' => $id_proveedor, 'id_contribuyente' => $usuario->id_contribuyente)));
            if(!$proveedor) {
                $proveedor = Proveedor::findFirst(array("num_doc = :num_doc: and id_contribuyente = :id_contribuyente: and id_tipodocidentidad = :id_tipodocidentidad:", 'bind' => array('num_doc' => $num_doc, 'id_contribuyente' => $usuario->id_contribuyente, 'id_tipodocidentidad' => $id_tipodocidentidad)));
                if(!$proveedor) {
                    $nuevo_registro = true; 
                    $proveedor = new proveedor();
                    $proveedor->fecha_registro = date('Y-m-d H:i:s');
                    $proveedor->id_contribuyente = $usuario->id_contribuyente;
                    $proveedor->estado = 'activo';
                } else {
                    $msj['respuesta'] = 'error';
                    $msj['titulo'] = 'Error';
                    $msj['mensaje'] = 'Ya existe un usuario con el tipo de documento y número de documento!, por favor revise el listado de usuarios!';
                    echo json_encode($msj);
                    exit();
                }
            }

            if($nuevo_registro) {
                $registro_codigo = Proveedor::findFirst(array("codigo = :codigo:", 'bind' => array('codigo' => $codigo)));
            } else {
                $registro_codigo = Proveedor::findFirst(array("codigo = :codigo: and id_proveedor <> :id_proveedor:", 'bind' => array('codigo' => $codigo, 'id_proveedor' => $proveedor->id_proveedor)));
            }

            if($registro_codigo) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El código ya existe, por favor debes generar o ingresar un nuevo código!.';
                echo json_encode($resp);
                exit();
            }

            $proveedor->id_tipodocidentidad = $id_tipodocidentidad;
            $proveedor->num_doc = $num_doc;
            $proveedor->razon_social = $razon_social;
            $proveedor->direccion_fiscal = $direccion_fiscal;
            $proveedor->id_cod_ubigeo = $id_cod_ubigeo;
            $proveedor->codigo = $codigo;
            $proveedor->email = $email;
            $proveedor->celular = $celular;
            $proveedor->detalle_adicional = $detalle_adicional;

            try {
                if(!$proveedor->save()) {
                    $msg = '';
                    foreach ($proveedor->getMessages() as $message) {
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
            $resp['mensaje'] = 'Se guardaron correctamente los datos del proveedor!';
            $resp['idproveedor'] = $proveedor->id_proveedor;
            echo json_encode($resp);
            exit();
        }
    }

    public function getListaProveedoresAction() {
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

            $lista_proveedores = Proveedor::find(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            $lista = array();

            foreach($lista_proveedores as $item) {
                if($item->estado == 'activo') {
                    $estado = '<span class="label label-success">Activo</span>';
                } else {
                    $estado = '<span class="label label-danger">Inactivo</span>';
                }
                $documento_identidad = SunatTipodocidentidad::findFirst(array("id_tipodocidentidad = :id_tipodocidentidad:", 'bind' => array('id_tipodocidentidad' => $item->id_tipodocidentidad)));
                $opciones = '
                <ul class="icons-list text-center">
                    <li class="dropdown">
                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="icon-menu9"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li><a onclick="editar_proveedor('.$item->id_proveedor.')" data-idproveedor="'.$item->id_proveedor.'" href="javascript:void(0)"><i class="icon-pencil4"></i> Editar</a></li>
                            <li><a onclick="eliminar_proveedor('.$item->id_proveedor.')" data-idproveedor="'.$item->id_proveedor.'" href="javascript:void(0)"><i class="icon-user-cancel"></i> Eliminar</a></li>
                        </ul>
                    </li>
                </ul>';
                $lista[] = array(date("d-m-Y", strtotime($item->fecha_registro)), $documento_identidad->nombre, $item->num_doc, $item->codigo, $item->razon_social, $estado, $opciones);
            }

            $resp['respuesta'] = 'ok';
            $resp['lista'] = $lista;
            echo json_encode($resp);
            exit();
        }
    }

    public function getDataProveedoresAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $id_proveedor = !isset($datapost['idproveedor'])?0:intval($datapost['idproveedor']) + 0;
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

            $proveedores = Proveedor::findFirst(array("id_proveedor = :id_proveedor: and id_contribuyente = :id_contribuyente:", 'bind' => array('id_proveedor' => $id_proveedor, 'id_contribuyente' => $usuario->id_contribuyente)));
            if(!$proveedores) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El proveedor seleccionado no existe!';
                echo json_encode($resp);
                exit();
            }

            $resp['respuesta'] = 'ok';
            $resp['proveedores'] = $proveedores;
            echo json_encode($resp);
            exit();
        }
    }

    public function eliminarProveedoresAction() {
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $id_proveedor = !isset($datapost['idproveedor'])?0:intval($datapost['idproveedor']) + 0;
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

            $proveedor = Proveedor::findFirst(array("id_proveedor = :id_proveedor: and id_contribuyente = :id_contribuyente:", 'bind' => array('id_proveedor' => $id_proveedor, 'id_contribuyente' => $usuario->id_contribuyente)));
            if(!$proveedor) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El proveedor seleccionado no existe!';
                echo json_encode($resp);
                exit();
            }

            $proveedor->estado = 'inactivo';

            try {
                if(!$proveedor->save()) {
                    $msg = '';
                    foreach ($proveedor->getMessages() as $message) {
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
            $resp['mensaje'] = 'El proveedor fue desactivado correctamente!';
            $resp['proveedor'] = $proveedor->id_proveedor;
            echo json_encode($resp);
            exit();
        }
    }

    public function getListaSugerenciasProveedorAction() {
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
                $query = "select * from proveedor where (num_doc like :termino or razon_social like :termino) and id_contribuyente = ".$usuario->id_contribuyente;
            } else {
                //https://desarrolloweb.com/articulos/2087.php
                $termino = "'".$termino."'";
                $query = "select * from proveedor where (MATCH (razon_social, num_doc) AGAINST (:termino)) and id_contribuyente = ".$usuario->id_contribuyente;
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
                $lista[] = array('id' => $fila->id_proveedor, 'text' => $fila->num_doc.' - '.$fila->razon_social);
            }

            if(count($lista) <= 0) {
                $lista[] = array('id' => '', 'text' => '');
            }
			
            $resp['items'] = $lista;
            echo json_encode($resp);
            exit();
		}
    }
}
?>