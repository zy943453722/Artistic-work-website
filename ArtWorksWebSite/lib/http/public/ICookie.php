<?php
/**
 * User: zy
 * Date: 18-6-3
 * Time: 下午8:19
 */

namespace ArtWorksWebSite\lib\http;


interface ICookie
{
    public function init($cookie=null);
    public function getName();
    public function getValue();
    public function setExpire($expires=null);//设置过期时间
    public function getExpire();
    public function setPath($path=null);//设置cookie可访问路径
    public function getPath();
    public function setDomain($domain=null);//设置cookie域
    public function getDomain();
    public function isSecure();//只要通过SSL或HTTPS创建时才true
    public function setSecure();
    public function isHttpOnly();
    public function setHttpOnly();
    public function deleteCookie($name);//删除某一个cookie
    public function deleteAllCookie();//删除所有cookie
    public function getAllCookies();//获取所有cookie
    public function setCookies($name=null,$value=null,$expires=null,$path=null,$domain=null,$secure=false,$httpOnly=false);
    public function altCookies($name,$value=null,$expires=null,$secure=false,$httpOnly=false);
}