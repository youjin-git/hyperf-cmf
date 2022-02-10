<?php
namespace App\Controller\Admin\Order;


use App\Controller\Admin\BaseController;
use App\Dao\Order\OrderDao;
use Hyperf\Di\Annotation\Inject;
use Yj\Apidog\Annotation\ApiController;
use Yj\Apidog\Annotation\FormData;
use Yj\Apidog\Annotation\PostApi;
use Yj\Form\Elm;

/**
 * Class OrderController
 * @ApiController(prefix="admin/order/order")
 */
class OrderController extends BaseController
{
    /**
     * @Inject()
     * @var OrderDao
     */
    protected $orderDao;


    /**
     * @PostApi(path="lists")
     */
    public function lists(){
        $params = $this->request->post();
        $data = $this->orderDao->lists($params);
        _SUCCESS($data);
    }

    /**
     * @PostApi(path="detail")
     */
    public function detail(){
        $params = $this->request->post();
        $data = $this->orderDao->detail($params['order_id']);
        _SUCCESS($data);
    }

    /**
     * @PostApi(path="finish")
     * @FormData(key="order_id",rule="required")
     * @FormData(key="vedio",rule="required")
     */
    public function finish(){
        $params = $this->getValidatorData();
        $order_id = $params->get('order_id');

        $order = $this->orderDao->DaoWhere(compact('order_id'))->first();
        if($order->status !== 2){
            _Error('订单状态不对');
        }
        $order->status = 3;
        $order->vedio =$params->get('vedio');
        $order->save();
        _SUCCESS();
    }

    /**
     * @PostApi(path="finish-form")
     */
    public function finishForm(){
        $order_id = $this->request->input('order_id');
        $formData = [];
        $form =  Elm::createForm('admin/order/order/finish');
        $form->setRule([
            Elm::YjFile()->title('视频')->field('vedio'),
            Elm::hidden('order_id',$order_id)
        ]);
        $lists = $form->setTitle('提交制作')->formData($formData);
        succ(formToData($lists));
    }
}