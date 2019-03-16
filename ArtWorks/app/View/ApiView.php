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
        $output = array_merge($output, $data);
        $response = $response->withStatus($code);
        $response = $response->withHeader('Content-type','application/json');
        $body = $response->getBody();
        $body->write(json_encode($output));
        return $response;
    }
}