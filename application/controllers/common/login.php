<?php
/**
 * Created by PhpStorm.
 * User: baiyan
 * Date: 2018-12-11
 * Time: 18:10
 */

use Firebase\JWT\JWT;
use Nos\Comm\Config;
use Nos\Comm\Log;
use Nos\Comm\Validator;
use Nos\Exception\OperateFailedException;
use Nos\Http\Request;
use Nos\Comm\Redis;
use Nos\Http\Response;
use Wx\Wx;

class Common_LoginController extends BaseController{

    const REDIS_SMS_VERIFY = 'bang_sms_verify_%s';

    public $needAuth = false;

    public function checkParam()
    {
        Validator::make($this->params = Request::all(), array(
            'phone' => 'required',
            'code' => 'required',
            'wxCode' => 'required',
            'avatar' => 'required',
            'nickname' => 'required'
        ));
    }

    /**
     * 验证短信验证码正确性
     * @throws OperateFailedException
     * @throws \Nos\Exception\CoreException
     */
    public function indexAction()
    {
        $phone = $this->params['phone'];
//        $frontCode = $this->params['code'];
//        $key = sprintf(self::REDIS_SMS_VERIFY,$phone);
//        $backCode = Redis::get($key);
//        if ($frontCode != $backCode){
//            Log::notice('sms|wrong_sms_code|key:' . $key . '|frontCode:' . $frontCode . '|backCode:' . $backCode);
//            throw new OperateFailedException('短信验证码验证失败，请重试');
//        }
        $openid = Wx::getOpenid($this->params['wxCode']);
        $data = array(
            'phone' => $phone,
            'name'  => $this->params['nickname'],
            'avatar' => $this->params['avatar'],
            'openid' => $openid
        );
        $user = $this->getLatestUser($data);
        $token = $this->setToken($user);
        Response::apiSuccess(array('token' => $token));
    }

    /**
     * 设置token
     * @param $data
     * @return mixed
     * @throws \Nos\Exception\CoreException
     */
    private function setToken($data){
        $key = Config::get('common.JWT');
        $token = JWT::encode($data, $key);
        $redisKey = sprintf(self::REDIS_TOKEN_PREFIX, $data['uid']);
        Redis::set($redisKey, $token, 2678400);
        return $token;
    }

    /**
     * 不存在则创建，存在则更新，返回最新的用户模型
     * @param $data
     * @return mixed
     * @throws OperateFailedException
     * @throws \Nos\Exception\CoreException
     */
    private function getLatestUser($data){
        $userModel = new UserModel();
        $user = $userModel->getByPhone($data['phone']);
        if (!$user){
            $userModel->create($data);
        } else{
            $userModel->updateByPhone($data['phone'], $data);
            $user = $userModel->getByPhone($data['phone']);
        }
        return $user;
    }

}