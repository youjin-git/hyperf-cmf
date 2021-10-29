<?php


namespace App\Service\Pay;


class AliPay extends AbstractPay
{
    public function init(){
        //echo 'alipay->pay';

    }
    public function exec()
    {
        // TODO: Implement exec() method.

        $price = 1;
        $params = new \Yurun\PaySDK\AlipayApp\Params\PublicParams;

        $pay_config =  c(['zfb_app_id','zfb_private_key','zfb_public_key']);
        p($pay_config);

        $params->appID = $pay_config['zfb_app_id'];
        $params->appPrivateKey=$pay_config['zfb_private_key'];
        $params->appPublicKey=$pay_config['zfb_public_key'];

        $pay = new \Yurun\PaySDK\AlipayApp\SDK($params);
        $request = new \Yurun\PaySDK\AlipayApp\Wap\Params\Pay\Request;

        $request->businessParams->subject = $this->getTitle(); // 商品描述
        $request->businessParams->out_trade_no = $this->getOrderSn(); // 订单号
        $request->businessParams->total_amount  = $this->getPrice(); // 订单总金额，单位为：分
        $request->notify_url = c('site_url').$this->getNotifyUrl(); // 异步通知地址

        $pay->prepareExecute($request, $url);
        return $url;
    }

}