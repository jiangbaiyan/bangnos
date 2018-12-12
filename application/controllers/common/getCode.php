<?php
/**
 * Created by PhpStorm.
 * User: baiyan
 * Date: 2018-12-11
 * Time: 12:50
 */

use Nos\Comm\Validator;
use Nos\Http\Request;
use Nos\Http\Response;
use Sms\Sms;
use Nos\Comm\Redis;


class Common_GetCodeController extends BaseController{

    const REDIS_SMS_VERIFY = 'bang_sms_verify_%s';

    public $needAuth = false;

    public function checkParam()
    {
        Validator::make($this->params = Request::all(), array(
            'phone' => 'phone|required'
        ));
    }


    /**
     * 发送短信接口
     * @throws \Nos\Exception\OperateFailedException
     */
    public function indexAction()
    {
        $phone = $this->params['phone'];
        $code = rand(1000, 9999);
        Sms::send($phone, array(
            'code' => $code
        ));
        //验证用
        $key = sprintf(self::REDIS_SMS_VERIFY, $phone);
        Redis::set($key,$code, 61);
        Response::apiSuccess();
    }

}