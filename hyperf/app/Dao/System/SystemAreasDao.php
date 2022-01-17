<?php


namespace App\Dao\System;



use App\Model\System\SystemAreas;
use App\Model\User;
use Hyperf\Database\Model\Builder;
use Yj\Daos\Verify;


/**
 * @var SystemAreas
 */
class SystemAreasDao extends \Yj\Daos\BaseDao
{

    public function DaoWhere(array $params)
    {
//        return $this->getDaoQuery($params, function (Verify $verify) {
//            $verify('id', function (Builder $query, $id) {
//                $query->where('id', $id);
//            });
//            $verify('title', function (Builder $query, $title) {
//                $query->where('title', $title);
//            });
//        });
    }

    public function DaoLevel(int $level){
        return $this->getDaoQuery(compact('level'),function (Verify $verify){
                $verify('level',function(Builder $query, $level){
                        $query->where('level','<=',$level);
                });
        });
    }

    public function getListsByLevel($level){

        return $this->DaoLevel($level)->get();
    }


    public function lists($params){
        return $this->DaoLevelWhere($params)->get();
    }





}