<?php
/**
 * Created by PhpStorm.
 * User: zy
 * Email: zy943453722@gmail.com
 * Date: 2019/2/26
 * Time: 17:02
 */
use App\Controller\WelcomeController;

$app->group('/users',function () {
    $this->get('/{uid}',WelcomeController::class.":getUser")->setName('getUserInformation');
});