<?php
/**
 * Created by PhpStorm.
 * User: zy
 * Email: zy943453722@gmail.com
 * Date: 2019/3/15
 * Time: 9:22
 */

namespace App\Controller;

use App\Model\User;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Validation\Validator;
use App\View\ApiView;
use App\View\ResultCode;

class UserController extends baseController
{
    public function Login(Request $request, Response $response)
    {

    }

    public function Register(Request $request, Response $response)
    {

    }

    public function getUserDetail(Request $request, Response $response)
    {
        $params = $request->getQueryParams();
        $rules = [
            'phoneNumber' => 'required|string'
        ];
        if (!Validator::validators($rules, $params)) {
            return ApiView::jsonResponse($response,ResultCode::PARAM_IS_INVAILD);
        }
        $user = new User();
        $result = $user->getUserDetail($params['phoneNumber']);
        $data = ['data' => $result];
        //接收到返回的参数组装成response返回
        return ApiView::jsonResponse($response, ResultCode::SUCCESS, $data);

    }
}