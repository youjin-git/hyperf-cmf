<?php

namespace App\Dao\System;
use \Yj\Daos\BaseDao;
use Hyperf\Database\Model\Builder;
use Yj\Daos\Verify;

/**
 * @var SystemFileDao
 */
class SystemFileDao extends BaseDao
{

    public function DaoWhere(array $params)
    {
        return $this->getDaoQuery($params, function (Verify $verify) {
            $verify('tags_id', function (Builder $query, $tags_id) {
                $query->where('tags_id', $tags_id);
            });
//            $verify('title', function (Builder $query, $title) {
//                $query->where('title', $title);
//            });
        });
    }

    public function lists(\Hyperf\Utils\Collection $params)
    {
        return $this->DaoWhere($params->toArray())->getList();
    }

}