<?php
/**
 * Created by PhpStorm.
 * User: zy
 * Email: zy943453722@gmail.com
 * Date: 2019/3/16
 * Time: 15:36
 */
use Slim\Middleware\JwtAuthentication;
use App\View\ResultCode;
use App\View\ApiView;
use PhpMiddleware\RequestId\Generator\PhpUniqidGenerator;
use Tuupola\Middleware\CorsMiddleware;
use App\Middleware\DatabaseMiddleware;

//jwt验证的中间件
$app->add(new JwtAuthentication(
    [
        "secure" => false,//是否启用HTTPS
        "secret" => $container['setting']['secret'],//密钥
        "header" => "Authorization",//放在http头里面
        "regexp" => "/Bearer\s+(.*)$/i",//匹配字符串
        "attribute" => "token",//放入$request对象，可用getAttribute获取
        "path" => "/",//需要检查的路径
        "passthrough" => [
            "/users/token",
            "/users/touristListUserRecord",
            "/works/touristList",
            "/users/add",
            "/users/getUserDetail",
            "/users/findUserPassword",
            "/users/getUserRecord",
            "/users/touristListWorks",
            "/works/touristGetWorksDetail",
            "/works/getLikesDetail",
            "/works/touristGetCommentsDetail",
            "/users/getLikemeDetail",
            "/users/getILikeDetail",
            "/users/getComments",
            "/users/updateToken",
            "/users/login",
            "/users/feedback",
            "/users/verifyUserCode",
            "/users/getSms",
            "/users/uploadCallback",
            "/users/getPin"
        ],//不需要检查路径
        "error" => function ($request, $response, $args) {//错误处理
            $codes = ResultCode::mapCode();
            //如果token过期返回成功，刷新token
            if ($args['message'] == $codes[ResultCode::TOKEN_IS_EXPIRED]['message']) {
                return ApiView::jsonResponse($response, ResultCode::TOKEN_IS_EXPIRED);
            }
            //其他情况均是不合法的身份
            $generator = new PhpUniqidGenerator();
            $requestId = $generator->generateRequestId();
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
        }//正确时的回调
    ]));
//跨域请求访问的中间件
/*$app->add(new CorsMiddleware([
        "logger" => $container['logger'],
        "origin" => "http://www.artgallery.com:8888",//请求源
        "method" => ["GET", "POST", "PUT", "DELETE"],//允许请求方法
        "headers.allow" => ["Authorization", "If-Match", "If-Unmodified-Since"],//允许
        "headers.expose" => ["Authorization", "Etag"],//除了基础6个头外，想获取其他头部
        "credentials" => true,//是否可以发送cookie
        "error" => function ($request, $response, $args) {
            $generator = new PhpUniqidGenerator();
            $requestId = $generator->generateRequestId();
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
        }
        ]
));*/

$app->add(DatabaseMiddleware::class);
