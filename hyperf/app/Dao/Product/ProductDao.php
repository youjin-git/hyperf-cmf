<?php


namespace App\Dao\Product;


use App\Dao\BaseDao;
use Hyperf\Database\Model\Builder;


class ProductDao extends BaseDao
{
    public function MakeWith(): array
    {
        return [];
    }

    public function MakeWhere(Builder $query, $params)
    {
        if (isset($params['category_id']) && $params['category_id']) {
            $query->where('category_id', $params['category_id']);
        }
        
    }

//    public function

}