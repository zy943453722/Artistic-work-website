<?php
/**
 * User: zy
 * Date: 18-5-29
 * Time: 下午5:10
 */

namespace ArtWorksWebSite\lib\listener;


use ArtWorksWebSite\lib\event\GetResponseEvent;

class ValidateRequestListener implements IListener
{
    public function handleRequestEvent(GetResponseEvent $event)
    {
        echo "hello";
    }
    public function getMonitorEvents()
    {
        // TODO: Implement getMonitorEvents() method.
        return array(
            "REQUEST" => array('handleRequestEvent',0)
        );
    }
}