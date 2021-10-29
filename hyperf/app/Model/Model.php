<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Model;


use App\Ide\ModelIDE;
use Hyperf\Database\ConnectionInterface;

use Hyperf\Database\Model\Builder;
use Hyperf\Database\Query\Expression;
use Hyperf\DbConnection\Db;
use Hyperf\DbConnection\Model\Model as BaseModel;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\ModelCache\Cacheable;
use Hyperf\ModelCache\CacheableInterface;
use Hyperf\Redis\Redis;
use Hyperf\Utils\Collection;
use Hyperf\Utils\Context;
use function Swoole\Coroutine\Http\request;


/**
 * @mixin Builder
 * @property int getList
 * @method static BaseModel | Collection getList($columns = [], $pageName = 'page', $page = null)
 */
class Model extends BaseModel implements CacheableInterface
{

    use Cacheable;

    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
    const DELETED_AT = 'delete_time';

//    protected $dateFormat = 'U';


    public function fillableData($data)
    {
        $fillable = $this->getFillable();
        $data = array_filter($data, function ($key) use ($fillable) {
            return in_array($key, $fillable);
        }, ARRAY_FILTER_USE_KEY);
        return $data;
    }

    protected function error($msg)
    {
        Context::set('error_msg', $msg);
        return false;
    }

    public function getError()
    {
        return Context::get('error_msg');
    }

    protected $params = [];


    public function scopeDaoWhere($query, array $params)
    {
        $this->params = $params;
        $query->where(function ($query) use ($params) {
            $this->MakeWhere($query, $params);
        });
    }

    public function scopeGetList(Builder $query, $columns = ['*'], $pageName = 'page', $page = null)
    {

        if (Context::get(\App\Constants\Context::ISPAGE, true)) {
            $limit = di()->get(RequestInterface::class)->input('limit', config('page.defaultLimit'));
            return $query->paginate($limit, $columns, $pageName, $page);
        } else {
            Context::set(\App\Constants\Context::ISPAGE, false);
            return $query->get($columns);
        }
    }


    public function MakeWhere(Builder $query, $params)
    {
//        $this->verify('nurseid', function ($nurseid) use ($query) {
//            $query->where('nurseid', $nurseid);
//        });
    }


    /**
     * @Inject()
     * @var Redis
     */
    protected $redis;

    public function lists($limit = 15)
    {
        return $this->paginate($limit);
    }


    public function checkAttributes($attribute, $callback, $empty = '')
    {
        if (!is_array($attribute)) {
            $attribute = [$attribute];
        }
//        _Collect($attribute)->isSuccess(function ($item, $key) {
//            return array_key_exists($key, $this->attributes);
//        }, function ($query) {
//            return $query;
//        })
        $newAttribute = collect($attribute)->filter(function ($value) {
            return array_key_exists($value, $this->attributes);
        })->map(function ($key) {
            return $this->attributes[$key];
        });
        if ($newAttribute->count() == count($attribute)) {
            return $callback(...$newAttribute->toArray());
        } else {
            return $empty;
        }
    }


}
