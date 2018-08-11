<?php
/**
 * User: zy
 * Date: 18-5-27
 * Time: 下午7:47
 */
namespace ArtWorksWebSite\lib\config;
/**
 * Class Config
 * 1. 初始化configs数组
 * 2. 获取配置文件
 * 3. 设置配置文件
 */
class Config
{
    private static $configs = [];
    public function __construct()
    {
        self::$configs = include_once CONFIG_PATH.'/configs.php';
    }
    //定义获取配置参数的格式，大名字.小名字...
    public function getConfig($configName = null)
    {
        if ($configName === null) {
            return self::$configs;
        }
        $configArr = explode('.',$configName);
        switch(count($configArr)) {
            case 1:
                return isset(self::$configs[$configArr[0]])?self::$configs[$configArr[0]]:null;
            case 2:
                return isset(self::$configs[$configArr[0]][$configArr[1]])?
                    self::$configs[$configArr[0]][$configArr[1]]:null;
            case 3:
                return isset(self::$configs[$configArr[0]][$configArr[1]][$configArr[2]])?
                    self::$configs[$configArr[0]][$configArr[1]][$configArr[2]]:null;
            case 4:
                return isset(self::$configs[$configArr[0]][$configArr[1]][$configArr[2]][$configArr[3]])?
                    self::$configs[$configArr[0]][$configArr[1]][$configArr[2]][$configArr[3]]:null;
            default:
                return null;
        }
    }

    /**
     * @param $configName
     * @param $value
     * @return bool
     */
    public function setConfig($configName,$value)
    {
        if ($value === null)
            return false;
        $configArr = explode('.',$configName);
        switch(count($configArr)) {
            case 1:
                self::$configs[$configArr[0]] = $value;
                return true;
            case 2:
                self::$configs[$configArr[0]][$configArr[1]] = $value;
                return true;
            case 3:
                self::$configs[$configArr[0]][$configArr[1]][$configArr[2]] = $value;
                return true;
            case 4:
                self::$configs[$configArr[0]][$configArr[1]][$configArr[2]][$configArr[3]] = $value;
                return true;
            default:
                return false;
        }
    }

}