<?php

namespace App\Controller\Admin\Config;

use App\Controller\Admin\BaseController;
use App\Dao\System\SystemConfigDao;
use Hyperf\Di\Annotation\Inject;
use Yj\Apidog\Annotation\ApiController;
use Yj\Apidog\Annotation\PostApi;

/**
 * @ApiController(prefix="/admin/config")
 */
class Config extends BaseController
{

    /**
     * @Inject()
     * @var SystemConfigDao
     */
    protected $systemConfigDao;

    /**
     * @PostApi(path="lists")
     * @return void
     */
    public function lists(){
        $params = $this->getValidatorData();
        $data = $this->systemConfigDao->lists($params);
        _SUCCESS($data);
    }
}