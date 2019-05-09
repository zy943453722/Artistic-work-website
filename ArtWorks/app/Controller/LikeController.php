<?php
/**
 * Created by PhpStorm.
 * User: zy
 * Email: zy943453722@gmail.com
 * Date: 2019/3/15
 * Time: 9:23
 */

namespace App\Controller;

use App\Model\UserRecord;
use App\Model\Works;
use App\Model\WorksLike;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Validation\Validator;
use App\View\ApiView;
use App\View\ResultCode;

class LikeController extends baseController
{
    public function addLikes(Request $request, Response $response)
    {
        $params = $request->getParsedBody();
        $rules = [
            'worksId' => 'required|integer|min:1',
            'pin' => 'required|string|between:0,255'
        ];
        if (!Validator::validators($rules, $params)) {
            return ApiView::jsonResponse($response, ResultCode::PARAM_IS_INVAILD);
        }

        $pin = base64_decode($params['pin']);
        $token = (Array)$this->token;
        $work = new Works();
        $worksLike = new WorksLike();
        $userRecord = new UserRecord();
        $worksLike->addWorksLike($token['pin'], $params['worksId']);
        $work->addWorksLike($params['worksId']);
        $userRecord->modifyUserRecord($pin, 'likes_number', '+');

        return ApiView::jsonResponse($response, ResultCode::SUCCESS, []);
    }

    public function deleteLikes(Request $request, Response $response)
    {
        $params = $request->getParsedBody();
        $rules = [
            'worksId' => 'required|integer|min:1',
            'pin' => 'required|string|between:0,255'
        ];
        if (!Validator::validators($rules, $params)) {
            return ApiView::jsonResponse($response, ResultCode::PARAM_IS_INVAILD);
        }

        $pin = base64_decode($params['pin']);
        $token = (Array)$this->token;
        $work = new Works();
        $worksLike = new WorksLike();
        $userRecord = new UserRecord();
        $worksLike->deleteWorksLike($params['worksId'], $token['pin']);
        $work->deleteWorksLike($params['worksId']);
        $userRecord->modifyUserRecord($pin, 'likes_number', '-');

        return ApiView::jsonResponse($response, ResultCode::SUCCESS, []);
    }

    public function getLikemeDetail(Request $request, Response $response)
    {
        $params = $request->getQueryParams();
        $rules = [
            'pin' => 'required|string|between:0,255'
        ];
        if (!Validator::validators($rules, $params)) {
            return ApiView::jsonResponse($response, ResultCode::PARAM_IS_INVAILD);
        }

        $pin = base64_decode($params['pin']);
        $works = new Works();
        $result = $works->getWorksLikeDetail($pin);
        $count = $works->getWorksLikeDetailOfCount($pin);
        $result['count'] = $count;

        $data = ['data' => $result];
        return ApiView::jsonResponse($response, ResultCode::SUCCESS, $data);
    }

    public function getWorksLikeDetail(Request $request, Response $response)
    {
        $params = $request->getQueryParams();
        $rules = [
            'worksId' => 'required|integer|min:1'
        ];
        if (!Validator::validators($rules, $params)) {
            return ApiView::jsonResponse($response, ResultCode::PARAM_IS_INVAILD);
        }

        $worksLike = new WorksLike();
        $result = $worksLike->getWorksLikeDetail($params['worksId']);
        $data = ['data' => $result];

        return ApiView::jsonResponse($response, ResultCode::SUCCESS, $data);
    }

    public function getILikeDetail(Request $request, Response $response)
    {
        $params = $request->getQueryParams();
        $rules = [
            'pin' => 'required|string|between:0,255'
        ];
        if (!Validator::validators($rules, $params)) {
            return ApiView::jsonResponse($response, ResultCode::PARAM_IS_INVAILD);
        }

        $pin = base64_decode($params['pin']);
        $works = new Works();
        $result = $works->getLikeDetail($pin);
        $count = $works->getLikeDetailOfCount($pin);
        $result['count'] = $count;

        $data = ['data' => $result];
        return ApiView::jsonResponse($response, ResultCode::SUCCESS, $data);
    }

}
