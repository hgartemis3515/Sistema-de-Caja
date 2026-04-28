<?php

use Phalcon\Mvc\Router;

$di = \Phalcon\Di\Di::getDefault();
$baseUri = $di->getShared('config')->application->baseUri;

$router = new Router(false); // No usar rutas predeterminadas

// true convierte "///" en "/" pero en algunas versiones la raíz queda "" y el matcheo falla.
$router->removeExtraSlashes(true);

$trimBase = rtrim((string) $baseUri, '/');
$pathPrefix = ($trimBase === '') ? '/' : ($trimBase . '/');
$homePattern = ($trimBase === '') ? '/' : ($trimBase . '/');

$defHome = [
    'controller' => 'index',
    'action'     => 'index',
];
$router->add($homePattern, $defHome)->setName('home');
if ($pathPrefix === '/') {
    $router->add('', $defHome)->setName('homeEmpty');
}

$router->add(
    $pathPrefix . ':controller/:action/:params',
    [
        'controller' => 1,
        'action' => 2,
        'params' => 3,
    ]
)->setName('defaultRoute');

$router->add(
    $pathPrefix . ':controller',
    [
        'controller' => 1,
        'action' => 'index',
        'params' => '',
    ]
);

// Registrar el router en el contenedor de dependencias
$di->setShared('router', $router);
?>