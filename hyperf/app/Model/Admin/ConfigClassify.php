<?php
declare (strict_types=1);
namespace App\Model\Admin;

use App\Model\Model;
use Hyperf\Redis\Redis;

class ConfigClassify extends Model
{
    protected $table = 'system_config_classify';
}