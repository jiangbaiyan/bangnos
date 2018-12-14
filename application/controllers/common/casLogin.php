<?php

use Nos\Comm\Config;
use Nos\Comm\Log;
use Nos\Comm\Validator;
use Nos\Exception\OperateFailedException;
use Nos\Http\Request;
use Nos\Http\Response;
use Des\Des;


/**
 * Created by PhpStorm.
 * User: baiyan
 * Date: 2018-12-12
 * Time: 15:23
 */

class Common_CasLoginController extends BaseController{

    protected $needAuth = false;

    const LOGIN_SERVER = 'http://cas.hdu.edu.cn/cas/login';

    const VALIDATE_SERVER = 'http://cas.hdu.edu.cn/cas/serviceValidate';

    const REDIS_TOKEN_PREFIX = 'bang_token_%s';

    private $thisUrl;


    public function checkParam()
    {
        Validator::make($params = Request::all(),array(
            'uid' => 'required',
            'password' => 'required',
            'phone' => 'required',
            'code' => 'required',
        ));
    }

    public function indexAction()
    {
        $this->thisUrl = Config::get('common.host') . '/api/v1/common/casLogin';
        if (!empty($_REQUEST["ticket"])) {
            //获取登录后的返回信息
            try {//认证ticket
                $validateurl = self::VALIDATE_SERVER . "?ticket=" . $_REQUEST["ticket"] .  "&service=" . $this->thisUrl;

                $validateResult = file_get_contents($validateurl);

                //节点替换，去除sso:，否则解析的时候有问题
                $validateResult = preg_replace("/sso:/", "", $validateResult);

                $validateXML = simplexml_load_string($validateResult);

                $nodeArr = json_decode(json_encode($validateXML),true);

                $attributes = $nodeArr['authenticationSuccess']['attributes']['attribute'];

                $data = [];

                foreach ($attributes as $attribute){
                    switch ($attribute['@attributes']['name']){
                        case 'user_name'://姓名
                            $data['name'] = $attribute['@attributes']['value'];
                            break;
                        case 'id_type'://用户类型 1-本科生 2-研究生 其他-教师
                            $data['idType'] = $attribute['@attributes']['value'];
                            break;
                        case 'userName'://学号/工号
                            $data['uid'] = $attribute['@attributes']['value'];
                            break;
                        case 'user_sex'://性别 1-男 2-女
                            $data['sex'] = $attribute['@attributes']['value'];
                            break;
                        case 'unit_name'://学院
                            $data['unit'] = $attribute['@attributes']['value'];
                            break;
                        case 'classid'://班级号
                            $data['class'] = $attribute['@attributes']['value'];
                            break;
                    }
                }

                if (!empty($data['class'])){
                    $data['grade'] = '20' . substr($data['class'],0,2);
                }
                unset($data['idType']);
                $data['school'] = '杭州电子科技大学';
                $data['phone'] = Request::get('phone');
                $data['openid'] = Request::get('openid');
                !empty(Request::get('avatar')) && $data['avatar'] = Request::get('avatar');
                $user = $this->getLatestUser($data);
                $token = $this->setToken($user);

                return Response::apiSuccess(array_merge(['token' => $token],$data));

            }
            catch (\Exception $e) {
                Log::notice('login|get_user_info_from_hdu_api_failed|msg:' . json_encode($e->getMessage()));
                throw new OperateFailedException('杭电官方系统异常，请稍后再试');
            }
        } else//没有ticket，说明没有登录，需要重定向到登录服务器
        {
            Validator::make($params = Request::all(),[
                'uid' => 'required',
                'password' => 'required',
                'phone' => 'required',
                'code' => 'required',
            ]);
            $ticket = $this->getTicket($params['uid'],$params['password']);
            exit;
            $openid = WxService::getOpenid($params['code']);
            $avatar = !empty($params['avatar']) ? $params['avatar'] : '';
            return redirect($this->thisUrl . '?ticket=' . $ticket . '&phone=' . $params['phone'] . '&openid=' . $openid . '&avatar=' . $avatar);
        }
    }



    /**
     * 获取cas的ticket
     * @param $uid
     * @param $password
     * @return mixed
     * @throws OperateFailedException
     */
    private function getTicket($uid,$password){
        $password = trim(str_replace(PHP_EOL,'',$password));
        $uid = trim(str_replace(PHP_EOL,'',$uid));
        $ul = strlen($uid);
        $pl = strlen($password);
        $res = Request::send('GET',self::LOGIN_SERVER . '?service=' . $this->thisUrl, array(), array(
            CURLOPT_HEADER => 1,
        ));
        list($header, $body) = explode("\r\n\r\n", $res);
        preg_match_all("/set\-cookie:([^\r\n]*)/i", $header, $cookie);//获取sessionId
        preg_match('/LT-\d+-\w+-cas/',$res,$LT);//获取LT
        preg_match('/name="execution" value="(\w+)"/', $res, $execution);//获取execution
        if (empty($cookie) || empty($LT) || empty($execution)){
            throw new OperateFailedException('login|get_data_from_cas_failed|req:' . json_encode($res));
        }
        $arr = explode(';',trim($cookie[1][1]));
        $cookie = 'hdu_cas_un=' . $uid . ';' . 'Language=zh_CN' . ';' . $arr[0] . ';path=/;domain=.cas.hdu.edu.cn;';
        $des = new Des();
        $plus = $ul + $pl;
        $str = $des->strEnc($plus. $LT[0], '1', '2', '3');
        $payload = array(
            'rsa' => '8495AD5D5B42D03B9BF21FF3DB2B6556EB715DC39BBFE09FD75489C6D9D861626E1BB40B9E78198D3F87C346C8672F7C64238FA5794389778B0F6FE2DF01C70FDFD0E0F4149DC4BB10B44D7D804A3E337A6852F46EFB55E839F6DE108EE69A9CBC423A76B21C2CE28D028D9652B56EEE183E361B945511478AB35967D5AA0BA7',
            'ul' => $ul,
            'pl' => $pl,
            'lt' => $LT[0],
            'execution' => $execution[1],
            '_eventId' => 'submit',
        );
        $res = Request::send('POST',self::LOGIN_SERVER . '?service=' . $this->thisUrl, $payload, array(
            CURLOPT_COOKIE => $cookie,
            CURLINFO_HEADER_OUT => true
        ));
        echo $res;exit;
        if (strpos($res,'错误的用户名或密码')){
            Log::notice('login|wrong_password|res:' . json_encode($res));
            throw new OperateFailedException('用户名或密码错误，请重新输入');
        }
        preg_match('/ticket=(\w+-\d+-\w+)/',$res,$matches);
        if (empty($matches)){
            throw new OperateFailedException('login|get_ticket_from_cas_failed|req:' . json_encode($res));
        }
        return $matches[1];
    }
}