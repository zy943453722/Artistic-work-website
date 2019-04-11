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

class UserRecord extends Model
{
    public $model = null;
    protected $table = "userRecord";
    public $timestamps = false;

    public function init()
    {
        $this->model = new self();
    }

    public function addUserRecord($pin,
                                  $worksNum=0,
                                  $followerNum=0,
                                  $likesNum=0,
                                  $masterpiece="",
                                  $id=0
    )
    {
        if (is_null($this->model)) {
            $this->init();
        }
        return $this->model::insert(
            [
                'pin' => $pin,
                'works_number' => $worksNum,
                'followers_number' => $followerNum,
                'likes_number' => $likesNum,
                'masterpiece' => $masterpiece,
                'masterpiece_id' => $id
            ]
        );
    }

    /**
     * @param $pin
     * @param $params
     * @param $symbol
     * @return mixed
     * 修改用户部分记录
     */
    public function modifyUserRecord($pin,$params,$symbol,$number=1)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        if ($symbol == '+') {
            return $this->model::where('pin','=',$pin)
                ->increment($params,$number);
        } else {
            return $this->model::where('pin','=',$pin)
                ->decrement($params,$number);
        }


    }

    public function pinGetUserRecord($limit=9, $offset,$pin)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        return $this->model::join('userInformation', $this->table.'.pin','=','userInformation.pin')
            ->join('works', $this->table.'.masterpiece_id','=','works.id')
            ->where($this->table.'.pin','!=',$pin)
            ->select('userInformation.pin','works_number','followers_number','likes_number',
                'masterpiece','masterpiece_id','nickname','avator','website','name','make_at','type','likes')
            ->orderBy('followers_number','desc')
            ->limit($limit)
            ->offset($offset)
            ->get()
            ->toArray();
    }

    public function touristGetUserRecord($limit=9, $offset)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        return $this->model::join('userInformation', $this->table.'.pin','=','userInformation.pin')
            ->select('userInformation.pin','works_number','followers_number','likes_number',
                'masterpiece','masterpiece_id','website','nickname')
            ->orderBy('followers_number','desc')
            ->limit($limit)
            ->offset($offset)
            ->get()
            ->toArray();
    }

    /**
     * @param $pin
     * @return mixed
     * 用户个人主页的展示
     */
    public function getUserRecordDetail($pin)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        return $this->model::join('userInformation', $this->table.'.pin','=','userInformation.pin')
            ->where($this->table.'.pin','=',$pin)
            ->select('nickname','avator','introduction','city','userInformation.pin','works_number','followers_number','likes_number')
            ->get()
            ->toArray();
    }

    /**
     * 修改用户记录表中的作品部分
     */
    public function modifyUserWorks($pin, $feator)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        return $this->model::where('pin','=',$pin)
            ->update($feator);
    }

    public function pinGetUserRecordDetail($pin, $id)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        return $this->model::where(['pin' => $pin, 'masterpiece_id'=> $id],'=')
            ->get()
            ->toArray();
    }

}