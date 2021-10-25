<?php
declare (strict_types=1);
namespace App\Model\Hy;

use App\Model\Model;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Redis\Redis;
use Hyperf\Snowflake\Concern\Snowflake;
use Hyperf\Database\Model\SoftDeletes;


class News extends Model
{
    /**
     * @Inject()
     * @var Redis
     */
    protected $redis;
    protected $table = 'news';

    protected $fillable = [
        'id',
        'title',
        'type',
        'content',
    ];
    protected $appends = ['create_time_format','type_format','content_format'];
    public static $type = [
            1=>'公告通知',
    ];

    public function getLists(){
        $config = [];
        $lists = $this->orderBy('create_time','desc')->get();
        return compact('config','lists');
    }

    public function getTypeFormatAttribute($value){
        return self::$type[$this->getAttribute('type')];
    }
    public function getContentFormatAttribute(){
        return htmlspecialchars_decode($this->getAttribute('content'));
    }
    public function getCreateTimeFormatAttribute($value)
    {


        return  $this->getAttribute('create_time')->toDateTimeString();
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
