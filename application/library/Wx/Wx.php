<?php
/**
 * Created by PhpStorm.
 * User: baiyan
 * Date: 2018-12-15
 * Time: 09:18
 */

namespace Wx;

use Nos\Comm\Config;
use Nos\Comm\Log;
use Nos\Exception\OperateFailedException;
use Nos\Http\Request;

class Wx{

    /**
     * 获取openid
     * @param $code
     * @return mixed
     * @throws OperateFailedException
     * @throws \Nos\Exception\CoreException
     */
    public static function getOpenid($code){
        $config = Config::get('wx');
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
}