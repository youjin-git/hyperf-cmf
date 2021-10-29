<?php

namespace App\Middleware;

use App\Constants\ErrorCode;
use App\Service\CommunityService;
use Hyperf\HttpServer\Contract\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Model\User;
use Hyperf\Utils\Context;
use Hyperf\HttpServer\Contract\ResponseInterface as Response;
use Hyperf\Di\Annotation\Inject;

/**
 * 社区封禁状态
 * Class CommunityUserBankMiddleware
 * @package App\Middleware
 */
class CommunityUserBankMiddleware implements MiddlewareInterface
{
    /**
     * @Inject
     * @var Response
     */
    protected $response;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        //取出用户id
        $uid = Context::get('uid');
        //判断用户是否拥有管理员权限
        $communityService = new CommunityService();
        $info = $communityService->getUserInfo($uid);
        if ($info["community_ban_status"] == 1) {
            $arr = err(ErrorCode::COMMUNITY_ACCOUNT_FOREVER_BAN["msg"], ErrorCode::COMMUNITY_ACCOUNT_FOREVER_BAN["code"]);
            return $this->response->json($arr);
        }
        if (time() < $info["community_ban_end_time"]) {
            $arr = err(ErrorCode::COMMUNITY_ACCOUNT_SHORT_BAN["msg"] . " " . date("Y-m-d H:i:s", $info["community_ban_end_time"]), ErrorCode::COMMUNITY_ACCOUNT_SHORT_BAN["code"]);
            return $this->response->json($arr);
        }
//        Context::set('communityUserInfo', $info);
        return $handler->handle($request);

    }

}