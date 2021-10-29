<?php


namespace App\Aop;


use App\Controller\Api\Product\Product;
use App\Service\Product\ProductService;
use Hyperf\Di\Annotation\Aspect;
use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;

/**
 * @Aspect
 */
class ErrorAspect extends AbstractAspect
{
    public $classes = [
        ProductService::class.'::detail',
    ];

    public function process(ProceedingJoinPoint $proceedingJoinPoint)
    {
        $result = $proceedingJoinPoint->process();
        if(empty($result)){
            err('不存在数据');
        }
        return $result;
    }

}