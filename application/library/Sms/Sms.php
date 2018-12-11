<?php
/**
 * 短信处理类
 * Created by PhpStorm.
 * User: baiyan
 * Date: 2018-12-11
 * Time: 17:36
 */

namespace Sms;

use Flc\Dysms\Client;
use Flc\Dysms\Request\SendSms;
use Nos\Comm\Config;
use Nos\Comm\Log;
use Nos\Exception\OperateFailedException;

class Sms{

    const REDIS_SMS_VERIFY = 'bang_sms_verify_%s';

    private static $client;

    private static $sendSms;

    /**
     * 初始化对象
     * @throws \Nos\Exception\CoreException
     */
    private static function init(){
        if (!self::$client instanceof Client){
            $config = Config::get('sms.aliSms');
            $params = array(
                'accessKeyId'    => $config['ACCESS_KEY_ID'],
                'accessKeySecret' => $config['ACCESS_KEY_SECRET'],
            );
            self::$client = new Client($params);
        }
        if (!self::$sendSms instanceof SendSms){
            self::$sendSms = new SendSms();
        }
    }


    /**
     * 发送短信
     * @param $phone
     * @param array $data
     * @throws OperateFailedException
     * @throws \Nos\Exception\CoreException
     */
    public static function send($phone, $data = array()){
        self::init();
        $sendSms = self::$sendSms;
        $sendSms->setPhoneNumbers($phone);
        $sendSms->setSignName('帮帮吧');
        $sendSms->setTemplateCode('SMS_126460515');
        if (!empty($data)){
            $sendSms->setTemplateParam($data);
        }
        try{
            $res = self::$client->execute($sendSms);
            $res = json_decode(json_encode($res),true);
            if ($res['Code'] != 'OK'){
                throw new OperateFailedException($res['Message']);
            }
        } catch (\Exception $e){
            Log::fatal('sms|send_sms_failed|msg:' . json_encode($e->getMessage()));
            throw new OperateFailedException('短信官方接口异常,请稍后重试');
        }
    }
}