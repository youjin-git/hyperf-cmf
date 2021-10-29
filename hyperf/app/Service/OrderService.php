<?php

namespace App\Service;

use App\Constants\AccountLogType;
use App\Constants\AccountType;
use App\Constants\OrderStatus;
use App\Dao\Order\OrderDao;
use App\Dao\User\UserAccountDao;
use App\Exception\YjException;
use App\Model\Community;
use App\Model\CommunityLike;
use App\Model\CommunityReport;
use App\Model\Model;
use App\Model\Order;
use App\Model\Order\OrderProduct;
use App\Model\Product\Product;
use App\Model\User;
use App\Model\UserBalanceLog;
use App\Service\Product\ProductService;
use Hyperf\Database\Model\ModelIDE;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use App\Constants\ErrorCode;
use PharIo\Manifest\InvalidApplicationNameException;


class OrderService extends Service
{

    /**
     * @Inject()
     * @var OrderDao
     */
    protected $orderDao;

    /**
     * @Inject()
     * @var UserAccountDao
     */
    protected $userAccountDao;

    /**
     * @Inject()
     * @var User
     */
    protected $userModel;

    /**
     * @Inject()
     * @var UserBalanceLog
     */
    protected $userBalanceLogModel;

    /**
     * @Inject()
     * @var ProductService
     */
    protected $productService;


    public function statistics($uid)
    {
        $data = [];
        $data[OrderStatus::ALL] = $this->orderDao->DaoWhere(['status' => OrderStatus::ALL])->count();
        $data[(string)OrderStatus::WAIT_PAY] = $this->orderDao->DaoWhere(['status' => OrderStatus::WAIT_PAY])->count();
        $data[(string)OrderStatus::WAIT_DELIVERY] = $this->orderDao->DaoWhere(['status' => OrderStatus::WAIT_DELIVERY])->count();
        $data[(string)OrderStatus::ALL_DELIVERY] = $this->orderDao->DaoWhere(['status' => OrderStatus::ALL_DELIVERY])->count();
        $data[(string)OrderStatus::WAIT_COMMENT] = $this->orderDao->DaoWhere(['status' => OrderStatus::WAIT_COMMENT])->count();
        $data[(string)OrderStatus::DONE] = $this->orderDao->DaoWhere(['status' => OrderStatus::DONE])->count();
        $data[(string)OrderStatus::CLOSE] = $this->orderDao->DaoWhere(['status' => OrderStatus::CLOSE])->count();
        return $data;
    }


    /***
     * 发货
     */
    public function deliver($orderId = 0)
    {
        $orderData = $this->orderDao->DaoWhere(['id' => $orderId])->first();
        if ($orderData->status !== OrderStatus::WAIT_DELIVERY) {
            return $this->error('该订单' . OrderStatus::getMessage($orderData->status));
        }
        $orderData->status = OrderStatus::WAIT_CONFIRM_DELIVERY;

        
        //创建发货单



    }

    /**
     * 订单列表
     */
    public function list($params)
    {
        return $this->orderDao->DaoWhere($params)
            ->DaoWith()
            ->orderBy('create_time', 'desc')->paginate();
    }

    public function detail($orderId, $userId)
    {
        $data = $this->orderDao->DaoWhere(['id' => $orderId, 'user_id' => $userId])->DaoWith()->first();
        if (empty($data)) {
            return $this->error('查询不到该订单');
        }
        return $data;
    }

    public function add($params)
    {
        $productData = $this->productService->detail(['id' => $params['productId']]);
        Db::beginTransaction();
        try {
            //扣除用户积分
            $data = $this->userAccountDao->Op($params['user_id'], AccountType::INTEGRAL, -$productData['integral'], AccountLogType::ORDER) || err();

            //添加订单
            $params['status'] = OrderStatus::WAIT_DELIVERY;

            //算出商品总积分
            $params['integral'] = $productData->integral;

            $order = $this->orderDao->create($params);
            $orderProductData = [new OrderProduct([
                'num' => 1,
                'user_id' => $params['user_id'],
                'product_id' => $productData->id,
                'integral' => $productData->integral,
                'product_info' => json_encode($productData->toArray())
            ])];
            $order->orderProduct()->saveMany($orderProductData);

            Db::commit();
        } catch (\Throwable $ex) {
            Db::rollBack();
            return $this->error($ex->getMessage());
        }
        return true;
    }


    public function handel($order_id, $pay_price, $pay_time)
    {

        $orderInfo = $this->orderModel->where('id', $order_id)->first();
        if ($orderInfo['status'] == 0) {
            $orderInfo->status = 1;
            $orderInfo->pay_price = $pay_price;
            $orderInfo->pay_time = $pay_time;
            $orderInfo->save();
        } else {
            dump('更新失败');
        }

    }

    public function handelBybalance($order_id, $uid)
    {

        Db::beginTransaction();
        try {
            $userInfo = $this->userModel->where('id', $uid)->lock(true)->first();
            $orderInfo = $this->orderModel->where('id', $order_id)->lock(true)->where('user_id', $uid)->first();
            if ($orderInfo->status == 1) {
                err('该订单已经支付');
            }
            if ($userInfo->balance < $orderInfo->price) {
                err('余额不足');
            }
            //减少余额
            $balance = $userInfo->balance;

            $price = $orderInfo->price;
            $userInfo->balance -= $price;
            $userInfo->save();


            //添加到记录里面去
            $this->userBalanceLogModel->create([
                'uid' => $uid,
                'type' => 2,
                'title' => '消费',
                'balance' => -$balance,
                'number' => $orderInfo->price,
                'link_id' => $order_id
            ]);


            //订单修改状态
            $orderInfo->status = 1;
            $orderInfo->pay_price = $orderInfo->price;
            $orderInfo->pay_time = date('Y-m-d H:i:s');
            $orderInfo->save();

            Db::commit();
        } catch (YjException $ex) {
            Db::rollBack();
            err($ex->getMsg());
        }
        succ();
    }
}