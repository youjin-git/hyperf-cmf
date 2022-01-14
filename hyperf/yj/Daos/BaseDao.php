<?php

namespace Yj\Daos;


/**
 * @Notes: []
 * @User: zwc
 * @Date: 2022/1/14 13:56
 */
class BaseDao
{
    protected $baseDao;

    protected $model = null;

    public function getDaoQuery(array $params = [], callable $callback = null)
    {
        $daoQuery = app(Verify::class)->init($params);
        if (is_callable($callback)) {
            $callback($daoQuery);
        }
        $this->daoQuerys[] = $daoQuery->getQuery();
        return $this;
    }
}