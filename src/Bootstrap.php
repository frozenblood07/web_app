<?php
/**
 * File: Bootstrap.php
 * User: karan.tuteja26@gmail.com
 * Description: Main app file which will load all necessary configuration and dependencies required by the app
 */

namespace Ticket;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use WoohooLabs\Harmony\Examples\Controller\GetBookAction;
use WoohooLabs\Harmony\Examples\Controller\UserController;
use WoohooLabs\Harmony\Harmony;
use WoohooLabs\Harmony\Middleware\DiactorosResponderMiddleware;
use WoohooLabs\Harmony\Middleware\DispatcherMiddleware;
use WoohooLabs\Harmony\Middleware\FastRouteMiddleware;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\ServerRequestFactory;


$ENVIRONMENT = getenv('ENVIRONMENT');

if(!$ENVIRONMENT) {
    $ENVIRONMENT = "development";
}

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . "/../config/config/config.php";

error_reporting(ERROR_LEVEL);

/**
 * Register the error handler
 */
$whoops = new \Whoops\Run;

if (ENVIRONMENT !== 'production') {
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
}else {
    $whoops->pushHandler(function($e){
        /**
         * An error occured on the production environment log error and return a message;
         */
        $logger = new \Katzgrau\KLogger\Logger(LOG_PATH, LOG_LEVEL,array ('extension' => 'log'));
        $logger->error("Exception ".$e->getMessage());
        echo "Some error occurred. We will get back soon.";
    });
}

$whoops->register();

//inject all the dependencies
$injector = include('Dependencies/Dependencies.php');

$request = $injector->make('Http\HttpRequest');
$response = $injector->make('Http\HttpResponse');

//routing module
$routeDefinitionCallback = function (\FastRoute\RouteCollector $r) {
    $routes = include('Routes/Routes.php');
    foreach ($routes as $route) {
        $r->addRoute($route[0], $route[1], $route[2]);
    }
};

$dispatcher = \FastRoute\simpleDispatcher($routeDefinitionCallback);
//when api is hit process starts
$routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getPath());
switch ($routeInfo[0]) {
    case \FastRoute\Dispatcher::NOT_FOUND:
        $response->setContent('404 - Page not found');
        $response->setStatusCode(404);
        break;
    case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $response->setContent('405 - Method not allowed');
        $response->setStatusCode(405);
        break;
    case \FastRoute\Dispatcher::FOUND:
        //route found
        $className = $routeInfo[1][0];
        $method = $routeInfo[1][1];
        $vars = $routeInfo[2];

        $class = $injector->make($className);
        $class->$method($vars);//call the call method
        break;
}

foreach ($response->getHeaders() as $header) {
    header($header);
}
echo $response->getContent();