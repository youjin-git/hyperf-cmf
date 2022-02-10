<?php

namespace App\Dao\Order;
use App\Model\Order\Order;
use App\Model\Order\OrderChange;
use App\Model\Order\OrderMaidian;
use App\Model\Order\OrderMaidianDesc;
use Hyperf\DbConnection\Db;
use Hyperf\Utils\Collection;
use \Yj\Daos\BaseDao;
use Hyperf\Database\Model\Builder;
use Yj\Daos\Verify;

/**
 * @var Order
 */
class OrderDao extends BaseDao
{

    public function DaoWhere(array $params)
    {
        return $this->getDaoQuery($params, function (Verify $verify) {
            $verify('order_id', function (Builder $query, $id) {
                $query->where('id', $id);
            });
            $verify('status', function (Builder $query, $status) {
                if($status === 'paid'){
                    $query->where('status','>',0);
                }else{
                    $query->where('status', $status);
                }

            });

            $verify('title', function (Builder $query, $title) {
                $query->where('title', $title);
            });
        });
    }

    public function add($params)
    {
        $order = $this->create($params);
        //添加卖点
        collect($params['maidian'])->filter(function ($value){
                return $value>0;
        })->each(function ($num,$maidianId)use($order){
            App(OrderMaidian::class)->create([
                'nums'=>$num,
                'order_id'=>$order->id,
                'maidian_id'=>trim($maidianId,'_'),
            ]);
        });
        return $order;
    }

    public function detail(int $order_id)
    {
        $data = $this->DaoWhere(compact('order_id'))->with(['OrderMaidian'=>function($query){
            $query->with('Maidian');
        },'OrderMaidianDesc'=>function($query){
            $query->with('Maidian');
        }])->first();
        $orderMaidianDetail = [];
        collect($data->OrderMaidian)->transform(function($item)use(&$orderMaidianDetail){
            for($i=1;$i<=$item->nums;$i++){
                $orderMaidianDetail[] = [
                    'name'=>$item->Maidian->name.$i,
                    'order_id'=>$item->order_id,
                    'id'=>$item->id,
                    'maidian_id'=>$item->maidian_id,
                ];
            }
        });
        $data->orderMaidianDetail = $orderMaidianDetail;

        return $data;
    }

    public function addDesc($desc, $users_id)
    {
        Db::beginTransaction();
        try{
            $desc = collect($desc);
            dump( $desc->first());

            $orderId = $desc->first()['order_id'];
            $order = $this->DaoWhere(['order_id'=>$orderId])->first();
            dump($order['status']);
            if($order['status'] !== 1){
                _Exception('订单异常');
            }
            $order->status = 2;
            $order->save();
            App(OrderMaidianDesc::class)->where('order_id',$orderId)->delete();
            $desc->each(function ($item){
                App(OrderMaidianDesc::class)->create([
                    'order_id'=>$item['order_id'],
                    'order_maidian_id'=>$item['id'],
                    'maidian_id'=>$item['maidian_id'],
                    'desc'=>$item['desc'],
                    'images'=> json_encode($item['images']),
                ]);
                return true;
            });
            Db::commit();
            _GetLastSql();
        }catch (\Exception $exception){
            dump($exception);
            Db::rollBack();
        }
    }

    public function lists($params)
    {
        $data = $this->DaoWhere($params)->orderByDesc('id')->with('OrderChangeDetail')->getList();
        return $data;
    }

    public function addChange(Collection $params, $users_id)
    {
        if(App(OrderChange::class)->where('order_id',$params->get('order_id'))->where('status',1)->first()){
            _Error('已经提交了修改');
        }
        $order = $this->DaoWhere(['order_id'=>$params->get('order_id')])->first();
        if($order->status !== 3){
            _Error('订单状态不对');
        }
        $order->status = 2;
        $order->save();
        $params->offsetSet('images',json_encode($params->get('images')));
        return App(OrderChange::class)->create($params->toArray());
    }


}