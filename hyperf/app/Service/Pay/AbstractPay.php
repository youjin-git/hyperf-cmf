<?php


namespace App\Service\Pay;
//sudo fuser -k -n tcp 9503

abstract class AbstractPay implements PayInterface
{
    private $price; //订单价格
    private $title; //标题
    private $order_sn;//订单号
    private $notify_url;
    private $openid;

    abstract public function init();

    abstract public function exec();

    public function setTitle($title){
        $this->title = $title;
    }

    public function getTitle(){
        return $this->title;
    }
    public function setOpenid($openid){
        $this->openid = $openid;
    }
    public function getOpenid(){
        return $this->openid;
    }
    public function getOrderSn(){
        return $this->order_sn;
    }

    public function setOrderSn($order_sn){
        $this->order_sn = $order_sn;
    }


    public function setPrice($price){
        $this->price = $price;
    }

    public function getPrice(){
        return $this->price;
    }

    public function setNotifyUrl($notify_url){
        $this->notify_url = $notify_url;
    }

    public function getNotifyUrl(){
        return $this->notify_url;
    }

}