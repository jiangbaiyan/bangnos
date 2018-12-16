<?php

use Nos\Comm\Log;
use Nos\Exception\OperateFailedException;
use Wx\Wx;

/**
 * 支付结果通知
 * Created by PhpStorm.
 * User: baiyan
 * Date: 2018-12-16
 * Time: 16:13
 */

class Pay_NotifyController extends BaseController{


    public function checkParam()
    {

    }


    public function indexAction()
    {
        try{
            $app = Wx::getWxPayApp();
            $app->verify();
            return $app->success();
        } catch (\Exception $e){
            Log::notice('wxpay|notify_failed|msg:' . json_encode($e->getMessage()));
            throw new OperateFailedException($e->getMessage());
        }
    }
}