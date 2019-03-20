<?php
/**
 * Created by PhpStorm.
 * User: zy
 * Email: zy943453722@gmail.com
 * Date: 2019/3/19
 * Time: 11:16
 */

namespace App\Middleware;

use PhpMiddleware\RequestId\Generator\PhpUniqidGenerator;

class PinMiddleware extends baseMiddleware
{
    public function __invoke($request, $response, $next)
    {
        $pin = $request->getHeader('x-artgallery-pin');
        if (!empty($pin)) {
            $next($request, $response);
            return $response;
        } else {
            $generator = new PhpUniqidGenerator();
            $requestId = $generator->generateRequestId();
            $output = [
                'requestId' =>  $requestId,
                'error' => [
                    'code' => 401,
                    'message' => "user authentication failed,lack pin"
                ]
            ];
            return $response->withStatus(401)
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($output));
        }
    }
}