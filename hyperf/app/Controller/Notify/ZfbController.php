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

namespace App\Controller\Notify;

use App\Controller\AbstractController;
use App\Model\Admin;
use App\Model\Order;
use App\Model\SystemConfigValue;
use App\Model\User;


use App\Model\UserBalance;
use App\Model\UserBalanceLog;
use App\Service\OrderService;
use App\Service\Pay\AliPay;
use App\Service\Pay\PayFactory;
use App\Service\UserService;
use Endroid\QrCode\Writer\PngWriter;
use Hyperf\DbConnection\Db;
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

/**
 * @ApiController(tag="支付回调",prefix="notify/zfb",description="")
 */
class ZfbController extends AbstractController
{

    /**
     * @Inject
     * @var OrderService
     */
    private $orderServer;

    /**
     * @Inject()
     * @var UserBalance
     */
    private $userBalanceModel;

    /**
     * @Inject()
     * @var UserBalanceLog
     */
    private $userBalanceLogModel;
    /**
     * @Inject()
     * @var User
     */
    private $user;

    /**
     * @GetApi(path="", description="客服")
     * @PostApi(path="", description="客服")
     */
    public function notify(){

//        dump($this->request->getBody()->getContents());
//        dump($this->request->all());
        $data = $this->request->all();
        $params = new \Yurun\PaySDK\AlipayApp\Params\PublicParams;

        $pay_config =  c(['wx_app_id','wx_mch_id','wx_key']);

        $params->appID = '2021002125600973';
        $params->appPrivateKey="MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCSAQ+HtWjqsnjKGHiWzjthEIAIwlPT8Z1YFTE62PY03Yvg0hXdzXpDgUCXkBczzeT/t2H9CLVTGiCBttkAmgoki2xdT2trSpqgO8MZGoJHvTudqGdMvb5tX2O8GQ4yK8BHztGdo8hiRnWs0dcSboEmv0NDotmogb8pUbwkoUopVsWqCpuw0KVzd1Jgsf/nJfR8sGkGFWnOmmjyJRdhptQE0fr83jYTKw/9b6clh4lduT0i62WuwljungExIXBzFGZkS7qBexPUPld82/gWcu+XeRdk+IN1WsqwAmWn65LG9sj3KCEiJ8019TJNJx+OnlsQQkAO9UUjSOFLGrzOQ7gDAgMBAAECggEAGLuxQ84Jbei56ZJnqzRYfsLqzZEN3lOJ0ggVBOEIJEB7l6Q/LAnI8nKM3J/+Ljps1pzcLp8xCFjetNqivCVcHoC35L61dcF3nDlDfZcuBrUZykVi6m8iOSj1nkGoU/txYTi57bh3E57YBGSvyGGL42J3JJgLwy3nkjpUYugnxgS5GG/Xe3uSIOyHbhMRfAl6VUW+NF+Ct5mthr9ViJR5CMe4B50NgUnJs0UtzX/2GgQZ/Js2180Eb+AJkkNOVu3a0sYaM3VdF06eTazxaZDOGrudnLkJTSfUKVDo5sEq795L/zeQQHgmjeg6qu8QucyEumJ/RwPep7PAdMrN2t71MQKBgQDZSGEy3CxW7AN5HQ5rBBPAISmV6QK1bzZWw2eho8sUrue+Y+HccGUbGx78uVy3/qzn+QNZ7gWWY/lqi+Q1tvzUOTuWRy7YpnfS9OvtcDhztsX/I/IxDKYYxbvWOovwOyVEwcVCRQlwBZiZXgrJj9CEKUne+NnuQgds4RFDgIqE7wKBgQCsBThUmbmfRR61AG8QRdVSCkPrdgp4cKE+TrnbM+t7g3ZHILtMWJYEco0lxaQpu8lSy50pzFy8tcxa0uAmWGGA59kZuRXwFWLGGpBGowqTOVgwRgldeQ01+FzsIb0fdJe74krDYHPvYY0lbZ42J3OHVAK3qSZqTi00kxlSzX9GLQKBgQDUhm07+AsOgg7rmbYOipBe7pDkZmMV38+AkeR9P95VPrbBjKTQnsbl+mMWwp+kAGBTDUdv4NZqQSMmSepPd8pYMhrMZJe3pSuylYlmVsuAsxd69UfhHgQgWtfdNKrHsLJFTFqK9UfD4FVgPZHDkP1dJ51vkGxpFQB6SLJAGixi1QKBgEkGKkSXwZngMMnrtjo1OvqJyw1w3b8FKR4XM4TfhI65XwOQ3JHrZTvcGRk4zpi81BkmwMAWtiOSFX9U17zPdgzP33SxAqQZoAWzDx49ZmbbSBmuEZmxoq8PkPrY070/tI4Y4V4RWwX65n1GDIy2sK95NXIcB2fHah9QdsaU3/u9AoGBAITd5HZVnDrPrg0idWELO8Smkz2uqgFFQAjd8V6HoYQQrevSrJbt4W+Pibt8ma1Jq8Z8jVQ/g/wHHFxokS9W/eYp/Mje11I+UaXp5F3QQWNHhzfDb66IBmXjcQfiky7TzLwNsIMdHUp1R3OCWAcuYyoaeAAcUFqyMlwoGizPpPEC";
//支付宝公钥
        $params->appPublicKey="MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAi0R5/4w4BRd2faUf74d/AxEsFK5wNuCbAkGizzwjT6+pe8LHfegimOGjJODMsmVPE+VpSxopAa9zF47ao2aZDaekw6OtySxne6vubfNuADv+C6vfZpbrX9efWd8piePetpZ7jD4dz0M8A9g2/yfdp+G6LEDJnStI/M6pF4B8iviGnCw6wKI7HHl2xxaBWCNmFRiYgZcmcw6RNLuIxFef/+6boTb4BVZpff3ZnSIqyIGLU5BHro1BVfA/iGkbmNzIdHrRzTWNf6/c9PCggKJgCjTO2IWSwpLUBWsgDgN/IlDuuqz6Q6o5oQ1UZGI5yc6hAFALSZEBaq0ktSZgyUtkWQIDAQAB";

        $pay = new \Yurun\PaySDK\AlipayApp\SDK($params);

        if($pay->verifyCallback($data)){
             dump($data);
             $this->orderServer->handel($data['out_trade_no'],$data['buyer_pay_amount'],$data['notify_time']);
        }

    }

    /**
     * @GetApi(path="balance", description="balance")
     * @PostApi(path="balance", description="balance")
     */
    public function balance(){
        $data = $this->request->all();
        $params = new \Yurun\PaySDK\AlipayApp\Params\PublicParams;
        $pay_config =  c(['wx_app_id','wx_mch_id','wx_key']);

        $params->appID = '2021002125600973';
        $params->appPrivateKey="MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCSAQ+HtWjqsnjKGHiWzjthEIAIwlPT8Z1YFTE62PY03Yvg0hXdzXpDgUCXkBczzeT/t2H9CLVTGiCBttkAmgoki2xdT2trSpqgO8MZGoJHvTudqGdMvb5tX2O8GQ4yK8BHztGdo8hiRnWs0dcSboEmv0NDotmogb8pUbwkoUopVsWqCpuw0KVzd1Jgsf/nJfR8sGkGFWnOmmjyJRdhptQE0fr83jYTKw/9b6clh4lduT0i62WuwljungExIXBzFGZkS7qBexPUPld82/gWcu+XeRdk+IN1WsqwAmWn65LG9sj3KCEiJ8019TJNJx+OnlsQQkAO9UUjSOFLGrzOQ7gDAgMBAAECggEAGLuxQ84Jbei56ZJnqzRYfsLqzZEN3lOJ0ggVBOEIJEB7l6Q/LAnI8nKM3J/+Ljps1pzcLp8xCFjetNqivCVcHoC35L61dcF3nDlDfZcuBrUZykVi6m8iOSj1nkGoU/txYTi57bh3E57YBGSvyGGL42J3JJgLwy3nkjpUYugnxgS5GG/Xe3uSIOyHbhMRfAl6VUW+NF+Ct5mthr9ViJR5CMe4B50NgUnJs0UtzX/2GgQZ/Js2180Eb+AJkkNOVu3a0sYaM3VdF06eTazxaZDOGrudnLkJTSfUKVDo5sEq795L/zeQQHgmjeg6qu8QucyEumJ/RwPep7PAdMrN2t71MQKBgQDZSGEy3CxW7AN5HQ5rBBPAISmV6QK1bzZWw2eho8sUrue+Y+HccGUbGx78uVy3/qzn+QNZ7gWWY/lqi+Q1tvzUOTuWRy7YpnfS9OvtcDhztsX/I/IxDKYYxbvWOovwOyVEwcVCRQlwBZiZXgrJj9CEKUne+NnuQgds4RFDgIqE7wKBgQCsBThUmbmfRR61AG8QRdVSCkPrdgp4cKE+TrnbM+t7g3ZHILtMWJYEco0lxaQpu8lSy50pzFy8tcxa0uAmWGGA59kZuRXwFWLGGpBGowqTOVgwRgldeQ01+FzsIb0fdJe74krDYHPvYY0lbZ42J3OHVAK3qSZqTi00kxlSzX9GLQKBgQDUhm07+AsOgg7rmbYOipBe7pDkZmMV38+AkeR9P95VPrbBjKTQnsbl+mMWwp+kAGBTDUdv4NZqQSMmSepPd8pYMhrMZJe3pSuylYlmVsuAsxd69UfhHgQgWtfdNKrHsLJFTFqK9UfD4FVgPZHDkP1dJ51vkGxpFQB6SLJAGixi1QKBgEkGKkSXwZngMMnrtjo1OvqJyw1w3b8FKR4XM4TfhI65XwOQ3JHrZTvcGRk4zpi81BkmwMAWtiOSFX9U17zPdgzP33SxAqQZoAWzDx49ZmbbSBmuEZmxoq8PkPrY070/tI4Y4V4RWwX65n1GDIy2sK95NXIcB2fHah9QdsaU3/u9AoGBAITd5HZVnDrPrg0idWELO8Smkz2uqgFFQAjd8V6HoYQQrevSrJbt4W+Pibt8ma1Jq8Z8jVQ/g/wHHFxokS9W/eYp/Mje11I+UaXp5F3QQWNHhzfDb66IBmXjcQfiky7TzLwNsIMdHUp1R3OCWAcuYyoaeAAcUFqyMlwoGizPpPEC";
//支付宝公钥
        $params->appPublicKey="MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAi0R5/4w4BRd2faUf74d/AxEsFK5wNuCbAkGizzwjT6+pe8LHfegimOGjJODMsmVPE+VpSxopAa9zF47ao2aZDaekw6OtySxne6vubfNuADv+C6vfZpbrX9efWd8piePetpZ7jD4dz0M8A9g2/yfdp+G6LEDJnStI/M6pF4B8iviGnCw6wKI7HHl2xxaBWCNmFRiYgZcmcw6RNLuIxFef/+6boTb4BVZpff3ZnSIqyIGLU5BHro1BVfA/iGkbmNzIdHrRzTWNf6/c9PCggKJgCjTO2IWSwpLUBWsgDgN/IlDuuqz6Q6o5oQ1UZGI5yc6hAFALSZEBaq0ktSZgyUtkWQIDAQAB";

        $pay = new \Yurun\PaySDK\AlipayApp\SDK($params);

        if($pay->verifyCallback($data)){
              $this->zfb_balance($data['out_trade_no'],$data['buyer_pay_amount'],$data['notify_time']);
//            $this->orderServer->handel($data['out_trade_no'],$data['buyer_pay_amount'],$data['notify_time']);
        }
    }

    /**
     * @GetApi(path="zfb_balance_test", description="balance")
     */
    public function zfb_balance_test(){
            $this->zfb_balance('234641814887387137',100,'');

    }
    public function zfb_balance($order_id,$price,$pay_time=''){
            dump($order_id);

            Db::beginTransaction();
            try {
                $userBalanceInfo = $this->userBalanceModel->lock(true)->where('id',$order_id)->first();
                if($userBalanceInfo->status == 1){
                    err('该订单已经支付');
                }
                $userBalanceInfo->status = 1;
                $userBalanceInfo->pay_price = $price;
                $userBalanceInfo->pay_time = $pay_time;
                $userBalanceInfo->save();
                dump(222222);

                //添加用户余额
                $userInfo = $this->user->lock(true)->where('id',$userBalanceInfo->uid)->first();
                $balance = $userInfo->balance;
                $userInfo->balance += $userBalanceInfo->price;

                $userInfo->save();
                //添加到记录里面去
                $this->userBalanceLogModel->create([
                    'uid'=>$userBalanceInfo->uid,
                    'type'=>1,
                    'title'=>'充值',
                    'balance'=>$userInfo->balance,
                    'number'=>$userBalanceInfo->price,
                    'link_id'=>$order_id
                ]);
                Db::commit();
                } catch (\Throwable $ex) {
                    p($ex);
                    Db::rollBack();
                    err($ex->getMessage());
                }
    }


}


