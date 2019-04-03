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
            return $this->model::join('userInformation',$this->table.'.pin','=','userInformation.pin')
                ->select('works.id','instance','name','works.pin','likes','nickname')
                ->limit($limit)
                ->offset($offset)
                ->get()
                ->toArray();
        }

        $var = $this->model::join('userInformation',$this->table.'.pin','=','userInformation.pin');

        foreach ($factor as $key => $value) {
            $temp = $this->buildWhere($key,$value,$var);
            if ($temp != false) {
                $var = $temp;
            }
        }
        return $var->select('works.id','instance','name','works.pin','likes','nickname')
            ->limit($limit)
            ->offset($offset)
            ->get()
            ->toArray();
    }
}