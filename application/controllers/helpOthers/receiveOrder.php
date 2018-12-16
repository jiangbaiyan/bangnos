<?php

use Nos\Comm\Log;
use Nos\Comm\Validator;
use Nos\Exception\OperateFailedException;
use Nos\Http\Request;
use Nos\Http\Response;

/**
 * 接单
 * Created by PhpStorm.
 * User: baiyan
 * Date: 2018-12-14
 * Time: 20:03
 */

class HelpOthers_ReceiveOrderController extends BaseController{

    public $needAuth = true;

    public $user;

    private $orderModel;

    public function checkParam()
    {
        Validator::make($this->params = Request::all(),array(
            'id' => 'required'
        ));
    }

    public function loadModel()
    {
        $this->orderModel = new OrderModel();
    }


    public function indexAction()
    {
        $order = $this->orderModel->getOrderById($this->params['id']);
        if ($order['status'] != OrderModel::STATUS_RELEASED){
            Log::notice('ho|wrong_order_status|order:' . json_encode($order));
            throw new OperateFailedException('错误的订单状态');
        }
        if ($order->sender_id == $this->user->id){
            Log::notice('ho|can_not_receive_own_order|order:' . json_encode($order));
            throw new OperateFailedException('您不能接自己发布的订单');
        }
        $this->orderModel->update(array(
            'status' => OrderModel::STATUS_RUNNING,
            'receiver_id' => $this->user->id
        ), 'where id = ?', array($this->params['id']));
        Response::apiSuccess();
    }
}