<?php

namespace App\Service;

use App\Model\Community;
use App\Model\CommunityLike;
use App\Model\CommunityReport;
use App\Model\User;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use App\Constants\ErrorCode;


class UserService extends Service
{
    /**
     * @Inject()
     * @var User
     */
    protected $userModel;

    public function lists()
    {
        return $this->userModel->paginate();
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