<?php

namespace App\Dao\System;
use App\Model\System\SystemAdmin;
use \Yj\Daos\BaseDao;
use Hyperf\Database\Model\Builder;
use Yj\Daos\Verify;

/**
 * @var SystemAdmin
 */
class SystemAdminDao extends BaseDao
{

    public function DaoWhere(array $params)
    {
//        return $this->getDaoQuery($params, function (Verify $verify) {
//            $verify('id', function (Builder $query, $id) {
//                $query->where('id', $id);
//            });
//            $verify('title', function (Builder $query, $title) {
//                $query->where('title', $title);
//            });
//        });
    }


    public function lists(\Hyperf\Utils\Collection $params)
    {
        return $this->paginate();
    }

    public function add(\Hyperf\Utils\Collection $params)
    {
        return $this->create($params->toArray());
    }

}