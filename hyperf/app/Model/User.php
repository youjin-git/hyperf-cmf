<?php
declare (strict_types=1);

namespace App\Model;

use App\Model\Model;
use Hyperf\Database\Model\Events\Saving;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Redis\Redis;
use Hyperf\Database\Model\SoftDeletes;

class User extends Model
{
    use SoftDeletes;

    /**
     * @Inject()
     * @var Redis
     */
    protected $redis;
    
    protected $table = 'user';
    protected $primaryKey = 'id';
    protected $token_pre = 'user:';
    protected $fillable = [
        'status',
        'openid',
        'nickname',//昵称
        'real_name',
        'icon',//头像
        'phone', //手机号码
        'password', //密码
        'province', //省
        'city', //市
        'area', //区
        'address', //公司地址
    ];


    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = md5($value);
    }


    public function get_token($uid)
    {
        //生成随机树
        $token = time() . rand(10000, 9999999) . $uid;
        dump($this->token_pre . $token);

        if ($this->redis->set($this->token_pre . $token, $uid)) {

        } else {
            err('获取token失败');
        }

        return $token;
    }

    public function check_token($token)
    {

        if (!$this->redis->exists($this->token_pre . $token)) {
            return false;
        }
        $uid = $this->redis->get($this->token_pre . $token);
        return $uid;
    }

    public function insuredPriceConfig()
    {
        return $this->hasOne(InsuredPriceConfig::class, 'id', 'insured_price_config_id');
    }
}
