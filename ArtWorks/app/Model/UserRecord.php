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
}