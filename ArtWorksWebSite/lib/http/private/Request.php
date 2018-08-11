<?php
/**
 * User: zy
 * Date: 18-6-1
 * Time: 下午2:36
 */

namespace ArtWorksWebSite\lib\http;

use ArtWorksWebSite\lib\http\Cookie;
use Kernel;

class Request implements IRequest
{
    private $get = array();//存放_Get[]的值
    private $post = array();
    private $server = array();
    private $file = array();
    private $cookie = array();
    private $method = null;//当前请求用的方法
    private $body = array();
    private $attributes = array();//无法通过$_post获取的json提交
    private $url;
    private $baseUrl;

    /**
     * Request constructor.
     * @param \ArtWorksWebSite\lib\http\Cookie $cookie
     * 通过构造函数获取$_Get/$_Post/$_server/$_file/$_cookie中的值
     */
    public function __construct(Cookie $cookie)
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->file = $_FILES;
        $this->server = $_SERVER;
        $this->cookie = $_COOKIE;
        $cookie->init($this->cookie);
    }

    /**
     * 过滤请求操作，即给request对象的属性附加值
     * @throws \Exception
     */
    public function validateRequest()
    {
        $this->setMethod();
        $conf = Kernel::getComponents('config');
        $mode = $conf->getConfig('router.mode');
        $this->setUrl($mode);
        $this->setAttributes($mode);
    }
    public function getGet()
    {
        // TODO: Implement getGet() method.
        return (empty($this->get))?null:$this->get;
    }
    public function getPost()
    {
        // TODO: Implement getPost() method.
        return (empty($this->post))?null:$this->post;
    }
    public function getServer()
    {
        // TODO: Implement getServer() method.
        return (empty($this->server))?null:$this->server;
    }
    public function getFile()
    {
        // TODO: Implement getFile() method.
        return (empty($this->file))?null:$this->file;
    }

    public function getCookie()
    {
        // TODO: Implement getCookie() method.
        return (empty($this->cookie))?null:$this->cookie;
    }

    /**
     * @return bool
     * 如果$_server数组中的HTTP_X_REQUESTED_WITH参数为xmlhttprequest，则属于ajax请求
     */
    public function isAjaxRequest()
    {
        //忽略大小写比较
        if(strcasecmp($this->server['HTTP_X_REQUESTED_WITH'],'xmlhttprequest')==0){
            $this->method='AJAX';
            return true;
        }
        return false;
    }

    /**
     * 设置请求用的方法
     */
    public function setMethod()
    {
        if ($this->method === null || !$this->isAjaxRequest()) {
            $this->method = $this->server['REQUEST_METHOD'];
        }
    }
    public function getMethod()
    {
        // TODO: Implement getMethod() method.
        return ($this->method === null)?null:$this->method;
    }
    public function setUrl($mode)
    {
        if (!empty($this->server['REQUEST_URI'])) {
            $this->url = HOST_NAME.$this->server['REQUEST_URI'];
        }
        if ($mode == 3 || $mode == 4 || $mode == 5) {
            $this->baseUrl = HOST_NAME.trim($this->server['SCRIPT_NAME'],"/index.php");
        } else {
            $this->baseUrl = HOST_NAME.$this->server['SCRIPT_NAME'];
        }
    }

    public function setAttributes($mode)
    {
        $method = $this->getMethod();
        /**
         * 对应get方法
         */
        if (strcasecmp($method,'get') == 0) {
            switch ($mode) {
                case 1:
                    $strings = explode(ltrim($this->server['QUERY_STRING'],"?"),"&");
                    foreach ($strings as $key => $value) {
                        $arr = explode($value,"=");
                        $this->attributes[$arr[0]] = $arr[1];
                    }
                    break;
                case 2:
                    $strings = explode(ltrim($this->server['PATH_INFO'],"/"),"/");
                    $counts = count($strings);
                    for($i = 0; $i < $counts; $i+= 2) {
                        $this->attributes[$strings[$i]] = $strings[$i+1];
                    }
                    break;
                case 3:
                    $strings = ltrim($this->server['QUERY_STRING'],"?");
                    $this->attributes['mode3'] = $strings;
                    break;
                case 4:
                    $strings = explode(ltrim($this->server['REQUEST_URI'],"/"),"/");
                    $counts = count($strings);
                    for($i = 0; $i < $counts; $i+= 2) {
                        $this->attributes[$strings[$i]] = $strings[$i+1];
                    }
                    break;
                case 5:
                    $strings = explode(ltrim($this->server['QUERY_STRING'],"?"),"&");
                    foreach ($strings as $key => $value) {
                        $arr = explode($value,"=");
                        $this->attributes[$arr[0]] = $arr[1];
                    }
                    break;
                default:
                    break;
            }
        }
        /**
         * 对应post方法
         */
        if (strcasecmp($method,'post') == 0) {

        }
        /**
         * 对应ajax请求
         */
        if (strcasecmp($method,'ajax') == 0) {

        }

    }
}