<?php


namespace App\Service;


use App\Model\User\UserAccount;
use App\Model\User\UserAccountLog;
use Hyperf\Cache\Annotation\Cacheable;
use Hyperf\Database\Model\Builder;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Guzzle\ClientFactory;

class AccountLogService extends BaseService
{
    /**
     * @Inject()
     * @var UserAccountLog
     */
    public $baseModel;


    public function makeWith(): array
    {

    }

    public function makeWhere(Builder $query, array $params)
    {
        if (isset($params['user_id']) && $params['user_id']) {
            $query->where('user_id', $params['user_id']);
        }
    }

    public function find(int $userId, int $type)
    {

    }

}