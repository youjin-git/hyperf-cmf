<?php
/**
 * PHP表单生成器
 *
 * @package  FormBuilder
 * @author   xaboy <xaboy2005@qq.com>
 * @version  2.0
 * @license  MIT
 * @link     https://github.com/xaboy/form-builder
 * @document http://php.form-create.com
 */

namespace Yj\FormBuilder\Factory;

use Yj\FormBuilder\Exception\FormBuilderException;
use Yj\FormBuilder\Form;
use Yj\FormBuilder\UI\Elm\Components\Button;
use Yj\FormBuilder\UI\Elm\Components\Option;
use Yj\FormBuilder\UI\Elm\Components\Popover;
use Yj\FormBuilder\UI\Elm\Components\Tooltip;
use Yj\FormBuilder\UI\Elm\Config;
use Yj\FormBuilder\UI\Elm\Traits\CascaderFactoryTrait;
use Yj\FormBuilder\UI\Elm\Traits\CheckBoxFactoryTrait;
use Yj\FormBuilder\UI\Elm\Traits\ColorPickerFactoryTrait;
use Yj\FormBuilder\UI\Elm\Traits\DatePickerFactoryTrait;
use Yj\FormBuilder\UI\Elm\Traits\FrameFactoryTrait;
use Yj\FormBuilder\UI\Elm\Traits\FormStyleFactoryTrait;
use Yj\FormBuilder\UI\Elm\Traits\GroupFactoryTrait;
use Yj\FormBuilder\UI\Elm\Traits\HiddenFactoryTrait;
use Yj\FormBuilder\UI\Elm\Traits\InputFactoryTrait;
use Yj\FormBuilder\UI\Elm\Traits\InputNumberFactoryTrait;
use Yj\FormBuilder\UI\Elm\Traits\RadioFactoryTrait;
use Yj\FormBuilder\UI\Elm\Traits\RateFactoryTrait;
use Yj\FormBuilder\UI\Elm\Traits\SelectFactoryTrait;
use Yj\FormBuilder\UI\Elm\Traits\SliderFactoryTrait;
use Yj\FormBuilder\UI\Elm\Traits\SwitchesFactoryTrait;
use Yj\FormBuilder\UI\Elm\Traits\TimePickerFactoryTrait;
use Yj\FormBuilder\UI\Elm\Traits\TreeFactoryTrait;
use Yj\FormBuilder\UI\Elm\Traits\UploadFactoryTrait;
use Yj\FormBuilder\UI\Elm\Traits\ValidateFactoryTrait;

abstract class Elm extends Base
{
    use CascaderFactoryTrait;
    use CheckBoxFactoryTrait;
    use ColorPickerFactoryTrait;
    use DatePickerFactoryTrait;
    use FrameFactoryTrait;
    use HiddenFactoryTrait;
    use InputNumberFactoryTrait;
    use InputFactoryTrait;
    use RadioFactoryTrait;
    use RateFactoryTrait;
    use SliderFactoryTrait;
    use SelectFactoryTrait;
    use FormStyleFactoryTrait;
    use SwitchesFactoryTrait;
    use TimePickerFactoryTrait;
    use TreeFactoryTrait;
    use UploadFactoryTrait;
    use ValidateFactoryTrait;
    use GroupFactoryTrait;

    /**
     * 获取选择类组件 option 类
     *
     * @param string|number $value
     * @param string $label
     * @param bool $disabled
     * @return Option
     */
    public static function option($value, $label = '', $disabled = false)
    {
        return new Option($value, $label, $disabled);
    }

    /**
     * @param array $rule
     * @return \Yj\FormBuilder\UI\Elm\Style\FormStyle
     * @author xaboy
     * @day 2020/5/22
     */
    public static function formStyle(array $rule = [])
    {
        return self::style($rule);
    }

    /**
     * 全局配置
     *
     * @param array $config
     * @return Config
     */
    public static function config(array $config = [])
    {
        return new Config($config);
    }

    /**
     * 组件提示消息配置 Popover
     *
     * @return Popover
     */
    public static function popover()
    {
        return new Popover();
    }

    /**
     * 组件提示消息配置 Tooltip
     *
     * @return Tooltip
     */
    public static function tooltip()
    {
        return new Tooltip();
    }

    /**
     * 按钮组件
     *
     * @return Button
     */
    public static function button()
    {
        return new Button();
    }


    /**
     * 创建表单
     *
     * @param string $action
     * @param array $rule
     * @param array $config
     * @return Form
     * @throws FormBuilderException
     */
    public static function createForm($action = '', $rule = [], $config = [])
    {

        return Form::elm($action, $rule, $config);
    }

    /**
     * 组件分组
     *
     * @param array $children
     * @return \Yj\FormBuilder\Driver\CustomComponent
     */
    public static function item($children = [])
    {
        return self::createComponent('el-row')->children($children);
    }
}