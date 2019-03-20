<?php
/**
 * Created by PhpStorm.
 * User: zy
 * Email: zy943453722@gmail.com
 * Date: 2019/2/25
 * Time: 21:39
 */

session_start();

define('ROOT_PATH',dirname(dirname(__FILE__)));
define('APP_PATH',ROOT_PATH.'/app');
define('CONFIG_PATH',ROOT_PATH.'/config');

require ROOT_PATH.'/bootstrap/autoload.php';

$appConfig = require CONFIG_PATH.'/app.php';
$dbConfig = require CONFIG_PATH.'/database.php';
$loggerConfig = require CONFIG_PATH.'/logger.php';
$keyConfig = require CONFIG_PATH . '/key.php';
$config = ['setting' => array_merge($appConfig,$dbConfig,$loggerConfig,$keyConfig)];

$app = require_once ROOT_PATH.'/bootstrap/app.php';

require_once ROOT_PATH.'/bootstrap/middleware.php';

require ROOT_PATH.'/bootstrap/route.php';

$app->run();