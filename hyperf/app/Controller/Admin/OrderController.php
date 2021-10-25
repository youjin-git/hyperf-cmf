<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Controller\Admin;


use App\Middleware\CheckLoginMiddleware;
use App\Model\Admin\ConfigValue;
use App\Model\Order;
use App\Model\OrderDetail;
use App\Model\User;
use App\Model\Waste;
use App\Request\RegiterRequest;
use App\Request\WasteRequest;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\Utils\Context;
use App\Controller\AbstractController;
use App\Middleware\CheckAdminMiddleware;

/**
 * @AutoController()
 * @Middleware(CheckAdminMiddleware::class)
 */
class OrderController extends AbstractController
{

    /**
     * @Inject()
     * @var ConfigValue
     */
    protected $configValueModel;


    /**
     * @Inject()
     * @var OrderDetail
     */
    protected $orderDetailModel;

    /**
     * @Inject()
     * @var User
     */
    protected $userModel;

    public function lists()
    {
        $uid = $this->request->input('uid');
        $status = $this->request->input('status');
        $order_sn = $this->request->input('order_sn');
        $where = [];
        if ($status !== '') {
            $where[] = ['status', $status];
        }

        if ($order_sn !== '') {
            $where[] = ['id', 'like', "%{$order_sn}%"];
        }

        $lists = $this->orderModel
            ->orderBy('id', 'desc')
            ->where($where)->paginate()->toArray();

        foreach ($lists['data'] as &$v) {
            $v['phone'] = $this->userModel->where('id', $v['user_id'])->value('phone');
        }
        dump($lists);

        succ($lists);
    }


    public function _add(WasteRequest $request)
    {
        $data = $request->validated();
        $uid = Context::get('uid');
        $data['user_id'] = $uid;
        $this->wasteModel->create($data);
        succ();
    }


    public function _edit(WasteRequest $request)
    {
        $data = $request->validated();
        $uid = Context::get('uid');
        $id = $this->request->input('id') ?: err('id is empty');
        if ($this->wasteModel->where('user_id', $uid)->where('id', $id)->update($data)) {
            succ();
        } else {
            err('编辑失败');
        }

    }


    public function _delete()
    {
        $id = $this->request->input('id') ?: err('id is empty');
        $uid = Context::get('uid');
        if ($this->wasteModel->where('user_id', $uid)->where('id', $id)->delete()) {
            succ();
        } else {
            err('删除失败');
        }
    }

    public function change_status()
    {
        $id = $this->request->input('id') ?: err('id is empty');
        $status = $this->request->input('status', 0);
        $this->orderModel->where('id', $id)->update(['status' => $status]) ? succ() : err();

    }

    public function detail()
    {
        $id = $this->request->input('id') ?: err('id is empty');
        $res = $this->orderModel->with(['insured', 'orderDetail'])->where('id', $id)->first();
//        $res = $this->orderDetailModel->where('order_id',$id)->get();

        succ($res);
    }


}
