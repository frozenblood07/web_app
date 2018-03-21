<?php
/**
 * File: Dependencies.php
 * User: karan.tuteja26@gmail.com
 * Description: Dependency injection object creator. Will return an object containing all the dependencies in the app
 */

$injector = new \Auryn\Injector;

$injector->alias('Http\Request', 'Http\HttpRequest');
$injector->share('Http\HttpRequest');
$injector->define('Http\HttpRequest', [
    ':get' => $_GET,
    ':post' => $_POST,
    ':cookies' => $_COOKIE,
    ':files' => $_FILES,
    ':server' => $_SERVER,
    ':inputStream' => file_get_contents('php://input')
]);

$injector->define('Mustache_Engine', [
    ':options' => [
        'loader' => new Mustache_Loader_FilesystemLoader(__DIR__ . '/../../templates', [
            'extension' => '.html',
        ]),
    ],
]);

$injector->delegate('Twig_Environment', function () use ($injector) {
    $loader = new Twig_Loader_Filesystem(__DIR__ . '/../../templates');
    $twig = new Twig_Environment($loader);
    return $twig;
});

$injector->alias('Http\Response', 'Http\HttpResponse');
$injector->share('Http\HttpResponse');

$injector->share("Ticket\Utilities\Validator");
$injector->share("Ticket\Utilities\ResponseFormatter");
$injector->share("Ticket\Services\ShowService");

$injector->alias('Ticket\Template\Renderer', 'Ticket\Template\TwigRenderer');

$redisParams = parse_ini_file(__DIR__ . '/../../config/redis/redis_'.ENVIRONMENT.'.ini');

$redisClient = new Predis\Client([
    'scheme' => $redisParams['scheme'],
    'host'   => $redisParams['host'],
    'port'   => $redisParams['port'],
]);

$injector->share($redisClient);

return $injector;




