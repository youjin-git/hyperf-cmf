<?php
declare (strict_types=1);
namespace App\Model\Admin;

use App\Model\Model;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\Redis\Redis;


class Config extends Model
{
    protected $table = 'system_config';
    protected $fillable = [
      'classify_id',
      'name',
      'key',
        'value',
      'type',
      'rule',
      'required',
        'info',
        'status',
        'sort',
    ];

}