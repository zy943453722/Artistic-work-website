<?php
/**
 * Created by PhpStorm.
 * User: zy
 * Email: zy943453722@gmail.com
 * Date: 2019/3/15
 * Time: 9:22
 */

namespace App\Controller;

use App\Common\FilterParams;
use App\Model\Comments;
use App\Model\UserRecord;
use App\Model\Works;
use App\Model\WorksLike;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Validation\Validator;
use App\View\ApiView;
use App\View\ResultCode;

class WorksController extends baseController
{
    public function touristGetWorksList(Request $request, Response $response)
    {
        $params = $request->getQueryParams();
        $rules = [
            'pageSize' => 'required|numeric',
            'pageNumber' => 'required|numeric',
            'type' => 'numeric',
            'lengthMin' => 'numeric',
            'lengthMax' => 'numeric',
            'makeAtStart' => 'integer',
            'makeAtEnd' => 'integer',
            'name' => 'string',
            'nickname' => 'string'
        ];
        if (!Validator::validators($rules, $params)) {
            return ApiView::jsonResponse($response, ResultCode::PARAM_IS_INVAILD);
        }

        $factor = [];
        $lengthMin = array_key_exists('lengthMin',$params);
        $lengthMax = array_key_exists('lengthMax',$params);
        if ($lengthMin || $lengthMax) {
            if ($lengthMin && $lengthMax) {
                $factor['lengthMin']['symbol'] = '>';
                $factor['lengthMin']['data'] = $params['lengthMin'];
                $factor['lengthMax']['symbol'] = '<';
                $factor['lengthMax']['data'] = $params['lengthMax'];
            } else {
                $factor['lengthMin']['symbol'] = ">";
                $factor['lengthMin']['data'] = $params['lengthMin'];
            }
        }
        unset($params['lengthMin']);
        unset($params['lengthMax']);

        $makeAtStart = array_key_exists('makeAtStart',$params);
        $makeAtEnd = array_key_exists('makeAtEnd',$params);
        if ($makeAtStart || $makeAtEnd) {
            if ($makeAtStart && $makeAtEnd) {
                $factor['makeAtStart']['symbol'] = ">";
                $factor['makeAtStart']['data'] = $params['makeAtStart'];
                $factor['makeAtEnd']['symbol'] = "<";
                $factor['makeAtEnd']['data'] = $params['makeAtEnd'];
            } else if ($makeAtStart){
                $factor['makeAtStart']['symbol'] = "=";
                $factor['makeAtStart']['data'] = $params['makeAtStart'];
            } else {
                $factor['makeAtEnd']['symbol'] = ">";
                $factor['makeAtEnd']['data'] = $params['makeAtEnd'];
            }
        }
        unset($params['makeAtStart']);
        unset($params['makeAtEnd']);

        $limit = $params['pageSize'];
        $offset = $params['pageSize'] * ($params['pageNumber'] - 1);
        unset($params['pageSize']);
        unset($params['pageNumber']);

        $ret = FilterParams::toUnderline($params);
        $works = new Works();
        if ($ret != false) {
            $factor = array_merge($factor,$ret);
        }
        $result = $works->getWorksList($limit,$offset,$factor);

        $data = ['data' => $result];
        return ApiView::jsonResponse($response, ResultCode::SUCCESS, $data);
    }

    public function pinGetWorksList(Request $request, Response $response)
    {
        $params = $request->getQueryParams();
        $rules = [
            'pageSize' => 'required|numeric',
            'pageNumber' => 'required|numeric',
            'type' => 'numeric',
            'length' => 'numeric',
            'makeAt' => 'integer',
            'name' => 'string',
            'nickname' => 'string'
        ];
        if (!Validator::validators($rules, $params)) {
            return ApiView::jsonResponse($response, ResultCode::PARAM_IS_INVAILD);
        }

        $factor = [];
        $lengthMin = array_key_exists('lengthMin',$params);
        $lengthMax = array_key_exists('lengthMax',$params);
        if ($lengthMin || $lengthMax) {
            if ($lengthMin && $lengthMax) {
                $factor['lengthMin']['symbol'] = '>';
                $factor['lengthMin']['data'] = $params['lengthMin'];
                $factor['lengthMax']['symbol'] = '<';
                $factor['lengthMax']['data'] = $params['lengthMax'];
            } else {
                $factor['lengthMin']['symbol'] = ">";
                $factor['lengthMin']['data'] = $params['lengthMin'];
            }
        }
        unset($params['lengthMin']);
        unset($params['lengthMax']);

        $makeAtStart = array_key_exists('makeAtStart',$params);
        $makeAtEnd = array_key_exists('makeAtEnd',$params);
        if ($makeAtStart || $makeAtEnd) {
            if ($makeAtStart && $makeAtEnd) {
                $factor['makeAtStart']['symbol'] = ">";
                $factor['makeAtStart']['data'] = $params['makeAtStart'];
                $factor['makeAtEnd']['symbol'] = "<";
                $factor['makeAtEnd']['data'] = $params['makeAtEnd'];
            } else if ($makeAtStart){
                $factor['makeAtStart']['symbol'] = "=";
                $factor['makeAtStart']['data'] = $params['makeAtStart'];
            } else {
                $factor['makeAtEnd']['symbol'] = ">";
                $factor['makeAtEnd']['data'] = $params['makeAtEnd'];
            }
        }
        unset($params['makeAtStart']);
        unset($params['makeAtEnd']);

        $limit = $params['pageSize'];
        $offset = $params['pageSize'] * ($params['pageNumber'] - 1);
        unset($params['pageSize']);
        unset($params['pageNumber']);

        $ret = FilterParams::toUnderline($params);
        $works = new Works();
        if ($ret != false) {
            $factor = array_merge($factor,$ret);
        }
        $result = $works->getWorksList($limit,$offset,$factor);

        $worksLike = new WorksLike();
        $token = (Array)$this->token;
        foreach ($result as $key => &$value) {
            $res = $worksLike->getUserWorksLike($token['pin'],$value['id']);
            if ($res !== 0) {
                $value['relation'] = "已点赞";
            }
        }
        $data = ['data' => $result];
        return ApiView::jsonResponse($response, ResultCode::SUCCESS, $data);
    }

    public function addWorks(Request $request, Response $response)
    {
        $params = $request->getParsedBody();
        $rules = [
            'length' => 'required|numeric',
            'height' => 'required|numeric',
            'type' => 'required|numeric',
            'name' => 'required|string',
            'introduction' => 'required|string',
            'instance' => 'required|string',
            'makeAt' => 'required|int'
        ];
        if (!Validator::validators($rules, $params)) {
            return ApiView::jsonResponse($response, ResultCode::PARAM_IS_INVAILD);
        }

        $token = (Array)$this->token;
        $works = new Works();
        $userRecord = new UserRecord();
        $ret = FilterParams::toUnderline($params);
        $id = $works->addWorks($token['pin'], $ret);
        $userRecord->modifyUserRecord($token['pin'], 'works_number', '+');
        $feator = [
            'masterpiece' => $params['instance'],
            'masterpiece_id' => $id
        ];
        $userRecord->modifyUserWorks($token['pin'], $feator);

        return ApiView::jsonResponse($response, ResultCode::SUCCESS,[]);
    }

    public function modifyWorks(Request $request, Response $response)
    {
        $params = $request->getParsedBody();
        $rules = [
            'id' => 'required|numeric',
            'length' => 'numeric',
            'height' => 'numeric',
            'type' => 'numeric',
            'name' => 'string',
            'introduction' => 'string',
            'instance' => 'string',
            'makeAt' => 'int'
        ];
        if (!Validator::validators($rules, $params)) {
            return ApiView::jsonResponse($response, ResultCode::PARAM_IS_INVAILD);
        }

        $id = $params['id'];
        unset($params['id']);
        $token = (Array)$this->token;
        $works = new Works();
        $ret = FilterParams::toUnderline($params);
        $works->modifyWorks($id, $ret);
        if (array_key_exists('instance', $ret)) {
            $userRecord = new UserRecord();
            $res = $userRecord->pinGetUserRecordDetail($token['pin'], $id);
            if (!empty($res)) {
                $userRecord->modifyUserWorks($token['pin'], ['masterpiece'=> $params['instance']]);
            }
        }

        return ApiView::jsonResponse($response, ResultCode::SUCCESS,[]);
    }

    public function deleteWorks(Request $request, Response $response)
    {
        $params = $request->getParsedBody();
        $rules = [
            'id' => 'required|numeric'
        ];
        if (!Validator::validators($rules, $params)) {
            return ApiView::jsonResponse($response, ResultCode::PARAM_IS_INVAILD);
        }

        $token = (Array)$this->token;
        $works = new Works();
        $works->deleteWorks($params['id']);
        $count = $works->getWorksLike($params['id']);
        $worksLike = new WorksLike();
        $worksLike->deleteWorksLikeById($params['id']);
        $comments = new Comments();
        $comments->deleteCommentsByWorks($params['id']);
        $userRecord = new UserRecord();
        $userRecord->modifyUserRecord($token['pin'], 'works_number','-');
        $userRecord->modifyUserRecord($token['pin'], 'likes_number','-',$count);
        $res = $userRecord->pinGetUserRecordDetail($token['pin'], $params['id']);
        if (!empty($res)) {
            $ret = $works->getWorksIdAndInstance($token['pin']);
            $factor = [
                'masterpiece' => $ret['instance'],
                'masterpiece_id' => $ret['id']
            ];
            $userRecord->modifyUserWorks($token['pin'], $factor);
        }

        return ApiView::jsonResponse($response, ResultCode::SUCCESS,[]);
    }

    public function touristGetWorksDetail(Request $request, Response $response)
    {
        $params = $request->getQueryParams();
        $rules = [
            'worksId' => 'required|numeric'
        ];
        if (!Validator::validators($rules, $params)) {
            return ApiView::jsonResponse($response, ResultCode::PARAM_IS_INVAILD);
        }

        $works = new Works();
        $result = $works->getWorksDetail($params['worksId']);
        $data = ['data' => $result];

        return ApiView::jsonResponse($response, ResultCode::SUCCESS,$data);
    }

    public function pinGetWorksDetail(Request $request, Response $response)
    {
        $params = $request->getQueryParams();
        $rules = [
            'worksId' => 'required|numeric'
        ];
        if (!Validator::validators($rules, $params)) {
            return ApiView::jsonResponse($response, ResultCode::PARAM_IS_INVAILD);
        }

        $token = (Array)$this->token;
        $works = new Works();
        $result = $works->getWorksDetail($params['worksId']);
        $worksLike = new WorksLike();
        $count = $worksLike->getUserWorksLike($token['pin'], $params['worksId']);
        if (empty($result)) {
            return ApiView::jsonResponse($response, ResultCode::WORKS_NOT_EXIST);
        }
        $result = $result[0];
        if ($count != 0) {
            $result['relation'] = "已点赞";
        }
        if ($result['pin'] === $token['pin']) {
            $result['right'] = "可删改";
        }

        $data = ['data' => $result];
        return ApiView::jsonResponse($response, ResultCode::SUCCESS,$data);
    }
}




