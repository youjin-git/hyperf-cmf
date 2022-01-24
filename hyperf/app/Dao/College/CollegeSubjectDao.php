<?php

namespace App\Dao\College;
use \Yj\Daos\BaseDao;
use Hyperf\Database\Model\Builder;
use Yj\Daos\Verify;

/**
 * @var CollegeSubjectDao
 */
class CollegeSubjectDao extends BaseDao
{

    public function DaoWhere(array $params)
    {
        return $this->getDaoQuery($params, function (Verify $verify) {
            $verify('id', function (Builder $query, $id) {
                $query->where('id', $id);
            });
            $verify('title', function (Builder $query, $title) {
                $query->where('title', $title);
            });
        });
    }

}