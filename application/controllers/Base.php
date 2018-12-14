<?php
/**
 * Created by PhpStorm.
 * User: guobutao001
 * Date: 2018/11/30
 * Time: 10:00
 */
/**
 * 控制器公共基类
 * Created by PhpStorm.
 * User: baiyan
 * Date: 2018-11-27
 * Time: 17:21
 */

use Firebase\JWT\JWT;
use Nos\Comm\Config;
use Nos\Comm\Log;
use Nos\Exception\UnauthorizedException;
use Nos\Http\Request;
use Nos\Comm\Redis;
use Yaf\Controller_Abstract;

class BaseController extends Controller_Abstract{

    const REDIS_TOKEN_PREFIX = 'bang_token_%s';

    /**
     * 请求参数
     *
     * @var array
     */
    protected $params = array();

    /**
     * 返回数据
     *
     * @var array
     */
    protected $output = array();

    /**
     * 是否需要校验
     *
     * @var bool
     */
    protected $needAuth = true;//是否需要校验


    /**
     * 初始化
     */
    private function init(){
        $user = $this->needAuth && $this->auth();
        $this->checkParam();//请求参数校验
        $this->loadModel($user);//模型载入
    }

    /**
     * 用户授权
     * @return object
     * @throws UnauthorizedException
     */
    protected function auth(){
        $frontToken = Request::header('Authorization');
        if (empty($frontToken)) {
            Log::notice('auth|header_token_empty');
            throw new UnAuthorizedException();
        }
        try{
            $key = Config::get('common.jwt');
            $user = JWT::decode($frontToken, $key ,['HS256']);
        }catch (\Exception $e){
            Log::notice('auth|decode_token_failed|msg:' . $e->getMessage() . 'frontToken:'. $frontToken);
            throw new UnAuthorizedException();
        }
        $redisKey = sprintf(self::REDIS_TOKEN_PREFIX, $user->uid);
        $token = Redis::get($redisKey);//查redis里token，比较
        if ($frontToken !== $token) {
            Log::notice('auth|front_token_not_equals_redis_token|front_token:' . $frontToken . '|redis_token:' . $token);
            throw new UnAuthorizedException();
        }
        return $user;
    }

    /**
     * 参数校验
     */
    protected function checkParam(){}

    /**
     * 业务逻辑
     */
    protected function indexAction(){}

    /**
     * 加载模型
     * @param $user
     */
    protected function loadModel($user){}


}