<?php
use ArtWorksWebSite\lib\config\Config;
use ArtWorksWebSite\lib\core\Container;

class Kernel
{
    public static $container;
    public static $config;
    public static $time;
    public static $booted = false;//默认框架是关闭的
    public static $components = array();
    public static $classMap = array();
    public static $serviceFile = array();//为了让类名完整，要保存每个实体类对应的目录

    /**
     * 让框架跑起来，即框架的整个流程
     * 主要完成工作：初始化、获取容器中的对象，通过解析url，得到response，再发送给前端
     */
    public static function run()
    {
        self::init();
        /**
         * 请求-响应的过程
         */
        $dispatcher = self::getComponents('eventDispatcher');
        self::addListeners($dispatcher);
        $cookie = self::getComponents('cookie');
        $request = self::getComponents('request',[$cookie]);
        $event = self::getComponents('event.GetResponseEvent',[$request]);
        $dispatcher->dispatch('REQUEST',$event);
        //$router = self::getComponents('router');
    }

    /**
     * 框架初始化
     * 1. 定义常量
     * 2. 设置自动加载
     * 3. 加载配置文件
     * 4. 开启session
     * 5. 注册自定义错误、异常、关闭函数
     * 6. 初始化服务容器对象
     */
    public static function init()
    {
        if (self::$booted === true)
            return;
        self::setDefine();
        self::$time = microtime();
        spl_autoload_register('self::autoload');
        self::$config = new Config();
        self::$serviceFile = self::$config->getConfig('serviceFile');
        self::$container = Container::getSingleton();
        self::$booted = true;
        //session_start();session的处理
        //set_error_handler('self::errorHandler');
        //set_exception_handler('self::exceptionHandler');
        //register_shutdown_function('self::shutdown');
    }

    /**
     * 定义目录别名
     */
    public static function setDefine()
    {
        if (isset($_SERVER['argc'])) {
            define('IS_CLI', true);//传递给命令行才有
        } else {
            define('IS_CLI', false);
            $http_type = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] = 'on') ? 'https://' : 'http://';
            define('HOST_NAME', $http_type . $_SERVER['HTTP_HOST']);
        }
        define('PROJECT_PATH', dirname(ROOT_PATH));
        define('CONFIG_PATH', ROOT_PATH . '/config');
        define('APP_PATH', ROOT_PATH . '/app');
        define('ASSET_PATH', ROOT_PATH . '/www');
        define('VAR_PATH', ROOT_PATH . '/var');
        define('LIB_PATH',ROOT_PATH.'/lib');
        //spl_autoload_register('self::autoload');
        //self::addListeners();
    }
    public static function addListeners($dispatcher)
    {
        $dispatcher->addListener('REQUEST',array('SessionListener','handleRequestEvent'),1);
        $dispatcher->addListener('REQUEST',array('ValidateRequestListener','handleRequestEvent'),0);
        $dispatcher->addListener('REQUEST',array('RouterListener','handleRequestEvent'),2);
        $dispatcher->addListener('RESPONSE',array('SaveSessionListener','handleResponseEvent'),0);
        $dispatcher->addListener('RESPONSE',array('PrepareResponseListener','handleResponseEvent'),1);
        $dispatcher->addListener('CONTROLLER',array('RouterListener','handleControllerEvent'),0);
        $dispatcher->addListener('CONTROLLER',array('ControllerListener','handleControllerEvent'),1);
    }

    /**
     * @param $className
     * @return bool
     * @throws Exception
     * 自动加载函数
     */
    public static function autoload($className)
    {
        $className = str_replace('\\','/',ltrim($className,'\\'));
        $lastPos = strrpos($className,'/');
        $classDir = substr($className,0,$lastPos);//从0开始，长度为$lastPos
        $classPublic = $classDir.'/public/'.substr($className,$lastPos+1);
        $classPrivate = $classDir.'/private/'.substr($className,$lastPos+1);
        if (isset(self::$classMap[$className])) {
            return true;
        } else {
            if (is_file(PROJECT_PATH."/".$className.".php")) {
                require_once PROJECT_PATH."/".$className.".php";
                self::$classMap[$className] = $className;
                return true;
            }
            if (is_file(PROJECT_PATH."/".$classPrivate.".php")) {
                require_once PROJECT_PATH."/".$classPrivate.".php";
                self::$classMap[$className] = $className;
                return true;
            }
            if (is_file(PROJECT_PATH."/".$classPublic.".php")) {
                require_once PROJECT_PATH."/".$classPublic.".php";
                self::$classMap[$className] = $className;
                return true;
            } else {
                $str = "没找到对应的文件\n";
                self::exceptionHandle($str);
                return false;
            }
        }
    }
    public static function loadServiceFile($componentName)
    {
        /*
        eval函数版本，可扩展性强，但容易出现安全问题
        $comArr = explode('.',$componentName);
        $num = count($comArr);
        $str = 'self::$serviceFile[$comArr[0]]';
        for ($i = 1; $i < $num; $i++) {
            $str .= ('[$comArr['.$i.']]');
        }
        eval('$str='.$str.";");
        return $str;
        */
        $comArr = explode('.',$componentName);
        switch (count($comArr)) {
            case 2:
                return isset(self::$serviceFile[$comArr[0]][$comArr[1]])?self::$serviceFile[$comArr[0]][$comArr[1]]:null;
            case 3:
                return isset(self::$serviceFile[$comArr[0]][$comArr[1]][$comArr[2]])?self::$serviceFile[$comArr[0]][$comArr[1]][$comArr[2]]:null;
            case 4:
                return isset(self::$serviceFile[$comArr[0]][$comArr[1]][$comArr[2]][$comArr[3]])?
                    self::$serviceFile[$comArr[0]][$comArr[1]][$comArr[2]][$comArr[3]]:null;
            default:
                return null;
        }
    }

    /**
     * @param $componentName
     * @param array $param
     * @return mixed
     * @throws Exception
     */
    public static function getComponents($componentName,$param = [])
    {

        if (!isset(self::$components[$componentName])) {
            //没找到点号
            if (strpos($componentName,'.') === false) {
                $className = self::$serviceFile[$componentName];
                $comName = $componentName;
            } else {
                $lastPosition = strrpos($componentName,'.');
                $comName = substr($componentName,$lastPosition + 1);
                $className = self::loadServiceFile($componentName);
            }
            if ($className === null) {
                self::exceptionHandle('不存在对应类');
            }
            self::$components[$componentName] = self::$container->getClass($className.'\\'.ucfirst($comName),$param);
        }
        return self::$components[$componentName];
    }

    public static function errorHandle()
    {

    }

    /**
     * @param $str
     * @throws Exception
     */
    public static function exceptionHandle($str)
    {
        throw new \Exception($str);
        echo '出现异常\n';
        exit(1);
    }
    public static function shutdown()
    {

    }
    public static function reboot()
    {
        self::shutdown();
        self::run();
    }
}