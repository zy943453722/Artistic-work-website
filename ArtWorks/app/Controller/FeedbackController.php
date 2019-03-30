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
            'nickname' => 'required|string',
            'email' => 'required|email',
            'qq' => 'required|numeric',
            'content' => 'required|string'
        ];
        if (!Validator::validators($rules, $params)) {
            return ApiView::jsonResponse($response,ResultCode::PARAM_IS_INVAILD);
        }


    }
}