<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Application;

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

ini_set('display_errors', 1);
ini_set('log_errors', 1);
error_reporting(E_ALL);

try {
    // Crear el contenedor de dependencias
    $di = new FactoryDefault();

    // Registrar el contenedor de dependencias como el contenedor global por defecto
    FactoryDefault::setDefault($di);

    // Incluir configuraciones
    include APP_PATH . '/library/functions.php';
    include APP_PATH . '/config/services.php';
    include APP_PATH . '/config/custom_includes.php';
    include APP_PATH . '/config/router.php';

     // Obtener la configuración desde el contenedor de dependencias
     $config = $di->getConfig();

    // Incluir el autoloader
    include APP_PATH . '/config/loader.php';

    // Crear la aplicación MVC
    $application = new Application($di);
    
    // Procesar la solicitud y enviar la respuesta al cliente
    $application->handle($_SERVER['REQUEST_URI'])->send();

} catch (\Exception $e) {
    // Manejar excepciones
    echo $e->getMessage() . '<br>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}
?>