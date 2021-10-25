<?php
declare (strict_types=1);
namespace App\Model\Admin;

use App\Model\Model;
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
        'picture',
        'link',
    ];

    protected $appends = ['create_time_format','picture_format'];
    public static $type = [
            1=>'公告通知',
            2=>'系统帮助'
    ];


    public function getCreateTimeFormatAttribute($value)
    {
        return  $this->getAttribute('create_time')->toDateTimeString();
    }

    public function getPictureFormatAttribute(){
        return  $this->file->getFullPath($this->getAttribute('picture'));
    }

    public function getIdAttribute($value)
    {
        return (string)$value;
    }
    public function getRolesAttribute($value)
    {
        return explode(',', $value);
    }



    public function getUpdateTimeAttribute($value)
    {
        return date('Y-m-d H:i',(int)$value);
    }

    public function get_token($uid)
    {
        //生成随机树
        $token = time() . rand(10000, 9999999) . $uid;
        $this->redis->set($this->token_pre. $token, $uid);
        return $token;
    }

    public function setRolesAttribute($value)
    {
        return implode(',', $value);
    }

    public function getRealnameAttribute($value)
    {
        return $value ?: $this->username;
    }
}
