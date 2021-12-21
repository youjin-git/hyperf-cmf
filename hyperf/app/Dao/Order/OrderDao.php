<?php


namespace App\Dao\Order;


use App\Constants\OrderStatus;
use App\Dao\BaseDao;
use Hyperf\Database\Model\Builder;

class OrderDao extends BaseDao
{
    public function MakeWith(): array
    {
        return ['orderProduct' => function (\Hyperf\Database\Model\Relations\HasMany $query) {
            $query->with('product');
        }, 'user'];
    }

    public function MakeWhere(Builder $query, $params)
    {
        if (isset($params['status']) && $params['status'] != OrderStatus::ALL) {
            $query->where('status', $params['status']);
        }

        if (isset($params['id']) && $params['id']) {
            $query->where('id', $params['id']);
        }

        if (isset($params['user_id']) && $params['user_id']) {
            $query->where('user_id', $params['user_id']);
        }
    }


}