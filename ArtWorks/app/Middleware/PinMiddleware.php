<?php
/**
 * Created by PhpStorm.
 * User: zy
 * Email: zy943453722@gmail.com
 * Date: 2019/3/19
 * Time: 11:16
 */

namespace App\Middleware;

use App\View\ApiView;
use App\View\ResultCode;

class PinMiddleware extends baseMiddleware
{
    public function __invoke($request, $response, $next)
    {
        $pin = $request->getHeader('x-artgallery-pin');
        if (!empty($pin)) {
            $response = $next($request, $response);
            return $response;
        } else {
            return ApiView::jsonResponse($response, ResultCode::USER_PIN_LACK);
        }
    }
}