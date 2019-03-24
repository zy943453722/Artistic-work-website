<?php
/**
 * Created by PhpStorm.
 * User: zy
 * Email: zy943453722@gmail.com
 * Date: 2019/2/26
 * Time: 17:02
 */

use App\Controller;
use App\Middleware\PinMiddleware;

$app->group('/users',function () {
    //$this->get('/{uid}',Controller\WelcomeController::class.":getUser")->setName('getUserInformation');
    $this->get('/token', Controller\TokenController::class.":getToken")->setName('getToken')->add(PinMiddleware::class);
    $this->get('/getUserDetail', Controller\UserController::class.":getUserDetail");
    $this->get('/getSms', Controller\SmsController::class.":getSms");
    $this->post('/add', Controller\UserController::class.":register");
    $this->post('/login', Controller\UserController::class.":login");
    $this->post('/feedback', Controller\FeedbackController::class.":sendEmail");
});