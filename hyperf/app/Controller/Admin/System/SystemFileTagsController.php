<?php

namespace App\Controller\Admin\System;

use App\Controller\Admin\BaseController;
use App\Dao\System\SystemFileTagsDao;
use Hyperf\Di\Annotation\Inject;
use Yj\Apidog\Annotation\ApiController;
use Yj\Apidog\Annotation\PostApi;


/**
 * @ApiController(prefix="admin/system/file-tags")
 */
class SystemFileTagsController extends BaseController
{

    /**
     * @Inject()
     * @var SystemFileTagsDao
     */
    protected $systemFileTagsDao;

    /**
     * @PostApi(path="lists")
     * @return void
     */
    public function lists(){
        $data = $this->systemFileTagsDao->page(false)->lists();
        _SUCCESS($data);
    }
}