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

use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Annotation\AutoController;
use App\Controller\AbstractController;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\Utils\Context;
use App\Middleware\CheckAdminMiddleware;

/**
 * @AutoController()
 * @Middleware(CheckAdminMiddleware::class)
 */
class BalanceController extends AbstractController
{
    /**
     * @Inject()
     * @var Project
     */
    protected $projectModel;
    /**
     * @Inject()
     * @var UserBalance
     */
    protected $userBalance;

    /**
     * @Inject()
     * @var UserBalanceLog
     */
    protected $userBalanceLog;

    /**
     * @Inject()
     * @var UserAuthentication
     */
    protected $userAuthenticationModel;
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


    public function lists()
    {
        $params = $this->request->all();
        $where = [];
        if($params['type'] !==''){
            $where['type'] = $params['type'];
        }
        if($params['uid'] !==''){
            $where['uid'] = $params['uid'];
        }
        $lists =  $this->userBalanceLog->where($where)->orderBy('id','desc')->paginate($params['limit']);
        succ($lists);
    }

}
