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
use App\Middleware\CheckLoginMiddleware;
use App\Model\Hy\Banner;
use App\Model\Admin\ConfigValue;
use App\Model\Hy\News;
use App\Model\Order;
use App\Model\Project;
use App\Model\User;
use App\Model\UserProject;
use App\Model\Waste;
use App\Request\RegiterRequest;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\Apidog\Annotation\ApiVersion;
use Hyperf\Apidog\Annotation\ApiServer;
use Hyperf\Apidog\Annotation\ApiController;
use Hyperf\Apidog\Annotation\ApiResponse;
use Hyperf\Apidog\Annotation\Body;
use Hyperf\Apidog\Annotation\DeleteApi;
use Hyperf\Apidog\Annotation\FormData;
use Hyperf\Apidog\Annotation\GetApi;
use Hyperf\Apidog\Annotation\Header;
use Hyperf\Apidog\Annotation\PostApi;
use Hyperf\Apidog\Annotation\ApiDefinition;
use Hyperf\Apidog\Annotation\ApiDefinitions;
use Hyperf\Apidog\Annotation\Query;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Utils\Context;


/**
 * @ApiController(tag="用户管理",prefix="user",description="")
 * @Middleware(CheckLoginMiddleware::class)
 */
class UserController extends AbstractController
{
    /**
     * @Inject()
     * @var User
     */
    protected $user;
    /**
     * @Inject()
     * @var Project
     */
    protected $projectModel;

    /**
     * @Inject()
     * @var UserProject
     */
    protected $userProjectModel;



    /**
     * @PostApi(path="info", description="用户详情")
     */
    public function info()
    {
        $uid = Context::get('uid');
        $info = $this->user->where('id', $uid)->select('*')->first();
        succ($info);
    }

    /**
     * @PostApi(path="project", description="我的项目列表")
     * @FormData(key="status|状态", rule="required")
     */
    public function project()
    {
        $status = $this->request->input('status', 0);

        if ($status == 2) {
            $status = -1;
        }
        if ($status == 3) {
            $status = -2;
        }
        $uid = Context::get('uid');
        $data = $this->userProjectModel->where('uid', $uid)->where('status', $status)->orderBy('id', 'desc')->paginate(15);
        succ($data);
    }

    /**
     * @PostApi(path="update", description="我的项目列表")
     * @FormData(key="password|密码", rule="")
     * @FormData(key="address|常驻地址", rule="")
     * @FormData(key="real_name|真实姓名", rule="")
     */
    public function update()
    {
        $params = Context::get('validator.data');
        $uid = Context::get('uid');
        foreach ($params as $key => &$v) {
            if (!in_array($key, ['password', 'address', 'real_name'])) {
                err('参数异常');
            }
            if ($key == 'password') {
                $v = md5($v);
            }

        }
        if ($params) {
            $this->user->where('id', $uid)->update($params) ? succ() : err();
        } else {
            err();
        }

    }

    /**
     * @PostApi(path="change_new_phone", description="修改手机号码")
     * @FormData(key="new_phone|新手机号码", rule="required")
     * @FormData(key="new_phone_code|新手机验证码", rule="required")
     */
    public function change_new_phone()
    {
        $params = Context::get('validator.data');
        $uid = Context::get('uid');
        $this->user->where('phone', $params['new_phone'])->exists() ?: err('新手机号码已经被注册');
        $userInfo = $this->user::query()->where('id', $uid)->first();

        make(SmsController::class)->checkCode($params['new_phone'], $params['new_phone_code'], '新手机验证码');

        $userInfo->phone = $params['new_phone'];

        $userInfo->save();

    }

    /**
     * @PostApi(path="check_old_phone", description="检测老手机号码")
     * @FormData(key="old_phone|原手机号码", rule="required")
     * @FormData(key="old_phone_code|原手机验证码", rule="required")
     */
    public function check_old_phone()
    {
        $params = Context::get('validator.data');
        $uid = Context::get('uid');
        //判断新手机号码是否被注册

        $userInfo = $this->user->where('id', $uid)->first();

        if ($userInfo->phone != $params['old_phone']) {
            err('原手机号码不对');
        }
        //判断当前手机号码是否是你的

        make(SmsController::class)->checkCode($params['old_phone'], $params['old_phone_code'], '原手机验证码');

        succ();


    }


}
