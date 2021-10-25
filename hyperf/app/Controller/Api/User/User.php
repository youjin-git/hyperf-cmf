<?php


namespace App\Controller\Api\User;


use App\Controller\AbstractController;
use App\Middleware\CheckLoginMiddleware;
use App\Service\User\UserService;
use Hyperf\Apidog\Annotation\ApiController;
use Hyperf\Apidog\Annotation\PostApi;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Middleware;


/**
 * @Middleware(CheckLoginMiddleware::class)
 * @ApiController(tag="授权",prefix="api/user",description="")
 */
class User extends AbstractController
{
    /**
     * @Inject()
     * @var UserService
     */
    protected $userService;
    
    /**
     * @PostApi(path="get_info", description="获取用户信息")
     */
    public function getUserInfo()
    {
        $uid = $this->getUid();
        $userinfo = $this->userService->getInfo($uid);

        succ($userinfo);
    }


}