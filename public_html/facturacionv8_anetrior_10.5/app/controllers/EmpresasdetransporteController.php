<?php

class EmpresasdetransporteController extends ControllerBase

{

    public function indexAction() {
		$this->setTitle('Empresas de transporte');
        $this->view->setTemplateAfter('main');

        $this->assets
        ->addCss($this->baseUri . "public/extras/jqgrid/css/ui.jqgrid.css")
        ->addCss($this->baseUri . "public/css/main-indigo.css")
        ->addCss($this->baseUri . "public/extras/jqgrid/css/custom.css");
        
        $this->assets
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/switchery.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/styling/uniform.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/forms/selects/select2.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/plugins/tables/datatables/datatables.min.js?i=v2")
        ->addJs($this->baseUri . "public/template/assets/js/core/app.js?i=v2")
        ->addJs($this->baseUri . "public/extras/jqgrid/js/i18n/grid.locale-es.js?i=v2")
		->addJs($this->baseUri . "public/extras/jqgrid/js/jquery.jqGrid.min.js?i=v2")
        ->addJs($this->baseUri . "public/js/apisunat.js?i=v2")
        ->addJs($this->baseUri . "public/js/general.js?j=".rand()) 
        ->addJs($this->baseUri . "public/js/empresatransporte.js?i=".rand());

        $tipo_identidad = SunatTipodocidentidad::find();
        $this->view->tipo_identidad = $tipo_identidad;
    }

    public function saveAction(){
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $id_etransporte = !isset($datapost['id_etransporte'])?0:intval($datapost['id_etransporte']) + 0;
            $tipo_doc = !isset($datapost['tipo_doc'])?'':$datapost['tipo_doc'];
            $num_doc = !isset($datapost['cliente_numerodocumento'])?'':$datapost['cliente_numerodocumento'];
            $razon_social = !isset($datapost['cliente_nombre'])?'':$datapost['cliente_nombre'];
            $direccion_fiscal = !isset($datapost['cliente_direccion'])?'':$datapost['cliente_direccion'];
            $telefono = !isset($datapost['cliente_telefono'])?'':$datapost['cliente_telefono'];
            $id_cod_ubigeo = !isset($datapost['select_codigoubigeo'])?'':$datapost['select_codigoubigeo'];
            $detalle_adicional = !isset($datapost['detalle_adicional'])?'':$datapost['detalle_adicional'];
            $list_conductores = json_decode($datapost['list_conductores']);
            $lista_vehiculos = json_decode($datapost['lista_vehiculos']);
           
            //Verificando campos
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
                $msj['mensaje'] = 'Lo sentimos! Debes escribir el Razón Social o Nombre Completo,  no se permiten campos vacíos.';
                echo json_encode($msj);
                exit();
            } 
            if(empty($direccion_fiscal)){
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Lo sentimos! Debes escribir la dirección de tu empresa';
                echo json_encode($msj);
                exit();
            }
            if(empty($telefono)){
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Lo sentimos! Debes escribir un número de teléfono';
                echo json_encode($msj);
                exit();
            }
            if(empty($id_cod_ubigeo)){
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Lo sentimos! Debes seleccionar un ubigueo!';
                echo json_encode($msj);
                exit();
            }
            if(empty($list_conductores)) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Debes Agregar al menos un conductor!';
                echo json_encode($msj);
                exit();
            }

            if(empty($lista_vehiculos)) {
                $msj['respuesta'] = 'error';
                $msj['titulo'] = 'Error';
                $msj['mensaje'] = 'Debes Agregar al menos un vehiculo!';
                echo json_encode($msj);
                exit();
            }
            //Verificando sesion
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
            //Para agregar la empresa
            $empresa_transporte = EmpresaTransporte::findFirst(array("id_etransporte = :id_etransporte: and id_contribuyente = :id_contribuyente:", 'bind' => array('id_etransporte' => $id_etransporte, 'id_contribuyente' => $usuario->id_contribuyente)));
            if(!$empresa_transporte) {
                $empresa_transporte = new EmpresaTransporte();
                $empresa_transporte->id_contribuyente = $usuario->id_contribuyente;

                $consulta_num_doc = EmpresaTransporte::findFirst(array("num_doc = :num_doc: and id_contribuyente = :id_contribuyente:", 'bind' => array('num_doc' => $num_doc, 'id_contribuyente' => $usuario->id_contribuyente)));
                if($consulta_num_doc) {
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error en Usuario';
                    $resp['mensaje'] = 'Ya existe una empresa que está utilizando el siguiente número de RUC: '.$num_doc;
                    echo json_encode($resp);
                    exit();
                }
            } else {
                if($empresa_transporte->num_doc != $num_doc) {
                    $empresa_transporte = EmpresaTransporte::findFirst(array("num_doc = :num_doc: and id_contribuyente = :id_contribuyente: and id_etransporte <> :id_etransporte:", 'bind' => array('num_doc' => $num_doc, 'id_contribuyente' => $usuario->id_contribuyente, 'id_etransporte' => $empresa_transporte->id_etransporte)));
                    if($empresa_transporte) {
                        $resp['respuesta'] = 'error';
                        $resp['titulo'] = 'Error en Usuario';
                        $resp['mensaje'] = 'Ya existe una empresa que está utilizando el siguiente número de RUC: '.$num_doc;
                        echo json_encode($resp);
                        exit();
                    }
                }
            }
            
            $empresa_transporte->id_tipodocidentidad = $tipo_doc;
            $empresa_transporte->num_doc = $num_doc;
            $empresa_transporte->razon_social = $razon_social;
            $empresa_transporte->direccion = $direccion_fiscal;
            $empresa_transporte->codigo_ubigeo = $id_cod_ubigeo;
            $empresa_transporte->telefono = $telefono;
            $empresa_transporte->nota = $detalle_adicional;
            $empresa_transporte->estado = 'activo';

            if(!$empresa_transporte->save()) {
                $msg = '';
                foreach ($empresa_transporte->getMessages() as $message) {
                    $msg = $msg.$message."</br>\n";
                }
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en BD';
                $resp['mensaje'] = 'Obtenemos el siguiente error: '.$msg;
                echo json_encode($resp);
                exit();
            }
 
            //Para agregar conductores
            foreach($list_conductores as $item)
            {
                $conductor = Conductor::findFirst(array("id_etransporte = :id_etransporte: and idconductor = :idconductor:", 'bind' => array('id_etransporte' => $id_etransporte, 'idconductor' => $item->idconductor)));
                if(!$conductor) {
                    $conductor = new Conductor();
                    $conductor->id_etransporte = $empresa_transporte->id_etransporte;
                }
               $conductor->id_tipodocidentidad	 = $item->id_tipodocidentidad;
               $conductor->num_doc               = $item->numero_doc_conductor;
               $conductor->nombre_completo       = $item->nombre_completo;
               $conductor->licencia_conducir     = $item->licencia;
               $conductor->tipo_licencia	     = $item->tipo_licencia;
               $conductor->telefono              = $item->telefono;
               $conductor->estado                = 'activo';

              if(!$conductor->save()) {
                    $msg = '';
                    foreach ($conductor->getMessages() as $message) {
                        $msg = $msg.$message."</br>\n";
                    }
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error en BD';
                    $resp['mensaje'] = 'Obtenemos el siguiente error: '.$msg;
                    echo json_encode($resp);
                    exit();
                }
                
            }

            //Para agregar vehiculos
            foreach($lista_vehiculos as $item)
            {
                $vehiculo = Vehiculo::findFirst(array("id_etransporte = :id_etransporte: and id_vehiculo = :id_vehiculo:", 'bind' => array('id_etransporte' => $id_etransporte, 'id_vehiculo' => $item->idvehiculo)));
                if(!$vehiculo) {
                    $vehiculo = new Vehiculo();
                    $vehiculo->id_etransporte = $empresa_transporte->id_etransporte;
                }
               $vehiculo->nro_placa	            = $item->vh_numero_placa;
               $vehiculo->marca                 = $item->vh_marca;
               $vehiculo->const_inscripcion     = $item->vh_const_inscripcion;
               $vehiculo->capacidad             = $item->vh_capacidad;
               $vehiculo->dgh	                = $item->vh_dgh;
               $vehiculo->estado                = 'activo';

              if(!$vehiculo->save()) {
                    $msg = '';
                    foreach ($vehiculo->getMessages() as $message) {
                        $msg = $msg.$message."</br>\n";
                    }
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error en BD';
                    $resp['mensaje'] = 'Obtenemos el siguiente error: '.$msg;
                    echo json_encode($resp);
                    exit();
                }
                
            }
            
            $resp['respuesta'] = 'ok';
            $resp['titulo'] = '¡Excelente!';
            $resp['mensaje'] = 'La empresa de transporte, junto con sus conductores y vehículos se han guardado exitosamente!';
            echo json_encode($resp);
            exit();
        }
    }
    
    public function getListaEmpresaAction()
    {
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
            $lista_empresa = EmpresaTransporte::find(array("id_contribuyente = :id_contribuyente: and estado = 'activo'", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            $array_lista = array();
            foreach($lista_empresa as $item){
                $opciones = '<ul class="icons-list text-center">
                            <li class="dropdown">
                                <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="icon-menu9"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a onclick="edit_empresa('.$item->id_etransporte.')" data-empresatransporte="'.$item->id_etransporte.'" href="javascript:void(0)"><i class="icon-pencil4"></i> Editar</a></li>
                                    <li><a onclick="eliminar_empresa('.$item->id_etransporte.')" data-empresatransporte="'.$item->id_etransporte.'" href="javascript:void(0)"><i class="icon-user-cancel"></i> Eliminar</a></li>
                                </ul>
                            </li>
                        </ul>';

                $array_lista[] = array($item->id_etransporte, $item->num_doc,  $item->razon_social, $item->direccion,  $item->telefono, $opciones);
            }
            $resp['respuesta'] = 'ok';
            $resp['lista'] = $array_lista;
            echo json_encode($resp);
            exit();

        }
    }

    public function getDataEmpresaAction(){
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $id_etransporte = !isset($datapost['idempresa'])?0:intval($datapost['idempresa']) + 0;
            //Verificando sesion
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
            //Consulta
            $empresa_transporte = EmpresaTransporte::findFirst(array("id_etransporte = :id_etransporte: and estado = 'activo'", 'bind' => array('id_etransporte' => $id_etransporte)));
            if(!$empresa_transporte) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error!';
                $resp['mensaje'] = 'Lo sentimos! No hemos conseguido la empresa de transporte, intenta nuevamente';
                echo json_encode($resp);
                exit();

            }
             //Para extraer los conductores 
            $conductor = Conductor::find(array("id_etransporte = :id_etransporte:  and estado = 'activo'", 'bind' => array('id_etransporte' => $id_etransporte)));
            $list_conductor = array();
            foreach($conductor as $item){
                $tipo_identidad = SunatTipodocidentidad::findFirst(array("id_tipodocidentidad = :id_tipodocidentidad:", 'bind' => array('id_tipodocidentidad' => $item->id_tipodocidentidad)));
                $list_conductor[] = array(
                    "idconductor" 			=> $item->idconductor,
                    "id_etransporte" 		=> $item->id_etransporte,
                    "id_tipodocidentidad" 	=> $item->id_tipodocidentidad,
                    "tipo_doc" 	            => $tipo_identidad->nombre,
                    "numero_doc_conductor" 	=> $item->num_doc,
                    "nombre_completo" 		=> $item->nombre_completo,
                    "licencia" 	            => $item->licencia_conducir,
                    "tipo_licencia" 		=> $item->tipo_licencia,
                    "telefono" 		        => $item->telefono

                );
            }

             //Para extraer los vehiculos 
             $vehiculo = Vehiculo::find(array("id_etransporte = :id_etransporte:  and estado = 'activo'", 'bind' => array('id_etransporte' => $id_etransporte)));
             $lista_vehiculo = array();
             foreach($vehiculo as $item){
                 $lista_vehiculo[] = array(
                    "idvehiculo" 			=> $item->id_vehiculo,
                    "id_etransporte" 		=> $item->id_etransporte,
                    "vh_numero_placa" 	    => $item->nro_placa,
                    "vh_marca"              => $item->marca,
                    "vh_const_inscripcion"  => $item->const_inscripcion,
                    "vh_capacidad" 	        => $item->capacidad,
                    "vh_dgh" 		        => $item->dgh
 
                 );
             }

            //Respuesta de retorno
            $resp['respuesta'] = 'ok';
            $resp['etransporte'] = $empresa_transporte;
            $resp['list_conductor'] = $list_conductor;
            $resp['list_vehiculo'] = $lista_vehiculo;
            echo json_encode($resp);
            exit();

        }
    }

    public function eliminarEmpresaAction(){
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $id_etransporte = !isset($datapost['idempresa'])?0:intval($datapost['idempresa']) + 0;
            //Verificando sesion
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
            //Consulta
            $empresa_transporte = EmpresaTransporte::findFirst(array("id_etransporte = :id_etransporte:", 'bind' => array('id_etransporte' => $id_etransporte)));
            if(!$empresa_transporte) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error!';
                $resp['mensaje'] = 'Lo sentimos! No hemos conseguido la empresa de transporte, intenta nuevamente';
                echo json_encode($resp);
                exit();

            }
            
            $empresa_transporte->estado = 'inactivo';
            if(!$empresa_transporte->save()) {
                $msg = '';
                foreach ($empresa_transporte->getMessages() as $message) {
                    $msg = $msg.$message."</br>\n";
                }

                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en BD';
                $resp['mensaje'] = 'Obtenemos el siguiente error: '.$msg;
                echo json_encode($resp);
                exit();

            }

            //Para desactivar todos los vehículos de esa empresa
            $vehiculo = Vehiculo::find(array("id_etransporte = :id_etransporte: ", 'bind' => array('id_etransporte' => $id_etransporte)));
            foreach($vehiculo as $vehiculo){
                $vehiculo->estado = 'inactivo';
                if(!$vehiculo->save()) {
                    $msg = '';
                    foreach ($vehiculo->getMessages() as $message) {
                        $msg = $msg.$message."</br>\n";
                    }
    
                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error en BD';
                    $resp['mensaje'] = 'Obtenemos el siguiente error: '.$msg;
                    echo json_encode($resp);
                    exit();
    
                }
            }

            //Para desactivar todos los conductores de esa empresa
            $conductor = Conductor::find(array("id_etransporte = :id_etransporte: ", 'bind' => array('id_etransporte' => $id_etransporte)));
            foreach($conductor as $conductor){
                $conductor->estado = 'inactivo';
                if(!$conductor->save()) {
                    $msg = '';
                    foreach ($conductor->getMessages() as $message) {
                        $msg = $msg.$message."</br>\n";
                    }

                    $resp['respuesta'] = 'error';
                    $resp['titulo'] = 'Error en BD';
                    $resp['mensaje'] = 'Obtenemos el siguiente error: '.$msg;
                    echo json_encode($resp);
                    exit();

                }
            }
           

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = '¡Excelente!';
            $resp['mensaje'] = 'La empresa de transporte  se ha eliminado correctamente!';
            echo json_encode($resp);
            exit();

        }
    }

    public function eliminarConductorAction(){
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $idconductor = !isset($datapost['idconductor'])?0:intval($datapost['idconductor']) + 0;
            //Verificando sesion
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
            //Consulta
            $conductor = Conductor::findFirst(array("idconductor = :idconductor:", 'bind' => array('idconductor' => $idconductor)));
            if(!$conductor) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error!';
                $resp['mensaje'] = 'Lo sentimos! No hemos conseguido el conductor seleccionado, intenta nuevamente';
                echo json_encode($resp);
                exit();

            }
            
            $conductor->estado = 'inactivo';

            if(!$conductor->save()) {
                $msg = '';
                foreach ($conductor->getMessages() as $message) {
                    $msg = $msg.$message."</br>\n";
                }

                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en BD';
                $resp['mensaje'] = 'Obtenemos el siguiente error: '.$msg;
                echo json_encode($resp);
                exit();

            }

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = '¡Excelente!';
            $resp['mensaje'] = 'El conductor  se ha eliminado correctamente!';
            echo json_encode($resp);
            exit();

        }
    }

    public function eliminarVehiculoAction(){
        $this->view->disable();
        $request = $this->request;
        if($request->isAjax() == true) 
        {
            $datapost = $this->request->getPost();
            $id_vehiculo = !isset($datapost['idvehiculo'])?0:intval($datapost['idvehiculo']) + 0;
            //Verificando sesion
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
            //Consulta
            $vehiculo = Vehiculo::findFirst(array("id_vehiculo = :id_vehiculo:", 'bind' => array('id_vehiculo' => $id_vehiculo)));
            if(!$vehiculo) {
                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error!';
                $resp['mensaje'] = 'Lo sentimos! No hemos conseguido el vehículo seleccionado, intenta nuevamente';
                echo json_encode($resp);
                exit();

            }
            
            $vehiculo->estado = 'inactivo';

            if(!$vehiculo->save()) {
                $msg = '';
                foreach ($vehiculo->getMessages() as $message) {
                    $msg = $msg.$message."</br>\n";
                }

                $resp['respuesta'] = 'error';
                $resp['titulo'] = 'Error en BD';
                $resp['mensaje'] = 'Obtenemos el siguiente error: '.$msg;
                echo json_encode($resp);
                exit();

            }

            $resp['respuesta'] = 'ok';
            $resp['titulo'] = '¡Excelente!';
            $resp['mensaje'] = 'El vehículo  se ha eliminado correctamente!';
            echo json_encode($resp);
            exit();

        }
    }
}