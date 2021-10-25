<?php


namespace App\Dao\User;


use App\Dao\BaseDao;
use Hyperf\Database\Model\Builder;

class UserDao extends BaseDao
{
    public function MakeWith(): array
    {
        return ['userAccount'];
    }

    public function MakeWhere(Builder $query, $params)
    {
        if (isset($params['id']) && $params['id']) {
            if (is_array($params['id'])) {
                $query->whereIn('id', $params['id']);
            } else {
                $query->where('id', $params['id']);
            }
        }
        // TODO: Implement MakeWhere() method.
    }


}