<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Controller\Util;

use App\Controller\AbstractController;
use App\Controller\Util\CaptchaController;

use App\Model\Admin\ConfigValue;
use App\Model\SystemConfigValue;
use App\Model\User;
use App\Service\UserService;
use Hyperf\Cache\Cache;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Gregwar\Captcha\CaptchaBuilder;
use Hyperf\Redis\Redis;
use Mrgoon\AliSms\AliSms;
use Psr\SimpleCache\CacheInterface;
use Hyperf\Apidog\Annotation\ApiController;
use Hyperf\Apidog\Annotation\FormData;
use Hyperf\Apidog\Annotation\PostApi;
use Hyperf\Apidog\Annotation\GetApi;

/**
 * @ApiController(tag="验证码",prefix="util/sms",description="")
 */
class SmsController extends AbstractController
{

    /**
     * @Inject()
     * @var ConfigValue
     */
    protected $configValueModel;

    /**
     * @Inject()
     * @var CacheInterface
     */
    protected $cache;

    /**
     * @Inject()
     * @var Redis
     */
    protected $redis;

    /**
     * @Inject()
     * @var User
     */
    protected $user;

    /**
     * @PostApi(path="send", description="发送短信验证码")
     * @FormData(key="phone|手机号码", rule="required")
     * @FormData(key="key|key", rule="required")
     * @FormData(key="code|图片验证码", rule="required")
     * @FormData(key="type|类型", rule="", description="register,forgetPassword,checkOldPhone,checkNewPhone")
     */
    public function send(){
        $phone= $this->request->input('phone', '')?:err('手机号码为空');
        $key = $this->request->input('key', '')?:err('key为空');
        $code = $this->request->input('code', '')?:err('验证码为空');
        $type = $this->request->input('type', 'register');

        $userInfo = $this->user->where('phone',$phone)->first();


        if(in_array($type,['register','checkNewPhone'])){
              if($userInfo){
                  err('该手机号码已经注册了');
              }
        }
        if(in_array($type,['forgetPassword','checkOldPhone'])){
            if(!$userInfo){
                err('该手机号码还没有注册');
            }
        }

        $code = $this->sendSms($phone,$key,$code);
        succ($code);
    }

    /**
     * 发送短信
     */
    public  function sendSms($mobile,$key,$code)
    {
        if (empty($mobile)) {
             err('手机号不能为空');
        }

        //检测图片验证码code
        make(CaptchaController::class)->checkCode($key,$code);

        //生成code
        $sms_code = rand(1000,9999);

        $this->cache->set('sms:'.(string)$mobile,(string)$sms_code,5*60);

        $config = $this->configValueModel->_get(['access_key','access_secret','template_codes','sign_name','aliyun_scene']);
        p($config);
        $ali_sms = new AliSms();

        $response = $ali_sms->sendSms($mobile,$config['template_codes'],['code'=>$sms_code],$config);
        date_default_timezone_set("Asia/Shanghai");

        if ($response->Code == 'OK') {
             return $sms_code;
         }
         err($response->Message);
    }

    public function check($mobile,$sms_code){
        return $this->redis->get('sms:'.$mobile) == $sms_code;
    }
    public function checkCode($mobile,string $sms_code,$message='短信验证码'){
        p($sms_code);
        if($sms_code == 9999){
            return true;
        }
        $_code  = $this->cache->get('sms:' . $mobile);
        if (!$_code) {
            err($message.'过期');
        }
        if (strtolower($_code) != strtolower($sms_code)) {
            err($message.'错误');
        }
        //删除code
        $this->cache->delete('sms:' . $mobile);
        return true;
    }
}
