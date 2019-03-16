<?php
/**
 * Created by PhpStorm.
 * User: zy
 * Email: zy943453722@gmail.com
 * Date: 2019/2/26
 * Time: 11:26
 */
/**
 * 根据url的不同来分配路由
 */
$url = ltrim($_SERVER['REQUEST_URI'], '/');
$firstPos = strpos($url,'/');
$args = substr($url, 0, $firstPos);
switch ($args) {
    case 'users':
        require ROOT_PATH.'/routers/userRouter.php';
        break;
    case 'works':
        require ROOT_PATH.'./routers/workRouter.php';
        break;
    default:
        die();
        break;
}

