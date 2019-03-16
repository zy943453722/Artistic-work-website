<?php
/**
 * Created by PhpStorm.
 * User: zy
 * Email: zy943453722@gmail.com
 * Date: 2019/2/25
 * Time: 21:41
 */
use Illuminate\Database\Capsule\Manager;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Slim\Csrf\Guard;

$container = new Slim\Container($config);
$app = new Slim\App($container);

$container['db'] = function ($container) {
    $capsule = new Manager();
    $capsule->addConnection($container['setting']['db']);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();
    return $capsule;
};

$container['logger'] = function ($container) {
    $logger = new Logger($container['setting']['logger']['name']);
    $file_handle = new StreamHandler($container['setting']
        ['logger']['path'],$container['setting']['logger']['level']);
    $logger->pushHandler($file_handle);
    return $logger;
};

$container['csrf'] = function ($container) {
    return new Guard();
};

$container['notFoundHandler'] = function ($container) {

};

$container['errorHandler'] = function ($container) {

};

$container['notAllowedHandler'] = function ($container) {

};

return $app;