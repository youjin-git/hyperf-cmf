<?php

namespace App\Controller\Admin\College;

use App\Controller\Admin\BaseController;
use App\Dao\College\CollegeSchoolDao;
use Hyperf\Di\Annotation\Inject;
use Yj\Apidog\Annotation\ApiController;
use Yj\Apidog\Annotation\PostApi;

/**
 * @ApiController(prefix="admin/college/school")
 */
class SchoolController extends BaseController
{

    /**
     * @Inject()
     * @var CollegeSchoolDao
     */
    public $collegeSchoolDao;

    /**
     * @PostApi(path="lists")
     * @return void
     */
    public function lists(){
        $params = $this->getValidatorData();
        $data = $this->collegeSchoolDao->lists($params);
        _SUCCESS($data);
    }
}