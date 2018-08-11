<?php
/**
 * User: zy
 * Date: 18-5-30
 * Time: 上午10:05
 */

namespace ArtWorksWebSite\lib\event;


interface IEvent
{
    //标示事件是否停止传播
    public function isStop();
    //停止事件传播
    public function stop();
}