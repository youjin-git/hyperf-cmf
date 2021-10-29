<?php
declare (strict_types=1);
namespace App\Model;

use App\Model\Model;
use Hyperf\Database\Model\Events\Saving;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Redis\Redis;
use Hyperf\Database\Model\SoftDeletes;

class UserExtract extends Model
{
    use SoftDeletes;
    /**
     * @Inject()
     * @var Redis
     */
    protected $redis;
    protected $table = 'user_extract';
    protected $fillable = [
        'uid',
        'extract_price',
        'balance',
        'mark',
        'status',
    ];

    protected $statusFormat = [
        -1=>'审核拒绝',
        0 => '审核中',
        1 => '审核通过',
        2 =>'已打款',
    ];


    protected $appends = ['status_format'];


    public function getStatusFormatAttribute(){
        return $this->statusFormat[$this->getAttribute('status')];

    }


}
