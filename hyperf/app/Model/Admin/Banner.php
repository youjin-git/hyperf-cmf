<?php
declare (strict_types=1);
namespace App\Model\Admin;

use App\Model\Model;
use App\Model\System\SystemFile;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Redis\Redis;
use Hyperf\Snowflake\Concern\Snowflake;
use Hyperf\Database\Model\SoftDeletes;


class Banner extends Model
{


    /**
     * @Inject()
     * @var Redis
     */
    protected $redis;
    /**
     * @Inject()
     * @var File
     */
    protected $file;
    protected $table = 'banner';

    protected $fillable = [
        'id',
        'title',
        'image',
        'link',
    ];

    public static $type = [
            1=>'公告通知',
            2=>'系统帮助'
    ];


    public function getCreateTimeFormatAttribute($value)
    {
        return  $this->getAttribute('create_time')->toDateTimeString();
    }


    public function imagePath(){
        return $this->hasOne(SystemFile::class,'id','image')->withDefault(function(){
            return App(SystemFile::class)->where('suffix','jpg')->first();
        });
    }

    public function getIdAttribute($value)
    {
        return (string)$value;
    }


}
