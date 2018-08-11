<?php
/**
 * User: zy
 * Date: 18-5-29
 * Time: 下午5:16
 */

namespace ArtWorksWebSite\lib\eventDispatcher;


use ArtWorksWebSite\lib\event\Event;

class EventDispatcher implements IEventDispatcher
{
    /**
     * @var array
     * listeners数组包含事件名+优先级 = array($listener对象名+对应方法)
     */
    private $listeners = array();

    /**
     * @param $eventName
     * @param array $listener
     * @param $priority
     * @throws \ReflectionException
     */
    public function addListener($eventName,array $listener, $priority)
    {
        // TODO: Implement addListener() method.
        if ($this->checkMethod($listener) === false) {
            throw EventDispatchException::checkMethod();
        }
        if ($this->hasListener($eventName)) {
            if (isset($this->listeners[$eventName][$priority])) {
                throw EventDispatchException::priorityHandle();
            }
            $this->listeners[$eventName][$priority] = $listener;
        } else {
            $this->listeners[$eventName][$priority] = $listener;
        }
    }

    /**
     * @param $eventName
     * @return mixed|null
     */
    public function getListeners($eventName)
    {
        // TODO: Implement getListener() method.
        if ($this->hasListener($eventName)) {
            return $this->listeners[$eventName];
        } else {
            return null;
        }
    }

    /**
     * @param $eventName
     * @return bool
     */
    public function hasListener($eventName)
    {
        // TODO: Implement hasListener() method
        if (isset($this->listeners[$eventName])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $eventName
     * @param $listener
     * @return bool
     */
    public function removeListener($eventName, $listener)
    {
        // TODO: Implement removeListener() method.
        if ($this->hasListener($eventName) === false) {
            throw EventDispatchException::notExistEventListener();
        } else {
            $listenerQueue = $this->getListeners($eventName);
            foreach ($listenerQueue as $key => $value) {
                if ($listenerQueue[$key] == $listener) {
                    unset($this->listeners[$key]);
                    return true;
                }
            }
            throw EventDispatchException::removeListener();
        }
    }

    /**
     * @param $eventName
     * @param Event $event
     * @return bool
     * 分发事件函数，用回调函数的机制根据存放在监听队列中的监听者对象，调用其中的方法
     */
    public function dispatch($eventName,Event $event)
    {
        // TODO: Implement dispatch() method.
        if ($event === null) {
            $event = new Event();
        }
        if ($this->hasListener($eventName) === false) {
            throw EventDispatchException::notExistEventListener();
        } else {
            $listenerQueue = $this->getListeners($eventName);
            ksort($listenerQueue);
            foreach ($listenerQueue as $priority => $listener) {
                if ($event->isStop()) {
                    return false;
                }
                $classWithNamespace = "ArtWorksWebSite\lib\listener".'\\'.$listener[0];
                \call_user_func(array($classWithNamespace,$listener[1]),$event);//根据类名和方法回调
            }
        }
    }

    /**
     * @param array $listener
     * @return bool
     * @throws \ReflectionException
     * 判断对应类中是否存在对应方法
     */
    public function checkMethod(array $listener)
    {
        $func = new \ReflectionClass("ArtWorksWebSite\lib\listener".'\\'.$listener[0]);
        if ($func->hasMethod($listener[1]) === false)
            return false;
        return true;
    }
}