<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Controller;


use App\Model\Admin\ConfigValue;
use App\Model\InsuredConfig;
use App\Model\InsuredPriceValue;
use App\Model\Order;
use App\Model\OrderDetail;
use App\Model\User;
use App\Request\RegiterRequest;
use App\Request\WasteRequest;
use Carbon\Carbon;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use App\Middleware\CheckLoginMiddleware;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\Utils\Context;
use Hyperf\Snowflake\Concern\Snowflake;
use Hyperf\Database\Model\SoftDeletes;
use App\Model\Insured;
use Hyperf\Apidog\Annotation\ApiController;
use Hyperf\Apidog\Annotation\PostApi;

use Hyperf\Apidog\Annotation\FormData;


/**
 * @Middleware(CheckLoginMiddleware::class)
 * @ApiController(tag="订单管理",prefix="order",description="")
 */
class OrderController extends AbstractController
{

    /**
     * @Inject()
     * @var User
     */
    protected $userModel;
    /**
     * @Inject()
     * @var Insured
     */
    protected $insuredModel;
    /**
     * @Inject()
     * @var OrderDetail
     */
    protected $orderDetailModel;

    /**
     * @Inject()
     * @var InsuredConfig
     */
    protected $insuredConfigModel;


    /**
     * @Inject()
     * @var InsuredPriceValue
     */
    protected $insurePriceValueModel;

    /**
     * @PostApi(path="init", description="订单初始化")
     */
    public function init()
    {
        $insured_area = Db::table('insured_area')->get();
//        $insured_price = Db::table('insured_price_value')->where('insured_price_config_id',1)->pluck('prices','month');
        $insured_price = $this->insurePriceValueModel->getPriceList($this->getUid());

        $start_months = [];
        $now1 = $now = Carbon::now();

        //判断是否大于
        if ($now1->format('d') > 15) {
            $now1->addMonths(1);
        }
        $end_month = $now1->format('m月');

        $end_date = $now1->format("m月") . '15日';

        $insurance_config = c(['insurance_notice', 'problem', 'insurance_process']);


        $start_months[] = $now->addMonths(0)->format('Y-m');
        $start_months[] = $now->addMonths(1)->format('Y-m');
        $start_months[] = $now->addMonths(1)->format('Y-m');


        succ(compact('insured_area', 'insured_price', 'start_months', 'end_month', 'end_date', 'insurance_config'));

    }

    /**
     * @PostApi(path="get_insured_config", description="获取配置")
     * @FormData(key="start_month|开始月份", rule="required|date")
     */
    public function get_insured_config()
    {
        $params = Context::get('validator.data');
        $insured_price = $this->insuredConfigModel->where('month', $params['start_month'])->first();
        succ($insured_price);
    }


    /**
     * @PostApi(path="add", description="订单添加")
     * @FormData(key="social_security_base_price|社保基础价格", rule="required|int")
     * @FormData(key="is_repair|是否补缴", rule="required|int")
     * @FormData(key="insured_id|代缴人", rule="required|int")
     * @FormData(key="start_month|开始月份", rule="required|date")
     * @FormData(key="months|总共月份", rule="required|int|between:1,12")
     * @FormData(key="is_fund|是否缴纳公积金", rule="required|int")
     * @FormData(key="fund_base_price|公积金基础价格", rule="required|int")
     * @FormData(key="is_create|是否生成订单", rule="required|int")
     * @FormData(key="insured_area_id|地区", rule="required|int",description="310000:主城区,311100:杭州余杭区")
     */
    public function add()
    {
        $uid = Context::get('uid');
        $params = Context::get('validator.data');



        $months = [];
        $start_time = Carbon::create(explode('-', $params['start_month'])[0], explode('-', $params['start_month'])[1]);

        $months[] = $start_time->format('Y-m');
        for ($i = 1; $i < $params['months']; $i++) {
            $months[] = $start_time->addMonths(1)->format('Y-m');
        }

        p($months);
        $insuredConfigInfo = $this->insuredConfigModel->whereIn('month', $months)->get()->toArray();

        if (count($insuredConfigInfo) != count($months)) {
            err('后台配置有问题,请联系客服');
        }
        p($params);
        $social_security_prices = $fund_prices = $advance_payment_prices = $social_security_price = $fund_price = 0;

        $orderDetail = [];

        //获取服务费
//        $servicePrice = Db::table('insured_price')->where('month',$params['months'])->value('prices');
        $servicePrice = $this->insurePriceValueModel->getPriceValue($uid, $params['months']);

        //判断是否需要补交手
        if ($params['is_repair']) {
            $start_time = Carbon::create(explode('-', $params['start_month'])[0], explode('-', $params['start_month'])[1]);
            //获取补交手续费
//            $start_time = Carbon::create();
            $repair_month = $start_time->subMonth(1)->format('Y-m');
            succ($repair_month);
            $repair_insuredConfigInfo = $this->insuredConfigModel->where('month', $start_time->addMonth(1)->format('Y-m'))->first();
            $repair_insuredConfigInfo['is_repair'] = 1;
            $repair_insuredConfigInfo['month'] = $repair_month;
            array_unshift($insuredConfigInfo, $repair_insuredConfigInfo);

        }


        foreach ($insuredConfigInfo as $v) {
            $social_security_base_price = $params['social_security_base_price']; //social security base price
            $fund_base_price = $params['fund_base_price'];  //fund base price
            $social_security_base_price = max($social_security_base_price, $v['social_security_min_base_price']);
            $social_security_base_price = min($social_security_base_price, $v['social_security_max_base_price']);
            $social_security_price = ceil($social_security_base_price * $v['rate']) / 1000;
            $social_security_prices += $social_security_price;

            if ($params['is_fund'] && !isset($v['is_repair'])) {
                $fund_base_price = max($fund_base_price, $v['fund_min_base_price']);
                $fund_base_price = min($fund_base_price, $v['fund_max_base_price']);
                $fund_price = ceil($fund_base_price * $v['fund_rate']) / 1000;
                $fund_prices += $fund_price;
            }

            $advance_payment_prices += $v['advance_payment'];

            $orderDetail[] = [
                'is_repair' => $v['is_repair'] ?? 0,
                'month' => $v['month'],
                'insured_config_id' => $v['id'],
                'advance_payment_price' => $v['advance_payment'],
                'service_price' => $servicePrice,
                'fund_price' => $fund_price,
                'fund_base_price' => $fund_base_price,
                'fund_rate' => $v['fund_rate'],
                'price' => $fund_price + $social_security_price,
                'social_security_price' => $social_security_price,
                'social_security_rate' => $v['rate'],
                'social_security_base_price' => $social_security_base_price,
            ];
        }
//

        if (!$servicePrice) {
            err('填写月份异常');
        }
        $servicePrices = $servicePrice * count($insuredConfigInfo);

//        if($orderDetail[''])
        $orderInfo = [
            'advance_payment_prices' => $advance_payment_prices,
            'user_id' => $uid,
            'social_security_prices' => $social_security_prices,
            'fund_prices' => $fund_prices,
            'is_repair' => $params['is_repair'],
            'insured_id' => $params['insured_id'],
            'service_prices' => $servicePrices,
            'start_month' => $params['start_month'],
            'months' => $params['months'],
            'price' => $social_security_prices + $fund_prices + $servicePrices + $advance_payment_prices,
            'insured_area_id' => $params['insured_area_id']
        ];

        if ($params['is_create'] == 0) {
            foreach ($orderDetail as &$v) {
                $v['show'] = false;
                $v['insuredConfig'] = $this->insuredConfigModel->where('id', $v['insured_config_id'])->first();
            }
            $orderInfo['insured'] = $this->insuredModel->where('id', $orderInfo['insured_id'])->first();
            $orderInfo['orderDetail'] = $orderDetail;
            succ($orderInfo);
        }

        Db::beginTransaction();

        try {

            $order = $this->orderModel->create($orderInfo);
            foreach ($orderDetail as $v) {
                $v['order_id'] = $order->id;
                $this->orderDetailModel->create($v);
            }
            Db::commit();
        } catch (\Throwable $ex) {
            Db::rollBack();
            err($ex->getMessage());
        }
        succ(['order_id' => $order->id]);
    }

    /**
     * @PostApi(path="lists", description="订单列表")
     * @FormData(key="status|订单状态", rule="")
     */
    public function lists()
    {
        $uid = Context::get('uid');
        $status = $this->request->input('status', '');
        $where = [];
        if ($status !== '') {
            $where['status'] = $status;
        }
        $lists = $this->orderModel->where('user_id', $uid)->where($where)->orderBy('id', 'desc')->paginate()->toArray();
        foreach ($lists['data'] as &$v) {
            $v['insured_name'] = $this->insuredModel->where('id', $v['insured_id'])->value('name');

        }

        succ($lists);
    }

    /**
     * @PostApi(path="detail", description="订单详情")
     * @FormData(key="id|订单id", rule="required|int")
     */

    public function detail()
    {
        $uid = Context::get('uid');
        $id = $this->request->input('id');
        $data = $this->orderModel->where('id', $id)->first();
        p($data->insured_id);
        //返回用户的余额


        $data['insured'] = $this->insuredModel->where('id', $data->insured_id)->first();

        $orderDetail = $this->orderDetailModel->where('order_id', $data['id'])->get();
        foreach ($orderDetail as &$v) {
            $v['show'] = false;
            $v['insuredConfig'] = $this->insuredConfigModel->where('id', $v['insured_config_id'])->first();
        }
        $data['orderDetail'] = $orderDetail;
        //获得用户的余额
        $balance = $this->userModel->where('id', $uid)->value('balance');
        $data['balance'] = $balance;

        succ($data);
    }
}
