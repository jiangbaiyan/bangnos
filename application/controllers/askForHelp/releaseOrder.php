<?php

use Nos\Comm\Validator;
use Nos\Exception\ParamValidateFailedException;
use Nos\Http\Request;

/**
 * 发布订单
 * Created by PhpStorm.
 * User: baiyan
 * Date: 2018-12-14
 * Time: 11:27
 */

class AskForHelp_ReleaseOrder extends BaseController{

    public $needAuth = true;

    private $user;

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

    protected function loadModel($user)
    {
        $this->user = $user;
    }

    /**
     * 创建订单
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
        $orderModel->createOrder($data);
    }

}