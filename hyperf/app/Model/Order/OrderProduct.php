<?php
declare (strict_types=1);

namespace App\Model\Order;

use App\Model\Model;
use App\Model\Product\Product;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\Redis\Redis;
use Hyperf\Snowflake\Concern\Snowflake;


class OrderProduct extends Model
{
    use SoftDeletes;
    use Snowflake;

    protected $table = 'order_product';

    protected $fillable = [
        'user_id',
        'product_id',
        'product_info',
        'integral',
        'order_id',
        'num',
        'status',
    ];

    public function insured()
    {
        return $this->hasOne(Insured::class, 'id', 'insured_id');
    }

    public function orderDetail()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }

    public function getIdAttribute($value)
    {
        return (string)$value;
    }

    public function getProductIdAttribute($value)
    {
        return (string)$value;
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function getInsuredIdAttribute($value)
    {
        return (string)$value;
    }

}