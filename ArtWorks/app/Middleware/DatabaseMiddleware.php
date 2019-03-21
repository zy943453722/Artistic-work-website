<?php
/**
 * Created by PhpStorm.
 * User: zy
 * Email: zy943453722@gmail.com
 * Date: 2019/3/20
 * Time: 11:09
 */

namespace App\Middleware;


class DatabaseMiddleware extends baseMiddleware
{
    public function __invoke($request, $response, $next)
    {
        $this->db;
        $response = $next($request,$response);
        return $response;
    }
}