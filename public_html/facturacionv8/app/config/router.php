<?php

use Phalcon\Mvc\Router;

$di = \Phalcon\Di\Di::getDefault();
$baseUri = $di->getShared('config')->application->baseUri;

$router = new Router(false); // No usar rutas predeterminadas

// Eliminar barras adicionales
$router->removeExtraSlashes(true);

$router->add(
    $baseUri . ':controller/:action/:params',
    [
        'controller' => 1,
        'action' => 2,
        'params' => 3,
    ]
)->setName('defaultRoute');

$router->add(
    $baseUri . ':controller',
    [
        'controller' => 1,
        'action' => 'index',
        'params' => '',
    ]
);

// Registrar el router en el contenedor de dependencias
$di->setShared('router', $router);
?>