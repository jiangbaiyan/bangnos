<?php

use Nos\Http\Response;

/**
 * 获取个人信息
 * Created by PhpStorm.
 * User: baiyan
 * Date: 2018-12-16
 * Time: 15:08
 */

class User_GetUserInfoController extends BaseController{

    public $needAuth = true;

    public $user;

    public function checkParam()
    {

    }


    public function indexAction()
    {
        Response::apiSuccess($this->user);
    }
}