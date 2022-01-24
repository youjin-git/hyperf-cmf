<?php

namespace App\Controller\Admin\System;


use App\Controller\Admin\BaseController;
use App\Dao\System\SystemAdminDao;
use App\Model\System\SystemAdmin;
use Hyperf\Di\Annotation\Inject;
use Yj\Apidog\Annotation\ApiController;
use Yj\Apidog\Annotation\PostApi;

/**
 * @ApiController(prefix="admin/system/admin")
 */
class SystemAdminController extends BaseController
{

    /**
     * @Inject()
     * @var SystemAdminDao
     */
    protected $systemAdminDao;
    /**
     * @PostApi(path="lists")
     * @return void
     */
    public function lists()
    {
        $params = $this->getValidatorData();
        $data = $this->systemAdminDao->lists($params);
        _SUCCESS($data);
    }
}