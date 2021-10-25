<?php
declare(strict_types=1);

namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * @Constants
 * @method static string getMessage(int $status)
 */
class OrderStatus extends AbstractConstants
{
    /**
     * @Message("全部“);
     */
    const ALL = 'all';

    /**
     * @Message("等待支付")
     */
    const WAIT_PAY = 0;

    /**
     * @Message("部分支付")
     */
    const PART_PAY = 50;


    /**
     * @Message("已支付")
     */
    const WAIT_DELIVERY = 100;

    /**
     * @Message("部分发货")
     */
    const PART_DELIVERY = 101;


    /**
     * @Message("已发货")
     */
    const ALL_DELIVERY = 200;
    /**
     * @Message("等待收货")
     */
    const WAIT_CONFIRM_DELIVERY = 200;
//    /**
//     * @Message("全部发货，等收货")
//     */
//    const ALL_CONFIRM_DELIVERY = 300;

    /**
     * @Message("待评论")
     */
    const WAIT_COMMENT = 400;

    /**
     * @Message("已完成")
     */
    const DONE = 500;

    /**
     * @Message("已取消/关闭")
     */
    const CLOSE = -1;


//wait-pay：待支付 [0, 101]
//part-pay: 部分支付 [101]
//wait-delivery：待发货 [100]
//part-delivery：部分发货 [201]
//all-delivery: 全部发货 [200]
//wait-confirmdelivery：待收货 [201, 200]
//part-confirmdelivery: 部分收获 [401]
//all-confirmdelivery: 全部收货 [400]
//aftersale：售后/退款
//done：已完成or关闭 [0, 900]
}
