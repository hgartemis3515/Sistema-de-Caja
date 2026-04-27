<?php
use Phalcon\Config\Config;
/*
 * DB: variables de entorno (recomendado). Ver `.env.example` en la raíz «Sistema de caja».
 */
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

/** getenv + $_ENV (index.php rellena $_ENV desde .env; en Windows putenv/getenv a veces no coinciden). */
$env = static function (string $key): string|false {
    $v = getenv($key);
    if ($v !== false && $v !== '') {
        return $v;
    }
    if (array_key_exists($key, $_ENV) && (string) $_ENV[$key] !== '') {
        return (string) $_ENV[$key];
    }
    return false;
};

$dbHost = $env('DB_HOST');
if ($dbHost === false || $dbHost === '') {
    $dbHost = 'localhost';
}
$dbUser = $env('DB_USER');
if ($dbUser === false || $dbUser === '') {
    $dbUser = 'root';
}
$dbPass = $env('DB_PASS');
if ($dbPass === false || $dbPass === '') {
    $p = $env('DB_PASSWORD');
    $dbPass = $p !== false ? $p : '';
}
$dbName = $env('DB_NAME');
if ($dbName === false || $dbName === '') {
    $dbName = 'facturacion_local';
}
$dbPort = $env('DB_PORT');
if ($dbPort === false || $dbPort === '') {
    $mp = $env('MYSQL_PORT');
    $dbPort = $mp !== false ? $mp : '3306';
}
$dbPort = (int) $dbPort;

$baseUriOverride = $env('APP_BASE_URI');
if ($baseUriOverride !== false && $baseUriOverride !== '') {
    if (preg_match('#^https?://#i', $baseUriOverride)) {
        $path = parse_url($baseUriOverride, PHP_URL_PATH) ?: '/';
        $baseUri = $path === '/' ? '/' : (str_ends_with($path, '/') ? $path : $path . '/');
    } else {
        $baseUri = $baseUriOverride;
    }
} else {
    $baseUri = preg_replace('/public([\/\\\\])index.php$/', '', $_SERVER['PHP_SELF'] ?? '/');
}
if ($baseUri === '' || $baseUri === null) {
    $baseUri = '/';
}

return new Config([
    'database' => [
        'adapter'     => strtolower((string) (($a = $env('DB_ADAPTER')) !== false && $a !== '' ? $a : 'mysql')),
        'host'        => $dbHost,
        'port'        => $dbPort,
        'username'    => $dbUser,
        'password'    => $dbPass,
        'dbname'      => $dbName,
        'charset'     => 'utf8mb4',
    ],
    'application' => [
        'appDir'         => APP_PATH . '/',
        'controllersDir' => APP_PATH . '/controllers/',
        'modelsDir'      => APP_PATH . '/models/',
        'migrationsDir'  => APP_PATH . '/migrations/',
        'viewsDir'       => APP_PATH . '/views/',
        'pluginsDir'     => APP_PATH . '/plugins/',
        'libraryDir'     => APP_PATH . '/library/',
        'cacheDir'       => BASE_PATH . '/cache/',
        'formsDir'       => BASE_PATH . '/forms/',
        'baseUri'        => $baseUri,
    ]
]);
