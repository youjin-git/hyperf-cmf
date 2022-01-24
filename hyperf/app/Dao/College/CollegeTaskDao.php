<?php

namespace App\Dao\College;
use App\Model\College\CollegeTask;
use \Yj\Daos\BaseDao;
use Hyperf\Database\Model\Builder;
use Yj\Daos\Verify;

/**
 * @var CollegeTask
 */
class CollegeTaskDao extends BaseDao
{

    public function DaoWhere(array $params)
    {
        return $this->getDaoQuery($params, function (Verify $verify) {
            $verify('id', function (Builder $query, $id) {
                $query->where('id', $id);
            });
            $verify('username', function (Builder $query, $title) {
                $query->where('username','like', "%{$title}%");
            });
        });
    }

    public function add($params){
       $params = collect($params);
       return $this->create($params->toArray());
    }

    public function lists($params)
    {
        return $this->paginate();
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