<?php


namespace Yj\Apidog\Annotation;


use Hyperf\Di\Annotation\AbstractAnnotation;

use Hyperf\Di\Annotation\AnnotationInterface;
use Hyperf\Di\ReflectionManager;
use Hyperf\Utils\Contracts\Arrayable;
use Yj\Apidog\AnnotationCollector;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
class FormData implements AnnotationInterface
{
    public $key;

    public $rule;

    public $default;

    public $message;

    public function collectMethod(string $className, ?string $target): void
    {
        dump($className, $target);
        AnnotationCollector::collectMethod($className, $target, static::class, $this);
    }

    public function collectClass(string $className): void
    {
        // TODO: Implement collectClass() method.
    }

    public function collectProperty(string $className, ?string $target): void
    {
        // TODO: Implement collectProperty() method.
    }


}