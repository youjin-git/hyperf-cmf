<?php
declare (strict_types=1);
namespace App\Model;

use App\Model\Model;
use Hyperf\Database\Model\Events\Saving;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Redis\Redis;
use Hyperf\Database\Model\SoftDeletes;

class UserBill extends Model
{
    use SoftDeletes;
    /**
     * @Inject()
     * @var Redis
     */
    protected $redis;
    protected $table = 'user_bill';
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

    protected $typeFormat = [
        1=>'返现',
        2=>'提现',
        3=>'提现拒绝',
    ];

    protected $appends = ['type_format'];

    public function getTypeFormatAttribute(){
        return $this->typeFormat[$this->getAttribute('type')];
    }



}
