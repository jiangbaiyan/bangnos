<?php
/**
 * 微信工具类
 * Created by PhpStorm.
 * User: baiyan
 * Date: 2018-12-15
 * Time: 09:18
 */

namespace Wx;

use Nos\Comm\Config;
use Nos\Comm\Log;
use Nos\Comm\Redis;
use Nos\Exception\OperateFailedException;
use Nos\Http\Request;
use Yansongda\Pay\Pay;

class Wx{

    const REDIS_ACCESS_TOKEN_KEY = 'bang_access_token';

    const MODEL_RELEASE_ORDER = 1;

    private static $config;

    /**
     * 获取微信配置
     * @return mixed|string
     * @throws \Nos\Exception\CoreException
     */
    public static function getConfig(){
        if (!isset(self::$config)){
            self::$config = Config::get('wx');
        }
        return self::$config;
    }

    /**
     * 获取openid
     * @param $code
     * @return mixed
     * @throws OperateFailedException
     * @throws \Nos\Exception\CoreException
     */
    public static function getOpenid($code){
        $config = self::getConfig();
        $appId = $config['APP_ID'];
        $appKey = $config['APP_KEY'];
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid=$appId&secret=$appKey&js_code=$code&grant_type=authorization_code";
        $res = Request::send('GET',$url);
        $res = json_decode($res,true);
        if (array_key_exists('errmsg',$res)){
            Log::notice('wx|get_openid_from_api_failed|msg:' . json_encode($res));
            throw new OperateFailedException('获取微信授权失败');
        }
        return $res['openid'];
    }

    /**
     * 获取access_token
     * @return mixed
     * @throws OperateFailedException
     * @throws \Nos\Exception\CoreException
     */
    public static function getAccessToken(){
        $accessToken = Redis::get(self::REDIS_ACCESS_TOKEN_KEY);
        if (!empty($accessToken)){
            return $accessToken;
        }
        $config = self::getConfig();
        $appId = $config['APP_ID'];
        $appKey = $config['APP_KEY'];
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appId&secret=$appKey";
        $res = Request::send('GET', $url);
        $res = json_decode($res, true);
        if (isset($res['errmsg'])){
            Log::fatal('wx|get_access_token_failed|msg:' . json_encode($res));
            throw new OperateFailedException('获取access_token失败');
        }
        $accessToken = $res['access_token'];
        $expire = $res['expires_in'];
        Redis::set(self::REDIS_ACCESS_TOKEN_KEY, $accessToken, $expire);
        return $accessToken;
    }

    /**
     * 发送模板消息
     * @param $openid
     * @param $modelNum
     * @param array $params
     * @return bool
     * @throws OperateFailedException
     * @throws \Nos\Exception\CoreException
     */
    public static function sendModelInfo($openid, $modelNum, $params = array()){
        $accessToken = self::getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=$accessToken";
        $config = self::$config;
        switch ($modelNum){
            case self::MODEL_RELEASE_ORDER:
                $config = $config['MODEL_RELEASE_ORDER'];
                $config['touser'] = $openid;
                $config['form_id'] = $params['form_id'];
                $config['data']['keyword1']['value'] = $params['uuid'];
                $config['data']['keyword2']['value'] = $params['created_at'];
                $config['data']['keyword3']['value'] = $params['type'];
                $config['data']['keyword4']['value'] = $params['title'];
                $config['data']['keyword5']['value'] = $params['price'];
                break;
        }
        $res = Request::send('POST', $url, json_encode($config), array(
            CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($config)
            )
        ));
        $res = json_decode($res, true);
        if (isset($res['errmsg'])){
            throw new OperateFailedException('模板消息发送失败');
            Log::fatal('wx|send_model_info_failed|msg:' . json_encode($res));
        }
        return true;
    }

    /**
     * 获取支付实例
     * @return \Yansongda\Pay\Gateways\Wechat
     * @throws \Nos\Exception\CoreException
     */
    public static function getWxPayApp(){
        $config = self::getConfig();
        $config = $config['PAY'];
        return Pay::wechat($config);
    }
}