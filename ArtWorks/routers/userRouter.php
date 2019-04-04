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
use App\Middleware\VerifyRefreshTokenMiddleware;

$app->group('/users',function () {
    //$this->get('/{uid}',Controller\WelcomeController::class.":getUser")->setName('getUserInformation');
    $this->get('/token', Controller\TokenController::class.":getToken")->setName('getToken')->add(PinMiddleware::class);
    $this->get('/getUserDetail', Controller\UserController::class.":getUserDetail");
    $this->get('/getSms', Controller\SmsController::class.":getSms");
    $this->post('/add', Controller\UserController::class.":register");
    $this->post('/login', Controller\UserController::class.":login");
    $this->post('/feedback', Controller\FeedbackController::class.":sendEmail");
    $this->get('/verifyUserCode', Controller\UserController::class.":verifyUserCode");
    $this->put('/modifyUserPassword', Controller\UserController::class.":modifyUser");
    $this->put('/logout', Controller\UserController::class.":logout");
    $this->put('/updateToken', Controller\TokenController::class.":updateToken")->add(VerifyRefreshTokenMiddleware::class);
    $this->get('/touristListUserRecord', Controller\UserController::class.":touristGetUserRecord");
    $this->get('/pinListUserRecord', Controller\UserController::class.":pinGetUserRecord");
    $this->post('/addFollowing', Controller\FollowingController::class.":addFollowing");
    $this->put('/modifyFollowing', Controller\FollowingController::class.":modifyFollowing");
    $this->delete('/deleteFollowing', Controller\FollowingController::class.":deleteFollowing");
    $this->get('/getFollowing', Controller\FollowingController::class.":getFollowing");
});