<?php
/**
 * User: zy
 * Date: 18-5-29
 * Time: 下午5:16
 */

namespace ArtWorksWebSite\lib\eventDispatcher;


use ArtWorksWebSite\lib\event\Event;

interface IEventDispatcher
{
    public function addListener($eventName,array $listener,$priority);
    public function removeListener($eventName,$listener);
    public function getListeners($eventName);
    public function hasListener($eventName);
    public function dispatch($eventName,Event $event);
}