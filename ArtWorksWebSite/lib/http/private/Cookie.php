<?php
/**
 * User: zy
 * Date: 18-6-3
 * Time: 下午8:38
 */

namespace ArtWorksWebSite\lib\http;


class Cookie implements ICookie
{
    /**
     * @var array
     * 一个请求报文中的cookie或者相应报文中的set-cookie可以有多个
     * 因为cookie分为会话cookie(对比sessionId)和持久cookie(对比name+path+domain)
     */
    private $cookies = [];
    private $name;
    private $value;
    private $expires = 0;
    private $path = '/';
    private $domain = HOST_NAME;
    private $secure = false;
    private $httpOnly = false;

    public function init($cookies=null)
    {
        $this->cookies = $cookies;//保存request时顺便保存cookie
    }

    /**
     * @param null $name
     * @param null $value
     * @param null $expires
     * @param null $path
     * @param null $domain
     * @param false $secure
     * @param false $httpOnly
     * 1. 考虑不用php自带的session来处理，因此cookie可以在sessionID生成之后生成
     * 2. 也可以在请求的时候就生成
     * $name字段可以存放的是持久cookie的name=value，即数组.也可以存放的是会话cookie的sessionID和value
     */
    public function setCookies($name=null,$value=null,$expires=null,$path=null,$domain=null,$secure=false,$httpOnly=false)
    {
        $this->expires = $this->setExpire($expires);
        $this->path = $this->setPath($path);
        $this->domain = $this->setDomain($domain);
        $this->secure = ($secure === true)?$this->setSecure():false;
        $this->httpOnly = ($httpOnly === true)?$this->httpOnly():false;
        //持久cookie的设置
        if (is_array($name)) {
            foreach ($name as $key => $val) {
                setcookie($key,$val,$this->expires,$this->path,$this->domain,$this->secure,$this->httpOnly);//和剩余的http头一起发送给客户端
            }
        } else {
            //会话cookie的设置
            setcookie($name,$value,'',$this->path,$this->domain,$this->secure,$this->httpOnly);
        }
    }

    /**
     * @param $name
     * @param null $value
     * @param null $expires
     * @param bool $secure
     * @param bool $httpOnly
     * @return bool
     * 只有name/path/domain这三个字段不变时才说明是修改，否则变其中一个都是新生成
     */
    public function altCookies($name,$value=null,$expires=null,$secure=false,$httpOnly=false)
    {
        $this->expires = $this->setExpire($expires);
        $this->secure = ($secure === true)?$this->setSecure():false;
        $this->httpOnly = ($httpOnly === true)?$this->httpOnly():false;
        if (array_key_exists($name,$this->cookies)) {
            $this->value = ($value === null)?$this->cookies[$name]['value']:$value;
            if ($this->cookies[$name]['expires'] !== '') {//这是持久cookie
                setcookie($name,$this->value,$this->expires,$this->cookies[$name]['path'],$this->cookies[$name]['domain'],
                    $this->secure,$this->httpOnly);
            } else {//这是会话cookie
                setcookie($name,$this->value,'',$this->cookies[$name]['path'],$this->cookies[$name]['domain'],
                    $this->secure,$this->httpOnly);
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $name
     * @return bool
     */
    public function deleteCookie($name)
    {
        if (array_key_exists($name,$this->cookies)) {
            unset($this->cookies[$name]);
            //不管哪个形式的cookie都设置过期时间为当前时间前1小时
            setcookie($name,$this->cookies[$name]['value'],time()-3600,$this->cookies[$name]['path'],
                $this->cookies[$name]['domain']);
            return true;
        } else {
            return false;
        }
        // TODO: Implement deleteCookie() method.
    }
    public function getAllCookies()
    {
        return $this->cookies;
        // TODO: Implement getAllCookies() method.
    }

    /**
     * @return bool
     */
    public function deleteAllCookie()
    {
        // TODO: Implement deleteAllCookie() method.
        if (!empty($this->cookies)) {
            foreach ($this->cookies as $key => $value) {
                $this->deleteCookie($key);
            }
            return true;
        } else {
            return false;
        }
    }
    public function getName()
    {
        // TODO: Implement getName() method.
        return $this->name;
    }
    public function setPath($path=null)
    {
       return ($path === null)?$this->path:$path;
    }
    public function getPath()
    {
        // TODO: Implement getPath() method.
        return $this->path;
    }
    public function getValue()
    {
        // TODO: Implement getValue() method.
        return $this->value;
    }
    public function setExpire($expires=null)
    {
        if ($expires === null) {
            return $this->expires;
        } else {
            return $expires;
        }
    }
    public function getExpire()
    {
        // TODO: Implement getExpire() method.
        return $this->expires;
    }
    public function setDomain($domain=null)
    {
        return ($domain === null)?$this->domain:$domain;
    }
    public function getDomain()
    {
        // TODO: Implement getDomain() method.
        return $this->domain;
    }
    //默认是关闭的
    public function setHttpOnly()
    {
        // TODO: Implement setHttpOnly() method.
        $this->httpOnly = true;
    }
    public function isHttpOnly()
    {
        // TODO: Implement isHttpOnly() method.
        return $this->httpOnly;
    }
    //默认是关闭的，需要开启
    public function setSecure()
    {
        // TODO: Implement setSecure() method.
        $this->secure = true;
    }
    public function isSecure()
    {
        // TODO: Implement isSecure() method.
        return $this->secure;
    }

}