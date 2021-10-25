<?php
declare (strict_types=1);
namespace App\Model;

use App\Model\Model;
use Hyperf\Database\Model\Events\Saving;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Redis\Redis;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\Snowflake\Concern\Snowflake;

class UserBalanceLog extends Model
{
    use Snowflake;
    use SoftDeletes;
    /**
     * @Inject()
     * @var Redis
     */
    protected $redis;
    protected $table = 'user_balance_log';


    protected $fillable = [
        'send_time',
        'uid',
        'title',
        'type',
        'number',
        'balance',
        'mark',
        'link_id',
    ];



}
