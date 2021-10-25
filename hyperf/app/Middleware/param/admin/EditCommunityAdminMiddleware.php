<?php

namespace App\Middleware\param\admin;

use App\Constants\ErrorCode;
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
 * 校验后台修改社区管理员参数
 * Class CommunityParamMiddleware
 * @package App\Middleware\param
 */
class EditCommunityAdminMiddleware implements MiddlewareInterface
{
    /**
     * @Inject
     * @var Response
     */
    protected $response;
    /**
     * @Inject
     * @var RequestInterface
     */
    protected $request_cl;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $params = ["id", "username", "telephone", "gender", "head_img_url",
            "bookshelf_capacity", "introduce", "status", "password", "community_admin_status"];
        $request_param = $this->request_cl->inputs($params);
        if(empty($request_param["id"])){
            return $this->response->json(err(ErrorCode::PARAM_ERROR["msg"], ErrorCode::PARAM_ERROR["code"]));
        }
        $request_param["delete_reason"] = "修改管理员信息";
        $request_param["type"] = 78;
        $serverParams = $this->request_cl->getServerParams();
        $request_param["operator_ip"] = $serverParams['remote_addr'];
        Context::set("edit_community_admin_param",$request_param);
        return $handler->handle($request);
    }

}