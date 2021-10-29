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

namespace App\Controller\Admin;

use App\Controller\Util\CaptchaController;

use App\Controller\Utils\SmsController;
use App\Controller\Utils\VerifyController;
use App\Exception\FooException;
use App\Exception\Handler\AppExceptionHandler;
use App\Model\Admin;
use App\Model\Model;
use App\Model\User;
use App\Request\AdminRequest;
use App\Request\LoginRequest;
use Carbon\Carbon;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use App\Controller\AbstractController;
use Hyperf\Redis\Redis;
use HyperfAdmin\BaseUtils\Constants\ErrorCode;
use HyperfAdmin\BaseUtils\JWT;

/**
 * @AutoController()
 */
class LoginController extends AbstractController
{

    /**
     * @Inject()
     * @var Admin\Admin
     */
    protected $adminModel;

    /**
     * @Inject()
     * @var Redis
     */
    protected $redis;




    public function get_code(){
             ($phone = $this->request->input('phone', '')) || err('手机号码为空');

             $key = $this->request->input('key') || err('key is empty');

             $this->smsUtil->sendSms($phone);
             succ();
    }
    public function register(){


        ($phone = $this->request->input('phone', '')) || err('手机号码为空');
        ($password = $this->request->input('password', '')) || err('密码为空');
        ($phoneCode = $this->request->input('phoneCode', '')) || err('验证码为空');

          make(SmsController::class)->checkCode($phone,$phoneCode);

//        $this->checkCode($phone,$phoneCode);

        if($this->adminModel->where('username',$username)->first()){
              err('该用户已经注册');
        }

        $password = $this->passwordHash($password);
        $res = $this->getModel()->create(['account'=>$phone,'phone'=>$phone,'pwd'=>$password,'roles'=>'2']);
        if($res){
            succ($res);
        }else{
            err($res);
        }
    }
    public function checkCode($phone,$code){
//                 $this->checkCode()
                if($code == 9999){
                    return true;
                }else{
                    err('验证码不对');
                }
    }


    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        $user = $this->adminModel->where('username', $data['username'])->first();

        if (!$user){
            err( '该用户不存在或已被禁用');
        }

        if ($user->password !== $this->passwordHash($data['password'])) {
            err( '密码错误');
        }

        if($user->status ==2){
            err('你已经被封禁!');
        }

        //登录成功
        $user->last_login_ip = $this->request->getHeaderLine('X-Real-IP')?:'127.0.0.1';
        $user->last_login_time = date('Y-m-d H:i:s');
        $user->save();

        $token =  $this->adminModel->get_token($user->id);
        succ([
            'roles'=>$user->roles,
            'id' => $user->id,
            'username' => $user->username,
            'token' => $token,
        ]);
    }

    public function passwordHash($password)
    {
        return md5($password);
        return sha1(md5($password) . md5(config('password.salt')));
    }

    public function logout()
    {

    }

}
