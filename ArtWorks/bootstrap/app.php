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
use PhpMiddleware\RequestId\Generator\PhpUniqidGenerator;

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
    $guard = new Guard();
    $guard->setPersistentTokenMode(true);
    $guard->setFailureCallable(function ($request, $response, $next) use ($container){
        $generator = new PhpUniqidGenerator();
        $requestId = $generator->generateRequestId();
        return $container['response']
            ->withStatus(400)
            ->withHeader('Content-type','application/json')
            ->write(json_encode(['requestId'=> $requestId,
                'error' => [
                    'code' => 400,
                    'message' => "Failed CSRF check!"
                ]]));
    });
    return $guard;
};

$container['notFoundHandler'] = function ($container) {
    return function ($request, $response) use ($container) {
        $generator = new PhpUniqidGenerator();
        $requestId = $generator->generateRequestId();
      return $container['response']
          ->withStatus(404)
          ->withHeader('Content-type','application/json')
          ->write(json_encode(['requestId'=> $requestId,
              'error' => [
                  'code' => 404,
                  'message' => "Resource not valid"
              ]]));
    };
};

$container['errorHandler'] = function ($container) {
    return function ($request, $response,$exception=null) use ($container) {
        $generator = new PhpUniqidGenerator();
        $requestId = $generator->generateRequestId();
        if ($exception !== null) {
            $code = $exception->getCode();
            $message = $exception->getMessage();
        }
        if (!is_integer($code) || $code < 100 || $code > 599) {
            $code = 500;
        }
        return $container['response']
            ->withStatus(500)
            ->withHeader('Content-type','application/json')
            ->write(json_encode(['requestId'=> $requestId,
                'error' => [
                    'code' => $code,
                    'message' => $message
                ]]));
    };
};

$container['notAllowedHandler'] = function ($container) {
    return function ($request, $response) use ($container) {
        $generator = new PhpUniqidGenerator();
        $requestId = $generator->generateRequestId();
        return $container['response']
            ->withStatus(405)
            ->withHeader('Content-type','application/json')
            ->write(json_encode(['requestId'=> $requestId,
                'error' => [
                    'code' => 405,
                    'message' => "Method not allowed"
                ]]));
    };
};

return $app;