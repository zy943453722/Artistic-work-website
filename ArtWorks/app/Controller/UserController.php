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
use App\Model\Works;
use App\Model\WorksLike;
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
            'phoneNumber' => 'required|string|size:11',
            'password' => [
                'required',
                'regex:/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z_]{8,16}$/'
            ],
        ];
        if (!Validator::validators($rules, $params)) {
            return ApiView::jsonResponse($response, ResultCode::PARAM_IS_INVAILD);
        }

        $userStatus = new UserStatus();
        $result = $userStatus->getUserStatus($params['phoneNumber']);
        if (empty($result)) {
            return ApiView::jsonResponse($response, ResultCode::USER_NOT_EXIST);
        }
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
        $id = $user->getUserId($params['phoneNumber']);
        $data = ['data' => ['id' => $id]];
        return ApiView::jsonResponse($response, ResultCode::SUCCESS, $data);
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
            'phoneNumber' => 'required|string|size:11',
            'password' => [
                'required',
                'regex:/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z_]{8,16}$/'
            ],
            'verifyCode' => [
                'required',
                'regex:/^\d{4}$/'
            ],
            'nickname' => 'required|alpha_num|between:1,16'
        ];
        if (!Validator::validators($rules, $params)) {
            return ApiView::jsonResponse($response,ResultCologide::PARAM_IS_INVAILD);
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
        $count = $usrInfo->getCountOfUserInfo() + 1;
        $result &= $usrInfo->addUserInfo($params['phoneNumber'], $params['nickname']);
        $usrStatus = new UserStatus();
        $result &= $usrStatus->addUserStatus($params['phoneNumber']);

        $data = ['data' => ['pin' => $params['phoneNumber'],
            'id' => $count
        ]];
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
            'phoneNumber' => 'required|string|size:11'
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
            'phoneNumber' => 'required|string|size:11',
            'verifyCode' => [
                'required',
                'regex:/^\d{4}$/'
            ]
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
            'phoneNumber' => 'required|string|size:11',
            'password' => [
                'required',
                'regex:/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z_]{8,16}$/'
            ]
        ];
        if (!Validator::validators($rules, $params)) {
            return ApiView::jsonResponse($response,ResultCode::PARAM_IS_INVAILD);
        }

        $user = new User();
        $user->modifyUserPassword($params['phoneNumber'], $params['password'], $this->setting['salt']);
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
            'pageSize' => 'required|integer|between:0,9',
            'pageNumber' => 'required|integer|min:1',
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
            } elseif ($result[0]['status'] === 0) {
                $value['relation'] = "已关注";
            } elseif ($result[0]['status'] == 2) {
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
            'pageSize' => 'required|integer|between:0,9',
            'pageNumber' => 'required|integer|min:1',
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

    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     * 用户个人主页的展示
     */
    public function getUserRecord(Request $request, Response $response)
    {
        $params = $request->getQueryParams();
        $rules = [
            'pin' => 'required|string|between:0,255'
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
            'avator' => 'url',
            'nickname' => 'string|between:1,16',
            'sex' => 'integer|in:0,1,2',
            'birthday' => 'integer',
            'city' => 'string|between:0,255',
            'introduction' => 'string|between:0,500'
        ];
        if (!Validator::validators($rules, $params)) {
            return ApiView::jsonResponse($response,ResultCode::PARAM_IS_INVAILD);
        }

        $token = (Array)$this->token;
        $userInfo = new UserInformation();
        $userInfo->modifyUserInfo($token['pin'],$params);
        return ApiView::jsonResponse($response, ResultCode::SUCCESS,[]);
    }

    public function modifyPassword(Request $request, Response $response)
    {
        $params = $request->getParsedBody();
        $rules = [
            'oldPassword' => [
                'required',
                'regex:/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z_]{8,16}$/'
            ],
            'newPassword' => [
                'required',
                'regex:/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z_]{8,16}$/'
            ]
        ];
        if (!Validator::validators($rules, $params)) {
            return ApiView::jsonResponse($response,ResultCode::PARAM_IS_INVAILD);
        }

        $token = (Array)$this->token;
        $user = new User();
        $password = md5($params['oldPassword'].$this->setting['salt']);
        $count = $user->verifyUserLogin($token['pin'], $password);
        if ($count == 0) {
            return ApiView::jsonResponse($response, ResultCode::USER_PASSWORD_ERROR);
        }

        $user->modifyUserPassword($token['pin'], $params['newPassword'], $this->setting['salt']);
        return ApiView::jsonResponse($response, ResultCode::SUCCESS, []);
    }

    public function touristGetUserWorksList(Request $request, Response $response)
    {
        $params = $request->getQueryParams();
        $rules = [
            'pin' => 'required|string|between:0,255',
            'pageSize' => 'required|integer|between:0,9',
            'pageNumber' => 'required|integer|min:1',
            'worksId' => 'integer|min:1'
        ];
        if (!Validator::validators($rules, $params)) {
            return ApiView::jsonResponse($response,ResultCode::PARAM_IS_INVAILD);
        }

        $pin = base64_decode($params['pin']);
        $works = new Works();
        $limit = $params['pageSize'];
        $offset = $params['pageSize'] * ($params['pageNumber'] - 1);
        if (array_key_exists('worksId', $params)) {
            $result = $works->getUserWorks($pin, $limit, $offset, $params['worksId']);
        } else {
            $result = $works->getUserWorks($pin, $limit, $offset);
        }

        $data = ['data' => $result];
        return ApiView::jsonResponse($response, ResultCode::SUCCESS, $data);
    }

    public function pinGetUserWorksList(Request $request, Response $response)
    {
        $params = $request->getQueryParams();
        $rules = [
            'pin' => 'required|string|between:0,255',
            'pageSize' => 'required|integer|between:0,9',
            'pageNumber' => 'required|integer|min:1',
            'worksId' => 'integer|min:1'
        ];
        if (!Validator::validators($rules, $params)) {
            return ApiView::jsonResponse($response,ResultCode::PARAM_IS_INVAILD);
        }

        $pin = base64_decode($params['pin']);
        $token = (Array)$this->token;
        $works = new Works();
        $worksLike = new WorksLike();
        $limit = $params['pageSize'];
        $offset = $params['pageSize'] * ($params['pageNumber'] - 1);
        if (array_key_exists('worksId', $params)) {
            $result = $works->getUserWorks($pin, $limit, $offset, $params['worksId']);
        } else {
            $result = $works->getUserWorks($pin, $limit, $offset);
        }
        $sum = $result['count'];
        unset($result['count']);
        foreach ($result as $key => &$value) {
            $count = $worksLike->getUserWorksLike($token['pin'], $value['id']);
            if ($count === 1) {
                $value['relation'] = "已点赞";
            }
        }
        $result['count'] = $sum;

        $data = ['data' => $result];
        return ApiView::jsonResponse($response, ResultCode::SUCCESS, $data);
    }

    public function getPinById(Request $request, Response $response)
    {
        $params = $request->getQueryParams();
        $rules = [
            'id' => 'required|integer'
        ];
        if (!Validator::validators($rules, $params)) {
            return ApiView::jsonResponse($response,ResultCode::PARAM_IS_INVAILD);
        }

        $user = new User();
        $value = $user->getUserPin($params['id']);

        $data = ['data' => ['pin'=> $value]];
        return ApiView::jsonResponse($response, ResultCode::SUCCESS, $data);
    }

    public function getRight(Request $request, Response $response)
    {
        $params = $request->getQueryParams();
        $rules = [
            'pin' => 'required|string|between:0,255'
        ];
        if (!Validator::validators($rules, $params)) {
            return ApiView::jsonResponse($response,ResultCode::PARAM_IS_INVAILD);
        }

        $token = (Array)$this->token;
        $pin = base64_decode($params['pin']);
        /**
         * 0 代表自己
         * 1 代表未关注
         * 2 代表已关注
         * 3 代表互相关注
         */
        if ($token['pin'] == $pin) {
            $value['relation'] = 0;
        } else {
            $userFriend = new UserFriends();
            $result = $userFriend->getUserRelation($token['pin'], $pin);
            if (empty($result)) {
                $value['relation'] = 1;
            } elseif ($result[0]['status'] === 0) {
                $value['relation'] = 2;
            } elseif ($result[0]['status'] == 2) {
                $value['relation'] = 3;
            } else {
                $value['relation'] = 1;
            }
        }

        $data = ['data' => $value];
        return ApiView::jsonResponse($response, ResultCode::SUCCESS, $data);
    }
}