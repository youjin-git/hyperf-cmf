<?php

namespace App\Model\Order;
use App\Model\Model;


class Order extends Model
{
    protected $table = 'order';

    protected $fillable = [
        'name',
        'price',
        'status',
        'house_area',
        'tel',
        'house_price',
        'house_type',
        'house_years',
        'is_sysc',
        'type',
        'is_real_person',
        'is_ad',
        'quality',
        'photo_type',
        'house_listing_price',
        'house_renovation',
        'is_accelerate',
    ];

    protected $appends = [
      'status_format',
        'type_format',
    ];

    protected $status_formats = [
      '0'=>'待支付',
      '1'=>'等待提交资料',
      '2'=>'制作中',
      '3'=>'待付尾款',
      '4'=>'待修改',
      '5'=>'完成',
    ];

    protected $type_formats = [
        '1'=>'中介卖家',
        '2'=>'设计装修',
        '3'=>'渠道分销',
    ];
    
    public function getStatusFormatAttribute(){
        return $this->checkAttributes('status',function ($status){
            return $this->status_formats[$status];
        });
    }

    public function getTypeFormatAttribute(){
        return $this->checkAttributes('type',function ($type){
            return $this->type_formats[$type];
        });
    }

    public function OrderChange(){
        return $this->hasOne(OrderChange::class,'order_id','id');
    }
    public function OrderChangeDetail(){
        return $this->hasOne(OrderChange::class,'order_id','id')->where('status',1);
    }

    public function OrderMaidian()
    {
        return $this->hasMany(OrderMaidian::class,'order_id','id');
    }

    public function OrderMaidianDesc()
    {
        return $this->hasMany(OrderMaidianDesc::class,'order_id','id');
    }
}
