<?php
declare (strict_types=1);
namespace App\Model\Admin;

use App\Model\Model;
use Hyperf\Redis\Redis;
use Hyperf\Snowflake\Concern\Snowflake;

class Order extends Model
{
    use Snowflake;
    protected $table = 'order';

    protected $fillable = [
        'user_id',
        'waste_data',
        'remark',
    ];

    public function getWasteDataAttribute($value){
        p(11);
        return json_decode($value,true);
    }

    public function getIdAttribute($value){
            return  (string)$value;
    }

}