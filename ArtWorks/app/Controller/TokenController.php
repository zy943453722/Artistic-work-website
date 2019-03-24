<?php
/**
 * Created by PhpStorm.
 * User: zy
 * Email: zy943453722@gmail.com
 * Date: 2019/3/18
 * Time: 11:25
 */

namespace App\Controller;

use App\View\ApiView;
use App\View\ResultCode;
use Slim\Http\Request;
use Slim\Http\Response;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use PhpMiddleware\RequestId\Generator\PhpUniqidGenerator;

class TokenController extends baseController
{
    public function getToken(Request $request, Response $response)
    {
        $pin = $request->getHeader('x-artgallery-pin')[0];
        if (empty($pin)) {
            return ApiView::jsonResponse($response, ResultCode::USER_PIN_LACK);
        }
        $pin = base64_decode($request->getHeader('x-artgallery-pin')[0]);
        $generator = new PhpUniqidGenerator();
        $id = $generator->generateRequestId();
        $signer = new Sha256();
        $token = (new Builder())
            ->setIssuer('http://artgallery.com')//配置iss颁布者
            ->setAudience('http://api.artworks.com')//设置aud接收者受众
            ->setId($id, true)//设置jti即编号
            ->setIssuedAt(time())//设置iat即签发时间
            ->setNotBefore(time())//设置nbf即生效时间
            ->setExpiration(time()+3600)//设置exp即过期时间
            ->setSubject('userToken')//设置sub即主题
            ->set('pin', $pin)
            ->sign($signer, $this->ci['setting']['secret'])//设置
            ->getToken();

        $expires = time()+7200;
        $response = $response->withHeader('Set-Cookie',"token=$token;expires=$expires;httpOnly");

        return ApiView::jsonResponse($response, ResultCode::SUCCESS,[]);
    }
}