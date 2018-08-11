<?php
/**
 * User: zy
 * Date: 18-5-29
 * Time: 下午5:13
 */

namespace ArtWorksWebSite\lib\listener;

use ArtWorksWebSite\lib\event\GetControllerEvent;

class ControllerListener implements IListener
{
    /**
     * @param GetControllerEvent $event
     * 过滤controller得到对应的controller
     */
    public function handleControllerEvent(GetControllerEvent $event)
    {

    }

    /**
     * @return array|mixed
     */
    public function getMonitorEvents()
    {
        // TODO: Implement getMonitorEvents() method.
        return array(
            "CONTROLLER" => array('handleControllerEvent',1)
        );
    }
}