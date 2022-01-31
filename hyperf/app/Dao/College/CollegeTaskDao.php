<?php

namespace App\Dao\College;
use App\Model\College\CollegeTask;
use Hyperf\Utils\Collection;
use \Yj\Daos\BaseDao;
use Hyperf\Database\Model\Builder;
use Yj\Daos\Verify;

/**
 * @var CollegeTask
 */
class CollegeTaskDao extends BaseDao
{

    public function DaoWhere(Collection|array $params)
    {
        return $this->getDaoQuery($params, function (Verify $verify) {
            $verify('id', function (Builder $query, $id) {
                $query->where('id', $id);
            });
            $verify('username', function (Builder $query, $title) {
                $query->where('username','like', "%{$title}%");
            });
            $verify->verify('delivery_id',function(Builder $query,$delivery_id){
                if(is_numeric($delivery_id)){
                    $query->where('delivery_id',$delivery_id);
                }
                $this->getOperator($delivery_id,function ($op,$delivery_id)use($query){
                    $query->where('delivery_id',$op,$delivery_id);
                });
            });
        });
    }

    public function add($params){
       $params = collect($params);
       return $this->create($params->toArray());
    }

    public function lists($params)
    {
        return $this->DaoWhere($params)->with('SystemAdmin')->getList();
    }

    public function detail(int $id)
    {
        return $this->DaoWhere(compact('id'))->first();
    }

    public function edit(mixed $id, \Hyperf\Utils\Collection $data)
    {
        $dao = $this->DaoWhere(compact('id'))->first();
        $dao->fill($data->toArray());
        return $dao->save();
    }

}