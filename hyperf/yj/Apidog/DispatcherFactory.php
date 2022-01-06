<?php


namespace Yj\Apidog;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Router\DispatcherFactory as HyperfDispatcherFactory;
use Yj\Apidog\Annotation\ApiController;

class DispatcherFactory extends HyperfDispatcherFactory
{


    protected function handleController(string $className, Controller $annotation, array $methodMetadata, array $middlewares = []): void
    {

        if (!$methodMetadata) {
            return;
        }
        $router = $this->getRouter($annotation->server);
        $prefix = $this->getPrefix($className, $annotation->prefix);

        foreach ($methodMetadata as $methodName => $values) {
            $methodMiddlewares = $middlewares;
            if (isset($values)) {
                $methodMiddlewares = array_merge($methodMiddlewares, $this->handleMiddleware($values));
                $methodMiddlewares = array_unique($methodMiddlewares);
            }
            foreach ($values as $mapping) {
                $path = $mapping->path;
                if ($path === '') {
                    $path = $prefix;
                } elseif ($path[0] !== '/') {
                    $path = $prefix . '/' . $path;
                }
                dump($mapping->methods, $path, $className, $methodName);
                $router->addRoute($mapping->methods, $path, [$className, $methodName], [
                    'middleware' => $methodMiddlewares,
                ]);
            }
        }
    }

    protected function initAnnotationRoute(array $collector): void
    {

        foreach ($collector as $className => $metadata) {
            if (isset($metadata['_c'][ApiController::class])) {
                $middlewares = $this->handleMiddleware($metadata['_c']);
                $this->handleController($className, $metadata['_c'][ApiController::class], $metadata['_m'] ?? [], $middlewares);
            }
        }

        parent::initAnnotationRoute($collector);
    }
}