<?php


namespace App\Dao\User;


use App\Dao\BaseDao;
use Hyperf\Database\Model\Builder;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;

class UserAccountDao extends BaseDao
{
    /**
     * @Inject()
     * @var UserAccountLogDao
     */
    protected $userAccountLogDao;

    public function MakeWith(): array
    {
        return [];
    }

    public function MakeWhere(Builder $query, $params)
    {
        // TODO: Implement MakeWhere() method.
    }


    public function find($userId, $type)
    {
        return $this->lock(true)->firstOrCreate(['user_id' => $userId, 'type' => $type]);
    }

    /**
     * 操作余额
     */
    public function Op(int $userId, int $accountType, int $value, int $logType)
    {

        $this->find($userId, $accountType);

        if ($value === 0) {
            return true;
        }

        Db::beginTransaction();
        try {
            $accountLogObj = $this->find($userId, $accountType);
            if ($value < 0) {
                if ($accountLogObj->value + $value < 0) {
                    err('积分不足');
                }
            }
            $accountLogObj->value += $value;
            $accountLogObj->save();
            $this->userAccountLogDao->create([
                'user_id' => $userId,
                'account_type' => $accountType,
                'type' => $logType,
                'value' => $value,
                'after_value' => $accountLogObj->value,
            ]);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            return $this->error($e->getMessage());
        }
        return true;
    }


}