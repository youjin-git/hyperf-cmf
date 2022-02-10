<?php
/**
 * Created by PhpStorm.
 * User: zwc
 * Date: 2022/2/4
 * Time: 14:43
 */

namespace App\Controller\User;


use App\Dao\Order\OrderDao;
use App\Dao\Users\UsersDao;
use App\Middleware\CheckLoginMiddleware;
use App\Service\Pay\PayFactory;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Middleware;
use Yj\Apidog\Annotation\ApiController;
use Yj\Apidog\Annotation\FormData;
use Yj\Apidog\Annotation\PostApi;


/**
 * Class OrderController
 * @ApiController(prefix="user/order")
 * @Middleware(CheckLoginMiddleware::class)
 */
class OrderController extends BaseController
{
    /**
     * @Inject()
     * @var OrderDao
     */
    private $orderDao;

    /**
     * @Inject()
     * @var UsersDao
     */
    protected $usersDao;

    /**
     * @PostApi(path="lists")
     */
    public function lists(){
        $params = $this->request->post();

        $order = $this->orderDao->lists($params+['status'=>'paid']);
        _SUCCESS($order);
    }

    /**
     * @PostApi(path="add")
     */
    public function add(){
        $params = $this->request->post();
        $order = $this->orderDao->add($params);
        _SUCCESS($order);
    }

    /**
     * @PostApi(path="pay")
     * @FormData(key="order_id",rule="required")
     */
    public function pay(){
        $usersId = $this->getUid();
        $openid = $this->usersDao->getOpenid($usersId);
        $orderId = $this->getValidatorData('order_id');
        $orderInfo = $this->orderDao->DaoWhere(['order_id'=>$orderId])->first();
        $payFactory = PayFactory::wechat();
        $payFactory->setPrice(0.01);
        $payFactory->setTitle($orderInfo->id . '代缴');
        $payFactory->setOrderSn($orderInfo->id);
        $payFactory->setOpenid($openid);
        $payFactory->setNotifyUrl('/notify/wx');
        $url = $payFactory->exec();
        succ($url);

    }

    /**
     * @PostApi(path="detail")
     * @FormData(key="order_id",rule="required")
     */
    public function detail(){
        $order_id = $this->getValidatorData('order_id');
        $data = $this->orderDao->detail($order_id);
        _SUCCESS($data);
    }

    /**
     * @PostApi(path="add_desc")
     * @FormData(key="desc",rule="required")
     */
    public function addDesc(){
        $desc = $this->getValidatorData('desc');
        $users_id = $this->getUid();
         $this->orderDao->addDesc($desc,$users_id);
        _SUCCESS();
    }

    /**
     * @PostApi(path="add_change")
     * @FormData(key="content",rule="required")
     * @FormData(key="images",rule="required")
     * @FormData(key="type",rule="required")
     * @FormData(key="order_id",rule="required")
     */
    public function addChange(){
        $params = $this->getValidatorData();
        $users_id = $this->getUid();
        $this->orderDao->addChange($params,$users_id);
        _SUCCESS();
    }
}