<?php


namespace App\Dao\Generator;


use App\Dao\BaseDao;
use App\Model\Generator\GeneratorTable;
use Hyperf\Database\Model\Builder;
use Hyperf\Utils\Collection;

/**
 * @var GeneratorTable
 * @Notes：
 * @author: zwc
 * @time: 2021/8/23 13:43
 * @method self DaoWhere(array $params)
 * @method self DaoWith(array $withParams = [])
 * @method self DaoOrder($params = [])
 */
class GeneratorTableDao extends BaseDao
{
    public function MakeWhere(Builder $query, $params)
    {
        $query->where();
        return $this;
    }

    public function add(Collection $data)
    {
        return $this->create($data->toArray());
    }

    public function lists($params)
    {
        return $this->DaoWhere($params)->paginate();
    }


}