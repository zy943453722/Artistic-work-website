<?php
/**
 * Created by PhpStorm.
 * User: zy
 * Email: zy943453722@gmail.com
 * Date: 2019/3/19
 * Time: 9:25
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    public $model = null;
    protected $table = "comments";
    public $timestamps = false;

    public function init()
    {
        $this->model = new self();
    }

    public function addComments($params)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        $factor = [
            'create_at' => time()
        ];
        $factor = array_merge($factor, $params);
        return $this->model::insert($factor);
    }

    public function deleteComments($id)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        return $this->model::where('id','=',$id)
            ->update([
                'deleted_at' => time(),
                'is_delete' => 1
            ]);
    }

    public function getCommentsDetail($id)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        return $this->model::join('userInformation',$this->table.'.from_pin','=','userInformation.pin')
            ->where(['works_id'=>$id,'is_delete'=>0])
            ->select('pin','avator','nickname','website',$this->table.'.id','works_id','content','to_pin','create_at')
            ->get()
            ->toArray();
    }

    public function getComments($pin)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        $res = $this->model::join('userInformation',$this->table.'.from_pin','=','userInformation.pin')
            ->where(['to_pin'=>$pin, 'is_delete'=>0])
            ->select('avator','nickname','website','userInformation.pin','create_at',$this->table.'.id','content','to_pin')
            ->get()
            ->toArray();
        $ret = $this->model::join('userInformation',$this->table.'.from_pin','=','userInformation.pin')
            ->join('works',$this->table.'.works_id','=','works.id')
            ->where('from_pin','!=',$pin)
            ->where('to_pin','!=',$pin)
            ->where(['works.pin'=>$pin,$this->table.'.is_delete'=>0])
            ->select('avator','nickname','website','userInformation.pin',$this->table.'.create_at',
                $this->table.'.id','content','works_id','name')
            ->get()
            ->toArray();
        return array_merge($res, $ret);

    }

    public function deleteCommentsByWorks($worksId)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        return $this->model::where(['works_id'=>$worksId, 'is_delete'=>0])
            ->update([
                'deleted_at' => time(),
                'is_delete' => 1
            ]);
    }
}