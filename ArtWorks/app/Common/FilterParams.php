<?php
/**
 * Created by PhpStorm.
 * User: zy
 * Email: zy943453722@gmail.com
 * Date: 2019/4/2
 * Time: 20:06
 */

namespace App\Common;

class FilterParams
{
    public static function toUnderline($params)
    {
        if (empty($params)) {
            return false;
        }
        $arr = [];
        $separator = '_';
        foreach ($params as $key => $value) {
            if (preg_match('/[A-Z]/',$key)) {
                $key = strtolower(preg_replace('/([a-z])([A-Z])+/', "$1".$separator."$2", $key));
            }
            $arr[$key] = $value;
        }
        return $arr;
    }




}