<?php


namespace Yj\Apidog\Middleware;


use FastRoute\Dispatcher;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\CoreMiddleware;
use Hyperf\HttpServer\Router\Dispatched;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Yj\Apidog\Validation\ValidationApi;

class ApiValidationMiddleware implements MiddlewareInterface
{

    /**
     * @Inject()
     * @var RequestInterface
     */
    protected $request;

    /**
     * @Inject()
     * @var \Hyperf\HttpServer\Contract\ResponseInterface
     */
    protected $response;

    /**
     * @Inject()
     * @var ValidationApi
     */
    protected $validation;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // TODO: Implement process() method.
        $dispatched = $request->getAttribute(Dispatched::class);

        if ($dispatched->status !== Dispatcher::FOUND) {
            return $handler->handle($request);
        }

        // do not check Closure
        if ($dispatched->handler->callback instanceof \Closure) {
            return $handler->handle($request);
        }

        [$controller, $action] = $this->prepareHandler($dispatched->handler->callback);

        $this->validation->Validated($controller, $action);

        return $handler->handle($request);
    }

    /**
     * @param array|string $handler
     */
    protected function prepareHandler($handler): array
    {
        if (is_string($handler)) {
            if (strpos($handler, '@') !== false) {
                return explode('@', $handler);
            }
            return explode('::', $handler);
        }
        if (is_array($handler) && isset($handler[0], $handler[1])) {
            return $handler;
        }
        throw new \RuntimeException('Handler not exist.');
    }
}