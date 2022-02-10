<?php
/**
 * Created by PhpStorm.
 * User: zwc
 * Date: 2022/2/5
 * Time: 22:16
 */

namespace App\Service\Notice;


use App\Dao\Order\OrderDao;
use App\Service\BaseService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use function Swoole\Coroutine\Http\request;

class OrderNoticeService extends \Yurun\PaySDK\Weixin\Notify\Pay
{

    /**
     * @Inject()
     * @var OrderDao
     */
    protected $orderDao;

    public function __exec()
    {
        $data = $this->data;
        $order_id = $data['out_trade_no'];
        $order = $this->orderDao->DaoWhere(compact('order_id'))->first();
        if($order->status == 0){
            $order->status = 1;
            $order->save();
        }

    }


    public function notice(){
        $params = new \Yurun\PaySDK\Weixin\Params\PublicParams;
        $pay_config =  systemConfig(['wx_app_id','wx_mch_id','wx_key']);
        $params->appID = $pay_config['wx_app_id'];
        $params->mch_id = $pay_config['wx_mch_id'];
        $params->key =$pay_config['wx_key'];
        $pay = new \Yurun\PaySDK\Weixin\SDK($params);
        $pay->notify(new static());
    }

    public function getNotifyData(){
        return   \Yurun\PaySDK\Lib\XML::fromString(App(RequestInterface::class)->getBody()->getContents());
    }

}