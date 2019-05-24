<?php
/**
 * Created by PhpStorm.
 * User: zy
 * Email: zy943453722@gmail.com
 * Date: 2019/4/7
 * Time: 16:56
 */

namespace App\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Validation\Validator;
use App\View\ApiView;
use App\View\ResultCode;


class FileController extends baseController
{
    public function getPolicy(Request $request, Response $response)
    {
        $params = $request->getQueryParams();
        $rules = [
            'status' => 'required|integer|in:0,1'
        ];
        if (!Validator::validators($rules, $params)) {
            return ApiView::jsonResponse($response, ResultCode::PARAM_IS_INVAILD);
        }

        $id = $this->setting['oss']['accessKeyId'];
        $key= $this->setting['oss']['accessKeySecret'];
        $host = 'http://artgallery1.oss-cn-beijing.aliyuncs.com';
        //$callbackUrl = 'http://api.artworks.com/users/uploadCallback';
        if ($params['status'] == 0) {//是头像上传时
            $dir = 'avatars/';
        } else {
            $dir = 'works/';
        }

        $expiration = time() + 36000;
        $expiration = $this->gmt_iso8601($expiration);

        //最大文件大小.用户可以自己设置
        $condition = array('content-length-range', 0, 1048576000);
        $conditions[] = $condition;
        // 表示用户上传的数据，必须是以$dir开始，不然上传会失败，这一步不是必须项，只是为了安全起见，防止用户通过policy上传到别人的目录。
        $start = array('starts-with', '$key', $dir);
        $conditions[] = $start;

        $arr = array('expiration'=>$expiration,'conditions'=>$conditions);
        $policy = json_encode($arr);
        $base64_policy = base64_encode($policy);
        $signature = base64_encode(hash_hmac('sha1', $base64_policy, $key, true));

        $data = [
            'data' => [
                'accessid' => $id,
                'host' => $host,
                'policy' => $base64_policy,
                'signature' => $signature,
                'expire' => $expiration,
                'dir' => $dir
            ]
        ];
        return ApiView::jsonResponse($response, ResultCode::SUCCESS, $data);
    }
    public function gmt_iso8601($time) {
        $dtStr = date("c", $time);
        $mydatetime = new \DateTime($dtStr);
        $expiration = $mydatetime->format(\DateTime::ISO8601);
        $pos = strpos($expiration, '+');
        $expiration = substr($expiration, 0, $pos);
        return $expiration."Z";
    }
}