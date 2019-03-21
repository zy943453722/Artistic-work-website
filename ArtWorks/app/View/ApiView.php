<?php
/**
 * Created by PhpStorm.
 * User: zy
 * Email: zy943453722@gmail.com
 * Date: 2019/3/7
 * Time: 10:06
 */

namespace App\View;

use PhpMiddleware\RequestId\Generator\PhpUniqidGenerator;

class ApiView
{
    /**
     * 当正常调用API时返回
     */
    public static function jsonResponse($response, $message,$data=[], $code=200)
    {
        $generator = new PhpUniqidGenerator();
        $requestId = $generator->generateRequestId();
        $codes = ResultCode::mapCode();
        $output = [
            'requestId' => $requestId,
            'errno' => $message
        ];
        $output['errmsg'] = $codes[$message]['message'];
        $output['data'] = [];
        $output = array_merge($output, $data);

        return $response->withStatus($code)
                        ->withHeader("Content-Type", "application/json")
                        ->write(json_encode($output));

    }
}