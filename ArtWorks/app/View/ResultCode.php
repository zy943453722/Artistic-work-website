<?php
/**
 * Created by PhpStorm.
 * User: zy
 * Email: zy943453722@gmail.com
 * Date: 2019/3/7
 * Time: 15:03
 */

namespace App\View;


class ResultCode
{
    //成功
    const SUCCESS = 10000;
    //API参数相关
    const PARAM_IS_INVAILD = 20001;
    const PARAM_IS_BLANK = 20002;
    const PARAM_TYPE_BIND_ERROR = 20003;
    //作品资源系统相关
    const WORKS_TYPE_ERROR = 30001;
    //用户系统相关
    const USER_NOT_EXIST = 40001;


    public static function mapCode(){
        return [
          self::SUCCESS => [
              'message' => "操作成功"
          ],
          self::PARAM_TYPE_BIND_ERROR => [
               'message' => "参数类型绑定失败"
          ],
          self::PARAM_IS_INVAILD => [
              'message' => "参数无效"
          ]
        ];
    }
}
