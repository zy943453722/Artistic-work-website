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
}




