<?php

namespace App\Service;


use App\Model\Admin\Admin;
use App\Model\Admin\Roles;
use App\Model\Community;
use App\Model\CommunityLike;
use App\Model\CommunityReport;
use App\Model\Form;
use App\Model\User;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use App\Constants\ErrorCode;


class AdminService extends Service
{
    /**
     * @Inject()
     * @var Admin
     */
    protected $adminModel;
    /**
     * @Inject()
     * @var Roles
     */
    protected $rolesModel;
    /**
     * 获取用户的权限
     */
    public function getRules($uid){

        $role_id = $this->adminModel->getRoles($uid); //获取用户的角色
        if($role_id!=1){
            $rules = $this->rolesModel->getRules($role_id);
        }else{
            return $role_id;
        }
        return explode(',',$rules);
    }

    public function getModel()
    {
        return $this->adminModel;
    }


}