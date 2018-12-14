<?php

use Nos\Comm\Log;
use Nos\Comm\Validator;
use Nos\Exception\OperateFailedException;
use Nos\Exception\ParamValidateFailedException;
use Nos\Http\Request;
use Nos\Http\Response;

/**
 * 发布订单
 * Created by PhpStorm.
 * User: baiyan
 * Date: 2018-12-14
 * Time: 11:27
 */

class AskForHelp_ReleaseOrderController extends BaseController{

    public $needAuth = true;

    public $user;

    protected function checkParam()
    {
        Validator::make($this->params = Request::all(),array(
            'title' => 'required',
            'content' => 'required',
            'beginTime' => 'required|date',
            'endTime' => 'required|date',
            'type' => 'required',
            'price' => 'required',
            'longitude' => 'required',
            'latitude' => 'required'
        ));
        if (strtotime($this->params['beginTime']) > strtotime($this->params['endTime'])){
            throw new ParamValidateFailedException('起止日期时间不合法');
        }
    }

    /**
     * 创建订单
     * @throws OperateFailedException
     * @throws \Nos\Exception\CoreException
     */
    protected function indexAction()
    {
        $data = array();
        $data['title'] = $this->params['title'];
        $data['content'] = $this->params['content'];
        $data['begin_time'] = $this->params['begin_time'];
        $data['end_time'] = $this->params['end_time'];
        $data['type'] = $this->params['type'];
        $data['status'] = OrderModel::STATUS_RELEASED;
        $data['price'] = $this->params['price'];
        $data['uuid'] = time() . mt_rand(0, 100000);
        $data['longitude'] = $this->params['longitude'];
        $data['latitude'] = $this->params['latitude'];
        $data['sender_id'] = $this->user->id;
        $orderModel = new OrderModel();
        $rows = $orderModel->createOrder($data);
        if (!$rows){
            Log::fatal('ask|insert_into_orders_failed|data:' . json_encode($data));
            throw new OperateFailedException('新订单创建失败，请重试');
        }
        Response::apiSuccess();
    }

}