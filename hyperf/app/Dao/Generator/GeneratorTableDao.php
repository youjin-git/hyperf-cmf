<?php


namespace App\Dao\Generator;


use App\Dao\BaseDao;
use App\Model\Generator\GeneratorTable;
use Hyperf\Database\Model\Builder;

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

    }

}