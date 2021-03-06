<?php

use Nos\Comm\Log;
use Nos\Comm\Validator;
use Nos\Exception\OperateFailedException;
use Nos\Http\Response;
use Wx\Wx;

/**
 * 转账给接单者
 * Created by PhpStorm.
 * User: baiyan
 * Date: 2018-12-16
 * Time: 16:21
 */

class Pay_TransferController extends BaseController{

    private $orderModel;

    private $userModel;

    public function loadModel()
    {
        $this->orderModel = new OrderModel();
        $this->userModel = new UserModel();
    }


    public function checkParam()
    {
        Validator::make($this->params,array(
            'id' => 'required'
        ));
    }


    public function indexAction()
    {
        $order = $this->orderModel->getById($this->params['id']);
        if ($order->status != OrderModel::STATUS_WAITING_COMMENT){
            Log::notice('wxpay|wrong_order_status|order:' . json_encode($order));
            throw new OperateFailedException('错误的订单状态');
        }
        $receiver = $this->userModel->getById($order['receiver_id']);
        if (empty($receiver['openid']) || empty($order['uuid']) || empty($order['price']) || empty($order['title'])){
            Log::notice('wxpay|transfer_params_error');
            throw new OperateFailedException('转账参数不正确');
        }
        $params = [
            'partner_trade_no' => $order['uuid'],              //商户订单号
            'openid' => $receiver['openid'],        //收款人的openid
            'check_name' => 'NO_CHECK',                //NO_CHECK：不校验真实姓名\FORCE_CHECK：强校验真实姓名
            'amount' => intval($order['price']) * 100,         //企业付款金额，单位为分
            'desc' => $order['title'],                   //付款说明
            'type' => 'miniapp'
        ];
        $app = Wx::getWxPayApp();
        Log::notice('wxpay|wxtransfer_pay_params:' . json_encode($params));
        try{
            $res = $app->transfer($params);
        } catch (\Exception $e){
            Log::fatal('wxpay|wxtransfer_error:' . json_encode($e->getMessage()));
            throw new OperateFailedException('调用转账接口失败');
        }
        Log::notice('wxpay|wxtransfer_pay_res:|res:' . json_encode($res));
        Response::apiSuccess($res);
    }
}