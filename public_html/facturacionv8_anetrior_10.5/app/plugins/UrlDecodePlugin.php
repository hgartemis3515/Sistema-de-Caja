<?php
namespace Plugins;

use Phalcon\Di\Injectable;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;

class UrlDecodePlugin extends Injectable
{
    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        $params = $dispatcher->getParams();
        
        foreach ($params as $key => $value) {
            if (is_string($value)) {
                $params[$key] = urldecode($value);
            }
        }
        
        $dispatcher->setParams($params);
    }
}