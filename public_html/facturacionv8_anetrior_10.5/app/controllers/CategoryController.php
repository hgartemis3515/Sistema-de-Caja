<?php
class CategoryController extends ControllerBase
{
	public function indexAction() {
		$this->setTitle('Categoría');
        $this->view->setTemplateAfter('template_new');

        $this->assets
        ->addCss($this->baseUri . "public/css/main-indigo.css")
        ->addCss($this->baseUri . "public/css/new_style.css");
        $this->assets
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/switchery.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/uniform.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/selects/select2.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/datatables.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/plugins/notifications/sweet_alert.min.js?i=v2")
            ->addJs($this->baseUri . "public/template/assets/js/core/app.js?i=v2")
            ->addJs($this->baseUri . "public/js/general.js?j=".rand()) 
            ->addJs($this->baseUri . "public/js/category.js?i=v3");

            $codigosunat = SunatCodigoproducto::find();
            $this->view->codigosunat = $codigosunat;
    }
    
    public function insertAction(){
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $idcategoria = !isset($datapost['idcategoria'])?0:intval($datapost['idcategoria']) + 0;
            $codigo = empty($datapost['codigo'])?'':$datapost['codigo'];
            $sunatcodigoproducto_id = empty($datapost['codigosunat'])?null:$datapost['codigosunat'];
            $nombre_categoria = $datapost['nombre_categoria']; 
            $descripcion_categoria = $datapost['descripcion_categoria'];

            $codigo_cuenta_contable = isset($datapost['codigo_cuenta_contable'])?$datapost['codigo_cuenta_contable']:null; 
            $codigo_centro_costo = isset($datapost['codigo_centro_costo'])?$datapost['codigo_centro_costo']:null;
            $codigo_presupuesto = isset($datapost['codigo_presupuesto'])?$datapost['codigo_presupuesto']:null;

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
                $msj['titulo'] = 'Error en Código';
                $msj['mensaje'] = 'Lo sentimos! Debes generar un código, no se permiten campos vacíos.';
                echo json_encode($msj);
                exit();
            }
            if(empty($nombre_categoria)){
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error en Nombre';
                $msj['mensaje'] = 'Lo sentimos! Debes escribir un Nombre de la Categoría, no se permiten campos vacíos.';
                echo json_encode($msj);
                exit();
            }
             

            /*
            //Consultas
            $sunatcodigoproducto = SunatCodigoproducto::findFirst(array("id_codigoproducto = :id_codigoproducto:", 'bind' => array('id_codigoproducto' => $sunatcodigoproducto_id)));
            if(!$sunatcodigoproducto){
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error en el Código';
                $msj['mensaje'] = 'Lo siento, el código seleccionado no existe!';
                echo json_encode($msj);
                exit();
            }*/

            $categoria = Categoria::findFirst(array("idcategoria = :idcategoria: and id_contribuyente = :id_contribuyente:", 'bind' => array('idcategoria' => $idcategoria, 'id_contribuyente' => $usuario->id_contribuyente)));
            $nuevo_registro = false;

            if(!$categoria) {
                $nuevo_registro = true; 
                $categoria = new Categoria();
                $categoria->fecha_registro = date('Y-m-d H:i:s');
                $categoria->id_contribuyente = $usuario->id_contribuyente;
            }

            if($nuevo_registro) {
                $categoria_codigo = Categoria::findFirst(array("codigo = :codigo: and id_contribuyente = :id_contribuyente:", 'bind' => array('codigo' => $codigo, 'id_contribuyente' => $usuario->id_contribuyente)));
                $nombre_categoria_row = Categoria::findFirst(array("nombre = :nombre: and id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('nombre' => $nombre_categoria, 'id_contribuyente' => $usuario->id_contribuyente)));
            } else {
                $categoria_codigo = Categoria::findFirst(array("codigo = :codigo: and idcategoria <> :idcategoria: and id_contribuyente = :id_contribuyente:", 'bind' => array('codigo' => $codigo, 'idcategoria' => $categoria->idcategoria, 'id_contribuyente' => $usuario->id_contribuyente)));
                $nombre_categoria_row = Categoria::findFirst(array("nombre = :nombre: and id_contribuyente = :id_contribuyente: and idcategoria <> :idcategoria: and estado = 'activo'", 'bind' => array('nombre' => $nombre_categoria, 'idcategoria' => $categoria->idcategoria, 'id_contribuyente' => $usuario->id_contribuyente)));
            }

            if($nombre_categoria_row) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El nombre de la categoría ya existe!.';
                echo json_encode($resp);
                exit();
            }

            if($categoria_codigo) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error';
                $resp['mensaje'] = 'El código ya existe, por favor debes generar o ingresar un nuevo código!.';
                echo json_encode($resp);
                exit();
            }

            $categoria->codigo = $codigo;
            $categoria->nombre = $nombre_categoria;
            if(!empty($descripcion_categoria)){
                $categoria->descripcion = $descripcion_categoria;
            }
            $categoria->id_codigoproducto = $sunatcodigoproducto_id;

            $categoria->codigo_cuenta_contable = $codigo_cuenta_contable;
            $categoria->codigo_centro_costo = $codigo_centro_costo;
            $categoria->codigo_presupuesto = $codigo_presupuesto;
            
            try {
                if(!$categoria->save()) {
                    $msg = '';
                    foreach ($categoria->getMessages() as $message) {
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
            $resp['mensaje'] = ($nuevo_registro)?'La Categoría "'.$categoria->nombre.'" con código: '.$categoria->codigo.', se ha guardado correctamente!':'La Categoría "'.$categoria->nombre.'" con código: '.$categoria->codigo.', se ha Editado correctamente!';
            $resp['idcategoria'] = $categoria->idcategoria;
            echo json_encode($resp);
            exit();
        }
    }

    public function getListaCategoriasAction() {
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
                $msj['mensaje'] = 'Debes iniciar sesión.';
                echo json_encode($msj);
                exit();
            }

            $lista = Categoria::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            $array_lista = array();
            foreach($lista as $item) {
                if($item->estado == 'activo') {
                    $estado = '<span class="label label-success">Activo</span>';
                } else {
                    $estado = '<span class="label label-danger">Inactivo</span>';
                }
                
                $opciones = '<ul class="icons-list text-center">
                                <li class="dropdown">
                                    <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li><a onclick="editar_categoria('.$item->idcategoria.')" data-idcategoria="'.$item->idcategoria.'" href="javascript:void(0)"><i class="icon-pencil4"></i> Editar</a></li>
                                        <li><a onclick="eliminar_categoria('.$item->idcategoria.')" data-idcategoria="'.$item->idcategoria.'" href="javascript:void(0)"><i class="icon-user-cancel"></i> Eliminar</a></li>
                                    </ul>
                                </li>
                            </ul>';
                $array_lista[] = array(
                    date("d-m-Y", strtotime($item->fecha_registro)), 
                    $item->idcategoria,
                    $item->codigo, 
                    $item->nombre, 
                    $estado, 
                    $opciones
                );
            }

            $resp['respuesta'] = 'ok';
            $resp['lista'] = $array_lista;
            echo json_encode($resp);
            exit();
        }
    }

    public function eliminarCategoriaAction() {
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

            $idcategoria = !isset($datapost['idcategoria'])?0:intval($datapost['idcategoria']) + 0;
            $categoria = Categoria::findFirst(array("idcategoria = :idcategoria: and id_contribuyente = :id_contribuyente:", 'bind' => array('idcategoria' => $idcategoria, 'id_contribuyente' => $usuario->id_contribuyente)));
             if(!$categoria) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error en Categoría';
                $msj['mensaje'] = 'La categoría que intenta eliminar no existe.';
                echo json_encode($msj);
                exit();
            }

            $productos = Producto::find(array("id_categoria = :id_categoria:", 'bind' => array('id_categoria' => $categoria->idcategoria)));
            if(count($productos) > 0) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'No podemos desactivar la categoría, porque aún hay productos que utilizan esta categoría!';
                echo json_encode($msj);
                exit();
            }

            $categoria->estado = 'inactivo';
            try {
                if(!$categoria->save()) {
                    $msg = '';
                    foreach ($categoria->getMessages() as $message) {
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
            $resp['mensaje'] = 'Se desactivó correctamente la categoría con id = '.$categoria->idcategoria;
            $resp['idcategoria'] = $categoria->idcategoria;
            echo json_encode($resp);
            exit();
        }
    }

    public function getDataCategoriaAction() {
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

            $idcategoria = !isset($datapost['idcategoria'])?0:intval($datapost['idcategoria']) + 0;
            $categoria = Categoria::findFirst(array("idcategoria = :idcategoria: and id_contribuyente = :id_contribuyente:", 'bind' => array('idcategoria' => $idcategoria, 'id_contribuyente' => $usuario->id_contribuyente)));
            if(!$categoria) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error en Categoría';
                $msj['mensaje'] = 'La categoría que intenta editar no existe.';
                echo json_encode($msj);
                exit();
            }

            $resp['respuesta'] = 'ok';
            $resp['categoria'] = $categoria;
            echo json_encode($resp);
            exit();
        }
    }

    public function getSugerenciasCategoriasAction() {
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

            $array_lista = array();
            $termino = trim($datapost['keyword']);
            if(strlen($termino) <= 3) {
                echo json_encode($array_lista);
                exit();
            }

            $termino = '%'.$termino.'%';
            $query = "SELECT nombre, descripcion, codigo, fecha_registro FROM `categoria` where id_contribuyente = $contribuyente->id_contribuyente and nombre IS NOT null and nombre LIKE :termino LIMIT 20";
            
			try {
                $sentencia = $this->db->prepare($query);
                $sentencia->bindParam(':termino', $termino, PDO::PARAM_STR);
                $sentencia->execute();
            } catch (Exception $e) {
                $this->saveLogger($e);
                echo 'Excepción capturada: ',  $e->getMessage(), "\n";
                exit();
            }

            $n = 0;
            while($fila = $sentencia->fetch()) {
                $fila = (object)$fila;
                $array_lista[] = array('nombre_cate' => $fila->nombre, 'descripcion' => $fila->descripcion, 'codigo' => $fila->codigo, 'fecha_registro' => date("d-m-Y", strtotime($fila->fecha_registro)), 'texto' => $fila->nombre.' - '.date("d-m-Y", strtotime($fila->fecha_registro)));
            }

            echo json_encode($array_lista);
            exit();
		}
	}
}
?>