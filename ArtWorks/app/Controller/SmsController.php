<?php
/**
 * Created by PhpStorm.
 * User: zy
 * Email: zy943453722@gmail.com
 * Date: 2019/3/21
 * Time: 15:41
 */

namespace App\Controller;

use App\View\ApiView;
use App\View\ResultCode;
use App\Common\ZhenziSmsClient;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Validation\Validator;
use PhpMiddleware\RequestId\Generator\PhpUniqidGenerator;

class SmsController extends baseController
{
    public function getSms(Request $request, Response $response)
    {
        $params = $request->getQueryParams();
        $rules = [
            'phoneNumber' => 'required|numeric'
        ];
        if (!Validator::validators($rules, $params)) {
            return ApiView::jsonResponse($response,ResultCode::PARAM_IS_INVAILD);
        }


        $client = new ZhenziSmsClient($this->setting['sms']['url'],
            $this->setting['sms']['app_id'],
            $this->setting['sms']['app_secret']);
        $verifyCode = $this->randomKeys(4);
        $result = $client->send($params['phoneNumber'], "您的验证码为".$verifyCode.",有效时间为5分钟");


        if ($this->dealResult($result,$params['phoneNumber'],$verifyCode) === true) {
            return ApiView::jsonResponse($response, ResultCode::SUCCESS,[]);
        } else {
            $generator = new PhpUniqidGenerator();
            $requestId = $generator->generateRequestId();
            $output = [
                'requestId' =>  $requestId,
                'error' => [
                    'code' => 401,
                    'message' => "短信获取失败"
                ]
            ];
            return $response->withStatus(404)
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($output));
        }
    }

    public function randomKeys($length)
    {
        $key = '';
        $pattern='1234567890'; //字符池
        for($i=0;$i<$length;$i++){
            $key.=$pattern{mt_rand(0,9)};//生成php随机数
        }
        return $key;
    }

    public function dealResult($result,$phoneNumber,$verifyCode)
    {
        $response = json_decode($result);
        if ($response->code === 0) {
            $redis = $this->redis;
            //$redis->set($phoneNumber, $verifyCode);
            $redis->setex($phoneNumber, 300, $verifyCode);
            return true;
        }
        return false;
    }
}