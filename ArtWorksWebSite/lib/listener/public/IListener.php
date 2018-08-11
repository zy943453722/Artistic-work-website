<?php
/**
 * User: zy
 * Date: 18-5-29
 * Time: 下午5:08
 */

namespace ArtWorksWebSite\lib\listener;


interface IListener
{
    /**
     * @return mixed
     * 获取监听的事件
     * 返回的格式：array(eventName => array(methodName,priority)
     */
    public function getMonitorEvents();
}