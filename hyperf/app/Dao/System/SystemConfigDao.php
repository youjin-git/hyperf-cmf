<?php

namespace App\Dao\System;
use App\Model\System\SystemConfig;
use phpDocumentor\Reflection\Types\Collection;
use \Yj\Daos\BaseDao;
use Hyperf\Database\Model\Builder;
use Yj\Daos\Verify;

/**
 * @var SystemConfig
 */
class SystemConfigDao extends BaseDao
{

    public function DaoWhere(\Hyperf\Utils\Collection|array $params)
    {
        return $this->getDaoQuery($params, function (Verify $verify) {
//            $verify('id', function (Builder $query, $id) {
//                $query->where('id', $id);
//            });
//            $verify('title', function (Builder $query, $title) {
//                $query->where('title', $title);
//            });
        });
    }

    public function lists($params){
        return $this->DaoWhere($params)->get();
    }
}