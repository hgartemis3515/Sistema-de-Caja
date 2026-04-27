<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Application;

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

// Cargar .env desde «Sistema de caja/.env» (o rutas alternativas) sin pisar variables ya definidas.
(function () {
    $candidates = [
        dirname(BASE_PATH, 2) . DIRECTORY_SEPARATOR . '.env',
        dirname(BASE_PATH) . DIRECTORY_SEPARATOR . '.env',
        BASE_PATH . DIRECTORY_SEPARATOR . '.env',
    ];
    foreach ($candidates as $envFile) {
        if (!is_readable($envFile)) {
            continue;
        }
        $raw = @file_get_contents($envFile);
        if ($raw === false || $raw === '') {
            continue;
        }
        $raw = preg_replace('/^\xEF\xBB\xBF/', '', $raw);
        foreach (preg_split('/\r\n|\n|\r/', $raw, -1, PREG_SPLIT_NO_EMPTY) as $line) {
            $line = trim($line);
            if ($line === '' || ($line[0] ?? '') === '#') {
                continue;
            }
            if (!str_contains($line, '=')) {
                continue;
            }
            [$k, $v] = array_map('trim', explode('=', $line, 2));
            if ($k === '') {
                continue;
            }
            // El .env del proyecto gana sobre variables de entorno (evita DB_USER=root del sistema y "using password: NO").
            putenv("{$k}={$v}");
            $_ENV[$k] = $v;
            $_SERVER[$k] = $v;
        }
        break;
    }
    $envEmpty = static function (string $name): bool {
        $v = getenv($name);
        return $v === false || $v === '';
    };
    if ($envEmpty('DB_NAME') && getenv('MYSQL_DATABASE') !== false) {
        putenv('DB_NAME=' . getenv('MYSQL_DATABASE'));
    }
    if ($envEmpty('DB_USER') && getenv('MYSQL_USER') !== false) {
        putenv('DB_USER=' . getenv('MYSQL_USER'));
    }
    // DB_PASSWORD del .env tiene prioridad sobre MYSQL_PASSWORD (Docker / ejemplos suelen definir ambos).
    if (getenv('DB_PASSWORD') !== false && getenv('DB_PASSWORD') !== '') {
        putenv('DB_PASS=' . getenv('DB_PASSWORD'));
    } elseif ($envEmpty('DB_PASS') && getenv('MYSQL_PASSWORD') !== false) {
        putenv('DB_PASS=' . getenv('MYSQL_PASSWORD'));
    }
    if ((getenv('DB_PORT') === false || getenv('DB_PORT') === '') && getenv('MYSQL_PORT') !== false) {
        putenv('DB_PORT=' . getenv('MYSQL_PORT'));
    }
})();

/**
 * Varios controladores y custom_includes usan:
 *   $_SERVER['DOCUMENT_ROOT'] . '/facturacionv8/apis/...'
 * Eso asume que el docroot del servidor es el directorio PADRE de la carpeta del proyecto
 * (como en cPanel: public_html/facturacionv8/...).
 * Si el docroot apunta solo a .../public, los requires fallan; se normaliza aquí.
 */
$apisRel = '/facturacionv8/apis';
$parentOfProject = dirname(str_replace('\\', '/', BASE_PATH));
$expectedApis = $parentOfProject . $apisRel;
if (is_dir($expectedApis)) {
    $_SERVER['DOCUMENT_ROOT'] = $parentOfProject;
}

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
    
    // mod_rewrite en .htaccess envía la ruta en _url
    $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
    if (!empty($_GET['_url'])) {
        $requestUri = $_GET['_url'];
    }
    $application->handle($requestUri)->send();

} catch (\Exception $e) {
    // Manejar excepciones
    echo $e->getMessage() . '<br>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}
?>
