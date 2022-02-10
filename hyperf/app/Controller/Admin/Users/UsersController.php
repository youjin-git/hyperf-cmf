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

namespace App\Controller\Admin\Users;

use App\Dao\Users\UsersDao;
use App\Form\Elm;
use App\Middleware\CheckLoginMiddleware;
use App\Model\Admin\Menu;
use App\Model\Project;
use App\Model\User;
use App\Model\UserAuthentication;
use App\Model\UserBalance;
use App\Model\UserBalanceLog;
use App\Model\UserBill;
use App\Model\UserProject;
use App\Service\AdminService;
use FormBuilder\Driver\CustomComponent;

use Hyperf\Apidog\Annotation\PostApi;
use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Annotation\AutoController;
use App\Controller\AbstractController;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\Utils\Context;
use App\Middleware\CheckAdminMiddleware;
use Yj\Apidog\Annotation\ApiController;

/**
 * @ApiController(prefix="admin/users/users")
 * @Middleware(CheckAdminMiddleware::class)
 */
class UsersController extends AbstractController
{
    /**
     * @Inject()
     * @var UsersDao
     */
    protected $usersDao;

    /**
     * @PostApi(path="list")
     */
    public function lists()
    {

        $data = $this->usersDao->getList();
        _SUCCESS($data);
    }



}
