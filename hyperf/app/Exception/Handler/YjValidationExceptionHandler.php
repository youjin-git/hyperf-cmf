<?php


namespace App\Exception\Handler;

use App\Constants\ErrorCode;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Validation\ValidationExceptionHandler;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class YjValidationExceptionHandler  extends ValidationExceptionHandler
{
    public function handle(Throwable $throwable, ResponseInterface $response)
    {
//        dump($throwable->getMessage());
        $body = $throwable->validator->errors()->first();
        $this->stopPropagation();
        $response = $response->withAddedHeader('content-type', 'application/json; charset=utf-8');
        return $response
            ->withStatus(200)
            ->withBody(new SwooleStream($this->format($body)));
    }
    public function format($message,$status = ErrorCode::FAIL){
            return json_encode(compact('message','status'));
    }

}