<?php
/**
 * User: zy
 * Date: 18-5-29
 * Time: 下午5:12
 */

namespace ArtWorksWebSite\lib\listener;

use ArtWorksWebSite\lib\event\FilterResponseEvent;

class PrepareResponseListener implements IListener
{
    public function handleResponseEvent(FilterResponseEvent $event)
    {

    }

    public function getMonitorEvents()
    {
        // TODO: Implement getMonitorEvents() method.
        return array(
          "RESPONSE" => array('handleResponseEvent',2)
        );
    }
}