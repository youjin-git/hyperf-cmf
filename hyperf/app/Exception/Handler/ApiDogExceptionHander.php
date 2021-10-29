<?php


namespace App\Exception\Handler;

use App\Constants\ErrorCode;
use Hyperf\Apidog\Exception\ApiDogException;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Validation\ValidationExceptionHandler;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class ApiDogExceptionHander  extends ExceptionHandler
{
    public function handle(Throwable $throwable, ResponseInterface $response)
    {
//        p($throwable->v);

//        dump($throwable->getMessage());
        $body = $throwable->getMessage();

        $body =  explode(PHP_EOL, $body)[0];

        $this->stopPropagation();
        $response = $response->withAddedHeader('content-type', 'application/json; charset=utf-8');
        return $response
            ->withStatus(200)
            ->withBody(new SwooleStream($this->format($body)));
    }

    public function format($message,$status = ErrorCode::FAIL){
            return json_encode(compact('message','status'));
    }

    public function isValid(Throwable $throwable): bool
    {
        return $throwable instanceof ApiDogException;
    }



}