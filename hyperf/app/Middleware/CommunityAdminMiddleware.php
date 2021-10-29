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


class  CommunityAdminMiddleware implements MiddlewareInterface
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
        if ($info["community_admin_status"] != 2) {
            $arr = err(ErrorCode::NO_COMMUNITY_MANAGE_ROLE["msg"], ErrorCode::NO_COMMUNITY_MANAGE_ROLE["code"]);
            return $this->response->json($arr);
        }
//        Context::set('communityUserInfo', $info);
        return $handler->handle($request);

    }

}