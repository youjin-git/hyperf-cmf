<?php
declare (strict_types=1);
namespace App\Model;

use App\Model\Model;
use Hyperf\Redis\Redis;
use Hyperf\Snowflake\Concern\Snowflake;

class LogMysql extends Model
{
    use Snowflake;
    protected $table = 'log_mysql';

    protected $fillable = [
        'sql',
        'exec_time',
        'tables_name'
    ];

    public function getIdAttribute($value){
        return  (string)$value;
    }

}