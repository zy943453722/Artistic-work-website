<?php
/**
 * Created by PhpStorm.
 * User: zy
 * Email: zy943453722@gmail.com
 * Date: 2019/3/19
 * Time: 9:18
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\View\ResultCode;

class Users extends Model
{
    public $model = null;
    protected $table = "user";
    public $timestamps = false;

    public function init()
    {
        $this->model = new self();
    }

    public function getUserPhoneNumber($phoneNumber)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        return $this->model::where('phone_number','=',$phoneNumber)
            ->count();
    }
    //获取密码
    public function getUserPassword($phoneNumber)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        $count = $this->getUserPhoneNumber($phoneNumber);
        if ($count === 0)
            return [
                'res'=>false,
                'message'=> ResultCode::PARAM_IS_INVAILD
            ];
        return $this->model::where('phone_number','=',$phoneNumber)
                    ->get()
                    ->toArray()[0]['password'];
    }
    //验证密码
    public function VerifyPassword()
    {

    }
}