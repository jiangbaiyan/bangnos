<?php

use Nos\Comm\Validator;
use Nos\Http\Request;
use Nos\Http\Response;

/**
 * 获取订单详情
 * Created by PhpStorm.
 * User: baiyan
 * Date: 2018-12-14
 * Time: 20:03
 */

class HelpOthers_GetReleasedOrderDetailController extends BaseController{

    public $needAuth = false;

    private $orderModel;

    private $userModel;

    public function checkParam()
    {
        Validator::make($this->params = Request::all(),array(
            'id' => 'required'
        ));
    }

    public function loadModel()
    {
        $this->orderModel = new OrderModel();
        $this->userModel  = new UserModel();
    }

    public function indexAction()
    {
        $order = $this->orderModel->getById($this->params['id']);
        $sender = $this->userModel->getById($order['sender_id']);
        $data  = array_merge($order, array('sender' => $sender));
        Response::apiSuccess($data);
    }
}