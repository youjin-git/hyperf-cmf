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
use Hyperf\Utils\Context;
use function Swoole\Coroutine\Http\request;


class Model extends BaseModel implements CacheableInterface
{

    use Cacheable;

    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
    const DELETED_AT = 'delete_time';

//    protected $dateFormat = 'U';

    public function booted()
    {
        //重写更新
//        ($limit = di()->get(RequestInterface::class)->input('limit')) && $this->setPerPage($limit);
    }

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
        if (array_key_exists($attribute, $this->attributes)) {
            return $callback($this->attributes[$attribute]);
        } else {
            return $empty;
        }
    }

//
//    public function scopeWhere2query($query = null, $where=[]){
//
//
//        $query = $query ?? $this->newQuery();
//        if (!$where) {
//            return $query;
//        }
//        if(is_callable($where)){
//            return $query->where($where);
//        }
//        $boolean = 'and';
//
//
//        foreach($where as $key => $item){
//            foreach ($item as $op => $val) {
//
//                if ($op == 'between') {
//                    $query->whereBetween($key, $val, $boolean);
//                    continue;
//                }
//
//                if ($op == 'like') {
//                    $query->where($key, 'like', $val, $boolean);
//                    continue;
//                }
//
//                $query->where($key, $op, $val, $boolean);
//            }
//        }
//
//        return $query;
//    }


}
