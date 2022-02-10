<?php
declare(strict_types=1);

namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * @Constants
 * @method static string getMessage(int $status)
 */
class GroupType extends AbstractConstants
{

    /**
     * @Message("余额")
     */
    const BALANCE = 'input';

    /**
     * @Message("积分")
     */
    const INTEGRAL = 2;

    /**
     * Get all of the constants defined on the class.
     *
     * @return array
     */
    protected static function getConstants(): array
    {
        $calledClass = get_called_class();
        dd($calledClass);
//        if (! array_key_exists($calledClass, static::$constCacheArray)) {
//            $reflect = new ReflectionClass($calledClass);
//            static::$constCacheArray[$calledClass] = $reflect->getConstants();
//        }
//
//        return static::$constCacheArray[$calledClass];
    }

}
