<?php


namespace Yj\Apidog;


use Doctrine\Common\Annotations\AnnotationReader;
use Hyperf\Di\ReflectionManager;

class Annotation
{
    public static function getMethodAnnotation($className, $method)
    {
        $reflectMethod = ReflectionManager::reflectMethod($className, $method);
        dump($reflectMethod);
        $reader = new AnnotationReader();
//        \Hyperf\Di\Annotation\AnnotationReader::
        return $reader->getMethodAnnotations($reflectMethod);
    }
}