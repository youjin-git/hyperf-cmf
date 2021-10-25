<?php
namespace App\Exception\Handler;


use Box\Spout\Common\Entity\Style\BorderPart;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\ResponseInterface;
use Throwable;
use Hyperf\ExceptionHandler\ExceptionHandler;

class YjExceptionHandler extends  ExceptionHandler
{
    public function handle(Throwable $throwable, ResponseInterface $response)
    {

            $this->stopPropagation();
            $response = $response->withAddedHeader('content-type', 'application/json; charset=utf-8');
            return $response
                ->withStatus(200)
                ->withBody(new SwooleStream($throwable->getMessage()));

    }
    public function isValid(Throwable $throwable): bool
    {
        return $throwable instanceof \App\Exception\YjException;
    }

}