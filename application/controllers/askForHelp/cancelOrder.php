<?php

use Nos\Comm\Db;
use Nos\Comm\Log;
use Nos\Comm\Validator;
use Nos\Exception\OperateFailedException;
use Nos\Http\Request;
use Nos\Http\Response;

/**
 * Created by PhpStorm.
 * User: baiyan
 * Date: 2018-12-14
 * Time: 13:50
 */

class AskForHelp_CancelOrder extends BaseController{

    public $needAuth = true;

    public $user;

    protected function checkParam()
    {
        Validator::make($this->params = Request::all(), array(
            'id' => 'required'
        ));
    }

    /**
     * 取消订单
     * @throws OperateFailedException
     * @throws \Nos\Exception\CoreException
     */
    protected function indexAction()
    {
        $id = $this->params['id'];
        $order = new OrderModel();
        $rows = $order->deleteOrder(true, 'where id = ?', array($id));
        if (!$rows){
            Log::fatal('ask|cancel_order_failed|id:' , $id);
            throw new OperateFailedException('订单不存在或取消失败');
        }
        Response::apiSuccess();
    }
}