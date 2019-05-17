<?php
/**
 * Created by PhpStorm.
 * User: zy
 * Email: zy943453722@gmail.com
 * Date: 2019/3/19
 * Time: 9:23
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserInformation extends Model
{
    public $model = null;
    protected $table = "userInformation";
    public $timestamps = false;

    public function init()
    {
        $this->model = new self();
    }

    /**
     * @return mixed
     * 查询当前用户记录数
     */
    public function getCountOfUserInfo()
    {
        if (is_null($this->model)) {
            $this->init();
        }
        return $this->model::count();
    }

    public function addUserInfo($pin, $nickname)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        $count = $this->getCountOfUserInfo();
        return $this->model::insert(
          [
              'pin' => $pin,
              'nickname' => $nickname,
              'website' => "http://artgallery.com/uid/".($count+1)
          ]
        );
    }

    public function getUserInfoDetail($pin)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        return $this->model::where('pin','=', $pin)
            ->get()
            ->toArray();

    }

    public function modifyUserInfo($pin,$params)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        return $this->model::where('pin','=',$pin)
            ->update($params);
    }
}