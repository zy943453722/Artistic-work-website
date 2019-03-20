<?php
/**
 * Created by PhpStorm.
 * User: zy
 * Email: zy943453722@gmail.com
 * Date: 2019/3/16
 * Time: 15:36
 */
use Slim\Middleware\JwtAuthentication;
use PhpMiddleware\RequestId\Generator\PhpUniqidGenerator;
use Tuupola\Middleware\CorsMiddleware;

//jwt验证的中间件
$app->add(new JwtAuthentication(
    [
        "secure" => false,//是否启用HTTPS
        "secret" => $container['setting']['secret'],//密钥
        "cookie" => "token",//cookie中的名字
        "attribute" => "token",//放入$request对象，可用getAttribute获取
        "path" => "/",//需要检查的路径
        "passthrough" => "/users/token",//不需要检查路径
        "error" => function ($request, $response, $args) {//错误处理
            $generator = new PhpUniqidGenerator();
            $requestId = $generator->generateRequestId();
            $output = [
                'requestId' =>  $requestId,
                'error' => [
                    'code' => 401,
                    'message' => $args['message']
                ]
            ];
            return $response->withStatus(401)
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($output));
        },
        "callback" => function ($request, $response, $param) use ($container){
            $container['token'] = $param['decoded'];
        }//正确时的回调
    ]));
//跨域请求访问的中间件
$app->add(new CorsMiddleware([
        "logger" => $container['logger'],
        "origin" => "http://www.artgallery.com:8888",//请求源
        "method" => ["GET", "POST", "PUT", "DELETE"],//允许请求方法
        "headers.allow" => ["Authorization", "If-Match", "If-Unmodified-Since"],//允许
        "headers.expose" => ["Authorization", "Etag"],//除了基础6个头外，想获取其他头部
        "credentials" => true,//是否可以发送cookie
        "error" => function ($request, $response, $args) {
            $generator = new PhpUniqidGenerator();
            $requestId = $generator->generateRequestId();
            $output = [
                'requestId' =>  $requestId,
                'error' => [
                    'code' => 401,
                    'message' => $args['message']
                ]
            ];
            return $response->withStatus(401)
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($output));
        }
        ]
));

$app->add($container['csrf']);

