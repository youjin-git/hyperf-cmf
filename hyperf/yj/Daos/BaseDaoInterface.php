<?php


namespace Yj\Daos;

use App\Daos\Store\StoreInfoDao;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;


interface BaseDaoInterface
{

    public function MakeWhere(Builder $query, $params);

    public function MakeWith(): array;


}
