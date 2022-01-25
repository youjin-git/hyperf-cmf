<?php

namespace App\Dao\System;
use App\Model\System\SystemFileTags;
use \Yj\Daos\BaseDao;
use Hyperf\Database\Model\Builder;
use Yj\Daos\Verify;

/**
 * @var SystemFileTags
 */
class SystemFileTagsDao extends BaseDao
{

    public function DaoWhere(array $params)
    {

    }

    public function lists()
    {
        return $this->getList();
    }

}