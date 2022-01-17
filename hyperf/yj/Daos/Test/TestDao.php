<?php

namespace Yj\Daos;


use App\Model\User;
use Hyperf\Database\Model\Builder;


/**
 * @Notes: []
 * @User: zwc
 * @Date: 2022/1/14 13:59
 */
class TestDao extends BaseDao
{
    public $model = User::class;

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