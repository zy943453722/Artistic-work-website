<?php
/**
 * User: zy
 * Date: 18-5-29
 * Time: 下午5:11
 */

namespace ArtWorksWebSite\lib\listener;


use ArtWorksWebSite\lib\event\GetControllerEvent;
use ArtWorksWebSite\lib\event\GetResponseEvent;

class RouterListener implements IListener
{
    public function handleRequestEvent(GetResponseEvent $event)
    {
        echo "router";
    }
    public function handleControllerEvent(GetControllerEvent $event)
    {

    }
    public function getMonitorEvents()
    {
        // TODO: Implement getMonitorEvents() method.
        return array(
            "REQUEST"  => array('handleRequestEvent',2),
            "CONTROLLER" => array('handleControllerEvent',0)
        );
    }
}