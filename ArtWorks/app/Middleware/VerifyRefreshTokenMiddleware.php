<?php
/**
 * Created by PhpStorm.
 * User: zy
 * Email: zy943453722@gmail.com
 * Date: 2019/3/30
 * Time: 10:50
 */

namespace App\Middleware;

use PhpMiddleware\RequestId\Generator\PhpUniqidGenerator;

class VerifyRefreshTokenMiddleware extends baseMiddleware
{
    //不管是哪种refreshToken不通过的情况，都要求重新登录
    public function __invoke($request, $response, $next)
    {
        $refreshToken = $request->getHeader('x-artgallery-refreshToken')[0];
        if (empty($refreshToken)) {
            $generator = new PhpUniqidGenerator();
            $requestId = $generator->generateRequestId();
            $output = [
                'requestId' =>  $requestId,
                'error' => [
                    'code' => 401,
                    'message' => "refreshToken is lack"
                ]
            ];
            return $response->withStatus(401)
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($output));
        }

        $time = $this->tokenRedis->hget($refreshToken,'expires');
        $is_del = $this->tokenRedis->hget($refreshToken, 'is_del');

        if ($is_del === 1) {
            $generator = new PhpUniqidGenerator();
            $requestId = $generator->generateRequestId();
            $output = [
                'requestId' =>  $requestId,
                'error' => [
                    'code' => 401,
                    'message' => "refreshToken is deleted"
                ]
            ];
            return $response->withStatus(401)
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($output));
        }
        if ($time <= time())
        {
            $generator = new PhpUniqidGenerator();
            $requestId = $generator->generateRequestId();
            $output = [
                'requestId' =>  $requestId,
                'error' => [
                    'code' => 401,
                    'message' => "refreshToken is expired"
                ]
            ];
            $this->tokenRedis->hset($refreshToken, 'is_del', 1);
            return $response->withStatus(401)
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($output));
        }
        $response = $next($request, $response);
        return $response;
    }
}