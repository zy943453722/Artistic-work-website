<?php
/**
 * Created by PhpStorm.
 * User: zy
 * Email: zy943453722@gmail.com
 * Date: 2019/3/7
 * Time: 17:10
 */

namespace App\Middleware;


class RequestMiddleware
{
    public function __invoke($resquest, $response, $next)
    {
        //业务
        $response = $next($resquest, $response);
        return $response;
    }

}