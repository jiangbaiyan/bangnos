<?php

use Nos\Comm\Log;
use Nos\Comm\Validator;
use Nos\Exception\OperateFailedException;
use Nos\Http\Request;
use Nos\Http\Response;

/**
 * 评论订单
 * Created by PhpStorm.
 * User: baiyan
 * Date: 2018-12-16
 * Time: 20:05
 */

class Order_CommentOrderController extends BaseController{

    private $orderModel;

    private $userModel;

    public function checkParam()
    {
        Validator::make($this->params = Request::all(),array(
            'id' => 'required',
            'star' => 'required'
        ));
    }

    public function loadModel()
    {
        $this->orderModel = new OrderModel();
        $this->userModel = new UserModel();
    }


    public function indexAction()
    {
        $order = $this->orderModel->getOrderById($this->params['id']);
        if ($order['status'] != OrderModel::STATUS_WAITING_COMMENT){
            Log::notice('order|wrong_order_status|order:' . json_encode($order));
            throw new OperateFailedException('错误的订单状态');
        }
        if (empty($order['receiver_id'])){
            Log::notice('order|no_order_receiver|order:' . json_encode($order));
            throw new OperateFailedException('暂时无人接单，无法对订单进行评论');
        }
        $this->orderModel->update(array(
            'status' => OrderModel::STATUS_FINISHED
        ), 'where id = ?', array($this->params['id']));
        $receiver = $this->userModel->getUserById($order['receiver_id']);
        $this->userModel->update(array(
            'point' => $receiver['point'] + $this->params['star'],
        ), 'where id = ?', array($order['receiver_id']));
        Response::apiSuccess();
    }
}