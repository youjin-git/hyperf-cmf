<?php


namespace Yj\Daos;

use App\Model\Model;
use Hyperf\Database\Model\Builder;
use Hyperf\Utils\Arr;
use Hyperf\Utils\Collection;


class Verify
{
    protected $params = [];

    protected $query = null;

    /**
     * Notes:
     * User: zwc
     * Date: 2021/12/31
     * Time: 17:44
     * @return Builder
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param mixed $query
     */
    public function setQuery($query): void
    {
        $this->query = $query;
    }

    /**
     * @return Collection
     */
    public function getParams()
    {
        return $this->params;
    }

    public function setParams($params)
    {
        $this->params = collect($params);
        return $this;
    }

    public function init($params)
    {
        $this->setQuery(Model::query());
        $this->setParams($params);
        return $this;
    }

    public function __invoke($field, $value = null, $callback = true)
    {
        return $this->verify($field, $value, $callback);
    }

    public function field($field)
    {
        return $this;
    }

    public function isnot()
    {

    }

    public function verify($field, $value = null, $callback = true)
    {
        if (is_callable($value)) {
            $callback = $value;
            $value = null;
        }

        if ($this->getParams()->offsetExists($field)) {
            $value = is_null($value) ? [$value] : Arr::wrap($value);
            $val = $this->getParams()->get($field);
            if (false === collect($value)->containsStrict($val)) {
                return (is_callable($callback) ? $callback($this->getQuery(), $this->params[$field]) : $callback);
            }
        }
        return false;
    }



}
