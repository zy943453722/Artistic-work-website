<?php
/**
 * Created by PhpStorm.
 * User: zy
 * Email: zy943453722@gmail.com
 * Date: 2019/2/26
 * Time: 17:02
 */

use App\Controller;

$app->group('/works',function () {
    $this->get('/touristList', Controller\WorksController::class.":touristGetWorksList");
    $this->get('/pinList', Controller\WorksController::class.":pinGetWorksList");
    $this->post('/addLikes', Controller\LikeController::class.":addLikes");
    $this->delete('/deleteLikes', Controller\LikeController::class.":deleteLikes");
    $this->post('/add', Controller\WorksController::class.":addWorks");
    $this->put('/modify', Controller\WorksController::class.":modifyWorks");
    $this->delete('/delete', Controller\WorksController::class.":deleteWorks");
    $this->get('/touristGetWorksDetail', Controller\WorksController::class.":touristGetWorksDetail");
    $this->get('/pinGetWorksDetail', Controller\WorksController::class.":pinGetWorksDetail");
});