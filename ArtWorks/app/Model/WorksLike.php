<?php
/**
 * Created by PhpStorm.
 * User: zy
 * Email: zy943453722@gmail.com
 * Date: 2019/3/19
 * Time: 9:26
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class WorksLike extends Model
{
    public $model = null;
    protected $table = "worksLike";
    public $timestamps = false;

    public function init()
    {
        $this->model = new self();
    }

    public function getUserWorksLike($pin, $id)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        return $this->model::where(['pin' => $pin, 'works_id'=>$id,'is_delete'=>0])
            ->count();
    }

    public function addWorksLike($pin, $id)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        return $this->model::insert([
            'works_id' => $id,
            'pin' => $pin,
            'like_time' => time()
        ]);
    }

    public function deleteWorksLike($id, $pin)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        return $this->model::where(['works_id'=>$id, 'pin'=>$pin, 'is_delete'=> 0])
            ->update([
               'deleted_at' => time(),
                'is_delete' => 1
            ]);
    }

    public function deleteWorksLikeById($id)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        return $this->model::where(['works_id'=>$id,'is_delete'=> 0])
            ->update([
                'deleted_at' => time(),
                'is_delete' => 1
            ]);
    }

    public function getWorksLikeDetail($id)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        return $this->model::join('userInformation',$this->table.'.pin','=','userInformation.pin')
            ->where(['works_id' => $id, 'is_delete'=> 0])
            ->select('userInformation.pin','avator','nickname')
            ->get()
            ->toArray();
    }
}