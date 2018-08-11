<?php
/**
 * User: zy
 * Date: 18-5-29
 * Time: 下午5:04
 */

namespace ArtWorksWebSite\lib\event;

use ArtWorksWebSite\lib\http\Request;

class Event implements IEvent
{
    private $stopped = false;
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return bool
     */
    public function isStop()
    {
        // TODO: Implement isStop() method.
        return $this->stopped;
    }

    /**
     * 停止事件传播，一旦多个监听者监听同一事件，事件触发后，当且仅当某个监听者处理事件时关闭事件传播
     */
    public function stop()
    {
        // TODO: Implement stop() method.
        if ($this->isStop()) {
            return;
        }
        $this->stopped = true;
    }
    public function getRequest()
    {
        return $this->request;
    }

}