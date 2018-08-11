<?php
/**
 * User: zy
 * Date: 18-6-3
 * Time: 下午8:18
 */

namespace ArtWorksWebSite\lib\http;


interface IResponse
{
    public function setHeader();
    public function setBody();
    public function setStatusCode();
    public function setVersion();
    public function send();
    public function sendHeader();
    public function sendBody();
    //对应的get方法
}