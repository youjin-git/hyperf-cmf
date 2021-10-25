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
use App\Middleware\CheckLoginMiddleware;
use App\Model\Hy\Banner;
use App\Model\Admin\ConfigValue;
use App\Model\Hy\News;
use App\Model\Project;
use App\Model\User;
use App\Model\UserAuthentication;
use App\Model\UserProject;
use App\Model\Waste;
use App\Request\AuthenticationRequest;
use App\Request\RegiterRequest;
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
 * @ApiController(tag="认证管理",prefix="authentication",description="")
 * @Middleware(CheckLoginMiddleware::class)
 */
class AuthenticationController extends AbstractController
{
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
     * @Inject()
     * @var UserAuthentication
     */
    protected $userAuthenticationModel;

    /**
     * @PostApi(path="info", description="认证详情")
     * @Middleware(CheckLoginMiddleware::class)
     */
    public function info()
    {
        $uid = Context::get('uid');
        $userAuthenticationInfo =  $this->userAuthenticationModel->orderBy('create_time','desc')->where('uid',$uid)->first()?:['status'=>-1];
//        p($this->userAuthenticationModel->user()->get()->toArray());

        succ($userAuthenticationInfo);
    }

    /**
     * @PostApi(path="add", description="添加认证")
     * @FormData(key="real_name|真实姓名", rule="required")
     * @FormData(key="id_card|身份证", rule="required")
     * @FormData(key="wx_picture_id|微信收款码图片id", rule="required")
     * @FormData(key="zfb_picture_id|支付宝收款码图片id", rule="required")
     * @Middleware(CheckLoginMiddleware::class)
     */
    public function add(AuthenticationRequest $request)
    {

        $data = $request->validated();
        $uid = Context::get('uid');
        $data['uid'] = $uid;

        //判断是否认证过
        if($this->userAuthenticationModel->where('uid',$uid)->where('status','>','-1')->exists()){
            err('认证正在审核中或审核通过');
        }

        $this->userAuthenticationModel->create($data)?succ():err();

    }


}
