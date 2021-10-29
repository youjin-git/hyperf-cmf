<?php


namespace App\Dao\User;


use App\Dao\BaseDao;
use Hyperf\Database\Model\Builder;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;


class UserSignDao extends BaseDao
{

    public function MakeWith(): array
    {
        return [];
    }

    public function MakeWhere(Builder $query, $params)
    {


        if ($this->verify('user_id')) {
            $query->where('user_id', $params['user_id']);
        }

        if ($this->verify('day')) {
            $query->where('day', $params['day']);
        }

        // TODO: Implement MakeWhere() method.
    }
}