<?php

use Nos\Comm\Config;
use Nos\Comm\Log;
use Nos\Comm\Validator;
use Nos\Exception\OperateFailedException;
use Nos\Http\Request;
use Nos\Http\Response;
use Wx\Wx;

/**
 * 统一下单
 * Created by PhpStorm.
 * User: baiyan
 * Date: 2018-12-14
 * Time: 20:05
 */

class Pay_UnifyPayController extends BaseController{

    public $needAuth = true;

    private $orderModel;

    public $user;

    /**
     * 参数校验
     */
    public function checkParam()
    {
        Validator::make($this->params = Request::all(), array(
            'id' => 'required'
        ));
    }

    public function loadModel()
    {
        $this->orderModel = new OrderModel();
    }

    /**
     * 业务逻辑
     */
    public function indexAction()
    {
        $order = $this->orderModel->getById($this->params['id']);
        if (empty($order['uuid']) || empty($order['price']) || empty($order['title']) || empty($this->user->openid)){
            Log::notice('wxpay|unify_pay_params_error');
            throw new OperateFailedException('统一下单参数不正确');
        }
        $params = array(
            'out_trade_no' => $order['uuid'],
            'total_fee' => ($order['price']) * 100,
            'body' => $order['title'],
            'openid' => $this->user->openid
        );
        $app = Wx::getWxPayApp();
        Log::notice('wxpay|unify_pay_params:' . json_encode($params));
        try{
            $res = $app->miniapp($params);
        } catch (\Exception $e){
            Log::fatal('wxpay|error:' . json_encode($e->getMessage()));
            throw new OperateFailedException('调用支付接口异常');
        }
        Log::notice('wxpay|unify_pay_res:|res:' . json_encode($res));
        $arr = explode('=', $res['package']);
        $status = Config::get('order.STATUS_MAPPING');
        Wx::sendModelInfo($this->user->openid, Wx::MODEL_RELEASE_ORDER, array(
            'form_id'    => $arr[1],
            'created_at' => $order['created_at'],
            'uuid'       => $order['uuid'],
            'type'       => $status[$order['type']],
            'title'      => $order['title'],
            'price'      => $order['price']
        ));
        Response::apiSuccess($res);
    }
}