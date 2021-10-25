<?php

namespace App\Middleware;

use App\Constants\ErrorCode;
use App\Service\CommunityService;
use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Contract\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Model\User;
use Hyperf\Utils\Context;
use Hyperf\HttpServer\Contract\ResponseInterface as Response;
use Hyperf\Di\Annotation\Inject;

/**增加浏览量
 * Class CommunityAddBrowseCountMiddleware
 * @package App\Middleware
 */
class CommunityAddBrowseCountMiddleware implements MiddlewareInterface
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
        //浏览量
        $community_id = $this->request_cl->input("community_id");
        if (empty($community_id)) {
            return $this->response->json(err(ErrorCode::COMMUNITY_ID_NOT_NULL["msg"], ErrorCode::COMMUNITY_ID_NOT_NULL["code"]));
        }
        //添加浏览量
        Db::table("community")->where("id", $community_id)->increment("browse_count", 1);
        return $handler->handle($request);

    }

}