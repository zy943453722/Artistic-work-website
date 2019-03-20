<?php
/**
 * Created by PhpStorm.
 * User: zy
 * Email: zy943453722@gmail.com
 * Date: 2019/2/28
 * Time: 15:04
 */

namespace App\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Validation\Validator;
use App\Model\User;
use App\View\ApiView;
use App\View\ResultCode;

class WelcomeController extends baseController
{
    public function getUser(Request $request, Response $response)
    {
        //获取请求参数，验证字段
        $params = $request->getAttributes();
        $rules = [
          'uid' => 'required|numeric'
        ];
        if (!Validator::validators($rules, $params)) {
            return ApiView::jsonResponse($response,ResultCode::PARAM_TYPE_BIND_ERROR);
        }
        $this->initDatabase();

        //传递参数到model层
        $user = new User();
        $result = $user->getUser();
        //判断是否出现逻辑错误
        if (isset($result['res']) && $result['res'] === false) {
            return ApiView::jsonResponse($response, $result['message']);
        }
        else {
            $data = ['data' => $result];
            //接收到返回的参数组装成response返回
            return ApiView::jsonResponse($response, ResultCode::SUCCESS, $data);
        }

    }
}