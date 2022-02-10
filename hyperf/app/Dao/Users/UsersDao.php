<?php

namespace App\Dao\Users;
use App\Model\Users\Users;
use \Yj\Daos\BaseDao;
use Hyperf\Database\Model\Builder;
use Yj\Daos\Verify;

/**
 * @var Users
 */
class UsersDao extends BaseDao
{

    public function DaoWhere(array $params)
    {
        return $this->getDaoQuery($params, function (Verify $verify) {
            $verify('id', function (Builder $query, $id) {
                $query->where('id', $id);
            });
            $verify('mobile', function (Builder $query, $title) {
                $query->where('mobile', $title);
            });
        });
    }

    public function isRegister(){

    }

    public function getOpenid($userId){
        return $this->DaoWhere(['id'=>$userId])->value('openid');
    }

    public function detail($usersId)
    {
       return  $this->DaoWhere(['id'=>$usersId])->first();
    }


}