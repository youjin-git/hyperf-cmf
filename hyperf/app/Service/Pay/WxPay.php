<?php


namespace App\Service\Pay;


class WxPay extends AbstractPay
{
    public function init(){
        //echo 'alipay->pay';

    }
    public function exec()
    {

        $params = new \Yurun\PaySDK\Weixin\Params\PublicParams;
        $pay_config =  systemConfig(['wx_app_id','wx_mch_id','wx_key']);
        $params->appID = $pay_config['wx_app_id'];
        $params->mch_id = $pay_config['wx_mch_id'];
        $params->key =$pay_config['wx_key'];
        $pay = new \Yurun\PaySDK\Weixin\SDK($params);
        $request = new \Yurun\PaySDK\Weixin\JSAPI\Params\Pay\Request;
        $request->body = $this->getOrderSn();; // 商品描述
        $request->out_trade_no = $this->getOrderSn();// 订单号
        $request->total_fee = $this->getPrice()*100; // 订单总金额，单位为：分
        $request->spbill_create_ip = '127.0.0.1'; // 客户端ip
        $request->openid = $this->getOpenid(); // 必须设置openid
        $request->notify_url = systemConfig('site_url').$this->getNotifyUrl();; // 异步通知地址
        dump(systemConfig('site_url').$this->getNotifyUrl());
        $result = $pay->execute($request);
        if($pay->checkResult())
        {
            $request = new \Yurun\PaySDK\Weixin\JSAPI\Params\JSParams\Request;
            $request->prepay_id = $result['prepay_id'];
            $jsapiParams = $pay->execute($request);
            // 最后需要将数据传给js，使用WeixinJSBridge进行支付
            return $jsapiParams;
        } else {
            err($pay->getErrorCode() . ':' . $pay->getError());
        }
//        $price = 1;
//        $params = new \Yurun\PaySDK\Weixin\Params\PublicParams;
//        $pay_config =  c(['wx_app_id','wx_mch_id','wx_key']);
//        p($pay_config);
//        $params->appID = 'wx8006fee89cbd4b6f';
////            $params->mch_id = $pay_config['wx_mch_id'];
//        $params->mch_id = '1606024094';
////            $params->key =$pay_config['wx_key'];
//        $params->key ='JEp6Ln3CD36N7BlBKHi7zA5jxoAhnxmM';
//        $pay = new \Yurun\PaySDK\Weixin\SDK($params);
//        $request = new \Yurun\PaySDK\Weixin\H5\Params\Pay\Request;
//        $request->body = $this->getTitle();// 商品描述
//        $request->out_trade_no = $this->getOrderSn();// 订单号
//        $request->total_fee = $this->getPrice()*100;; // 订单总金额，单位为：分
//
//        $request->spbill_create_ip = '127.0.0.1'; // 客户端ip
//        $request->notify_url = c('site_url').$this->getNotifyUrl();; // 异步通知地址
//        $result = $pay->execute($request);
//
//        if($pay->checkResult())
//        {
//            return $result['mweb_url'];
//            // 跳转支付界面
//        }
//        else
//        {
//            var_dump($pay->getErrorCode() . ':' . $pay->getError());
//        }

    }

}