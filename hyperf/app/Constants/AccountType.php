<?php
declare(strict_types=1);

namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * @Constants
 * @method static string getMessage(int $type)
 */
class AccountType extends BaseConstants
{

    /**
     * @Message("余额")
     */
    const BALANCE = 1;

    /**
     * @Message("积分")
     */
    const INTEGRAL = 2;


}
