<?php

namespace App\Dao\System;
use App\Model\System\SystemAdmin;
use \Yj\Daos\BaseDao;
use Hyperf\Database\Model\Builder;
use Yj\Daos\Verify;

/**
 * @var SystemAdmin
 */
class SystemAdminDao extends BaseDao
{

    public function DaoWhere(array $params)
    {
        return $this->getDaoQuery($params, function (Verify $verify) {
            $verify('id', function (Builder $query, $id) {
                $query->where('id', $id);
            });
            $verify('username', function (Builder $query, $username) {
                $query->where('username', $username);
            });

        });
    }

    public function lists(\Hyperf\Utils\Collection|array $params)
    {
        return $this->DaoWhere($params->toArray())->with('iconPath')->getList();
    }

    public function add(\Hyperf\Utils\Collection $params)
    {
        //判断是否已经注册
        $this->DaoWhere($params->only('username')->toArray())->exists() && _Error('该管理员已存在!');

        return $this->create($params->toArray());
    }

    public function detail(int $id)
    {
        return $this->DaoWhere(compact('id'))->first()->makeHidden(['password']);
    }


    public function edit(int $id, \Hyperf\Utils\Collection $params)
    {
        $dao = $this->DaoWhere(compact('id'))->firstOr(function(){
            _Error('该用户不存在');
        });
        $dao->fill($params->toArray());
        return $dao->save();
    }

}