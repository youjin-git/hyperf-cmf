<?php
declare (strict_types=1);

namespace App\Model\Order;

use App\Constants\OrderStatus;
use App\Model\Model;
use App\Model\User;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\Redis\Redis;
use Hyperf\Snowflake\Concern\Snowflake;


class Order extends Model
{
    use SoftDeletes;
    use Snowflake;

    protected $table = 'order';

    protected $appends = [
        'status_name'
    ];

    protected $fillable = [
        'user_id',
        'product_id',
        'integral',
        'status',
        'remark'
    ];


    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function orderProduct()
    {
        return $this->hasMany(OrderProduct::class, 'order_id', 'id');
    }


    public function getStatusNameAttribute($value)
    {
        return $this->checkAttributes('status', function ($value) {
            return OrderStatus::getMessage($value);
        });
    }

    public function getIdAttribute($value)
    {
        return (string)$value;
    }

    public function getInsuredIdAttribute($value)
    {
        return (string)$value;
    }

}