<?php

use Nos\Comm\Validator;
use Nos\Http\Request;
use Nos\Http\Response;

/**
 * 查看订单详情
 * Created by PhpStorm.
 * User: baiyan
 * Date: 2018-12-16
 * Time: 20:05
 */

class Order_GetOrderDetailController extends BaseController{

    public $needAuth = true;

    private $userModel;

    private $orderModel;

    public function checkParam()
    {
        Validator::make($this->params = Request::all(), array(
            'id' => 'required'
        ));
    }

    public function loadModel()
    {
        $this->userModel = new UserModel();
        $this->orderModel = new OrderModel();
    }


    public function indexAction()
    {
        $order = $this->orderModel->getById($this->params['id']);
        $sender = $this->userModel->getById($order['sender_id']);
        $receiver = $this->userModel->getById($order['receiver_id']);
        Response::apiSuccess(array_merge($order, array(
            'sender' => $sender,
            'receiver' => $receiver
        )));
    }
}