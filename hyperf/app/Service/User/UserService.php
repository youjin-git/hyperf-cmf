<?php

namespace App\Service\User;

use App\Constants\AccountType;
use App\Dao\User\UserAccountDao;
use App\Dao\User\UserDao;
use App\Model\Community;
use App\Model\CommunityLike;
use App\Model\CommunityReport;
use App\Model\User;
use App\Service\BaseService;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use App\Constants\ErrorCode;


class UserService extends BaseService
{
    /**
     * @Inject()
     * @var UserDao
     */
    public $userDao;

    /**
     * @Inject()
     * @var UserAccountDao
     */
    public $userAccountDao;

    public function getInfo(int $userId)
    {
        //处理账户
        return collect($this->userDao->DaoWhere(['id' => $userId])->DaoWith()->first())->pipe(function ($userInfo) use ($userId) {
            $userInfo['user_account'] = $this->InitAccount($userInfo['user_account'], $userId);
            return $userInfo;
        });
    }

    protected function InitAccount($userAccount, $userId)
    {
        $userAccount = array_column($userAccount, null, 'type');
        return array_map(function ($type) use ($userAccount, $userId) {
            if ($userAccount[$type]) {
                return $userAccount[$type];
            } else {
                return $this->userAccountDao->find($userId, $type);
            }
        }, array_change_key_case(AccountType::getConstants(), CASE_LOWER));
    }

    public function lists()
    {
        return $this->userDao->paginate();
    }

    public function getInvitationCcode()
    {
        $chars = [
            "a", "b", "c", "d", "e", "f",
            "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s",
            "t", "u", "v", "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G", "H", "I",
            "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V",
            "W", "X", "Y", "Z"
        ];
        $charsNumber = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9"];
        return strtoupper(generateShortUuid($chars, 3)) . generateShortUuid($charsNumber, 2);
    }

    public function RegByOpenid($routineOpenid, $data)
    {
        $data['openid'] = $routineOpenid;

        $wechatUser = $this->userModel->where('openid', $routineOpenid)->first();
        if ($wechatUser) {
            $wechatUser->fill($data);
            $wechatUser->save();
        } else {
            $wechatUser = $this->userModel->create($data);
        }
        return $wechatUser;
    }

    public function getToken($uid)
    {
        return $this->userModel->get_token($uid);
    }

    public function getUserInfo($uid)
    {
        return $this->userModel->where('id', $uid)->first()->toArray();
    }
}