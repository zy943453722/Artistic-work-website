<?php
/**
 * Created by PhpStorm.
 * User: zy
 * Email: zy943453722@gmail.com
 * Date: 2019/4/7
 * Time: 16:58
 */

namespace App\Middleware;


class OssMiddleware extends baseMiddleware
{
    public function __invoke($request, $response, $next)
    {
        $this->oss;
        $response = $next($request,$response);
        return $response;
    }
}