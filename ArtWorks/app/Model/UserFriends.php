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

    public function addUserFriend($pin, $friendPin)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        return $this->model::insert([
            [
                'pin' => $pin,
                'friend_pin' => $friendPin,
                'status' => 0,
                'create_at' => time(),
                'update_at' => time(),
            ],
            [
                'pin' => $friendPin,
                'friend_pin' => $pin,
                'status' => 1,
                'create_at' => time(),
                'update_at' => time(),
            ]
        ]);
    }

    public function modifyUserFriend($pin, $friendPin, $firstStatus, $secondStatus)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        $res = $this->model::where(['pin'=>$pin,'friend_pin'=>$friendPin,'is_delete'=>0])
            ->update(['status' => $firstStatus,'update_at'=>time()]);
        $ret = $this->model::where(['pin'=>$friendPin,'friend_pin'=>$pin,'is_delete'=>0])
            ->update(['status' => $secondStatus, 'update_at'=>time()]);
        return $res&&$ret;
    }

    public function deleteUserFriend($pin, $friendPin)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        $res = $this->model::where(['pin'=>$pin,'friend_pin'=>$friendPin,'is_delete'=>0])
            ->update(['status' => 3,'update_at'=>time(),'deleted_at'=>time(),'is_delete'=>1]);
        $ret = $this->model::where(['pin'=>$friendPin,'friend_pin'=>$pin,'is_delete'=>0])
            ->update(['status' => 3,'update_at'=>time(),'deleted_at'=>time(),'is_delete'=>1]);
        return $res&&$ret;
    }

    public function getUserFollowing($status, $pin)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        return $this->model::join('userInformation',$this->table.'.friend_pin','=','userInformation.pin')
            ->where([$this->table.'.pin'=>$pin, 'is_delete'=>0], '=')
            ->whereIn('status', [$status, 2])
            ->select('nickname','avator','status','website')
            ->get()
            ->toArray();
    }

    public function getUserFollowingCount($status, $pin)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        $first = $this->model::where(['pin'=>$pin, 'is_delete'=>0,'status'=>2], '=');
        $mutualCount = $first->count();
        $count = $this->model::where(['pin'=>$pin, 'is_delete'=>0,'status'=>$status], '=')
            ->unionAll($first)
            ->count();
        return [
            'mutualCount' => $mutualCount,
            'count' => $count
        ];
    }
}