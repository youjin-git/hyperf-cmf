<?php
declare (strict_types=1);
namespace App\Model;

use App\Model\Model;
use Hyperf\Redis\Redis;
use Hyperf\Snowflake\Concern\Snowflake;

class OrderDetail extends Model
{
    use Snowflake;
    protected $table = 'order_detail';

    protected $fillable = [
        'order_id',
        'month',
        'is_repair',
        'service_price',
        'advance_payment_price',
        'social_security_base_price',
        'social_security_rate',
        'social_security_price',
        'fund_base_price',
        'fund_price',
        'fund_rate',
        'price',
        'insured_config_id'
    ];

    public function getWasteDataAttribute($value){
        p(11);
        return json_decode($value,true);
    }

    public function getIdAttribute($value){
        return  (string)$value;
    }

}