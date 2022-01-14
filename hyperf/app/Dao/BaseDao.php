<?php


namespace App\Dao;

use App\Model\Model;
use Hyperf\Database\Model\Builder;
use Hyperf\Utils\Context;

/**
 * @author: zwc
 * @mixin static|Builder|Model
 */
abstract class BaseDao
{

    protected $params = [];

    /**
     * @var Builder
     */
    protected $baseDao;

    protected $baseMethods = [];

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


    public function setBaseMethods($method, $params)
    {
        return $this->baseMethods[] = [$method, $params];
    }

    public function __construct()
    {
        $modelClass = str_replace(['\Dao', 'Daos'], ['\Model', ''], get_class($this));
        $this->baseDao = App($modelClass);
    }

//    abstract public function MakeWhere(Builder $query, $params);

    public function getModel()
    {
        return $this->baseDao;
    }

    protected function error($msg)
    {
        Context::set('error_msg', $msg);
        return false;
    }

    protected function getNewModel()
    {
        return $this->baseDao = $this->baseDao->newQuery();
    }


    public function __call($method, $parameters)
    {

        return tap($this->getModel()->newQuery(), function ($query) {
        })->{$method}(...$parameters);
    }
}