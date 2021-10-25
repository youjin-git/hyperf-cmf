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
use App\Exception\YjException;
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
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Utils\Codec\Xml;
use Hyperf\Utils\Context;


/**
 * @ApiController(tag="支付管理",prefix="notify/wx/balance",description="")
 */
class WxBalanceController extends \Yurun\PaySDK\Weixin\Notify\Pay
{

    /**
     * @Inject()
     * @var RequestInterface
     */
    private $request;
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

    public function __exec()
    {
        // TODO: Implement __exec() method.
        p($data = $this->data);
        $this->zfb_balance($data['out_trade_no'],$data['total_fee']/100,time());
//        $this->handle_order($this->data['out_trade_no']);
    }
    public function getNotifyData(){
        return   \Yurun\PaySDK\Lib\XML::fromString($this->request->getBody()->getContents());
    }

    /**
     * @GetApi(path="", description="客服")
     * @PostApi(path="", description="客服")
     */
    public function notify(){
        $params = new \Yurun\PaySDK\Weixin\Params\PublicParams;
        $pay_config =  c(['wx_app_id','wx_mch_id','wx_key']);
        $params->appID = $pay_config['wx_app_id'];
        $params->mch_id = $pay_config['wx_mch_id'];
        $params->key =$pay_config['wx_key'];
        $pay = new \Yurun\PaySDK\Weixin\SDK($params);
        $pay->notify(make(WxBalanceController::class));
    }

    /**
     * @GetApi(path="zfb_balance_test", description="balance")
     */
    public function zfb_balance_test(){
        $this->zfb_balance('238463579434835969',100,'');
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
        } catch (YjException $ex) {
            Db::rollBack();
            err($ex->getMessage());
        }
    }

}


