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
use App\Middleware\CheckLoginMiddleware;
use App\Model\Admin;
use App\Model\Form;
use App\Model\FormData;
use App\Model\Order;

use App\Model\User;
use App\Service\FormService;
use App\Service\UserService;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\HttpServer\Contract\RequestInterface;
use Yurun\PaySDK\Lib\XML;


class NotifyController
{
   
    /**
     * @Inject
     * @var RequestInterface
     */
    protected $request;


    public function __exec()
    {
        // TODO: Implement __exec() method.
//          p($this->data);
        $this->handle_order($this->data['out_trade_no']);
    }

    public function wx()
    {
        $params = new \Yurun\PaySDK\Weixin\Params\PublicParams;
        $pay_config = $this->configValueModel->_get(['wx_app_id', 'wx_mch_id', 'wx_key']);
        $params->appID = $pay_config['wx_app_id'];
        $params->mch_id = $pay_config['wx_mch_id'];
        $params->key = $pay_config['wx_key'];
        $pay = new \Yurun\PaySDK\Weixin\SDK($params);
        $pay->notify(make('\App\Controller\NotifyController'));
    }

    public function zfb()
    {
        p($this->request->getBody()->getContents());
        p('zfb11111111111111');
    }

    public function getNotifyData()
    {
        return XML::fromString($this->request->getBody()->getContents());
    }

    public function handle_order($order_id)
    {
        p($order_id);
        if (!($order = $this->orderModel->where('id', $order_id)->first())) {
            err('订单不存在');
        }

        p($order);

        if ($order['status'] == 1) {
            err('已支付');
        }
        //开启事务
        Db::beginTransaction();
        try {
            //改变订单状态
            $order->status = 1;
            $order->save();

            //续费会员
            p($order->uid);
//                $data = $this->adminModel->where('admin_id',$order->uid)->first();
//            p($data);
//                p($data);
//                p($data->getOriginal());
            $due_time = Db::table('eb_system_admin')->where('admin_id', $order->uid)->value('due_time');

            p($due_time);
            if (time() > $due_time) {
                $due_time = time();
            }
            $due_time = strtotime("+{$order->year}month", $due_time);

            p($due_time);
            Db::table('eb_system_admin')->where('admin_id', $order->uid)->update(['due_time' => $due_time]);
            Db::commit();
        } catch (\Throwable $ex) {

            Db::rollBack();
        }
        succ();
    }

    public function add()
    {
        $form_id = $this->request->input('form_id');
        $data = $this->formService->info($form_id);
        $fields = json_decode($data->fields, true);

        $params = [];
        foreach ($fields as $v) {
            $value = $this->request->input($v['__vModel__']);
//                p($value);
//                p($v);
            if (in_array($v['__config__']['tag'], ['el-radio-group'])) {
                $value = $this->analysis($value, $v['__slot__']['options']);
            }
            $params[$v['__vModel__']] = $value;
        }
        $res = $this->formData->create(['form_id' => $form_id, 'data' => $params]);
        $res1 = $this->formModel::query()->where('id', $form_id)->increment('nums');
        p($res1);
        //添加提交记录
        succ($res);
    }

    public function analysis($data, $slot)
    {
        if (is_array($data)) {
            $res = array_map(function ($value) use ($slot) {
                p($slot);
                foreach ($slot as $v) {
                    if ($v['value'] == $value) {
                        return $v['label'];
                    }
                }
            }, $data);
            return $res;
        } else {
            foreach ($slot as $v) {
                if ($v['value'] == $data) {
                    return $v['label'];
                }
            }
        }
        return '';
    }
}
