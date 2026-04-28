<?php
use Phalcon\Di\Injectable;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher as MvcDispatcher;
use Phalcon\Mvc\Dispatcher\Exception as DispatcherException;

class NotFoundPlugin extends Injectable
{
    public function beforeException(Event $event, MvcDispatcher $dispatcher, \Exception $exception)
    {
        $msg = $exception->getMessage();
        if ($msg !== '' && stripos($msg, 'cyclic') !== false) {
            $event->stop();
            return false;
        }

        // Obtener el servicio del router desde el contenedor de dependencias
        $router = $this->getDI()->get('router');

        // Obtener la ruta coincidente y los parámetros
        $matchedRoute = $router->getMatchedRoute();
        $params = $router->getParams();
        
        // Obtener la URI solicitada desde el router
        $uri = $this->request->getURI();

        /*
        error_log('URI solicitada (desde request): ' . $uri);
        // Logs para depuración
        error_log('Se ha activado NotFoundPlugin');
        error_log('Excepción capturada: ' . get_class($exception));
        error_log('Mensaje: ' . $exception->getMessage());
        error_log('Código de excepción: ' . $exception->getCode());
        error_log('Controlador: ' . $dispatcher->getControllerName());
        error_log('Acción: ' . $dispatcher->getActionName());

        // Registrar variables de servidor y solicitud
        error_log('$_SERVER: ' . print_r($_SERVER, true));
        error_log('$_REQUEST: ' . print_r($_REQUEST, true));

        // Registrar la traza de la excepción
        error_log('Stack Trace: ' . $exception->getTraceAsString());

        if ($matchedRoute) {
            error_log('Ruta coincidente: ' . $matchedRoute->getPattern());
        } else {
            error_log('No se encontró una ruta coincidente.');
        }

        error_log('Parámetros de la ruta: ' . json_encode($params));
        error_log("\n\n");
        */
        
        // Manejo de excepciones de dispatcher
        if ($exception instanceof DispatcherException) {
            switch ($exception->getCode()) {
                case DispatcherException::EXCEPTION_HANDLER_NOT_FOUND:
                case DispatcherException::EXCEPTION_ACTION_NOT_FOUND:
                    if ($dispatcher->getControllerName() === 'errors'
                        && in_array($dispatcher->getActionName(), ['show404', 'show500', 'show401'], true)) {
                        $event->stop();
                        return false;
                    }
                    $dispatcher->forward([
                        'controller' => 'errors',
                        'action'     => 'show404',
                    ]);
                    return false;
            }
        }

        // Si no es una excepción manejada, redirigir a un error 500
        if ($dispatcher->getControllerName() !== 'errors') {
            $dispatcher->forward([
                'controller' => 'errors',
                'action'     => 'show500',
            ]);
        }

        return !$event->isStopped();
    }
}
