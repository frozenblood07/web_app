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

$injector->alias('Http\Response', 'Http\HttpResponse');
$injector->share('Http\HttpResponse');


$params = parse_ini_file(__DIR__ . '/../../config/database/database_'.ENVIRONMENT.'.ini');
if ($params === false) {
    throw new \Exception("Error reading database configuration file");
}

$conStr = sprintf("mysql:host=%s;port=%d;dbname=%s",
    $params['host'],
    $params['port'],
    $params['database']);

$pdo = new \PDO($conStr, $params['user'], $params['password']);

$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
$injector->share($pdo);

$redisParams = parse_ini_file(__DIR__ . '/../../config/redis/redis_'.ENVIRONMENT.'.ini');

$redisClient = new Predis\Client([
    'scheme' => $redisParams['scheme'],
    'host'   => $redisParams['host'],
    'port'   => $redisParams['port'],
]);

$injector->share($redisClient);

return $injector;




