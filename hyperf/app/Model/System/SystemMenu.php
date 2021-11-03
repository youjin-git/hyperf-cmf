<?php
declare (strict_types=1);

namespace App\Model\System;

use App\Model\Model;
use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Redis\Redis;

/**
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
        'color',
        'title',
        'hidden',
        'active',
        'type',
        'component',
    ];

    public function MakeWhere(Builder $query, $params)
    {
        $this->verify('id', function ($id) use ($query) {
            $query->where('id', $id);
        });
    }

    public function setPathAttribute($path)
    {
        return $this->attributes['path'] = empty($path) ? '/' : $path;
    }


}
