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
            ->select('pin','avator','nickname',$this->table.'.id','works_id','content','to_pin','create_at')
            ->get()
            ->toArray();
    }
}