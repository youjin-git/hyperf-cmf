<?php


namespace App\Service;


use App\Dao\User\UserAccountLogDao;
use App\Model\User\UserAccount;
use App\Model\User\UserAccountLog;
use Hyperf\Cache\Annotation\Cacheable;
use Hyperf\Database\Model\Builder;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Guzzle\ClientFactory;

class AccountService extends BaseService
{
    /**
     * @Inject()
     * @var UserAccount
     */
    public $baseModel;

    /**
     * @Inject()
     * @var UserAccountLogDao
     */
    protected $accountLogDao;


    public function makeWith(): array
    {

    }

    public function makeWhere(Builder $query, array $params)
    {
        if (isset($params['user_id']) && $params['user_id']) {
            $query->where('user_id', $params['user_id']);
        }
    }

    public function add($data)
    {
        $data = $this->getModel()->create($data);
        return $data;
    }



}