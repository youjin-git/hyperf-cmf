<?php
declare (strict_types=1);

namespace App\Model\System;

use App\Model\Model;
use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Redis\Redis;

/**
 * @Notes：
 * @author: zwc
 * @time: 2021/10/13 13:18
 * @method self DaoWhere(array $params)
 */
class SystemMenu extends Model
{
    use SoftDeletes;

    /**
     * @Inject()
     * @var Redis
     */
    protected $redis;

    protected $table = 'system_menu';

    protected $fillable = [
        'id',
        'path',
        'name',
        'sort',
        'pid',
        'icon',
    ];

    protected $guarded = [];

    protected $casts = [
        'id' => 'int',
        'status' => 'integer',
        'is_admin' => 'integer',
        'is_default_pass' => 'integer',
        'value' => 'integer',
    ];

    const USER_ENABLE = 1;
    const USER_DISABLE = 0;

    public static $status = [
        self::USER_DISABLE => '禁用',
        self::USER_ENABLE => '启用',
    ];

    public function MakeWhere(Builder $query, $params)
    {

    }

    public function getPathAttribute($value)
    {
        return $value;
    }


    public function getQueryAttribute($value)
    {
        $value && parse_str($value, $value);
        return $value;
    }


    public function getRealnameAttribute($value)
    {
        return $value ?: $this->username;
    }
}
