<?php
namespace App\Service\Pay;

interface PayInterface
{
    public function init();
    //支付行为
    public function exec();
}