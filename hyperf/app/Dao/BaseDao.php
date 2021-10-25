<?php


namespace App\Dao;

use Hyperf\Database\Model\Builder;
use Hyperf\Utils\Context;

/**
 * @author: zwc
 * @mixin static|Builder
 */
abstract class BaseDao
{

    /**
     * @var Builder
     */
    protected $baseDao;

    protected $baseMethods = [];

    protected $OrderColumn = 'create_time';

    protected $params = [];

    protected $OrderDirection = 'desc';

    public function verify($field, $value = '')
    {
        if (isset($this->params[$field])) {
            if ($this->params[$field] !== $value) {
                return true;
            }
        }
        return false;
    }

    public function setBaseMethods($method, $params)
    {
        return $this->baseMethods[] = [$method, $params];
    }

    public function __construct()
    {
        $modelClass = str_replace(['\Dao', 'Dao'], ['\Model', ''], get_class($this));

        $this->baseDao = App($modelClass);;
    }

    public function getModel($whereParams = [], $withParams = [])
    {
//        $whereParams && $this->DaoWhere($whereParams);
//        $this->DaoWith($withParams);
//        $baseDao = $this->baseDao;
        return $this->baseDao;
    }

    protected function getNewModel()
    {
        return $this->baseDao = $this->baseDao->newQuery();
    }

    public function error($message = '')
    {
        Context::set('error_msg', $message);
        $this->message = $message;
        return false;
    }

    public function getError()
    {
        return $this->message;
    }


    public function CallWhere($params)
    {
        return function ($query) use ($params) {
            $this->MakeWhere($query, $params);
        };
    }


    abstract public function MakeWhere(Builder $query, $params);

    abstract public function MakeWith(): array;


//    /**
//     * @author: zwc
//     * @time: 2021/6/9 16:18
//     */
//    protected function DaoWhere($params)
//    {
//        $this->baseDao = $this->baseDao->where($this->CallWhere($params));
//        return $this;
//    }

//    /**
//     * @author: zwc
//     * @time: 2021/7/3 13:51
//     */
//    protected function DaoWith(array $withParams = [])
//    {
//        $this->baseDao = $this->baseDao->with($withParams ?: $this->MakeWith());
//        return $this;
//    }

    public function DaoWhere($params = [])
    {
        $this->params = $params;
        $this->setBaseMethods('DaoWhere', $params);
        return $this;
    }

    public function DaoWith($params = [])
    {
        $this->setBaseMethods('DaoWith', $params);
        return $this;
    }

    public function DaoOrder($params = [])
    {
        $this->setBaseMethods('DaoOrder', $params);
        return $this;
    }

    protected function DaoOrderScope(Builder $query, $params)
    {

        if (method_exists($this, 'MakeOrder') && $this->MakeOrder()) {
            $params = $this->MakeOrder();
            $query->orderBy($params[0] ?? $this->OrderColumn, $params[1] ?? $this->OrderDirection);
        } else {
            $query->orderBy($this->OrderColumn, $this->OrderDirection);
        }
        return $this;
    }

    protected function DaoWhereScope($query, $params)
    {
        $query->where($this->CallWhere($params));
        return $this;
    }

    protected function DaoWithScope($query, array $withParams = [])
    {
        $query->with($withParams ?: $this->MakeWith());
        return $this;
    }

    public function __call($method, $parameters)
    {
        if (strpos($method, 'Dao') !== false) {
            $this->setBaseMethods($method, $parameters);
            return $this;
        }
        return tap($this->getModel()->newQuery(), function ($query) {
            foreach ($this->baseMethods as [$method, $params]) {
                $this->{$method . 'Scope'}($query, $params);
            }
            $this->baseMethods = [];
        })->{$method}(...$parameters);
    }
}