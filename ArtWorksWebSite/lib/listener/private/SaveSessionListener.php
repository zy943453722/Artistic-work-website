<?php
/**
 * User: zy
 * Date: 18-5-29
 * Time: 下午5:11
 */

namespace ArtWorksWebSite\lib\listener;


use ArtWorksWebSite\lib\event\FilterResponseEvent;

class SaveSessionListener implements IListener
{
    public function handleResponseEvent(FilterResponseEvent $event)
    {

    }
    public function getMonitorEvents()
    {
        // TODO: Implement getMonitorEvents() method.
        return array(
            "RESPONSE" => array('handleResponseEvent',0)
        );
    }
}