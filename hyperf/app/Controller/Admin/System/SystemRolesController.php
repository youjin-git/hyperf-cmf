<?php

namespace App\Controller\Admin\System;

use App\Controller\Admin\AdminController;
use App\Controller\Admin\BaseController;
use App\Dao\System\SystemRolesDao;
use Hyperf\Di\Annotation\Inject;
use Yj\Apidog\Annotation\ApiController;
use Yj\Apidog\Annotation\PostApi;

/**
 * @ApiController(prefix="admin/system/roles")
 */
class SystemRolesController extends BaseController
{
    /**
     * @Inject()
     * @var SystemRolesDao
     */
    protected $systemRolesDao;

    /**
     * @PostApi(path="lists")
     * @return void
     */
    public function lists()
    {
        $params = $this->getValidatorData();
        $data = $this->systemRolesDao->lists($params);
        _SUCCESS($data);
    }
}