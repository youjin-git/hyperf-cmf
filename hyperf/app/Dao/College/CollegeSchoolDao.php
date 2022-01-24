<?php

namespace App\Dao\College;
use App\Model\College\CollegeSchool;
use \Yj\Daos\BaseDao;
use Hyperf\Database\Model\Builder;
use Yj\Daos\Verify;

/**
 * @var CollegeSchool
 */
class CollegeSchoolDao extends BaseDao
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

}