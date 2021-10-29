<?php


namespace App\Dao\Order;


use App\Dao\BaseDao;
use Hyperf\Database\Model\Builder;

class OrderProductDao extends BaseDao
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