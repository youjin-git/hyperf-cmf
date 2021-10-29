<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Controller;

use App\Controller\AbstractController;
use App\Model\Admin;
use App\Model\Order;
use App\Model\SystemConfigValue;
use App\Model\User;


use App\Service\OrderService;
use App\Service\Pay\AliPay;
use App\Service\Pay\PayFactory;
use App\Service\UserService;
use Endroid\QrCode\Writer\PngWriter;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Gregwar\Captcha\CaptchaBuilder;

use Endroid\QrCode\QrCode;
use App\Middleware\CheckLoginMiddleware;
use Hyperf\Apidog\Annotation\ApiController;
use Hyperf\Apidog\Annotation\PostApi;
use Hyperf\Apidog\Annotation\GetApi;
use Hyperf\Apidog\Annotation\FormData;
use Hyperf\Utils\Context;


/**
 * @Middleware(CheckLoginMiddleware::class)
 * @ApiController(tag="支付管理",prefix="pay",description="")
 */
class PayController extends AbstractController
{

    /**
     * @Inject()
     * @var OrderService
     */
    protected $orderServer;

    /**
     * @PostApi(path="order", description="支付宝")
     * @GetApi(path="order", description="支付宝")
     * @FormData(key="id|订单id", rule="required")
     * @FormData(key="openid|openid", rule="")
     * @FormData(key="pay_type|支付方式", rule="required")
     */
    public function order()
    {
        $params = Context::get('validator.data');
        p($params);
        $uid = Context::get('uid');

        //查询订单价格
        $orderInfo = $this->orderModel->where('id', $params['id'])->first() ?: err('订单不存在');
        if ($orderInfo['status'] !== 0) {
            err('该订单已经支付或者取消了');
        }
        $pay_type = $params['pay_type'];
        p($pay_type);
//            p(AliPay::class);
//            p('App\\Service\\Pay\\'.$pay_type);
//            p(App\Service\Pay\AliPay::class);
//             make('App\\Service\\Pay\\'.$pay_type)->pay();
//             make(App\Service\Pay\AliPay::class)->pay();
//            p(AliPay::class);
        if ($pay_type == 'BalancePay') {
            $this->orderServer->handelBybalance($params['id'], $uid);
        } else {
            /* @var $payFactory AliPay */
            $payFactory = PayFactory::create($pay_type);
            //$orderInfo->price
            $payFactory->setPrice(0.01);
            $payFactory->setTitle($orderInfo->id . '代缴');
            $payFactory->setOrderSn($orderInfo->id);
            $payFactory->setOpenid($params['openid']);
            $payFactory->setNotifyUrl($pay_type == 'AliPay' ? '/notify/zfb' : '/notify/wx');
            $url = $payFactory->exec();
            succ($url);
        }

//               $payFactory->set

//            err();
    }

    /**
     * @GetApi(path="h5", description="微信二维码支付")
     * @PostApi(path="h5", description="微信二维码支付")
     */
    public function h5()
    {
        $price = 1;
        $params = new \Yurun\PaySDK\Weixin\Params\PublicParams;
        $pay_config = c(['wx_app_id', 'wx_mch_id', 'wx_key']);
        p($pay_config);
        $params->appID = 'wx8006fee89cbd4b6f';
//            $params->mch_id = $pay_config['wx_mch_id'];
        $params->mch_id = '1606024094';
//            $params->key =$pay_config['wx_key'];
        $params->key = 'JEp6Ln3CD36N7BlBKHi7zA5jxoAhnxmM';
        $pay = new \Yurun\PaySDK\Weixin\SDK($params);
        $request = new \Yurun\PaySDK\Weixin\H5\Params\Pay\Request;
        $request->body = '续费会员'; // 商品描述
        $request->out_trade_no = '1111111111111'; // 订单号
        $request->total_fee = $price; // 订单总金额，单位为：分
        $request->spbill_create_ip = '127.0.0.1'; // 客户端ip
        $request->notify_url = c('site_url') . '/notify/wx'; // 异步通知地址
        $result = $pay->execute($request);
        dump($result);
        dump(111);
        if ($pay->checkResult()) {
            dump($result['mweb_url']);
            // 跳转支付界面
            header('Location: ' . $result['mweb_url']);
        } else {
            var_dump($pay->getErrorCode() . ':' . $pay->getError());
        }

    }

    /**
     * @PostApi(path="qr_code", description="微信二维码支付")
     * @FormData(key="id|id", rule="")
     * @FormData(key="price|价格", rule="")
     */
    public function qr_code()
    {
        $price = 1;
        $params = new \Yurun\PaySDK\Weixin\Params\PublicParams;
        $pay_config = c(['wx_app_id', 'wx_mch_id', 'wx_key']);
        p($pay_config);
        $params->appID = $pay_config['wx_app_id'];
        $params->mch_id = $pay_config['wx_mch_id'];
        $params->key = $pay_config['wx_key'];
        $pay = new \Yurun\PaySDK\Weixin\SDK($params);
        $request = new \Yurun\PaySDK\Weixin\Native\Params\Pay\Request;
        $request->body = '续费会员'; // 商品描述
        $request->out_trade_no = '1111111111111'; // 订单号
        $request->total_fee = $price; // 订单总金额，单位为：分
        $request->spbill_create_ip = '127.0.0.1'; // 客户端ip
        $request->notify_url = c('site_url') . '/notify/wx'; // 异步通知地址
        $result = $pay->execute($request);
        $shortUrl = $result['code_url'];
        var_dump($result, $shortUrl);
        $qrCode = new QrCode($shortUrl);
        p($qrCode);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        return $result->getDataUri();
    }


    /**
     * @PostApi(path="jsapi", description="微信jsapi支付")
     */
    public function jsapi()
    {
        $price = 1;
        $params = new \Yurun\PaySDK\Weixin\Params\PublicParams;
        $pay_config = c(['wx_app_id', 'wx_mch_id', 'wx_key']);
        p($pay_config);
        $params->appID = $pay_config['wx_app_id'];
        $params->mch_id = $pay_config['wx_mch_id'];
        $params->key = $pay_config['wx_key'];
        $pay = new \Yurun\PaySDK\Weixin\SDK($params);
        $request = new \Yurun\PaySDK\Weixin\JSAPI\Params\Pay\Request;

        $request->body = '续费会员'; // 商品描述
        $request->out_trade_no = '1111111111111'; // 订单号
        $request->total_fee = $price; // 订单总金额，单位为：分
        $request->spbill_create_ip = '127.0.0.1'; // 客户端ip
        $request->notify_url = c('site_url') . '/notify/wx'; // 异步通知地址
        $request->openid = '111111111'; // 必须设置openid
        $result = $pay->execute($request);
        var_dump('result:', $result);
        var_dump('success:', $pay->checkResult());
        var_dump('error:', $pay->getError(), 'error_code:', $pay->getErrorCode());
        if ($pay->checkResult()) {
            // 跳转支付界面
            header('Location: ' . $result['mweb_url']);
        } else {
            var_dump($pay->getErrorCode() . ':' . $pay->getError());
        }

    }


    /**
     * @PostApi(path="zfb_h5", description="支付宝h5支付")
     * @GetApi(path="zfb_h5", description="支付宝h5支付")
     */
    public function zfb_h5()
    {
        $price = 1;
        $params = new \Yurun\PaySDK\AlipayApp\Params\PublicParams;

        $pay_config = c(['wx_app_id', 'wx_mch_id', 'wx_key']);
        p($pay_config);


//MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAkgEPh7Vo6rJ4yhh4ls47YRCACMJT0/GdWBUxOtj2NN2L4NIV3c16Q4FAl5AXM83k/7dh/Qi1UxoggbbZAJoKJItsXU9ra0qaoDvDGRqCR707nahnTL2+bV9jvBkOMivAR87RnaPIYkZ1rNHXEm6BJr9DQ6LZqIG/KVG8JKFKKVbFqgqbsNClc3dSYLH/5yX0fLBpBhVpzppo8iUXYabUBNH6/N42EysP/W+nJYeJXbk9IutlrsJY7p4BMSFwcxRmZEu6gXsT1D5XfNv4FnLvl3kXZPiDdVrKsAJlp+uSxvbI9yghIifNNfUyTScfjp5bEEJADvVFI0jhSxq8zkO4AwIDAQAB


        $params->appID = '2021002125600973';
        $params->appPrivateKey = "MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCSAQ+HtWjqsnjKGHiWzjthEIAIwlPT8Z1YFTE62PY03Yvg0hXdzXpDgUCXkBczzeT/t2H9CLVTGiCBttkAmgoki2xdT2trSpqgO8MZGoJHvTudqGdMvb5tX2O8GQ4yK8BHztGdo8hiRnWs0dcSboEmv0NDotmogb8pUbwkoUopVsWqCpuw0KVzd1Jgsf/nJfR8sGkGFWnOmmjyJRdhptQE0fr83jYTKw/9b6clh4lduT0i62WuwljungExIXBzFGZkS7qBexPUPld82/gWcu+XeRdk+IN1WsqwAmWn65LG9sj3KCEiJ8019TJNJx+OnlsQQkAO9UUjSOFLGrzOQ7gDAgMBAAECggEAGLuxQ84Jbei56ZJnqzRYfsLqzZEN3lOJ0ggVBOEIJEB7l6Q/LAnI8nKM3J/+Ljps1pzcLp8xCFjetNqivCVcHoC35L61dcF3nDlDfZcuBrUZykVi6m8iOSj1nkGoU/txYTi57bh3E57YBGSvyGGL42J3JJgLwy3nkjpUYugnxgS5GG/Xe3uSIOyHbhMRfAl6VUW+NF+Ct5mthr9ViJR5CMe4B50NgUnJs0UtzX/2GgQZ/Js2180Eb+AJkkNOVu3a0sYaM3VdF06eTazxaZDOGrudnLkJTSfUKVDo5sEq795L/zeQQHgmjeg6qu8QucyEumJ/RwPep7PAdMrN2t71MQKBgQDZSGEy3CxW7AN5HQ5rBBPAISmV6QK1bzZWw2eho8sUrue+Y+HccGUbGx78uVy3/qzn+QNZ7gWWY/lqi+Q1tvzUOTuWRy7YpnfS9OvtcDhztsX/I/IxDKYYxbvWOovwOyVEwcVCRQlwBZiZXgrJj9CEKUne+NnuQgds4RFDgIqE7wKBgQCsBThUmbmfRR61AG8QRdVSCkPrdgp4cKE+TrnbM+t7g3ZHILtMWJYEco0lxaQpu8lSy50pzFy8tcxa0uAmWGGA59kZuRXwFWLGGpBGowqTOVgwRgldeQ01+FzsIb0fdJe74krDYHPvYY0lbZ42J3OHVAK3qSZqTi00kxlSzX9GLQKBgQDUhm07+AsOgg7rmbYOipBe7pDkZmMV38+AkeR9P95VPrbBjKTQnsbl+mMWwp+kAGBTDUdv4NZqQSMmSepPd8pYMhrMZJe3pSuylYlmVsuAsxd69UfhHgQgWtfdNKrHsLJFTFqK9UfD4FVgPZHDkP1dJ51vkGxpFQB6SLJAGixi1QKBgEkGKkSXwZngMMnrtjo1OvqJyw1w3b8FKR4XM4TfhI65XwOQ3JHrZTvcGRk4zpi81BkmwMAWtiOSFX9U17zPdgzP33SxAqQZoAWzDx49ZmbbSBmuEZmxoq8PkPrY070/tI4Y4V4RWwX65n1GDIy2sK95NXIcB2fHah9QdsaU3/u9AoGBAITd5HZVnDrPrg0idWELO8Smkz2uqgFFQAjd8V6HoYQQrevSrJbt4W+Pibt8ma1Jq8Z8jVQ/g/wHHFxokS9W/eYp/Mje11I+UaXp5F3QQWNHhzfDb66IBmXjcQfiky7TzLwNsIMdHUp1R3OCWAcuYyoaeAAcUFqyMlwoGizPpPEC";
//支付宝公钥
        $params->appPublicKey = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAi0R5/4w4BRd2faUf74d/AxEsFK5wNuCbAkGizzwjT6+pe8LHfegimOGjJODMsmVPE+VpSxopAa9zF47ao2aZDaekw6OtySxne6vubfNuADv+C6vfZpbrX9efWd8piePetpZ7jD4dz0M8A9g2/yfdp+G6LEDJnStI/M6pF4B8iviGnCw6wKI7HHl2xxaBWCNmFRiYgZcmcw6RNLuIxFef/+6boTb4BVZpff3ZnSIqyIGLU5BHro1BVfA/iGkbmNzIdHrRzTWNf6/c9PCggKJgCjTO2IWSwpLUBWsgDgN/IlDuuqz6Q6o5oQ1UZGI5yc6hAFALSZEBaq0ktSZgyUtkWQIDAQAB";

        $pay = new \Yurun\PaySDK\AlipayApp\SDK($params);
        $request = new \Yurun\PaySDK\AlipayApp\Wap\Params\Pay\Request;

        $request->businessParams->subject = '续费会员'; // 商品描述
        $request->businessParams->out_trade_no = time(); // 订单号
        $request->businessParams->total_amount = 0.01; // 订单总金额，单位为：分
        $request->notify_url = c('site_url') . '/notify/zfb'; // 异步通知地址
        p($request);
        $pay->prepareExecute($request, $url);


    }


}
