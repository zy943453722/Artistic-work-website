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

class UserFriends extends Model
{
    public $model = null;
    protected $table = "userFriends";
    public $timestamps = false;

    public function init()
    {
        $this->model = new self();
    }

    public function getUserRelation($pin, $friendPin)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        return $this->model::where([
            'pin'=>$pin,
            'friend_pin' => $friendPin,
            'is_delete' => 0
        ],'=')
            ->select('status')
            ->get()
            ->toArray();
    }
}