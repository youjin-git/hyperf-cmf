<?php
declare (strict_types=1);
namespace App\Model;

use App\Model\Model;
use Hyperf\Database\Model\Events\Saving;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Redis\Redis;
use Hyperf\Database\Model\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;
    /**
     * @Inject()
     * @var Redis
     */
    protected $redis;
    protected $table = 'project';

    protected $fillable = [
        'name',
        'picture',
        'price',
        'content',
    ];

    protected $statusFormat = [
        0 => '关闭中',
        1 => '正常'
    ];
    protected $appends = ['create_time_format','picture_format','status_format'];

    public function getCreateTimeFormatAttribute($value)
    {
        return  $this->getAttribute('create_time')->toDateTimeString();
    }


//    public function getCreateTimeAttribute($value)
//    {
//        return  $this->getAttribute('create_time')->toDateTimeString();
//    }


    public function getPictureFormatAttribute(){
        return  getFilePath($this->getAttribute('picture'));
    }

    public function getStatusFormatAttribute(){
        return $this->statusFormat[$this->getAttribute('status')];

    }
}
