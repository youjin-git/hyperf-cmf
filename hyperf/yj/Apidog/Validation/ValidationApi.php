<?php


namespace Yj\Apidog\Validation;


use App\Model\Admin\ConfigValue;


use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Utils\Context;
use Yj\Apidog\Annotation;
use Yj\Apidog\AnnotationCollector;
use Yj\Request\BaseRequest;

class ValidationApi
{

    /**
     * @Inject()
     * @var RequestInterface
     */
    protected $request;

    public function getAnnotations($controller, $action)
    {
        return Annotation::getMethodAnnotation($controller, $action);
    }

    public function Validated($controller, $action)
    {
        $annotations = AnnotationCollector::getClassMethodAnnotation($controller, $action);

        $FormDataAnnotations = collect(collect($annotations)->get(Annotation\FormData::class));

        $FormDataRules = $FormDataAnnotations->mapWithKeys(function ($item) {
            return [$item->key => $item->rule];
        })->toArray();

        $Request = app(BaseRequest::class);
        $Request->setRules($FormDataRules);
        $Request->validateResolved();
        Context::set('validator.data', $Request->collection());

        return true;
    }
}