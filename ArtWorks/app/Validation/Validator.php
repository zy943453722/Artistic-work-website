<?php
/**
 * Created by PhpStorm.
 * User: zy
 * Email: zy943453722@gmail.com
 * Date: 2019/3/1
 * Time: 15:33
 */

namespace App\Validation;

use Illuminate\Validation\Factory;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Illuminate\Filesystem\Filesystem;

class Validator extends Factory
{
    private static $message = 'ok';
    private static $header = [
        'e' => 'rules|data is empty',
        'na' => 'rules|data is not a array'
    ];

    public static function getInstance()
    {
        //保证只需创建一次
        static $validator = null;
        if ($validator === null) {
            $translationPath = ROOT_PATH.'/database';
            $translationLocale = 'en';
            $translationLoader = new FileLoader(new Filesystem, $translationPath);
            $translator = new Translator($translationLoader, $translationLocale);
            $validator = new Factory($translator);
        }
        return $validator;
    }
    public static function validators($rules=[], $data=[])
    {
        if (empty($rules) || empty($data)) {
            self::$message = self::$header['e'];
            return false;
        }
        if (is_array($rules) && is_array($data)) {
            $v = self::getInstance()->make($data, $rules);
            if ($v->fails()) {
                self::$message = $v->messages();
                return false;
            }
            return true;
        }
        self::$message = self::$header['na'];
        return false;
    }

    public function getMessage()
    {
        return self::$message;
    }
}