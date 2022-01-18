<?php

namespace Yj\Daos;


use Hyperf\Database\Model\Builder;
use Hyperf\Utils\Collection;

/**
 * @Notes: []
 * @User: zwc
 * @Date: 2022/1/14 13:56
 * @mixin Builder
 */
class BaseDao
{
    protected $baseDao;

    protected $model = null;

    protected $daoQuerys = [];

    public function __construct()
    {
        $modelClass = str_replace(['\Dao', 'Dao'], ['\Model', ''], get_class($this));
        $this->baseDao = App($modelClass);
    }

//    abstract public function MakeWhere(Builder $query, $params);

    public function getModel()
    {
        return $this->baseDao;
    }


    /**
     * @param array $params
     * @param callable|null $callback
     * @return $this
     */
    public function getDaoQuery(Collection|array $params = [], callable $callback = null)
    {
        $daoQuery = app(Verify::class)->init($params);
        if (is_callable($callback)) {
            $callback($daoQuery);
        }
        $this->daoQuerys[] = $daoQuery->getQuery();
        return $this;
    }



    public function __call($method, $parameters)
    {

        return tap($this->getModel()->newQuery(), function (Builder $query) {
            /** @var Builder $daoQuery */
            foreach($this->daoQuerys as $daoQuery){
                $query->addNestedWhereQuery($daoQuery->getQuery());
            }
        })->{$method}(...$parameters);
    }


}