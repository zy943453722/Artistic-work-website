<?php
/**
 * Created by PhpStorm.
 * User: zy
 * Email: zy943453722@gmail.com
 * Date: 2019/3/24
 * Time: 20:27
 */

namespace App\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Validation\Validator;
use App\View\ApiView;
use App\View\ResultCode;

class FeedbackController extends baseController
{
    public function sendEmail(Request $request, Response $response)
    {
        $params = $request->getParsedBody();
        $rules = [
            'nickname' => 'required|alpha_num|between:1,16',
            'email' => 'required|email'
        ];
        if (!Validator::validators($rules, $params)) {
            return ApiView::jsonResponse($response,ResultCode::PARAM_IS_INVAILD);
        }

        $producer = $this->mq;
        $email = [
            'subject' => '反馈已收到',
            'from' => [$this->setting['email']['username'] => 'zy'],
            'to' => [$params['email'] => 'user'],
            'body' => "尊敬的".$params['nickname'].",您好！您的意见反馈已收到，我们会在三个工作日内回复您，请知悉，谢谢！"
        ];
        $producer->useTube('email')->put(json_encode($email));
        return ApiView::jsonResponse($response, ResultCode::SUCCESS);
    }

}