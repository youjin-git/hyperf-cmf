<?php
namespace App\Exception\Handler;


use App\Constants\ErrorCode;
use Box\Spout\Common\Entity\Style\BorderPart;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Overtrue\Socialite\Exceptions\AuthorizeFailedException;
use Psr\Http\Message\ResponseInterface;
use Throwable;
use Hyperf\ExceptionHandler\ExceptionHandler;

class AuthorizeFailedExceptionHandler extends  ExceptionHandler
{
    public function handle(Throwable $throwable, ResponseInterface $response)
    {
            $this->stopPropagation();
            $response = $response->withAddedHeader('content-type', 'application/json; charset=utf-8');
            return $response
                ->withStatus(200)
                ->withBody(new SwooleStream($this->format($throwable->getMessage())));
    }

    public function format($message,$status = ErrorCode::OPENID_IS_WRONG){
        return json_encode(compact('message','status'));
    }
    public function isValid(Throwable $throwable): bool
    {
        return $throwable instanceof AuthorizeFailedException;
    }

}