<?php

use Nos\Comm\Log;
use Nos\Comm\Validator;
use Nos\Exception\OperateFailedException;
use Nos\Http\Request;
use Nos\Http\Response;

/**
 * 完成订单
 * Created by PhpStorm.
 * User: baiyan
 * Date: 2018-12-15
 * Time: 20:04
 */

class HelpOthers_FinishOrderController extends BaseController{

    public $needAuth = true;

    private $orderModel;

    private $userModel;

    public $user;

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
        if ($order['status'] != OrderModel::STATUS_RUNNING){
            Log::notice('ho|wrong_order_status|order:' . json_encode($order));
            throw new OperateFailedException('错误的订单状态');
        }
        if ($order['sender_id'] != $this->user->id){
            Log::notice('ho|sender_id_not_eq_uid|msg:' . json_encode($order));
            throw new OperateFailedException('您不能完成其他人的订单');
        }
        $sender = $this->userModel->getById($order['sender_id']);
        $this->userModel->update(array(
            'point' => $sender['point'] + OrderModel::AWARD_SENDER,
        ), 'where id = ?', array($sender['id']));
        $receiver = $this->userModel->getById($order['receiver_id']);
        $this->userModel->update(array(
            'point' => $receiver['point'] + OrderModel::AWARD_RECEIVER,
        ), 'where id = ?', array($receiver['id']));
        $this->orderModel->update(array(
            'status' => OrderModel::STATUS_WAITING_COMMENT
        ), 'where id = ?', array($this->params['id']));
        Response::apiSuccess();
    }

}