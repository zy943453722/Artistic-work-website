 <?php
/**
 * User: zy
 * Date: 18-5-27
 * Time: 下午3:49
 * 入口文件： 引入文件，调用核心类的run方法
 */
define('ROOT_PATH',__DIR__);

require_once ROOT_PATH . "/lib/core/Kernel.php";

Kernel::run();

