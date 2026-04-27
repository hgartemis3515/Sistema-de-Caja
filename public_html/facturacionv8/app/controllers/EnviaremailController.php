<?php
class EnviaremailController extends ControllerBase {
    
    public function enviar_email_documento($id_contribuyente, $id_tipodoc_electronico, $serie_comprobante, $numero_comprobante, $tipo_envio_sunat, $email = '') {

        if($id_tipodoc_electronico == '01' || $id_tipodoc_electronico == '03' || $id_tipodoc_electronico == '07' || $id_tipodoc_electronico == '08' || $id_tipodoc_electronico == '09') {
            $documento_electronico = DocElectronico::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodoc_electronico = :id_tipodoc_electronico: and serie_comprobante = :serie_comprobante: and numero_comprobante = :numero_comprobante: and tipo_envio_sunat = :tipo_envio_sunat:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodoc_electronico' => $id_tipodoc_electronico, 'serie_comprobante' => $serie_comprobante, 'numero_comprobante' => $numero_comprobante, 'tipo_envio_sunat' => $tipo_envio_sunat)));
        } else if($id_tipodoc_electronico == '77' || $id_tipodoc_electronico == '88') {
            $documento_electronico = DocNoOficial::findFirst(array("id_contribuyente = :id_contribuyente: and id_tipodocumento = :id_tipodocumento: and numero_comprobante = :numero_comprobante: and modalidad = :modalidad: and estado_documento = 'activo'", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_tipodocumento' => $id_tipodoc_electronico, 'numero_comprobante' => $numero_comprobante, 'modalidad' => $tipo_envio_sunat)));
        } else {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error Documento';
            $resp['mensaje'] = 'El documento electrónico no se encuentra';
            return $resp;
        }
        
        if(!$documento_electronico) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error Documento';
            $resp['mensaje'] = 'El documento electrónico no se encuentra';
            return $resp;
        }

        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
		if(!$contribuyente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'El contribuyente no está registrado';
			return $resp;
        }

        $dominio = 'arpsystem.com.pe';

        $cliente = Cliente::findFirst(array("idcliente = :idcliente:", 'bind' => array('idcliente' => $documento_electronico->idcliente)));
		if(!$cliente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'El cliente seleccionado no existe!';
			return $resp;
        }
        
        $herramientas = new HerramientasController;
        
        $documento_serie_numero = '';
        if($id_tipodoc_electronico == '01' || $id_tipodoc_electronico == '03' || $id_tipodoc_electronico == '07' || $id_tipodoc_electronico == '08') {
            $string_encrypted_document_a4 = $herramientas->encriptar("$documento_electronico->id_contribuyente||".$documento_electronico->id_tipodoc_electronico."||".$serie_comprobante."||".$numero_comprobante."||".$tipo_envio_sunat."||a4");
            $string_encrypted_document_ticket = $herramientas->encriptar("$documento_electronico->id_contribuyente||".$documento_electronico->id_tipodoc_electronico."||".$serie_comprobante."||".$numero_comprobante."||".$tipo_envio_sunat."||ticket");

            $ruta_pdf_a4 = 'https://'.$dominio.'/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_a4);
            $ruta_pdf_ticket = 'https://'.$dominio.'/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_ticket);
            
            $ruta_xml = 'https://'.$dominio.'/facturacionv8/download/downloadcpe/'.$id_contribuyente.'/'.$documento_electronico->id_tipodoc_electronico.'/'.$serie_comprobante.'/'.$numero_comprobante.'/xml_cpe_zip';
            $documento_serie_numero = $serie_comprobante.'-'.$numero_comprobante;
        } else if($id_tipodoc_electronico == '09') {
            $string_encrypted_document_a4 = $herramientas->encriptar("$documento_electronico->id_contribuyente||".$documento_electronico->id_tipodoc_electronico."||".$serie_comprobante."||".$numero_comprobante."||".$tipo_envio_sunat."||a4");
            $string_encrypted_document_ticket = $herramientas->encriptar("$documento_electronico->id_contribuyente||".$documento_electronico->id_tipodoc_electronico."||".$serie_comprobante."||".$numero_comprobante."||".$tipo_envio_sunat."||ticket");

            $ruta_pdf_a4 = 'https://'.$dominio.'/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_a4);
            $ruta_pdf_ticket = 'https://'.$dominio.'/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_ticket);
        
            $ruta_xml = 'https://'.$dominio.'/facturacionv8/download/downloadcpe/'.$id_contribuyente.'/'.$documento_electronico->id_tipodoc_electronico.'/'.$serie_comprobante.'/'.$numero_comprobante.'/xml_cpe_zip';
            $documento_serie_numero = $serie_comprobante.'-'.$numero_comprobante;
        } else if($id_tipodoc_electronico == '77') {
            $string_encrypted_document_a4 = $herramientas->encriptar("$documento_electronico->id_contribuyente||77|| ||".$numero_comprobante."||".$tipo_envio_sunat."||a4");
            $string_encrypted_document_ticket = $herramientas->encriptar("$documento_electronico->id_contribuyente||77|| ||".$numero_comprobante."||".$tipo_envio_sunat."||ticket");

            $ruta_pdf_a4 = 'https://'.$dominio.'/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_a4);
            $ruta_pdf_ticket = 'https://'.$dominio.'/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_ticket);
            
            $ruta_xml = '';
            $documento_serie_numero = $numero_comprobante;
        } else if($id_tipodoc_electronico == '88') {
            $string_encrypted_document_a4 = $herramientas->encriptar("$documento_electronico->id_contribuyente||88|| ||".$numero_comprobante."||".$tipo_envio_sunat."||a4");
            $string_encrypted_document_ticket = $herramientas->encriptar("$documento_electronico->id_contribuyente||88|| ||".$numero_comprobante."||".$tipo_envio_sunat."||ticket");

            $ruta_pdf_a4 = 'https://'.$dominio.'/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_a4);
            $ruta_pdf_ticket = 'https://'.$dominio.'/facturacionv8/printpdf/?file='.urlencode($string_encrypted_document_ticket);
            
            $ruta_xml = '';
            $documento_serie_numero = $numero_comprobante;
        }
        
        $file_xml_encoded = '';
        $sunat_tipodocelectronicio = SunatTipodocelectronico::findFirst(array("id_tipodoc_electronico = :id_tipodoc_electronico:", 'bind' => array('id_tipodoc_electronico' => $id_tipodoc_electronico)));
        
            if($id_tipodoc_electronico == '01' || $id_tipodoc_electronico == '03' || $id_tipodoc_electronico == '07' || $id_tipodoc_electronico == '08' || $id_tipodoc_electronico == '09') {

            if($documento_electronico->tipo_envio_sunat == 'produccion') {
                $ruta_base_cpe = $this->ruta_base_files.'/'.$contribuyente->ruc.'/'.$contribuyente->ruta_xml_produccion.'/';
            } else {
                $ruta_base_cpe = $this->ruta_base_files.'/20100066603/'.$contribuyente->ruta_xml_prueba.'/';
            }

            if($documento_electronico->id_tipodoc_electronico == '01' || $documento_electronico->id_tipodoc_electronico == '03') {
                $ruta_final_cpe = $ruta_base_cpe.'facturas_boletas/'.$documento_electronico->name_xml_zip;
            } else if($documento_electronico->id_tipodoc_electronico == '07') {
                $ruta_final_cpe = $ruta_base_cpe.'nota_credito/'.$documento_electronico->name_xml_zip;
            } else if($documento_electronico->id_tipodoc_electronico == '08') {
                $ruta_final_cpe = $ruta_base_cpe.'nota_debito/'.$documento_electronico->name_xml_zip;
            } else if($documento_electronico->id_tipodoc_electronico == '09') {
                $ruta_final_cpe = $ruta_base_cpe.'guias_remision/'.$documento_electronico->name_xml_zip;
            }

            if (!file_exists($ruta_final_cpe) || empty($ruta_final_cpe) || !is_file($ruta_final_cpe)) {
                // La ruta no es válida o es un directorio
            } else {
                if (!empty($ruta_final_cpe)) {
                    $file_xml_encoded = base64_encode(file_get_contents($ruta_final_cpe));
                } else {
                    $file_xml_encoded = '';
                }
            }
        }
        
        $data = array(
			'asunto'                    => 'Comprobante Electronico',
			
            'emisor_idcontribuyente'    => $contribuyente->id_contribuyente,
            'emisor_email'              => $contribuyente->email,
            'emisor_razon_social'       => $contribuyente->razon_social,
			'emisor_num_doc'            => $contribuyente->ruc,

			'cliente_email'				=> empty($email)?$cliente->email:$email,
			'cliente_razon_social'      => $cliente->razon_social,
			
            'documento_nombre'          => ucwords($sunat_tipodocelectronicio->descripcion),
            'documento_serie_numero'    => $serie_comprobante.'-'.$numero_comprobante,
            'documento_fecha'           => date("d-m-Y", strtotime($documento_electronico->fecha_registro)),
            'documento_total'           => $documento_electronico->total,
            'documento_moneda'          => $documento_electronico->id_codigomoneda,
            'documento_pdf_a4'			=> '<a href="'.$ruta_pdf_a4.'">Ver PDF - A4</a>',
            'documento_pdf_ticket' 		=> '<a href="'.$ruta_pdf_ticket.'">Ver PDF - Ticket</a>',
            'documento_xml' 			=> !empty($ruta_xml)?' '.$ruta_xml:'',
            'token'                     => $this->token_user,
            'accion'                    => 'enviar_comprobante_cliente',


            'file_xml_encoded'          => $file_xml_encoded
		);
        
        $herramientas = new HerramientasController;
		$ruta = 'https://facturalahoy.com/api/enviaremail/comprobante_electronico';
        $resp_api_ws = $herramientas->envio_api_sunat($data, $ruta);
        
        return $resp_api_ws;
    }

    public function enviar_datos_de_acceso($idusuario) {
        $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
		if(!$usuario) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'No se encuentra el usuario.';
			return $resp;
        }

        if(empty($usuario->email)) {
            $resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Email Vacio.';
			return $resp;
        }
        
        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
		if(!$contribuyente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'El contribuyente no está registrado';
			return $resp;
        }
        
        $patrocinador = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => 1)));
        $dominio = 'arpsystem.com.pe';
        $emisor_idcontribuyente = $patrocinador->id_contribuyente;
        $emisor_email = empty($patrocinador->email)?'facturalaya.srl@gmail.com':$patrocinador->email;
        $emisor_razon_social = $patrocinador->razon_social;
        $emisor_num_doc = $patrocinador->ruc;

        $mensaje = "
        Hola $usuario->nombre, Bienvenido a $emisor_razon_social <br/><br/>

        Estos son los datos de acceso a tu cuenta:<br/><br/>

        Ingresa desde aquí: <a target='_blank' href='http://$dominio/facturacionv8/login'>http://$dominio/facturacionv8/login</a>.<br/>
        Tu usuario: $usuario->email<br/>
        Contaseña: $usuario->password<br/><br/>";

        if(!empty($dominio_empresa_principal)) {
            $mensaje = $mensaje."Recuerda que no podrás enviar comprobantes a la sunat sin antes activar una suscripción. Puedes contratar una suscripción aquí <a href='http://$dominio/'>$dominio</a><br/><br/>

            Mientras tanto todas las opciones en ambiente pruebas están activas para que comiences a personalizar tu cuenta.<br/><br/>
            ";
        }

        $mensaje = $mensaje."Cada uno de nosotros está feliz de tenerte aquí.<br/><br/>

        Atentamente<br/>
        $emisor_razon_social <br/>
        Asistencia al cliente
        ";

        if(!empty($url_soporte)) {
            //$mensaje = $mensaje."<br /> ¿Tienes preguntas? Contáctanos <a href='$url_soporte'>$url_soporte</a>";
        }

        $data = array(
			'asunto'                    => "$usuario->nombre tus datos de acceso están aquí",
			
            'emisor_idcontribuyente'    => $emisor_idcontribuyente,
            'emisor_email'              => $emisor_email,
            'emisor_razon_social'       => $emisor_razon_social,
			'emisor_num_doc'            => $emisor_num_doc,

			'destinatario_email'	    => $usuario->email,
            'destinatario_nombre'       => $usuario->nombre,
            'destinatario_apellido'     => $usuario->apellido,
            'token'                     => $this->token_user,
            'accion'                    => 'enviar_datos_acceso',
            'mensaje'                   => $mensaje
        );
        
        $herramientas = new HerramientasController;
		$ruta = 'https://facturalahoy.com/api/enviaremail/enviar_datos_acceso';
        $resp_api_ws = $herramientas->envio_api_sunat($data, $ruta);
        return $resp_api_ws;
    }

    public function enviar_correo_recoverpass($idusuario) {
        $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
		if(!$usuario) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'No se encuentra el usuario.';
			return $resp;
        }

        if(empty($usuario->email)) {
            $resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Email Vacio.';
			return $resp;
        }
        
        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
		if(!$contribuyente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'El contribuyente no está registrado';
			return $resp;
        }

        $patrocinador = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => 1)));
        $dominio = 'arpsystem.com.pe';
        $emisor_idcontribuyente = $patrocinador->id_contribuyente;
        $emisor_email = empty($patrocinador->email)?'facturalaya.srl@gmail.com':$patrocinador->email;
        $emisor_razon_social = $patrocinador->razon_social;
        $emisor_num_doc = $patrocinador->ruc;

        $cadena_recuperacion = sha1($usuario->id_contribuyente.','.$usuario->idusuario.','.$usuario->password);
        if($contribuyente->tipo_envio_sunat == 'prueba') {
            $mensaje_para_usuario = 'Recuerda que ahora mismo te encuentras en un ambiente de prueba, por lo que tendrás la libertad de utilizar todo nuestro sistema sin costo, podrás enviar facturas, boletas, notas de crédito, débito, etc, todo en modo de pruebas... Estoy seguro que te nuestro sistema te encantará, una vez decidas iniciar con el proceso de producción o envío directo a sunat, avísanos, envianos un email a: '.$emisor_email.', ¡Te Esperamos!';
        } else {
            $mensaje_para_usuario = 'Estamos Felices de Contar Contigo!!... Recuerda que cuentas con nosotros para ayudarte en todo lo que haga falta, puedes escribirnos al siguiente correo: '.$emisor_email;
        }

        $mensaje = '
        Hola '.$usuario->nombre.', para que puedas reestablecer tu contraseña debes hacer click en el siguiente enlace, y luego podrás ingresar tu nuevo password:<br /><br />
        
        Url para Reestablecer tu Password: http://'.$dominio.'/facturacionv8/login/resetpassword/'.$usuario->idusuario.'/'.$cadena_recuperacion.'<br /><br />

        '.$mensaje_para_usuario.'
        <br /><br />
        Atentamente,<br /><br />

        '.$emisor_razon_social.'<br />
        RUC: '.$emisor_num_doc.'<br />
        La Gerencia
        ';

        $data = array(
			'asunto'                    => 'Recuperar Password para '.$dominio,
			
            'emisor_idcontribuyente'    => $emisor_idcontribuyente,
            'emisor_email'              => $emisor_email,
            'emisor_razon_social'       => $emisor_razon_social,
			'emisor_num_doc'            => $emisor_num_doc,

			'destinatario_email'	    => $usuario->email,
            'destinatario_nombre'       => $usuario->nombre,
            'destinatario_apellido'     => $usuario->apellido,
            'token'                     => $this->token_user,
            'accion'                    => 'enviar_datos_acceso',
            'mensaje'                   => $mensaje
        );
        
        $herramientas = new HerramientasController;
		$ruta = 'https://facturalahoy.com/api/enviaremail/enviar_email';
        $resp_api_ws = $herramientas->envio_api_sunat($data, $ruta);
        return $resp_api_ws;
    }

    public function enviar_cambio_password($idusuario) {
        $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
		if(!$usuario) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'No se encuentra el usuario.';
			return $resp;
        }

        if(empty($usuario->email)) {
            $resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'Email Vacio.';
			return $resp;
        }
        
        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
		if(!$contribuyente) {
			$resp['respuesta'] = 'error';
			$resp['titulo'] = 'Error';
			$resp['mensaje'] = 'El contribuyente no está registrado';
			return $resp;
        }
        
        $patrocinador = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => 1)));
        $dominio = 'arpsystem.com.pe';
        $emisor_idcontribuyente = $patrocinador->id_contribuyente;
        $emisor_email = empty($patrocinador->email)?'facturalaya.srl@gmail.com':$patrocinador->email;
        $emisor_razon_social = $patrocinador->razon_social;
        $emisor_num_doc = $patrocinador->ruc;

        $cadena_recuperacion = sha1($usuario->id_contribuyente.','.$usuario->idusuario.','.$usuario->password);
        
        if($contribuyente->tipo_envio_sunat == 'prueba') {
            $mensaje_para_usuario = 'Cuántanos como te va con el sistema estimado '.$usuario->nombre.', ahora mismo te encuentras en el modo de Prueba por lo que tranquilamente puedes registrar tus productos, tus clientes, tus sucursales, etc. Es más incluso puedes enviar faturas, boletas, notas de crédito, débito, etc. en modo de pruebas para que de esa manera puedas analizar todas las funcionalidades de nuestro sistema... Para inicar con el modo de producción contacta conmigo al siguiente correo: '.$emisor_email;
        } else {
            $mensaje_para_usuario = 'Estamos Felices de Contar Contigo!!... Recuerda que cuentas con nosotros para ayudarte en todo lo que haga falta, puedes escribirnos al siguiente correo: '.$emisor_email;
        }
        $mensaje = '
        Hola '.$usuario->nombre.', para avisarte que tu password ha sido cambiado correctamente:<br /><br />
        
        Url para Reestablecer tu Password: http://'.$dominio.'/facturacionv8/login/resetpassword/'.$usuario->idusuario.'/'.$cadena_recuperacion.'

        DATOS DE ACCESO: <br />
        Sitio Web: http://'.$dominio.'/facturacionv8/login <br />
        Usuario: '.$usuario->email.' <br />
        Password: '.$usuario->password.' <br /><br />

        
        <br /><br />
        Atentamente,<br /><br />

        '.$emisor_razon_social.'<br />
        RUC: '.$emisor_num_doc.'<br />
        La Gerencia
        ';

        $data = array(
			'asunto'                    => 'Cambio de Password en '.$dominio,
			
            'emisor_idcontribuyente'    => $emisor_idcontribuyente,
            'emisor_email'              => $emisor_email,
            'emisor_razon_social'       => $emisor_razon_social,
			'emisor_num_doc'            => $emisor_num_doc,

			'destinatario_email'	    => $usuario->email,
            'destinatario_nombre'       => $usuario->nombre,
            'destinatario_apellido'     => $usuario->apellido,
            'token'                     => $this->token_user,
            'accion'                    => 'enviar_datos_acceso',
            'mensaje'                   => $mensaje
        );
        
        $herramientas = new HerramientasController;
		$ruta = 'https://facturalahoy.com/api/enviaremail/enviar_email';
        $resp_api_ws = $herramientas->envio_api_sunat($data, $ruta);
        return $resp_api_ws;
    }

    public function patrocinador_new_user($contribuyente, $usuario) {
        $patrocinador = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $contribuyente->id_patrocinador)));

        if(!empty($patrocinador->email)) {
            $mensaje = "Un nuevo prospecto se ha registrado!... A continuación te enviamos todos los datos para que puedas contactar con el:<br /><br />
            DATOS EMPRESA: <BR />
            - Razón Social: $contribuyente->razon_social<br />
            - Nombre Comercial: $contribuyente->nombre_comercial<br />
            - Email: $contribuyente->email<br />
            - Teléfono: $contribuyente->telefono<br /><br />
            - RUC: $contribuyente->ruc<br /><br />
            DATOS USUARIO: <br />
            - Nombre: $usuario->nombre $usuario->apellido<br />
            - Email: $usuario->email <br />
            - Telefonos: $usuario->telefono y $usuario->celular<br /><br />
            Atentamente,

            Tu Equipo de Soporte
            FacturalaYa SRL - https://facturalaya.com
            ";

            $data = array(
                'asunto'                    => 'Wow Nuevo Prospecto - '.$contribuyente->ruc,
            
                'emisor_idcontribuyente'    => 1,
                'emisor_email'              => 'facturalaya.srl@gmail.com',
                'emisor_razon_social'       => 'FacturalaYa SRL',
                'emisor_num_doc'            => '20604209987',
    
                'destinatario_email'	    => $patrocinador->email,
                'destinatario_nombre'       => 'Socio',
                'destinatario_apellido'     => 'Estratégico',
                'token'                     => $this->token_user,
                'accion'                    => 'enviar_datos_acceso',
                'mensaje'                   => $mensaje
            );
            
            $herramientas = new HerramientasController;
            $ruta = 'https://facturalahoy.com/api/enviaremail/enviar_email';
            $resp_api_ws = $herramientas->envio_api_sunat($data, $ruta);
            return $resp_api_ws;
        }
    }
}
?>