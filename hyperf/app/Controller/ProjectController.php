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
use App\Model\UserProject;
use App\Model\Waste;
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
 * @ApiController(tag="项目管理",prefix="project",description="")
 */
class ProjectController extends AbstractController
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
     * @var User
     */
    protected $userModel;

    /**
     * @PostApi(path="lists", description="项目列表")
     */
    public function lists()
    {
//        $status = $this->request->input('status') ?: err('状态为空');
        $data = $this->projectModel->where('status',1)->orderBy('id', 'desc')->paginate(15);
        succ($data);
    }

    /**
     * @PostApi(path="info", description="项目详情")
     * @FormData(key="project_id|项目id", rule="required")
     * @Middleware(CheckLoginMiddleware::class)
     */
    public function info()
    {
        $uid  = Context::get('uid');
        $project_id = $this->request->input('project_id') ?: err('项目id为空');
        $info = $this->projectModel->where('id', $project_id)->first();

        $userProject = $this->userProjectModel->where('uid',$uid)->where('project_id',$project_id)->where('status','>',-1)->exists();
        $info['userProjectStatus'] = (bool)$userProject;
        succ($info);
    }

    /**
     * @PostApi(path="add", description="项目申领")
     * @FormData(key="project_id|项目id", rule="required")
     * @Middleware(CheckLoginMiddleware::class)
     */
    public function add()
    {
        $project_id = $this->request->input('project_id') ?: err('项目id为空');
        $pictures = $this->request->input('pictures');
        $name = $this->request->input('name');
        $phone = $this->request->input('phone');

        $info = $this->projectModel->where('id', $project_id)->first()?:err('该项目不存在');

        p($info);
        $uid = Context::get('uid');
        //请先去认证
        if($this->userModel->getIsAuthentication($uid)!=1){
            err('请先去认证');
        }


        if ($info['status'] !== 1) {
            err('该项目已结束');
        }
        $uid = Context::get('uid');
       $info = $this->userProjectModel->where('uid',$uid)->where('project_id',$project_id)->where('status','>',-1)->exists();
       if($info){
           err('你已经申领过该项目');
       }
        $this->projectModel::query()->where('id',$project_id)->increment('nums');

        //判断是否已经申领过项目
        $this->userProjectModel->create(['uid'=>$uid,'project_id'=>$project_id,'pictures'=>$pictures,'name'=>$name,'phone'=>$phone])?succ():err('申领失败');

    }


}
