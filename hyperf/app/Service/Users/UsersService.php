<?php
/**
 * Created by PhpStorm.
 * User: zwc
 * Date: 2022/2/5
 * Time: 1:10
 */

namespace App\Service\Users;


use App\Dao\Users\UsersDao;
use App\Service\BaseService;
use Hyperf\Di\Annotation\Inject;

class UsersService extends BaseService
{


    /**
     * @Inject()
     * @var UsersDao
     */
    protected $usersDao;

    public function regAndLogin($mobile,$params){
        $this->register($mobile,$params);
        return $this->login($mobile);
    }


    public function login($mobile){
        $users = $this->usersDao->DaoWhere(compact('mobile'))->first();
        $token = $this->getToken($users->id);
        return compact('token');
    }

    public function register($mobile,$params){
        if($users = $this->usersDao->DaoWhere(compact('mobile'))->first()){
            $users->fill($params);
            return $users->save();
        }
        return $this->usersDao->create(compact('mobile')+$params);
    }

    public function getToken($usersId)
    {
        $token_pre = 'token:';
        //生成随机树
        $token = time() . rand(10000, 9999999) . $usersId;

        if ($this->redis->set($token_pre . $token, $usersId)) {

        } else {
            err('获取token失败');
        }

        return $token;
    }

    public function checkToken($token)
    {
        $token_pre = 'token:';
        if (!$this->redis->exists($token_pre . $token)) {
            return false;
        }
        $uid = $this->redis->get($token_pre . $token);
        return $uid;
    }


}