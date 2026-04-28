<?php
use Plugins\UrlDecodePlugin;
use Phalcon\Mvc\View;
use Phalcon\Di\DiInterface;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Session\Manager as SessionManager;
use Phalcon\Session\Adapter\Stream as SessionStream;
use Phalcon\Flash\Direct as FlashDirect;
use Phalcon\Flash\Session as FlashSession;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Events\Event;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Mvc\Router as Router;
use Phalcon\Http\Response\Cookies;
use Phalcon\Crypt;
use Phalcon\Session\Bag as SessionBag;
use Phalcon\Html\Escaper;
use Phalcon\Html\TagFactory;
use Phalcon\Assets\Manager as AssetsManager;

use Phalcon\Di\FactoryDefault;
use Phalcon\Logger\Logger;
use Phalcon\Logger\Adapter\Stream as FileAdapter;
use Phalcon\Db\Adapter\PdoFactory;
/**
 * Obtenemos la instancia del contenedor de dependencias por defecto
 */
$di = \Phalcon\Di\Di::getDefault();

/**
 * Servicio compartido de configuración
 */
$di->setShared('config', function () {
    return include APP_PATH . "/config/config.php";
});

$di->set(
    'tag',
    function () {
        $escaper = new Escaper();
        return new TagFactory($escaper);
    }
);

$di->set(
    'assets',
    function () {
        return new AssetsManager($this->get('tag'));
    }
);

/**
 * Configuración del servicio de sesiones
 */
$di->setShared('session', function () {
    // Configurar antes de iniciar la sesión
    ini_set('session.gc_maxlifetime', 24*3600); // 3 días
    ini_set('session.gc_probability', 1);
    ini_set('session.gc_divisor', 100);

    $sessionSave = getenv('SESSION_SAVE_PATH');
    if ($sessionSave === false || $sessionSave === '') {
        $sessionSave = BASE_PATH . '/var/sessions';
    }
    if (!is_dir($sessionSave)) {
        @mkdir($sessionSave, 0777, true);
    }

    $session = new SessionManager();
    $files = new SessionStream([
        'savePath' => $sessionSave,
        'prefix' => 'sess_',
        'lifetime' => 24*3600
    ]);
    $session->setAdapter($files);
    $session->start();
    return $session;
});

$di->setShared('sessionBag', function () use ($di) {
    $session = $di->get('session');
    return new SessionBag($session, 'bag');
});

$baseUri = $di->getShared('config')->application->baseUri;
$di->setShared('url', function () use ($baseUri) {
    $url = new Url();
    $url->setBaseUri($baseUri);

    return $url;
});

/**
 * Configuración del componente de vistas
 */
$di->setShared('view', function () {
    $config = $this->getConfig();

    $view = new View();
    $view->setDI($this);
    $view->setViewsDir($config->application->viewsDir);

    $view->registerEngines([
        '.volt' => function ($view) use ($config) {
            $volt = new VoltEngine($view, $this);
            $volt->setOptions([
                'path' => $config->application->cacheDir,
                'separator' => '_'
            ]);
            return $volt;
        },
        '.phtml' => PhpEngine::class
    ]);

    return $view;
});

/**
 * Dispatcher con EventsManager para Plugins
 */
$di->setShared('dispatcher', function () use ($di) {
    $eventsManager = $di->getShared('eventsManager');

    // Parámetros de ruta: primero (antes de Security); no usar el evento genérico "dispatch" (puede re-disparar y colapsar en ciclo de forwards).
    $eventsManager->attach('dispatch:beforeExecuteRoute', new UrlDecodePlugin());

    $securityPlugin = new SecurityPlugin();
    $eventsManager->attach('dispatch:beforeExecuteRoute', $securityPlugin);

    $eventsManager->attach('dispatch:beforeException', new NotFoundPlugin());

    $dispatcher = new Dispatcher();
    $dispatcher->setEventsManager($eventsManager);
    $dispatcher->setDefaultController('index');
    $dispatcher->setDefaultAction('index');

    return $dispatcher;
});

/**
 * Registro del servicio de elementos personalizados
 */
$di->set('elements', function () {
    return new Elements();
});

/**
 * Configuración del servicio de criptografía
 */
$di->set('crypt', function () {
    $crypt = new Crypt();
    $crypt->setKey('acastanedaa_facturalaya'); // Usa tu clave de encriptación
    return $crypt;
}, true);

// Logger
$di->setShared('logger', function () {
    $adapter = new FileAdapter(BASE_PATH . '/public/error_log');
    $logger = new Logger(
        'messages',
        [
            'main' => $adapter,
        ]
    );
    return $logger;
});

// Events Manager
$di->setShared('eventsManager', function () {
    return new EventsManager();
});

/**
 * Conexión a la base de datos basada en los parámetros definidos en el archivo de configuración
 */
$di->setShared('db', function () use ($di) {
    $config = $di->getShared('config');
    $eventsManager = $di->getShared('eventsManager');

    $db = $config->database;
    $port = isset($db->port) ? (int) $db->port : 3306;
    $charset = isset($db->charset) ? (string) $db->charset : 'utf8mb4';

    $params = [
        'host'     => (string) $db->host,
        'port'     => $port,
        'username' => (string) $db->username,
        'password' => (string) $db->password,
        'dbname'   => (string) $db->dbname,
        'charset'  => $charset,
    ];

    $factory = new PdoFactory();
    $connection = $factory->newInstance('mysql', $params);

    if (!is_object($connection)) {
        throw new \RuntimeException('El servicio db no pudo crear el adaptador PDO (valor no objeto).');
    }

    $connection->setEventsManager($eventsManager);

    return $connection;
});

// Asegurar que el ORM use el mismo contenedor (evita estados raros con el servidor embebido PHP)
if ($di->has('modelsManager')) {
    $di->getShared('modelsManager')->setDI($di);
}

// Fallo claro si MySQL no responde o credenciales/base incorrectas (antes solo: Invalid injected connection)
try {
    $di->getShared('db');
} catch (\Throwable $e) {
    error_log('[MySQL] ' . $e->getMessage());
    throw $e;
}

/**
 * Configuración del adaptador de metadatos
 */
$di->setShared('modelsMetadata', function () {
    return new MetaDataAdapter();
});

/**
 * Servicio Flash con clases CSS personalizadas
 */
$di->set('flash', function () {
    $escaper = new Escaper();
    $flash = new FlashDirect($escaper);
    $flash->setCssClasses([
        'error'   => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice'  => 'alert alert-info',
        'warning' => 'alert alert-warning',
    ]);
    return $flash;
});

$di->set('flashSession', function () {
    $escaper = new Escaper();
    $flash = new FlashSession($escaper);
    $flash->setCssClasses([
        'error'   => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice'  => 'alert alert-info',
        'warning' => 'alert alert-warning',
    ]);
    return $flash;
});

/**
 * Configuración del servicio de cookies
 */
$di->setShared('cookies', function () {
    $cookies = new Cookies();
    $cookies->useEncryption(false); // Ajusta según sea necesario
    return $cookies;
});

?>