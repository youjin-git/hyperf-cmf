<?php

namespace Yj\Daos;


use App\Model\Model;
use Hyperf\Database\Model\Builder;
use Hyperf\Utils\Collection;

/**
 * @Notes: []
 * @User: zwc
 * @Date: 2022/1/14 13:56
 * @mixin Builder|Model
 */
class BaseDao
{

    use Query;

    protected $baseDao;

    protected $model = null;

    public function page($isPage = true)
    {
        _SetNotPage($isPage);
        return $this;
    }

    public function getOperator($value,$callback){
        if(is_string($value) && in_array($value[0],['>','<','='])){
            return $callback($value[0],ltrim($value[0],$value));
        }
    }


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
     * @return $this
     */
    public function query(){
        return (new static());
    }

    /**
     * @param array $params
     * @param callable|null $callback
     * @return $this
     */
    public function getDaoQuery(Collection|array $params = [], callable $callback = null,$query = true)
    {
        if(is_null($this->daoQuerys) && $query){
            return $this->query()->getDaoQuery($params,$callback,false);
        }
        $daoQuery = app(Verify::class)->init($params);
        if (is_callable($callback)) {
            $callback($daoQuery);
        }
        $this->setDaoQuerys($daoQuery->getQuery());
        return $this;
    }



    public function __call($method, $parameters)
    {
        return tap($this->getModel()->newQuery(), function (Builder $query) {
            if($this->getDaoQuerys())
            /** @var Builder $daoQuery */
            foreach($this->getDaoQuerys() as $daoQuery){
                $query->addNestedWhereQuery($daoQuery->getQuery());
            }
        })->{$method}(...$parameters);
    }


}