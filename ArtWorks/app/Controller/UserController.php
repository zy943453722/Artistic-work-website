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
use App\Model\UserFriends;
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
    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     * 登录操作
     */
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

    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     * 注册操作
     */
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

    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     * 用户登出
     */
    public function logout(Request $request, Response $response)
    {
        $refreshToken = $request->getHeader('x-artgallery-refreshToken')[0];
        if (empty($refreshToken)) {
            return ApiView::jsonResponse($response,ResultCode::TOKEN_IS_LACK);
        }
        $pin = $this->token->pin;
        $usrStatus = new UserStatus();
        $result = $usrStatus->modifyUserStatus($pin, 1);
        if ($result == false) {
            return ApiView::jsonResponse($response, ResultCode::UNKNOWN_ERROR);
        }
        $this->tokenRedis->hset($refreshToken, 'is_del', 1);
        return ApiView::jsonResponse($response, ResultCode::SUCCESS,[]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     * 获取用户注册详情
     */
    public function getUserDetail(Request $request, Response $response)
    {
        $params = $request->getQueryParams();
        $rules = [
            'phoneNumber' => 'required|numeric'
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

    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     * 验证验证码
     */
    public function verifyUserCode(Request $request, Response $response)
    {
        $params = $request->getQueryParams();
        $rules = [
            'phoneNumber' =>  'required|numeric',
            'verifyCode' =>  'required|numeric'
        ];
        if (!Validator::validators($rules, $params)) {
            return ApiView::jsonResponse($response,ResultCode::PARAM_IS_INVAILD);
        }

        $redis = $this->redis;
        $verifyCode = $redis->get($params['phoneNumber']);
        if ($verifyCode !== $params['verifyCode']) {
            return ApiView::jsonResponse($response, ResultCode::VERIFYCODE_IS_ERROR);
        }
        return ApiView::jsonResponse($response, ResultCode::SUCCESS,[]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     * 找回密码操作
     */
    public function modifyUser(Request $request, Response $response)
    {
        $params = $request->getParsedBody();
        $rules = [
            'phoneNumber' => 'required|numeric',
            'password' => 'required|string',
        ];
        if (!Validator::validators($rules, $params)) {
            return ApiView::jsonResponse($response,ResultCode::PARAM_IS_INVAILD);
        }

        $user = new User();
        $res = $user->modifyUserPassword($params['phoneNumber'], $params['password'], $this->setting['salt']);
        if ($res == false) {
            return ApiView::jsonResponse($response, ResultCode::UNKNOWN_ERROR);
        }
        return ApiView::jsonResponse($response, ResultCode::SUCCESS, []);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     * 用户获取用户记录列表
     */
    public function pinGetUserRecord(Request $request, Response $response)
    {
        $params = $request->getQueryParams();
        $rules = [
            'pageSize' => 'required|numeric',
            'pageNumber' => 'required|numeric',
        ];
        if (!Validator::validators($rules, $params)) {
            return ApiView::jsonResponse($response,ResultCode::PARAM_IS_INVAILD);
        }

        $token = (Array)$this->token;
        $limit = $params['pageSize'];
        $offset = $params['pageSize'] * ($params['pageNumber'] - 1);
        $userRecord = new UserRecord();
        $res = $userRecord->pinGetUserRecord($limit, $offset,$token['pin']);

        $userFriend = new UserFriends();
        foreach ($res as $key => &$value) {
            $result = $userFriend->getUserRelation($token['pin'], $value['pin']);
            if (empty($result)) {
                continue;
            } else if ($result[0]['status'] === 0) {
                $value['relation'] = "已关注";
            } else if ($result[0]['status'] == 2) {
                $value['relation'] = "互相关注";
            } else {
                continue;
            }
        }
        $data = ['data'=> $res];
        return ApiView::jsonResponse($response, ResultCode::SUCCESS, $data);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     * 游客获取用户记录列表
     */
    public function touristGetUserRecord(Request $request, Response $response)
    {
        $params = $request->getQueryParams();
        $rules = [
            'pageSize' => 'required|numeric',
            'pageNumber' => 'required|numeric',
        ];
        if (!Validator::validators($rules, $params)) {
            return ApiView::jsonResponse($response,ResultCode::PARAM_IS_INVAILD);
        }

        $limit = $params['pageSize'];
        $offset = $params['pageSize'] * ($params['pageNumber'] - 1);
        $userRecord = new UserRecord();
        $res = $userRecord->touristGetUserRecord($limit, $offset);
        $data = ['data'=> $res];
        return ApiView::jsonResponse($response, ResultCode::SUCCESS, $data);
    }

    public function getUserInfo(Request $request, Response $response)
    {
        $token = (Array)$this->token;
        $userInfo = new UserInformation();
        $result = $userInfo->getUserInfoDetail($token['pin']);

        $data = ['data' => $result];
        return ApiView::jsonResponse($response, ResultCode::SUCCESS, $data);
    }

    public function getUserRecord(Request $request, Response $response)
    {
        $params = $request->getQueryParams();
        $rules = [
            'pin' => 'required|string'
        ];
        if (!Validator::validators($rules, $params)) {
            return ApiView::jsonResponse($response,ResultCode::PARAM_IS_INVAILD);
        }

        $pin = base64_decode($params['pin']);
        $userRecord = new UserRecord();
        $result = $userRecord->getUserRecordDetail($pin);

        $data = ['data' => $result];
        return ApiView::jsonResponse($response, ResultCode::SUCCESS, $data);
    }

    public function modifyUserInfo(Request $request, Response $response)
    {
        $params = $request->getParsedBody();
        $rules = [
            'avator' => 'string',
            'nickname' => 'string',
            'sex' => 'numeric',
            'birthday' => 'integer',
            'city' => 'string',
            'introduction' => 'string'
        ];
        if (!Validator::validators($rules, $params)) {
            return ApiView::jsonResponse($response,ResultCode::PARAM_IS_INVAILD);
        }

        $token = (Array)$this->token;
        $userInfo = new UserInformation();
        $userInfo->modifyUserInfo($token['pin'],$params);
        return ApiView::jsonResponse($response, ResultCode::SUCCESS,[]);
    }
}