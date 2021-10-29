<?php


namespace App\Controller\Api;


use App\Controller\AbstractController;
use App\Middleware\CheckLoginMiddleware;
use App\Service\MiniProgramService;
use App\Service\OrderService;
use App\Service\UserService;
use Hyperf\Apidog\Annotation\ApiController;
use Hyperf\Apidog\Annotation\FormData;
use Hyperf\Apidog\Annotation\PostApi;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Middleware;
use Symfony\Component\Serializer\Annotation\Ignore;

/**
 * User: 尤金
 * Date: 2021/6/29
 * @Middleware(CheckLoginMiddleware::class)
 * @ApiController(tag="订单管理",prefix="api/order",description="")
 */
class Order extends AbstractController
{

    /**
     * @Inject()
     * @var OrderService
     */
    protected $orderService;

    /**
     * @PostApi(path="statistics", description="添加订单")
     */
    public function statistics()
    {
        $data = $this->orderService->statistics($this->getUid());
        succ($data);
    }

    /**
     * @PostApi(path="add", description="添加订单")
     * @FormData(key="productId|产品ID", rule="required")
     */
    public function add()
    {
        $params = $this->getValidatorData();
        $params['user_id'] = $this->getUid();
        return $this->orderService->add($params) ? succ() : err();
    }

    /**
     * @PostApi(path="list", description="订单列表")
     * @FormData(key="status|关键词", rule="")
     */
    public function list()
    {
        $params = $this->getValidatorData();
        $user_id = $this->getUid();
        $lists = $this->orderService->list($params);
        succ($lists);
    }

    /**
     * @PostApi(path="detail", description="获取用户信息")
     * @FormData(key="order_id|关键词", rule="")
     */
    public function detail()
    {
        $params = $this->getValidatorData();
        $data = $this->orderService->detail($params['order_id'], $this->getUid());
        $data ? succ($data) : err();
    }
}