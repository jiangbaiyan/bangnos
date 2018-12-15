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

    private static $config;

    /**
     * 初始化对象
     * @throws \Nos\Exception\CoreException
     */
    private static function init()
    {
        if (empty(self::$config)) {
            self::$config = Config::get('sms.ALI_SMS');
        }
        if (!self::$client instanceof Client) {
            $params = array(
                'accessKeyId' => self::$config['ACCESS_KEY_ID'],
                'accessKeySecret' => self::$config['ACCESS_KEY_SECRET'],
            );
            self::$client = new Client($params);
        }
        if (!self::$sendSms instanceof SendSms) {
            self::$sendSms = new SendSms();
        }
        self::$sendSms->setSignName(self::$config['SIGN_NAME']);
        self::$sendSms->setTemplateCode(self::$config['TEMPLATE_CODE']);
    }


    /**
     * 发送短信
     * @param $phone
     * @param array $data
     * @throws OperateFailedException
     */
    public static function send($phone, $data = array())
    {
        try {
            if (empty($phone)) {
                throw new OperateFailedException('empty phone number');
            }
            self::init();
            self::$sendSms->setPhoneNumbers($phone);
            if (!empty($data)) {
                self::$sendSms->setTemplateParam($data);
            }
            $res = self::$client->execute(self::$sendSms);
            $res = json_decode(json_encode($res), true);
            if ($res['Code'] != 'OK') {
                throw new OperateFailedException($res['Message']);
            }
        } catch (\Exception $e) {
            Log::fatal('sms|send_sms_failed|msg:' . json_encode($e->getMessage()));
            throw new OperateFailedException('短信官方接口异常,请稍后重试');
        }
    }
}