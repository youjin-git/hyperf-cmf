<?php


namespace App\Constants;


use Hyperf\Constants\AbstractConstants;

class BaseConstants extends AbstractConstants
{

    public static $constCacheArray = [];

    /**
     * Get all of the constants defined on the class.
     *
     * @return array
     */
    public static function getConstants(): array
    {
        $calledClass = get_called_class();

        if (!array_key_exists($calledClass, static::$constCacheArray)) {
            $reflect = new \ReflectionClass($calledClass);
            static::$constCacheArray[$calledClass] = $reflect->getConstants();
        }

        return static::$constCacheArray[$calledClass];
    }


    /**
     * Get all of the enum keys.
     *
     * @return array
     */
    public static function getKeys(): array
    {
        return array_keys(static::getConstants());
    }

    /**
     * Get all of the enum values.
     *
     * @return array
     */
    public static function getValues(): array
    {
        return array_values(static::getConstants());
    }


    public static function getKey($value): string
    {
        return array_search($value, static::getConstants());
    }

    public static function getValue(string $key): string
    {
        return static::getConstants()[$key];
    }

}