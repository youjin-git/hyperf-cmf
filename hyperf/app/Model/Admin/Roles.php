<?php
declare (strict_types=1);
namespace App\Model\Admin;

use App\Model\Model;
use Hyperf\Redis\Redis;


class Roles extends Model
{

    protected $table = 'system_role';

    public function getRules($role_id){
            return $this->where('role_id',$role_id)->value('rules');
    }
}