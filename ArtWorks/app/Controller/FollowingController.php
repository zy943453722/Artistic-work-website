<?php
/**
 * Created by PhpStorm.
 * User: zy
 * Email: zy943453722@gmail.com
 * Date: 2019/3/15
 * Time: 9:23
 */

namespace App\Controller;

use App\Model\UserFriends;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Validation\Validator;
use App\View\ApiView;
use App\View\ResultCode;

class FollowingController extends baseController
{
    public function getFollowing(Request $request, Response $response)
    {
        $params = $request->getQueryParams();
        $rules = [
            'status' => 'required|numeric'
        ];
        if (!Validator::validators($rules, $params)) {
            return ApiView::jsonResponse($response, ResultCode::PARAM_IS_INVAILD);
        }
    }

    public function addFollowing(Request $request, Response $response)
    {
        $params = $request->getParsedBody();
        $rules = [
            'friendPin' => 'required|string'
        ];
        if (!Validator::validators($rules, $params)) {
            return ApiView::jsonResponse($response, ResultCode::PARAM_IS_INVAILD);
        }

        $friendPin = base64_decode($params['friendPin']);
        $token = (Array)$this->token;
        $userFriend = new UserFriends();
        $result = $userFriend->getUserRelation($token['pin'], $friendPin);
        if (!empty($result) && $result[0]['status'] == 1) {
            $res = $userFriend->modifyUserFriend($token['pin'], $friendPin, 2, 2);
        } else {
            $res = $userFriend->addUserFriend($token['pin'], $friendPin);
        }

        if ($res == false) {
            return ApiView::jsonResponse($response, ResultCode::UNKNOWN_ERROR);
        }
        return ApiView::jsonResponse($response, ResultCode::SUCCESS,[],201);
    }

    public function modifyFollowing(Request $request, Response $response)
    {
        $params = $request->getParsedBody();
        $rules = [
            'friendPin' => 'required|string'
        ];
        if (!Validator::validators($rules, $params)) {
            return ApiView::jsonResponse($response, ResultCode::PARAM_IS_INVAILD);
        }

        $friendPin = base64_decode($params['friendPin']);
        $token = (Array)$this->token;
        $userFriend = new UserFriends();
        $res = $userFriend->modifyUserFriend($token['pin'], $friendPin, 1, 0);
        if ($res == false) {
            return ApiView::jsonResponse($response, ResultCode::UNKNOWN_ERROR);
        }
        return ApiView::jsonResponse($response, ResultCode::SUCCESS,[]);
    }

    public function deleteFollowing(Request $request, Response $response)
    {
        $params = $request->getParsedBody();
        $rules = [
            'friendPin' => 'required|string'
        ];
        if (!Validator::validators($rules, $params)) {
            return ApiView::jsonResponse($response, ResultCode::PARAM_IS_INVAILD);
        }

        $friendPin = base64_decode($params['friendPin']);
        $token = (Array)$this->token;
        $userFriend = new UserFriends();
        $res = $userFriend->deleteUserFriend($token['pin'],$friendPin);
        if ($res == false) {
            return ApiView::jsonResponse($response, ResultCode::UNKNOWN_ERROR);
        }
        return ApiView::jsonResponse($response, ResultCode::SUCCESS,[],200);
    }
}