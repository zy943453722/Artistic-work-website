<?php
/**
 * User: zy
 * Date: 18-6-3
 * Time: 下午8:18
 */

namespace ArtWorksWebSite\lib\http;


interface IRequest
{
    /**
     * 几个超全局变量的获取和设置
     */
    public function getPost();
    public function getGet();
    public function getServer();
    public function getFile();
    public function getCookie();
    public function setMethod();
    public function getMethod();
    public function isAjaxRequest();
    //host/ip/port
}