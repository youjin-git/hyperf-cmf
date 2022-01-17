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

    /**
     * @Inject()
     * @var Redis
     */
    protected $redis;

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

//    abstract public function MakeWhere(Builder $query, $params);

    public function verify($field, $value = null, $callback = true)
    {
        if (is_callable($value)) {
            $callback = $value;
            $value = null;
        }
        if (!is_array($value)) {
            $value = [$value];
        }
        if (array_key_exists($field, $this->params) && !is_null($this->params[$field])) {
            $val = $this->params[$field];
            $count = count(array_filter($value, function ($item) use ($val, $field) {
                return $item === $val;
            }));
            return $count > 0 ? false : (is_callable($callback) ? $callback($this->params[$field]) : $callback);
        }
        return false;
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
