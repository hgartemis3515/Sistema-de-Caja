<?php
ini_set( 'serialize_precision', -1 );
use Phalcon\Mvc\Controller;
class ControllerBase extends Controller
{
    public $ruta_base_files = '/home/humbertoguadalup/.files_contribuyente';
    public $ruta_base_public_html = "/home/humbertoguadalup/public_html";
    public $token_user = 'facturalaya_humberto_m7rklvceUvq8GiT';
    public $data_patrocinador = array();
    public $bloquear_sunat = false;
    public $bloquear_correos = false;
    public $bloquear_busquedas = false;
    public $modalidad_envio_sunat = 'inmediato'; //'inmediato', 'solo_firma', 'no_enviar' => o vacio: cuando es vacio toma la opción del usuario
    public $factor_igv_sunat = 0.18;
    public $captcha_key_public = '6LfIdBEaAAAAAPRPUB-UVsijh8q1-Pez1SA91e2Q';
    public $captcha_key_private = '6LfIdBEaAAAAAN4XXZDnSvk5IhtqGsB_vsdaSVpV';
    public $dominio_principal = 'arpsystem.com.pe';
    public $snappy_zoom = 1.25;
    
    public $ruc_proveedor = '10097647246';
    public $token_cookie = 'cookieFacturalaYa_060300';

    protected $baseUri;

	public function initialize()
    {
        $this->baseUri = $this->url->getBaseUri();
        
        $this->view->user = $this->getSessionUser();
        $this->view->tipo_envio_sunat = $this->get_tipo_envio_sunat();
        
        $data_empresa = $this->get_parametros_iniciales();
        $this->data_patrocinador = $data_empresa;

        $this->view->data_empresa = $data_empresa;
        $this->view->data_personalizacion = $data_empresa['custom_data_style'];
        
        $this->view->lista_grupo_vendedores = $this->lista_grupo_vendedores();
    }

    protected function setTitle(string $title)
    {
        $this->view->pageTitle = $title;
    }

    protected function getHashedAssetPath($originalPath) {
        // Cargar el manifiesto una vez si aún no está cargado
        if (self::$manifest === null) {
            $manifestPath = __DIR__ . '/../../public/js/dist/manifest.json';
            if (file_exists($manifestPath)) {
                self::$manifest = json_decode(file_get_contents($manifestPath), true);
            } else {
                // Log error o lanzar excepción si el manifiesto no existe
                self::$manifest = [];
            }
        }
        
        // Verificar si el archivo existe en el manifiesto
        if (isset(self::$manifest[$originalPath])) {
            // Agregar el archivo con la ruta completa y hash a los assets
            $hashedPath = $this->baseUri . 'public/js/dist/' . self::$manifest[$originalPath];
            $this->assets->addJs($hashedPath);
        }
    }

    protected function lista_grupo_vendedores() {
        $auth = $this->session->get('authv8');
        if (!$auth) { return array(); }

        $idusuario = $auth['idusuario'];
        $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
        if(!$usuario) { return array(); }

        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
        if(!$contribuyente) { return array(); }

        if($contribuyente->tipo_empresa == 2) {
            //Extraer Lista de Empresas
            $id_patrocinador_grupal = $contribuyente->id_contribuyente;
        } else {
            $patrocinador = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $contribuyente->id_patrocinador)));
            if($patrocinador->tipo_empresa == 1) { return array(); }

            $id_patrocinador_grupal = $patrocinador->id_contribuyente;
        }

        $herramientas = new HerramientasController;
        $lista = array();

        $query = "SELECT * FROM usuario WHERE id_contribuyente in (select id_contribuyente from contribuyente where id_contribuyente = :id_patrocinador or id_patrocinador = :id_patrocinador) and estado = 'activo' and codigo = :codigo";
    
        try {
            $sentencia = $this->db->prepare($query);
            $sentencia->bindParam(':id_patrocinador', $id_patrocinador_grupal, PDO::PARAM_INT);
            $sentencia->bindParam(':codigo', $usuario->codigo, PDO::PARAM_STR);
            $sentencia->execute();
        } catch (Exception $e) {
            $this->saveLogger($e);
            return array();
        }
        
        while($fila = $sentencia->fetch()) {
            $usuario_vendedor = (object)$fila;
            $empresa_vendedor = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario_vendedor->id_contribuyente)));

            $cadena_encriptada = $herramientas->encriptar('FACTURALAYAAAAA||'.$usuario_vendedor->idusuario);
            $lista[] = array(
                'ruc'           =>  $empresa_vendedor->ruc,
                'razon_social'  =>  ucwords(strtolower($empresa_vendedor->nombre_comercial)),
                'email'         =>  $usuario_vendedor->email,
                'secret_key'    =>  $cadena_encriptada,
                'url_image'     =>  $usuario_vendedor->url_image,
                'url_acceso'    =>  "/facturacionv8/login/inicio_sesion_remoto?user=$cadena_encriptada"
            );
        }
        
        return $lista;
    }

    /** true si el host es desarrollo local (php -S, XAMPP, LAN típica, etc.). */
    protected function isLocalDevHost() {
        $hostClean = str_replace('www.', '', (string) (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ''));
        if (preg_match('/^(127\.0\.0\.1|localhost|::1)(\:\d+)?$/i', $hostClean)) {
            return true;
        }
        $hostNoPort = preg_replace('/:\d+$/', '', $hostClean);
        if (preg_match('/^192\.168\.\d{1,3}\.\d{1,3}$/', $hostNoPort)) {
            return true;
        }
        if (preg_match('/^10\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', $hostNoPort)) {
            return true;
        }
        if (preg_match('/^172\.(1[6-9]|2\d|3[01])\.\d{1,3}\.\d{1,3}$/', $hostNoPort)) {
            return true;
        }
        return false;
    }

    /** Claves de prueba oficiales: siempre pasan siteverify y admiten localhost. */
    protected function getRecaptchaTestKeys() {
        return array(
            'public' => '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI',
            'private' => '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe',
        );
    }
    
    protected function get_parametros_iniciales() {
        $dominio = $_SERVER['HTTP_HOST'];
        $dominio = str_replace('www.', '', $dominio);
        $id_contribuyente = 1;

        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
        
        $data['https'] = 'si';
        $mostrar_chat = 'no';

        $data['nombre_usuario'] = '';
        $data['email_usuario'] = '';
        $data['ruc_usuario'] = '';

        $auth = $this->session->get('authv8');
        $idusuario = null;
        if (is_array($auth) && isset($auth['idusuario'])) {
            $idusuario = $auth['idusuario'];
        }
        $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
        if($usuario) {
            $data['nombre_usuario'] = $usuario->nombre.' '.$usuario->apellido;
            $data['email_usuario'] = $usuario->email;

            $usuario_empresa = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            $data['ruc_usuario'] = $usuario_empresa->ruc;
        }
        
        $data['url_domain'] = 'arpsystem.com.pe';
        $data['nombre_empresa'] = ucwords(trim($contribuyente->razon_social));
        $data['logo_img_461'] = 'https://facturalaya.com/sys/herramientas/verimage/imguser-5ff62f076109c-4cdf0a3804c539cd626ba22ffa44bece.png';
        $data['logo_img_291'] = 'https://facturalaya.com/sys/herramientas/verimage/imguser-5ff62f0ec9f17-3e15c63c157cf4fe0a953ef011bb356d.png';
        $data['logo_img_56'] = 'https://facturalaya.com/sys/herramientas/verimage/imguser-5ff62f159ca20-279d6c5828bec22e04fbc2266033d1c3.png';
        $data['url_soporte'] = $contribuyente->url_soporte;
        $data['url_politica_privacidad'] = $contribuyente->url_politica_privacidad;
        $data['url_terminos_condiciones'] = $contribuyente->url_terminos_condiciones;
        $data['id_contribuyente'] = 1;
        $data['id_patrocinador'] = 1;
        $data['mostrar_chat'] = $mostrar_chat;
        $data['modulo_marketing'] = 'no';
        $data['dev_local_host'] = $this->isLocalDevHost();
        if ($data['dev_local_host']) {
            $t = $this->getRecaptchaTestKeys();
            $data['captcha_key_public'] = $t['public'];
            $data['captcha_key_private'] = $t['private'];
        } else {
            $data['captcha_key_public'] = $this->captcha_key_public;
            $data['captcha_key_private'] = $this->captcha_key_private;
        }
        $data['custom_data_style'] = $this->get_custom_data_style_system($id_contribuyente);

        return $data;
    }
	
	protected function _checkSession(){
        $auth = $this->session->get('authv8');
        if (!$auth){
            return false;
        } else {
            return true;
        }
    }

    protected function get_tipo_envio_sunat() {
        $auth = $this->session->get('authv8');
        if (!$auth){
            return '';
        }
        
        $idusuario = $auth['idusuario'];
        $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
        if(!$usuario) {
            return '';
        }
        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
        if(!$contribuyente) {
            return '';
        }
        
        if($contribuyente->tipo_envio_sunat == 'prueba') {
            $ambiente = 'Ambiente de Pruebas';
        } else {
            $ambiente = 'Ambiente de Producción';
        }
        return $ambiente;
    }
    
    protected function registrar_sesion($user, $redirect = true) {
        $auth = $this->session->set('authv8', $user);
       
        if ($this->cookies->has($this->token_cookie)){
            $rememberMe = $this->cookies->get($this->token_cookie);
            $rememberMe->delete();
        }

        $this->crear_cookie_session($user['idusuario']);
        if($redirect) {
            $this->redirect_user($user);
        }
    }

    protected function redirect_user($user) {
        if ($this->isLocalDevHost()) {
            $this->response->redirect('dashboard');
            $this->response->send();
            exit;
        }
        $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $user['idusuario'])));
        $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
        $dominio = $_SERVER['HTTP_HOST'];
        $dominio = str_replace('www.', '', $dominio);
        header("Location: https://$dominio/facturacionv8/dashboard");
        exit();
    }

    protected function get_lista_patrocinadores($id_contribuyente) {
        $lista_patrocinadores[] = 1;
        return $lista_patrocinadores;
    }
	
	protected function getSessionUser(){
        $auth = $this->session->get('authv8');
        if (!$auth){
            return null;
        } else {
            return $auth;
        }
    }

    protected function microtime_float() {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }

    protected function inicio_ejecucion() {
        return microtime(true);
    }

    protected function fin_ejecucion($time_inicio) {
        $end = microtime(true);
        return $end - $time_inicio;
    }

    protected function crear_cookie_session($idusuario) {
        if ($this->cookies->has($this->token_cookie)){
            $rememberMe = $this->cookies->get($this->token_cookie);
            $rememberMe->delete();
        }

        $herramientas = new HerramientasController;
        $texto_encriptar = $idusuario.'||'.date('dmY');
        $id_usuario_encripted = $herramientas->encriptar($texto_encriptar);
        $resp = $this->cookies->set($this->token_cookie, $id_usuario_encripted, time() + 15 * 24 * 3600);
        $this->cookies->send();
    }

    protected function eliminar_cookie_session() {
        if ($this->cookies->has($this->token_cookie)){
            $rememberMe = $this->cookies->get($this->token_cookie);
            $rememberMe->delete();
        }
    }

    protected function get_contribuyente_opciones($id_contribuyente) {
        $id_contribuyente = intval($id_contribuyente) + 0;
        //Valores por defecto: Siempre se deben inicializar.
        $opciones = array();
        $opciones['color_fondo_tipo'] = 'color_degradado';
        $opciones['color_fondo_1_rgb'] = '#3f51b5';
        $opciones['color_fondo_2_rgb'] = '#7880f0';
        $opciones['id_plantilla_login'] = 1;
        $opciones['id_plantilla_registro'] = 14;
        $opciones['img_background_login'] = 'https://arpsystem.com.pe/facturacionv8/img/38.jpg';
        $opciones['img_background_register'] = 'https://arpsystem.com.pe/facturacionv8/img/hero-6.jpg';
        $opciones['msj_expira_suscripcion'] = 'Su Suscripción ha vencido por favor renovar su sucripción lo antes posible.';

        $contribuyente_opciones = ContribuyenteOpciones::find(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));
        foreach($contribuyente_opciones as $contribuyente_opcion) {
            $opciones[$contribuyente_opcion->opcion_nombre] = $contribuyente_opcion->opcion_valor;
        }

        return $opciones;
    }

    //aquí se inicializan los datos (se deben cambiar los datos de inicialización)
    protected function set_contribuyente_opciones($id_contribuyente, $opcion_nombre, $opcion_valor) {
        if(empty($opcion_valor)) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'No se permiten valores vacíos';
            return $resp;
        }

        $contribuyente_opcion = ContribuyenteOpciones::findFirst(array("id_contribuyente = :id_contribuyente: and opcion_nombre = :opcion_nombre:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'opcion_nombre' => $opcion_nombre)));

        if($contribuyente_opcion) {
            if($contribuyente_opcion->opcion_valor == $opcion_valor) {
                $resp['respuesta'] = 'ok';
                return $resp;
            }

            $contribuyente_opcion->opcion_valor = $opcion_valor;
            try {
                if(!$contribuyente_opcion->save()) {
                    $msg = '';
                    foreach ($contribuyente_opcion->getMessages() as $message) {
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

        $contribuyente_opcion = new ContribuyenteOpciones();
        $contribuyente_opcion->id_contribuyente = $id_contribuyente;
        $contribuyente_opcion->opcion_nombre = $opcion_nombre;
        $contribuyente_opcion->opcion_valor = $opcion_valor;

        try {
            if(!$contribuyente_opcion->save()) {
                $msg = '';
                foreach ($contribuyente_opcion->getMessages() as $message) {
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

    protected function get_contribuyente_opcion($id_contribuyente, $opcion_nombre) {
        $contribuyente_opcion = ContribuyenteOpciones::findFirst(array("id_contribuyente = :id_contribuyente: and opcion_nombre = :opcion_nombre:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'opcion_nombre' => $opcion_nombre)));
        if(!$contribuyente_opcion) {
            return '';
        }

        return $contribuyente_opcion->opcion_valor;
    }

    protected function get_custom_data_style_system($id_contribuyente) {
        $contribuyente_opciones = $this->get_contribuyente_opciones($id_contribuyente);

        $data = array();
        $data['id_plantilla_login'] = isset($contribuyente_opciones['id_plantilla_login'])?intval($contribuyente_opciones['id_plantilla_login']) + 0:1;
        $data['id_plantilla_registro'] = isset($contribuyente_opciones['id_plantilla_registro'])?intval($contribuyente_opciones['id_plantilla_registro']) + 0:14;
        $data['img_background_login'] = isset($contribuyente_opciones['img_background_login'])?$contribuyente_opciones['img_background_login']:'https://arpsystem.com.pe/facturacionv8/img/38.jpg';
        $data['img_background_register'] = isset($contribuyente_opciones['img_background_register'])?$contribuyente_opciones['img_background_register']:'https://arpsystem.com.pe/facturacionv8/img/hero-6.jpg';

        //INICIO DE VALIDACIONES PARA PODER EXTRAER EL VALOR CORRECTO PARA LOS COLORES
        //Es muy importante que tenga valores correctos porque puede ocasionar un problema muy grande en el sistema
        $color_base_sistema = isset($contribuyente_opciones['color_base_sistema'])?$contribuyente_opciones['color_base_sistema']:'{"tipo_color":"color_degradado","color_1":"#3f51b5","color_2":"#7880f0"}';
        $color_base_sistema = @json_decode($color_base_sistema);
        if($color_base_sistema === null) {
            $color_base_sistema = json_decode('{"tipo_color":"color_degradado","color_1":"#3f51b5","color_2":"#7880f0"}');
        }

        if(!isset($color_base_sistema->tipo_color)) {
            $color_base_sistema = json_decode('{"tipo_color":"color_degradado","color_1":"#3f51b5","color_2":"#7880f0"}');
        }

        if($color_base_sistema->tipo_color != 'color_degradado' && $color_base_sistema->tipo_color != 'color_solido') {
            $color_base_sistema = json_decode('{"tipo_color":"color_degradado","color_1":"#3f51b5","color_2":"#7880f0"}');
        } else {
            if($color_base_sistema->tipo_color == 'color_degradado') {
                if(!isset($color_base_sistema->color_1) || !isset($color_base_sistema->color_2)) {
                    $color_base_sistema = json_decode('{"tipo_color":"color_degradado","color_1":"#3f51b5","color_2":"#7880f0"}');
                } else {
                    if(!preg_match("/^#[a-zA-Z0-9_]{6}$/", $color_base_sistema->color_1) || !preg_match("/^#[a-zA-Z0-9_]{6}$/", $color_base_sistema->color_2)) {
                        $color_base_sistema = json_decode('{"tipo_color":"color_degradado","color_1":"#3f51b5","color_2":"#7880f0"}');
                    }
                }
            } else {
                if(!isset($color_base_sistema->color_solido)) {
                    $color_base_sistema = json_decode('{"tipo_color":"color_degradado","color_1":"#3f51b5","color_2":"#7880f0"}');
                } else {
                    if(!preg_match("/^#[a-zA-Z0-9_]{6}$/", $color_base_sistema->color_solido)) {
                        $color_base_sistema = json_decode('{"tipo_color":"color_degradado","color_1":"#3f51b5","color_2":"#7880f0"}');
                    }
                }
            }
        }
        //FIN validación para colores base de sistema

        //este código es especial porque así se trabajó el cambio de color en toda la plantilla:
        if($color_base_sistema->tipo_color == 'color_degradado') {
            $data['color_fondo_1_rgb'] = $color_base_sistema->color_1;
            $data['color_fondo_2_rgb'] = $color_base_sistema->color_2;
        } else {
            $data['color_fondo_1_rgb'] = $color_base_sistema->color_solido;
            $data['color_fondo_2_rgb'] = $color_base_sistema->color_solido;
        }

        $data['color_base_sistema'] = $color_base_sistema;
        return $data;
    }

    protected function set_sucursal_opcion($id_contribuyente, $id_sucursal, $opcion_nombre, $opcion_valor) {
        if(empty($opcion_valor)) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'No se permiten valores vacíos';
            return $resp;
        }

        $sucursal_opcion = SucursalOpcion::findFirst(array("id_contribuyente = :id_contribuyente: and id_sucursal = :id_sucursal: and opcion_nombre = :opcion_nombre:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_sucursal' => $id_sucursal, 'opcion_nombre' => $opcion_nombre)));

        if($sucursal_opcion) {
            if($sucursal_opcion->opcion_valor == $opcion_valor) {
                $resp['respuesta'] = 'ok';
                return $resp;
            }

            $sucursal_opcion->opcion_valor = $opcion_valor;

            try {
                if(!$sucursal_opcion->save()) {
                    $msg = '';
                    foreach ($sucursal_opcion->getMessages() as $message) {
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

        $sucursal_opcion = new SucursalOpcion();
        $sucursal_opcion->id_contribuyente = $id_contribuyente;
        $sucursal_opcion->id_sucursal = $id_sucursal;
        $sucursal_opcion->opcion_nombre = $opcion_nombre;
        $sucursal_opcion->opcion_valor = $opcion_valor;
        try {
            if(!$sucursal_opcion->save()) {
                $msg = '';
                foreach ($sucursal_opcion->getMessages() as $message) {
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

    protected function get_sucursal_opcion($id_contribuyente, $id_sucursal, $opcion_nombre) {
        $sucursal_opcion = SucursalOpcion::findFirst(array("id_contribuyente = :id_contribuyente: and id_sucursal = :id_sucursal: and opcion_nombre = :opcion_nombre:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'id_sucursal' => $id_sucursal, 'opcion_nombre' => $opcion_nombre)));
        if(!$sucursal_opcion) {
            return '';
        }

        return $sucursal_opcion->opcion_valor;
    }

    protected function set_usuario_opcion($id_contribuyente, $idusuario, $opcion_nombre, $opcion_valor) {
        if(empty($opcion_valor)) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'No se permiten valores vacíos';
            return $resp;
        }

        $usuario_opcion = UsuarioOpcion::findFirst(array("id_contribuyente = :id_contribuyente: and idusuario = :idusuario: and opcion_nombre = :opcion_nombre:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'idusuario' => $idusuario, 'opcion_nombre' => $opcion_nombre)));
        
        if($usuario_opcion) {
            if($usuario_opcion->opcion_valor == $opcion_valor) {
                $resp['respuesta'] = 'ok';
                return $resp;
            }

            $usuario_opcion->opcion_valor = $opcion_valor;

            try {
                if(!$usuario_opcion->save()) {
                    $msg = '';
                    foreach ($usuario_opcion->getMessages() as $message) {
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

        $usuario_opcion = new UsuarioOpcion();
        $usuario_opcion->id_contribuyente   = $id_contribuyente;
        $usuario_opcion->idusuario          = $idusuario;
        $usuario_opcion->opcion_nombre      = $opcion_nombre;
        $usuario_opcion->opcion_valor       = $opcion_valor;

        try {
            if(!$usuario_opcion->save()) {
                $msg = '';
                foreach ($usuario_opcion->getMessages() as $message) {
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

    protected function get_usuario_opcion($id_contribuyente, $idusuario, $opcion_nombre) {
        $usuario_opcion = UsuarioOpcion::findFirst(array("id_contribuyente = :id_contribuyente: and idusuario = :idusuario: and opcion_nombre = :opcion_nombre:", 'bind' => array('id_contribuyente' => $id_contribuyente, 'idusuario' => $idusuario, 'opcion_nombre' => $opcion_nombre)));
        if(!$usuario_opcion) {
            return '';
        }

        return $usuario_opcion->opcion_valor;
    }

    protected function saveLogger($e, $info = '') {
        // Obtener el logger desde el contenedor de dependencias
        $logger = $this->di->getShared('logger');

        // Obtener detalles adicionales de la excepción y el entorno
        $controller = $this->dispatcher->getControllerName();
        $action = $this->dispatcher->getActionName();
        $file = $e->getFile();
        $line = $e->getLine();
        $trace = $e->getTraceAsString();
        
        // Crear un mensaje de error detallado
        $errorMessage = sprintf(
            "Excepción en controlador: %s, acción: %s, archivo: %s, línea: %d, mensaje: %s, traza: %s",
            $controller,
            $action,
            $file,
            $line,
            $e->getMessage().' '.$info,
            $trace
        );

        // Guardar la excepción en el log
        $logger->error($errorMessage);
    }

    public function getManifestAction($ruta_manifest) {
        // Ruta al archivo manifest
        $manifestPath = BASE_PATH . $ruta_manifest;

        // Verificar si el archivo existe
        if (!file_exists($manifestPath)) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'El archivo manifest no existe.';
            return $resp;
        }

        // Leer y decodificar el archivo JSON
        $manifestContent = file_get_contents($manifestPath);
        $manifest = json_decode($manifestContent, true);

        // Verificar si el JSON fue decodificado correctamente
        if (json_last_error() !== JSON_ERROR_NONE) {
            $resp['respuesta'] = 'error';
            $resp['titulo'] = 'Error';
            $resp['mensaje'] = 'El archivo manifest no es un JSON válido.';
            return $resp;
        }

        // Retornar el contenido del manifest como respuesta JSON
        $resp['respuesta'] = 'ok';
        $resp['manifest'] = $manifest;
        return $resp;
    }

    protected function get_data_suscripcion($idusuario) {
        $resp['tiene_suscripcion'] = 'no';
        $resp['fecha_expira_suscripcion'] = '';
        $resp['dias_restantes_suscripcion'] = 0;

        $resp['tipo_certificado'] = 'propio';
        $resp['fecha_expira_certificado'] = '';
        $resp['dias_restantes_certificado'] = 0;

        $resp['ambiente'] = 'pruebas';

        $usuario = Usuario::findFirst(array("idusuario = :idusuario:", 'bind' => array('idusuario' => $idusuario)));
        if($usuario) {
            $contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $usuario->id_contribuyente)));
            if($contribuyente) {
                $resp['ambiente'] = $contribuyente->tipo_envio_sunat;

                $herramientas = new HerramientasController;
                if(!empty($contribuyente->fecha_expiracion)) {
                    $resp_fecha_suscripcion = $herramientas->comparar_fechas($contribuyente->fecha_expiracion, date('Y-m-d'));
                    $resp['tiene_suscripcion'] = 'no';
                    $resp['fecha_expira_suscripcion'] = date("d-m-Y / H:i A", strtotime($contribuyente->fecha_expiracion));
                    $resp['dias_restantes_suscripcion'] = $resp_fecha_suscripcion['diferencia_primera_segunda'];
                    
                }

                if(!empty($contribuyente->fecha_expira_cert)) {
                    $resp_fecha_certificado = $herramientas->comparar_fechas($contribuyente->fecha_expira_cert, date('Y-m-d'));
                    $resp['tipo_certificado'] = $contribuyente->tipo_certificado;
                    $resp['fecha_expira_certificado'] = date("d-m-Y / H:i A", strtotime($contribuyente->fecha_expira_cert));
                    $resp['dias_restantes_certificado'] = $resp_fecha_certificado['diferencia_primera_segunda'];
                }
            }
        }

        return $resp;
    }

    protected function get_html_suscripcion($data_suscripcion) {
        $html = '';
        $html_suscripcion = '';
        $html_certificado = '';
        $suscripcion_activa = 'si';

        if($data_suscripcion['ambiente'] == 'produccion') {
            if($data_suscripcion['dias_restantes_suscripcion'] <= 7) {
                if($data_suscripcion['dias_restantes_suscripcion'] >= 0) {
                    if($data_suscripcion['dias_restantes_suscripcion'] >= 3) {
                        $html_suscripcion = '
                        <div class="alert alert-warning alert-styled-right" style="margin-top: 25px;">
                            <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                            Tu suscripción finaliza el día: '.$data_suscripcion['fecha_expira_suscripcion'].', debes renovar tu suscripción a la brevedad posible... 
                        </div>
                        ';
                    } else {
                        $html_suscripcion = '
                        <div class="alert alert-danger alert-styled-right" style="margin-top: 25px;">
                            <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                            Tu suscripción finaliza el día: '.$data_suscripcion['fecha_expira_suscripcion'].', ya solo faltan '.$data_suscripcion['dias_restantes_suscripcion'].' días, renueva a la brevedad posible... 
                        </div>
                        ';
                    }
                } else {
                    $html_suscripcion = '
                    <div class="alert alert-danger alert-styled-right" style="margin-top: 25px;">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                        Tu suscripción ha cadudado el día: '.$data_suscripcion['fecha_expira_suscripcion'].', renueva a la brevedad posible... 
                    </div>
                    ';
                    $suscripcion_activa = 'no';
                }
            }
            
            if($data_suscripcion['tipo_certificado'] != 'propio') {
                if($data_suscripcion['dias_restantes_certificado'] <= 7) {
                    if($data_suscripcion['dias_restantes_certificado'] >= 0) {
                        if($data_suscripcion['dias_restantes_certificado'] >= 3) {
                            $html_certificado = '
                            <div class="alert alert-warning alert-styled-right" style="margin-top: 25px;">
                                <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                                La activación de tu certificado finaliza el día: '.$data_suscripcion['fecha_expira_certificado'].', debes renovar tu certificado lo antes posible para evitar cortes en el envío de documentos electrónicos... 
                            </div>
                            ';
                        } else {
                            $html_certificado = '
                            <div class="alert alert-danger alert-styled-right" style="margin-top: 25px;">
                                <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                                La activación de tu certificado finaliza el día: '.$data_suscripcion['fecha_expira_certificado'].', ya solo faltan '.$data_suscripcion['dias_restantes_certificado'].' días, renueva a la brevedad posible... 
                            </div>
                            ';
                        }
                    } else {
                        $html_certificado = '
                        <div class="alert alert-danger alert-styled-right" style="margin-top: 25px;">
                            <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                            Tu certificado ha vencido el día: '.$data_suscripcion['fecha_expira_certificado'].', renueva a la brevedad posible... 
                        </div>
                        ';
                        $suscripcion_activa = 'no';
                    }
                }
            }

            if($html_suscripcion != '' || $html_certificado != '') {
                $html = '
                <div class="page-header-content" style="max-width: 1100px; margin: 0 auto;">
                '.$html_suscripcion.$html_certificado.'
                </div>
                ';
            }
        }

        $resp['html'] = $html;
        $resp['suscripcion_activa'] = $suscripcion_activa;
        return $resp;
    }

    public function validar_suscripcion($id_contribuyente) {
		$resp['tipo_acceso'] = 'libre'; //libre,
		$resp['tiene_suscripcion'] = 'no';
        $resp['fecha_expira_suscripcion'] = '';
		$resp['dias_restantes_suscripcion'] = 0;
		$resp['mensaje_expira'] = '';
		
		$contribuyente = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $id_contribuyente)));

		if($contribuyente->id_contribuyente == 1) {
			return $resp;
		}

		//si el usuario está en modo prueba, entonces se le deja acceso libre
		if($contribuyente->tipo_envio_sunat == 'prueba') {
			//aquí también se debería verificar si algún usuario está utilizando el sistema para control interno solamente
			return $resp;
		}

		if($contribuyente->id_patrocinador == 1) {
			$resp['mensaje_expira'] = 'Debe Realizar su Depósito a la Siguiente Cuenta en el BCP: 245-9603-5269-0-47 a Nombre de FacturalaYa SRL, y luego enviar una captura del voucher via whatsapp al siguiente número: 956295282 incluyendo tu número de RUC.';
		} else {
			$patrocinador = Contribuyente::findFirst(array("id_contribuyente = :id_contribuyente:", 'bind' => array('id_contribuyente' => $contribuyente->id_patrocinador)));
			$resp['mensaje_expira'] = 'Puedes contactar al siguiente número de celular: '.$patrocinador->telefono.', y/o al siguiente email: '.$patrocinador->email.'.';
		}
        
		$herramientas = new HerramientasController;

		//Aquí ingresa si el usuario ya tiene suscripciones activas...
		$resp_fecha = $herramientas->comparar_fechas($contribuyente->fecha_expiracion, date('Y-m-d'));
		$resp['tipo_acceso'] = 'restringido';
		$resp['tiene_suscripcion'] = 'si';
        $resp['fecha_expira_suscripcion'] = date("d-m-Y / H:i A", strtotime($contribuyente->fecha_expiracion));
		$resp['dias_restantes_suscripcion'] = $resp_fecha['diferencia_primera_segunda'];

		return $resp;
	}

    protected function get_valores_validos_factor_igv() {
        $valores = [18, 10, 10.5];
        return $valores;
    }
}
