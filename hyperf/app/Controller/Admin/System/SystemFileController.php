<?php

namespace App\Controller\Admin\System;

use App\Controller\Admin\BaseController;
use App\Dao\System\SystemFileDao;
use App\Model\System\SystemFile;
use Hyperf\Di\Annotation\Inject;
use Yj\Apidog\Annotation\ApiController;
use Yj\Apidog\Annotation\FormData;
use Yj\Apidog\Annotation\PostApi;

/**
 * @ApiController(prefix="admin/system/file")
 */
class SystemFileController extends BaseController
{

    /**
     * @Inject()
     * @var SystemFileDao
     */
    protected $systemFileDao;
    /**
     * @PostApi(path="lists")
     * @FormData(key="tags_id",rule="")
     * @return void
     */
    public function lists(){
        $params = $this->getValidatorData();
        $data = $this->systemFileDao->page(true)->lists($params);
        _SUCCESS($data);
    }
}