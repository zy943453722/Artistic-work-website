<?php
/**
 * Created by PhpStorm.
 * User: zy
 * Email: zy943453722@gmail.com
 * Date: 2019/3/19
 * Time: 9:24
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserStatus extends Model
{
    public $model = null;
    protected $table = "userStatus";
    public $timestamps = false;

    public function init()
    {
        $this->model = new self();
    }

    public function addUserStatus($pin)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        return $this->model::insert(
          [
              'pin' => $pin,
              'status' => 0,
              'create_at' => time(),
              'update_at' => time()
          ]
        );
    }

    public function getUserStatus($pin)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        return $this->model::where('pin','=',$pin)
            ->get()
            ->toArray();
    }

    public function modifyUserStatus($pin,$status)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        return $this->model::where('pin','=',$pin)
            ->update([
                'status' => $status,
                'update_at' => time()
            ]);
    }
}