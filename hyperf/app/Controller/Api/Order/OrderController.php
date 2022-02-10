<?php
namespace App\Controller\Api\Order;

use App\Dao\Order\OrderDao;
use Hyperf\Apidog\Annotation\PostApi;
use Hyperf\Di\Annotation\Inject;
use Yj\Apidog\Annotation\ApiController;

/**
 * Class OrderController
 * @ApiController(prefix="api/order/order")
 */
class OrderController extends  \App\Controller\AbstractController
{
    /**
     * @Inject()
     * @var OrderDao
     */
    protected $orderDao;

    /**
     * @PostApi(path="lastest")
     */
    public function lastest(){
        $data = $this->orderDao->orderByDesc('id')->limit(3)->get();
        _SUCCESS($data);
    }

}