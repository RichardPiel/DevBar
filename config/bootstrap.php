<?php

use Cake\Core\Configure;
use Cake\Event\EventManager;
use DevBar\Middleware\BarMiddleware;

if (Configure::read('debug')) {
    EventManager::instance()->on('Server.buildMiddleware', function ($event, $queue)  {
        $middleware = new BarMiddleware();
        $queue->insertAt(0, $middleware);
    });
}
