<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Controller;


use App\Controller\AbstractController;
use App\Controller\Util\SmsController;
use App\Model\Admin\ConfigValue;
use App\Model\User;
use App\Model\Waste;
use App\Request\RegiterRequest;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\Apidog\Annotation\ApiController;
use Hyperf\Apidog\Annotation\FormData;
use Hyperf\Apidog\Annotation\PostApi;
use Hyperf\Utils\Context;


/**
 * @ApiController(tag="登录",prefix="login",description="")
 */
class LoginController extends AbstractController
{
    /**
     * @Inject()
     * @var ConfigValue
     */
    protected $configValueModel;

    /**
     * @Inject()
     * @var User
     */
    protected $userModel;

    /**
     * @Inject()
     * @var Waste
     */
    protected $wasteModel;

    /**
     * @PostApi(path="sms", description="注册")
     * @FormData(key="phone|手机号码", rule="required")
     * @FormData(key="code|短信验证码", rule="required")
     * @FormData(key="password|密码", rule="required")
     */
    public function sms()
    {
        $phone = $this->request->input('phone') ?: err('请输入手机号码');
        $code = $this->request->input('code') ?: err('请输入验证码');
        $password = $this->request->input('password') ?: err('请输入密码');

        make(SmsController::class)->checkCode($phone, $code);

        //判断是否注册
        $user = $this->userModel->where('phone', $phone)->first();
        if (!$user) {
            //去注册
            $user = $this->userModel->create(['phone' => $phone, 'status' => 1, 'password' => $password]);
        }

        if ($user->status != 1) {
            err('正在审核中');
        }


        $token = $this->userModel->get_token($user->id);
        succ([
            'token' => $token,
        ]);


    }

    /**
     * @PostApi(path="login", description="密码登录")
     * @FormData(key="phone|手机号码", rule="required")
     * @FormData(key="password|密码", rule="required")
     */
    public function login()
    {
        $phone = $this->request->input('phone') ?: err('请输入手机号码');
        $password = $this->request->input('password') ?: err('请输入密码');
        $user = $this->userModel->where('phone', $phone)->first();
        dump($user);
        if (empty($user) || $user->password !== md5($password)) {
            err('用户不存在或者密码错误');
        }
        if ($user->status != 1) {
            err('正在审核中');
        }

        //登录成功
//        $user->last_login_ip = $this->request->getHeaderLine('X-Real-IP')?:'127.0.0.1';
//        $user->last_login_time = date('Y-m-d H:i:s');
//        $user->save();
        $token = $this->userModel->get_token($user->id);
        succ([
            'token' => $token,
        ]);

    }

    /**
     * @PostApi(path="forget", description="忘记密码")
     * @FormData(key="phone|手机号码", rule="required")
     * @FormData(key="code|验证码", rule="required")
     * @FormData(key="password|密码", rule="required")
     */
    public function forget()
    {
        $params = Context::get('validator.data');

        $userInfo = $this->userModel->where('phone', $params['phone'])->first();
        if (!$userInfo) {
            err('该手机号码还没有注册');
        }

        make(SmsController::class)->checkCode($params['phone'], $params['code']);

        $userInfo->password = $params['password'];

        $userInfo->save();
        succ();


    }

}
