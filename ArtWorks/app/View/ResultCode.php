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
    const USER_PIN_LACK = 40002;
    const USER_ALREADY_LOGIN = 40003;
    const USER_PASSWORD_ERROR = 40004;
    const TOKEN_IS_EXPIRED = 40005;
    const TOKEN_IS_LACK = 40006;
    //其他
    const VERIFYCODE_IS_ERROR = 50001;
    const UNKNOWN_ERROR = 50002;


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
          ],
           self::VERIFYCODE_IS_ERROR => [
               'message' => "验证码有误"
           ],
           self::USER_PIN_LACK => [
               'message' => "缺少用户pin"
           ],
           self::UNKNOWN_ERROR => [
               'message' => "未知错误"
           ],
           self::USER_ALREADY_LOGIN => [
               'message' => "用户已经登录"
           ],
           self::USER_PASSWORD_ERROR => [
               'message' => "用户密码错误"
           ],
           self::TOKEN_IS_EXPIRED => [
               'message' => "Expired token"
           ],
           self::TOKEN_IS_LACK => [
               'message' => "缺少token"
           ]
        ];
    }
}
