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

class User extends Model
{
    public $model = null;
    protected $table = "user";
    public $timestamps = false;

    public function init()
    {
        $this->model = new self();
    }

    /**
     * @param $phoneNumber
     * @return mixed
     * 根据电话号码查用户是否注册过
     */
    public function getUserDetail($phoneNumber)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        $result = $this->model::where('phone_number','=',$phoneNumber)
            ->get()
            ->toArray();
        $result['count'] = $this->getCountOfUserInfo($phoneNumber);
        return $result;
    }

    public function verifyUserLogin($phoneNumber, $password)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        return $this->model::where(['phone_number' => $phoneNumber,
            'password'=> $password],'=')
            ->count();
    }

    public function getCountOfUserInfo($phoneNumber)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        return $this->model::where('phone_number','=',$phoneNumber)
            ->count();
    }

    public function addUser($phoneNumber, $password,$salt)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        $pin = $phoneNumber;
        $password = md5($password.$salt);
        return $this->model::insert(
          [
              'pin' => $pin,
              'phone_number' => $phoneNumber,
              'password' => $password
          ]
        );
    }

    public function modifyUserPassword($phoneNumber, $password,$salt)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        $password = md5($password.$salt);
        return $this->model::where('pin','=',$phoneNumber)
            ->update([
                'password' => $password
            ]);
    }

    public function getUserId($phoneNumber)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        return $this->model::where('pin','=',$phoneNumber)
            ->select('id')
            ->get()
            ->toArray()[0]['id'];
    }
}