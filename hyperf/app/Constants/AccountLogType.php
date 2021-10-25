<?php
declare(strict_types=1);

namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * @Constants
 * @method static string getMessage(int $type)
 */
class AccountLogType extends AbstractConstants
{

    /**
     * @Message("下单兑换商品")
     */
    const ORDER = 1;


    /**
     * @Message("签到获得积分")
     */
    const SIGN = 2;
}
