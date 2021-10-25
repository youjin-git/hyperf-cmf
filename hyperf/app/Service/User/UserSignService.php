<?php

namespace App\Service\User;

use App\Constants\AccountLogType;
use App\Constants\AccountType;
use App\Dao\User\UserDao;
use App\Dao\User\UserSignDao;
use App\Model\Community;
use App\Model\CommunityLike;
use App\Model\CommunityReport;
use App\Model\User;
use App\Service\BaseService;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use App\Constants\ErrorCode;
use Hyperf\Utils\Collection;


class UserSignService extends BaseService
{
    /**
     * @Inject()
     * @var UserSignDao
     */
    protected $userSignDao;

    /**
     * @Inject()
     * @var UserService
     */
    protected $userService;

    /**
     * @var UserAccount
     */
    protected $userAccount;
    /**
     * @Inject()
     * @var User
     */
    protected $user;

    public function info($userId)
    {
        $userInfo = $this->userService->getInfo($userId);
        $title = systemGroupData('sign_day_config')->items();
        $sign_num = $this->getSign($userId);
        $count = $this->userSignDao->DaoWhere(['user_id' => $userId])->count();
        return compact('userInfo', 'title', 'sign_num', 'count');
    }

    public function list($params)
    {
        return $this->userSignDao->DaoWhere($params)->DaoWith()->paginate();
    }

    public function getSign(int $userId, string $day = ''): int
    {
        return $this->userSignDao->DaoWhere(['user_id' => $userId, 'day' => $day ?: date('Y-m-d')])->value('sign_num') ?: 0;
    }

    public function getDay($sign_num)
    {

    }

    public function create($userId)
    {
        Db::beginTransaction();
        try {
            $yesterday = date("Y-m-d", strtotime("-1 day"));
            $signNum = $this->getSign($userId, $yesterday) + 1;
            $signValue = $this->getSignValue($signNum);
            //添加签到日志
            $this->userSignDao->create([
                'title' => '签到',
                'day' => date('Y-m-d'),
                'user_id' => $userId,
                'sign_num' => $signNum,
                'number' => $signValue,
            ]);

            //添加用户积分
            if ($this->userService->userAccountDao->op($userId, AccountType::INTEGRAL, $signValue, AccountLogType::SIGN) == false) {
                err();
            }
            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            return $this->error($e->getMessage());
        }
        return true;
    }

    /**
     * 获取签到规则
     */
    public function getSignValue($signNum)
    {

        $sign = systemGroupData('sign_day_config');
        $signTotal = $sign->total();
        $sign_num = $signNum % $signTotal;
        $signData = $sign->items();
        return $signData[$sign_num - 1]->sign_integral;

    }
}