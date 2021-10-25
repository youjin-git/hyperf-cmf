<?php


namespace App\Aop;


use App\Controller\Api\Product\Product;
use App\Service\Product\ProductService;
use Hyperf\Di\Annotation\Aspect;
use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;


abstract class BaseAspect extends AbstractAspect
{
    /**
     * @var ProceedingJoinPoint
     */
    public $proceedingJoinPoint;

    abstract function beforeHandle(ProceedingJoinPoint $proceedingJoinPoint);

    abstract function afterHandle($result);

    public function process(ProceedingJoinPoint $proceedingJoinPoint)
    {
        $this->proceedingJoinPoint = $proceedingJoinPoint;
        $this->beforeHandle($this->proceedingJoinPoint);
        $result = $this->proceedingJoinPoint->process();
        return $this->afterHandle($result) ?: $result;
    }

    public function getArguments()
    {
        return $this->proceedingJoinPoint->getArguments();
    }

    public function getArgument($fields)
    {
        return $this->proceedingJoinPoint->arguments['keys'][$fields];
    }

    public function setArgument($fields, $value)
    {
//        $this->proceedingJoinPoint->arguments['keys'][$fields] ?? err('不存在' . $fields);
        $this->proceedingJoinPoint->arguments['keys'][$fields] = $value;
    }

}