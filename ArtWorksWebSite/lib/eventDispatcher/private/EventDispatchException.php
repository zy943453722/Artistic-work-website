<?php
/**
 * User: zy
 * Date: 18-5-30
 * Time: 下午5:21
 */

namespace ArtWorksWebSite\lib\eventDispatcher;

use Throwable;

class EventDispatchException extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
    public static function checkMethod()
    {
        echo "对应方法不存在\n";
    }
    public static function priorityHandle()
    {
        echo "对应的优先级已存在\n";
    }
    public static function notExistEventListener()
    {
        echo "对应事件不存在监听者\n";
    }
    public static function removeListener()
    {
        echo "对应事件要寻找的监听者不存在\n";
    }
}