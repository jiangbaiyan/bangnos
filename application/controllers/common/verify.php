<?php
/**
 * Created by PhpStorm.
 * User: baiyan
 * Date: 2018-12-11
 * Time: 18:10
 */

use Nos\Comm\Log;
use Nos\Comm\Validator;
use Nos\Exception\OperateFailedException;
use Nos\Http\Request;
use Nos\Comm\Redis;
use Nos\Http\Response;

class Common_VerifyController extends BaseController{

    const REDIS_SMS_VERIFY = 'bang_sms_verify_%s';

    public $needAuth = false;

    public function checkParam()
    {
        Validator::make($this->params = Request::all(), array(
            'phone' => 'required',
            'code' => 'required'
        ));
    }

    /**
     * 验证短信验证码正确性
     * @throws OperateFailedException
     */
    public function indexAction()
    {
        $phone = $this->params['phone'];
        $frontCode = $this->params['code'];
        $key = sprintf(self::REDIS_SMS_VERIFY,$phone);
        $backCode = Redis::get($key);
        if ($frontCode != $backCode){
            Log::notice('sms|wrong_sms_code|key:' . $key . '|frontCode:' . $frontCode . '|backCode:' . $backCode);
            throw new OperateFailedException('短信验证码验证失败，请重试');
        }
        Response::apiSuccess();
    }

}