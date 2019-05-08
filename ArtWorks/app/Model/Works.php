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
use Illuminate\Database\Capsule\Manager as DB;

class Works extends Model
{
    public $model = null;
    protected $table = "works";
    public $timestamps = false;

    public function init()
    {
        $this->model = new self();
    }

    public function buildWhere($key, $element,$strings)
    {
        if ($element === 0 || is_null($element)) {
            return false;
        }
        switch ($key) {
            case "name":
                $strings = $strings->where('name','like',$element."%");
                break;
            case "nickname":
                $strings = $strings->where('nickname','like',$element."%");
                break;
            case "lengthMin":
                $strings = $strings->where('length',$element['symbol'],$element['data']);
                break;
            case "lengthMax":
                $strings = $strings->where('length',$element['symbol'],$element['data']);
                break;
            case "makeAtStart":
                $strings = $strings->where('make_at',$element['symbol'],$element['data']);
                break;
            case "makeAtEnd":
                $strings = $strings->where('make_at',$element['symbol'],$element['data']);
                break;
            default:
                $strings = $strings->where($key,'=',$element);
                break;
        }
        return $strings;
    }

    public function getWorksList($limit=9,$offset,$factor)
    {
        if (is_null($this->model)) {
            $this->init();
        }

        if (empty($factor)) {
            $ret = $this->model::join('userInformation',$this->table.'.pin','=','userInformation.pin')
                ->select('works.id','instance','name','works.pin','likes','website','nickname')
                ->where('is_delete',0)
                ->limit($limit)
                ->offset($offset)
                ->get()
                ->toArray();
            $ret['count'] = $this->model::join('userInformation',$this->table.'.pin','=','userInformation.pin')
                ->select('works.id','instance','name','works.pin','likes','website','nickname')
                ->where('is_delete',0)
                ->count();
            return $ret;
        }

        $var = $this->model::join('userInformation',$this->table.'.pin','=','userInformation.pin');

        foreach ($factor as $key => $value) {
            $temp = $this->buildWhere($key,$value,$var);
            if ($temp != false) {
                $var = $temp;
            }
        }
        $res = $var->select('works.id','instance','name','works.pin','likes','website','nickname')
            ->where('is_delete',0)
            ->limit($limit)
            ->offset($offset)
            ->get()
            ->toArray();
        $res['count'] = $var->select('works.id','instance','name','works.pin','likes','website','nickname')
            ->where('is_delete',0)
            ->count();
        return $res;
    }


    public function addWorksLike($id)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        return $this->model::where('id','=',$id)
            ->increment('likes');
    }

    public function deleteWorksLike($id)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        return $this->model::where('id','=',$id)
            ->decrement('likes');
    }

    public function addWorks($pin, $params)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        $arr = [
            'pin' => $pin,
            'create_at' => time(),
            'update_at' => time()
        ];
        $params = array_merge($params,$arr);
        //开启事务保证获取到正确的id
        DB::beginTransaction();
        try {
            $res = $this->model::insert($params);
            $id = $this->model::lockForUpdate()->max('id');
            if ($res && $id) {
                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
        return $id;
    }

    public function modifyWorks($id, $params)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        $arr = ['update_at' => time()];
        $params = array_merge($params,$arr);
        return $this->model::where('id','=',$id)
            ->update($params);
    }

    public function deleteWorks($id)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        return $this->model::where('id','=',$id)
            ->update([
                'update_at' => time(),
                'deleted_at' => time(),
                'is_delete' => 1
            ]);
    }

    public function getWorksLike($id)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        return $this->model::where('id','=',$id)
            ->select('likes')
            ->get()
            ->toArray()[0]['likes'];
    }

    public function getWorksIdAndInstance($pin)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        return $this->model::where(['pin'=>$pin,'is_delete'=>0],'=')
            ->select('id','instance')
            ->orderBy('create_at','desc')
            ->get()
            ->toArray()[0];
    }

    public function getWorksDetail($id)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        return $this->model::join('userInformation', $this->table.'.pin',
            '=','userInformation.pin')
            ->where([$this->table.'.id' => $id,'is_delete' => 0])
            ->select($this->table.'.id','instance','name','type','length',
                'height',$this->table.'.introduction',$this->table.'.pin','website','nickname','avator')
            ->get()
            ->toArray();
    }

    public function getUserWorksOfCount($id, $pin)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        return $this->model::where([
            'id' => $id,
            'pin' => $pin,
            'is_delete' => 0
        ])->count();
    }

    public function getUserWorks($pin,$limit=9,$offset,$worksId=0)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        if ($worksId === 0) {
            return $this->model::where(['pin'=>$pin, 'is_delete'=> 0])
                ->select('id','instance','name','make_at','pin')
                ->limit($limit)
                ->offset($offset)
                ->get()
                ->toArray();
        } else {
            return $this->model::where(['pin'=>$pin, 'is_delete'=> 0])
                ->where('id','!=',$worksId)
                ->select('id','instance','name','make_at','pin')
                ->limit($limit)
                ->offset($offset)
                ->get()
                ->toArray();
        }
    }

    public function getWorksLikeDetail($pin)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        return $this->model::join('worksLike',$this->table.'.id','=','worksLike.works_id')
            ->join('userInformation',$this->table.'.pin','=','userInformation.pin')
            ->where([$this->table.'.pin' => $pin, 'worksLike.is_delete'=>0])
            ->select('avator','nickname','website','like_time','name','works_id','userInformation.pin')
            ->get()
            ->toArray();
    }

    public function getWorksLikeDetailOfCount($pin)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        return $this->model::join('worksLike',$this->table.'.id','=','worksLike.works_id')
            ->join('userInformation',$this->table.'.pin','=','userInformation.pin')
            ->where([$this->table.'.pin' => $pin, 'worksLike.is_delete'=>0])
            ->count();
    }

    public function getLikeDetail($pin)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        return $this->model::join('worksLike',$this->table.'.id','=','worksLike.works_id')
            ->where(['worksLike.pin' => $pin, 'worksLike.is_delete'=>0])
            ->select('works_id','name','instance',$this->table.'.pin','make_at','likes','worksLike.pin')
            ->get()
            ->toArray();
    }

    public function getLikeDetailOfCount($pin)
    {
        if (is_null($this->model)) {
            $this->init();
        }
        return $this->model::join('worksLike',$this->table.'.id','=','worksLike.works_id')
            ->where(['worksLike.pin' => $pin, 'worksLike.is_delete'=>0])
            ->count();
    }
}