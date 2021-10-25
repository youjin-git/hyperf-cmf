<?php

namespace App\Middleware;

use App\Constants\ErrorCode;
use App\Model\Admin\Admin;
use Hyperf\HttpServer\Contract\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Model\User;
use Hyperf\Utils\Context;
use Hyperf\HttpServer\Contract\ResponseInterface as Response;
use Hyperf\Di\Annotation\Inject;


class CheckLoginMiddleware implements MiddlewareInterface
{
    /**
     * @Inject
     * @var Response
     */
    protected $response;

    /**
     * @Inject()
     * @var Admin
     */
    protected $adminModel;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        //排除
//        p($request->getUri());
//       if(in_array($request->getUri()->getPath(),['/cloud/info'])){
//           return $handler->handle($request);
//       }

//        p($request->getUri()->getPath());

        if (Context::has('uid')) {
            return $handler->handle($request);
        }
        $User = new User();

        $token = $request->getHeaderLine('X-token') ?: '1614781075835825164';

        if (!$token) {
            //用户未登录
            return $this->response->json(err('用户未登录', ErrorCode::TOEKN_NOT_EXISTS));
        }

        $uid = $User->check_token($token);

        if (!$uid) {
            //登录失效
            err('token失效', ErrorCode::TOEKN_INVALID);
        }

        //获得用户信息
//        $info = $this->adminModel->where('id',$uid)->first();
//        if(empty($info)){
//            err('用户不存在',ErrorCode::TOEKN_INVALID);
//        }else{
//            Context::set('user_info', $info);
//        }


        //取出用户id
        Context::set('uid', $uid);
        return $handler->handle($request);

    }

}