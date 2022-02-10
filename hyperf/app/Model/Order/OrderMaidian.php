<?php

namespace App\Model\Order;
use App\Model\Maidian;
use App\Model\Model;



class OrderMaidian extends Model
{
    protected $table = 'order_maidian';

    protected $fillable = [
        'name',
        'maidian_id',
        'order_id',
        'nums',
    ];

    public function Maidian(){
        return $this->hasOne(Maidian::class,'id','maidian_id');
    }
}
