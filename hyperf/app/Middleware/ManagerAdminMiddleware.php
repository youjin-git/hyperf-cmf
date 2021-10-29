<?php

namespace App\Middleware;

use App\Constants\ErrorCode;
use App\Model\SysUser;
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
 * 判断后台用户是否登陆
 * Class ManagerAdminMiddleware
 * @package App\Middleware
 */
class ManagerAdminMiddleware implements MiddlewareInterface
{
    /**
     * @Inject
     * @var Response
     */
    protected $response;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (Context::has('admin_user')) {
            return $handler->handle($request);
        }
        $sysUser = new SysUser();
        $token = $request->getHeader('x-authentication-token')[0] ?? '';
        if (!$token) {
            //用户未登录
            return $this->response->json(err('用户未登录', ErrorCode::TOEKN_NOT_EXISTS));
        }
        $uid = $sysUser->check_token($token);
        if (!$uid) {
            //登录失效
            return $this->response->json(err('token失效', ErrorCode::TOEKN_INVALID));
        }
        $admin_user = SysUser::query()->where("id",$uid)->get()->first()->toArray();
        Context::set('admin_user', $admin_user);
//        var_dump($admin_user);
        return $handler->handle($request);

    }

}