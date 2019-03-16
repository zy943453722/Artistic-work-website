<?php
/**
 * Created by PhpStorm.
 * User: zy
 * Email: zy943453722@gmail.com
 * Date: 2019/3/1
 * Time: 16:39
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public $model = null;
    protected $table = "user";
    public $timestamps = false;

    public function init()
    {
        $this->model = new self();
    }

    public function getUser()
    {
        if (is_null($this->model)) {
            $this->init();
        }
        return $this->model::where('id','>',1)
            ->orderBy('username','desc')
            ->get()->toArray();
    }
}