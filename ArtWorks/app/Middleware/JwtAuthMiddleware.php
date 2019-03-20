<?php
/**
 * Created by PhpStorm.
 * User: zy
 * Email: zy943453722@gmail.com
 * Date: 2019/3/16
 * Time: 10:06
 */

namespace App\Middleware;

use Slim\Middleware\JwtAuthentication;
use PhpMiddleware\RequestId\Generator\PhpUniqidGenerator;
use App\View\ApiView;
use App\View\ResultCode;

class JwtAuthMiddleware extends baseMiddleware
{
    public function __invoke($request, $response, $next)
    {
        $generator = new PhpUniqidGenerator();
        $requestId = $generator->generateRequestId();
        $container = $this->ci;
        $jwt = new JwtAuthentication(
            [
                "secure" => false,
                "secret" => $this->ci->get('setting')['secret'],
                "cookie" => "token",
                "attribute" => "token",
                "path" => "/",
                "passthrough" => "/users/token",
                "error" => function ($request, $response, $args) use ($requestId){
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
                    return ApiView::jsonResponse($response,ResultCode::SUCCESS);
                }
            ]);
        return $response;
    }
}