<?php
/**
 * Created by PhpStorm.
 * User: zy
 * Email: zy943453722@gmail.com
 * Date: 2019/3/15
 * Time: 9:23
 */

namespace App\Controller;

use App\Model\Comments;
use App\Model\UserInformation;
use App\Model\Works;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Validation\Validator;
use App\View\ApiView;
use App\View\ResultCode;

class CommentController extends baseController
{
    public function addWorksComments(Request $request, Response $response)
    {
        $params = $request->getParsedBody();
        $rules = [
            'worksId' => 'required|integer|min:1',
            'content' => 'required|string|between:0,255',
            'toPin' => 'string|between:0,255'
        ];
        if (!Validator::validators($rules, $params)) {
            return ApiView::jsonResponse($response, ResultCode::PARAM_IS_INVAILD);
        }

        $token = (Array)$this->token;
        $comments = new Comments();
        $factor = [
            'works_id' => $params['worksId'],
            'content' => $params['content'],
            'from_pin' => $token['pin']
        ];
        if (isset($params['toPin'])) {
            $factor['to_pin'] = base64_decode($params['toPin']);
        }
        $comments->addComments($factor);

        return ApiView::jsonResponse($response, ResultCode::SUCCESS,[]);
    }

    public function deleteWorksComments(Request $request, Response $response)
    {
        $params = $request->getParsedBody();
        $rules = [
            'id' => 'required|integer|min:1',
        ];
        if (!Validator::validators($rules, $params)) {
            return ApiView::jsonResponse($response, ResultCode::PARAM_IS_INVAILD);
        }

        $comments = new Comments();
        $comments->deleteComments($params['id']);

        return ApiView::jsonResponse($response, ResultCode::SUCCESS,[]);
    }

    public function touristGetCommentsDetail(Request $request, Response $response)
    {
        $params = $request->getQueryParams();
        $rules = [
            'worksId' => 'required|integer|min:1',
        ];
        if (!Validator::validators($rules, $params)) {
            return ApiView::jsonResponse($response, ResultCode::PARAM_IS_INVAILD);
        }

        $comments = new Comments();
        $result = $comments->getCommentsDetail($params['worksId']);
        if (!empty($result)) {
            $user = new UserInformation();
            foreach ($result as $key => &$value) {
                if ($value['to_pin'] !== "") {
                    $info = $user->getUserInfoDetail($value['to_pin']);
                    $value['to_nickname'] = $info[0]['nickname'];
                    $value['to_id'] = $info[0]['id'];
                }
            }
        }
        $data = ['data' => $result];

        return ApiView::jsonResponse($response, ResultCode::SUCCESS, $data);
    }

    public function pinGetCommentsDetail(Request $request, Response $response)
    {
        $params = $request->getQueryParams();
        $rules = [
            'worksId' => 'required|integer|min:1',
        ];
        if (!Validator::validators($rules, $params)) {
            return ApiView::jsonResponse($response, ResultCode::PARAM_IS_INVAILD);
        }

        $token = (Array)$this->token;
        $comments = new Comments();
        $result = $comments->getCommentsDetail($params['worksId']);
        if (!empty($result)) {
            $works = new Works();
            $user = new UserInformation();
            $count = $works->getUserWorksOfCount($params['worksId'], $token['pin']);
            foreach ($result as $key => &$value) {
                if ($value['to_pin'] !== "") {
                    $info = $user->getUserInfoDetail($value['to_pin']);
                    $value['to_nickname'] = $info[0]['nickname'];
                    $value['to_id'] = $info[0]['id'];
                }
                if ($count == 1) {
                    $value['right'] = "可删除";
                } elseif ($value['pin'] === $token['pin']){
                    $value['right'] = "可删除";
                } else {
                    continue;
                }
            }
        }
        $data = ['data' => $result];

        return ApiView::jsonResponse($response, ResultCode::SUCCESS, $data);
    }

    public function getUserCommentsDetail(Request $request, Response $response)
    {
        $params = $request->getQueryParams();
        $rules = [
            'pin' => 'required|string|between:0,255'
        ];
        if (!Validator::validators($rules, $params)) {
            return ApiView::jsonResponse($response, ResultCode::PARAM_IS_INVAILD);
        }

        $pin = base64_decode($params['pin']);
        $comments = new Comments();
        $result = $comments->getComments($pin);
        $data = ['data' => $result];

        return ApiView::jsonResponse($response, ResultCode::SUCCESS, $data);
    }
}