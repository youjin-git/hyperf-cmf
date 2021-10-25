<?php


namespace App\Controller\Api\User;


use App\Constants\AccountType;
use App\Controller\AbstractController;
use App\Middleware\CheckLoginMiddleware;
use App\Service\User\UserService;
use App\Service\User\UserSignService;
use Hyperf\Apidog\Annotation\ApiController;
use Hyperf\Apidog\Annotation\PostApi;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Middleware;


/**
 * @Middleware(CheckLoginMiddleware::class)
 * @ApiController(tag="授权",prefix="api/user_sign",description="")
 */
class UserSign extends AbstractController
{

    /**
     * @Inject()
     * @var UserSignService
     */
    protected $userSignService;

    /**
     * @PostApi(path="info", description="获取用户信息")
     */
    public function info()
    {

        $user_id = $this->getUid();
        $data = $this->userSignService->info($user_id);
        succ($data);
    }

    /**
     * @PostApi(path="create", description="获取用户信息")
     */
    public function create()
    {
        $userId = $this->getUid();
        $day = date('Y-m-d');
        if ($this->userSignService->getSign($userId, $day)) {
            _GetLastSql();
            err('您今日已签到');
        }
        $data = $this->userSignService->create($userId);
        $data ? succ($data) : err();
    }

    /**
     * @PostApi(path="list", description="获取用户信息")
     */
    public function list()
    {
        $user_id = $this->getUid();
        $data = $this->userSignService->list(['user_id' => $user_id]);
        succ($data);
    }


}