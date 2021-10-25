<?php


namespace App\Aop;


use App\Controller\Api\Product\Product;
use App\Service\Product\ProductService;
use Hyperf\Database\Model\Builder;
use Hyperf\Di\Annotation\Aspect;
use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;
use function Swoole\Coroutine\Http\request;
use Hyperf\HttpServer\Contract\RequestInterface;

/**
 * @Aspect(priority=0)
 */
class PageAspect extends BaseAspect
{
    public $classes = [
        Builder::class . '::paginate'
    ];


    function beforeHandle(ProceedingJoinPoint $proceedingJoinPoint)
    {

        if (is_null($this->getArgument('perPage'))) {
            $this->setArgument('perPage', di()->get(RequestInterface::class)->input('limit', 20));
        }


        // TODO: Implement beforeHandle() method.
    }

    function afterHandle($result)
    {
        // TODO: Implement afterHandle() method.
    }


}