<?php
/**
 * User: zy
 * Date: 18-6-1
 * Time: 下午4:51
 */

namespace ArtWorksWebSite\lib\core;


class Container
{
    protected static $classMap = array();
    protected static $container;

    /**
     * @return Container
     * 单例模式获取全局唯一的容器对象
     */
    public static function getSingleton()
    {
        if (self::$container === null) {
            self::$container = new self();
        }
        return self::$container;
    }

    /**
     * @param $componentName
     * @param array $params
     * @return mixed
     * @throws \ReflectionException
     * 经典的Ioc容器获取对应类
     */
    public static function getClass($componentName,$params = [])
    {
        if (!isset(self::$classMap[$componentName])) {
            $ref = new \ReflectionClass($componentName);
            self::$classMap[$componentName] = $ref->newInstanceArgs($params);//给构造函数已数组形式传参
        }
        return self::$classMap[$componentName];
    }
}