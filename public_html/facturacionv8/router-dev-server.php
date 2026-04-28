<?php
/**
 * Router para `php -S` ejecutado en la carpeta facturacionv8/ (sin `-t public`).
 * Sirve ficheros bajo public/ tanto si la URL es /public/... como /template/..., /css/..., etc.
 * El resto delega en public/index.php (Phalcon).
 */
declare(strict_types=1);

$root = __DIR__;
$rawUri = $_SERVER['REQUEST_URI'] ?? '/';
$uriPath = parse_url($rawUri, PHP_URL_PATH);
$uriPath = $uriPath === false ? '/' : rawurldecode($uriPath);
$queryString = parse_url($rawUri, PHP_URL_QUERY);
$queryString = ($queryString !== false && $queryString !== null && $queryString !== '') ? '?' . $queryString : '';

if (str_contains($uriPath, '..')) {
    $uriPath = '/';
}

// Vistas y formularios usan /facturacionv8/...; con php -S y APP_BASE_URI=/ Phalcon espera /login/... sin prefijo.
if (preg_match('#^(/facturacionv8)(/|$)#i', $uriPath, $m)) {
    $stripped = substr($uriPath, strlen($m[1]));
    $uriPath = ($stripped === '' || $stripped === '/') ? '/' : $stripped;
}

/** @return string|null ruta absoluta al fichero estático o null */
$resolvePublicFile = static function (string $path) use ($root): ?string {
    if ($path === '' || $path === '/') {
        return null;
    }
    if (preg_match('#\.php(\?|$)#i', $path)) {
        return null;
    }
    $rel = ltrim(str_replace('\\', '/', $path), '/');
    if (str_starts_with($rel, 'public/')) {
        $rel = substr($rel, strlen('public/'));
    }
    $full = $root . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $rel);
    return is_file($full) ? $full : null;
};

$staticFile = $resolvePublicFile($uriPath);
if ($staticFile !== null) {
    $ext = strtolower(pathinfo($staticFile, PATHINFO_EXTENSION));
    $types = [
        'css' => 'text/css; charset=UTF-8',
        'js' => 'application/javascript; charset=UTF-8',
        'json' => 'application/json',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'svg' => 'image/svg+xml',
        'ico' => 'image/x-icon',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf' => 'font/ttf',
        'eot' => 'application/vnd.ms-fontobject',
        'map' => 'application/json',
        'webp' => 'image/webp',
        'txt' => 'text/plain; charset=UTF-8',
    ];
    header('Content-Type: ' . ($types[$ext] ?? 'application/octet-stream'));
    header('Content-Length: ' . (string) filesize($staticFile));
    readfile($staticFile);
    exit(0);
}

$_SERVER['REQUEST_URI'] = $uriPath . $queryString;

chdir($root . DIRECTORY_SEPARATOR . 'public');
require $root . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'index.php';
