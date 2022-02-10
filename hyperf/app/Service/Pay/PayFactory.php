<?php


namespace App\Service\Pay;


class PayFactory
{
    private $payTypes = ['AliPay'];

    public static function create($payType){
            $class_name = "\\App\\Service\\Pay\\".$payType;
            return make($class_name);
    }

    /**
     * @return WxPay
     */
    public static function wechat(){
        return make(WxPay::class);
    }
}