<?php


namespace App\Dao\User;


use App\Dao\BaseDao;
use Hyperf\Database\Model\Builder;

class UserAccountLogDao extends BaseDao
{
    public function MakeWith(): array
    {
        return [];
    }

    public function MakeWhere(Builder $query, $params)
    {
        // TODO: Implement MakeWhere() method.
    }


}