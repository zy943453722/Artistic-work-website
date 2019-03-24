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
use App\Model\UserInformation;
use App\Model\UserStatus;
use App\Model\UserRecord;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Validation\Validator;
use App\View\ApiView;
use App\View\ResultCode;

class UserController extends baseController
{
    public function login(Request $request, Response $response)
    {
        $params = $request->getParsedBody();
        $rules = [
            'phoneNumber' => 'required|numeric',
            'password' => 'required|string'
        ];
        if (!Validator::validators($rules, $params)) {
            return ApiView::jsonResponse($response, ResultCode::PARAM_IS_INVAILD);
        }

        $userStatus = new UserStatus();
        $result = $userStatus->getUserStatus($params['phoneNumber']);
        if ($result[0]['status'] === 0) {
            return ApiView::jsonResponse($response, ResultCode::USER_ALREADY_LOGIN);
        }

        $user = new User();
        $password = md5($params['password'].$this->setting['salt']);
        $res = $user->verifyUserLogin($params['phoneNumber'], $password);
        if ($res === 0) {
            return ApiView::jsonResponse($response, ResultCode::USER_PASSWORD_ERROR);
        }

        $ret = $userStatus->modifyUserStatus($params['phoneNumber'], 0);
        if ($ret == false) {
            return ApiView::jsonResponse($response, ResultCode::UNKNOWN_ERROR);
        }
        return ApiView::jsonResponse($response, ResultCode::SUCCESS, []);
    }

    public function register(Request $request, Response $response)
    {
        $params = $request->getParsedBody();
        $rules = [
            'phoneNumber' => 'required|numeric',
            'password' => 'required|string',
            'verifyCode' => 'required|numeric',
            'nickname' => 'required|string'
        ];
        if (!Validator::validators($rules, $params)) {
            return ApiView::jsonResponse($response,ResultCode::PARAM_IS_INVAILD);
        }

        $redis = $this->redis;
        $verifyCode = $redis->get($params['phoneNumber']);
        if ($verifyCode !== $params['verifyCode']) {
            return ApiView::jsonResponse($response, ResultCode::VERIFYCODE_IS_ERROR);
        }

        $user = new User();
        $result = $user->addUser($params['phoneNumber'],$params['password'],$this->setting['salt']);
        $userRecord = new UserRecord();
        $result &= $userRecord->addUserRecord($params['phoneNumber']);
        $usrInfo = new UserInformation();
        $result &= $usrInfo->addUserInfo($params['phoneNumber'], $params['nickname']);
        $usrStatus = new UserStatus();
        $result &= $usrStatus->addUserStatus($params['phoneNumber']);

        $data = ['data' => ['pin' => $params['phoneNumber']]];
        if ($result == true) {
            return ApiView::jsonResponse($response, ResultCode::SUCCESS, $data, 201);
        }
        return ApiView::jsonResponse($response, ResultCode::UNKNOWN_ERROR);
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