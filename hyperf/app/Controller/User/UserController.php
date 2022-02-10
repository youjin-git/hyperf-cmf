<?php
/**
 * Created by PhpStorm.
 * User: zwc
 * Date: 2022/2/7
 * Time: 13:39
 */

namespace App\Controller\User;


use App\Dao\Users\UsersDao;
use App\Middleware\CheckLoginMiddleware;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Middleware;
use Yj\Apidog\Annotation\ApiController;
use Yj\Apidog\Annotation\PostApi;


/**
 * Class UserController
 * @ApiController(prefix="user/user")
 * @Middleware(CheckLoginMiddleware::class)
 */
class UserController extends BaseController
{

    /**
     * @Inject()
     * @var UsersDao
     */
    protected $usersDao;

    /**
     * @PostApi(path="detail")
     */
    public function detail(){
        $usersId = $this->getUid();
        $detail = $this->usersDao->detail($usersId);
        _SUCCESS($detail);
    }
}