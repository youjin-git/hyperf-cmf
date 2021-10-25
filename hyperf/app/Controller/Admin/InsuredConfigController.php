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
use App\Model\InsuredConfig;
use App\Model\Order;
use App\Model\OrderDetail;
use App\Model\User;
use App\Model\Waste;
use App\Request\RegiterRequest;
use App\Request\WasteRequest;
use Carbon\Carbon;
use FormBuilder\Driver\CustomComponent;
use FormBuilder\Factory\Elm;
use Hyperf\DbConnection\Db;
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
class InsuredConfigController extends AbstractController
{

    /**
     * @Inject()
     * @var InsuredConfig
     */
    protected $insuredConfigModel;


    public function create(){
        $id = $this->request->input('id');
        if($id){
            $formData = $this->insuredConfigModel->where('id',$id)->first()->toArray();
        }else{
            $formData = [];
        }
        $formStyle = Elm::formStyle();
        $config = Elm::config();
        $formStyle->labelWidth('180px');
        $config->formStyle($formStyle);
        $form =  Elm::createForm($id?'admin/insured_config/edit':'admin/insured_config/add',[]);

        $form->setConfig($config);





//        p(Elm::input('social_security_max_base_price', '社保最高基数')->required()->);

        $form->setRule([
            $id?Elm::month('month','月份'):Elm::monthRange('month','月份')->col(16),
            Elm::input('fund_rate', '公积金费率')->required()->col(16),
            Elm::input('fund_min_base_price', '公积金最低基数')->required()->col(12),
            Elm::input('fund_max_base_price', '公积金最高基数')->required()->col(12),
            Elm::input('social_security_min_base_price', '社保最低基数')->required()->col(12),
            Elm::input('social_security_max_base_price', '社保最高基数')->required()->col(12),
            Elm::input('p_pension_rate', '个人养老保险费率')->required()->col(12),
            Elm::input('g_pension_rate', '企业养老保险费率')->required()->col(12),
            Elm::input('p_medical_rate', '个人医疗保险费率')->required()->col(12),
            Elm::input('g_medical_rate', '企业医疗保险费率')->required()->col(12),
            Elm::input('p_unemployment_rate', '个人失业保险费率')->required()->col(12),
            Elm::input('g_unemployment_rate', '企业失业保险费率')->required()->col(12),
            Elm::input('p_injury_rate', '个人工伤保险费率')->required()->col(12),
            Elm::input('g_injury_rate', '企业工伤保险费率')->required()->col(12),
            Elm::input('p_birth_rate', '个人生育保险费率')->required()->col(12),
            Elm::input('g_birth_rate', '企业生育保险费率')->required()->col(12),
            Elm::input('p_disability_rate', '个人残疾人保障金费率')->required()->col(12),
            Elm::input('g_disability_rate', '企业残疾人保障金费率')->required()->col(12),
            Elm::input('advance_payment', '预收款')->required()->col(12),
            Elm::hidden('id',$id)
        ]);
        foreach($formData as &$v){
             $v = (string)$v;
        }
        $lists = $form->setTitle($id?'编辑配置':'添加配置')->formData($formData);
        p($formData);

        succ(formToData($lists));
    }

    public function lists(){
        $uid =$this->request->input('uid');
        $status = $this->request->input('status');
        $order_sn = $this->request->input('order_sn');
        $where = [];
//        if($status!==''){
//            $where[] = ['status',$status];
//        }
//
//        if($order_sn!==''){
//            $where[] = ['id','like',"%{$order_sn}%"];
//        }

        $lists = $this->insuredConfigModel
            ->orderBy('month','desc')
            ->where($where)->paginate()->toArray();

        succ($lists);
    }


    public function add(){
                $month = $this->request->input('month');
                [$start_month,$end_month] = $month;
               $start_month = Carbon::create(explode('-',$start_month)[0],explode('-',$start_month)[1]);
               $end_month = Carbon::create(explode('-',$end_month)[0],explode('-',$end_month)[1]);
               $diff =  $start_month->diffInMonths($end_month);
               $diff_months = [$start_month->format('Y-m')];
               do{
                   $diff_months[] = $start_month->addMonth()->format('Y-m');
               }while($diff--);

                p($diff_months);

                $data = $this->request->inputs([
                    'advance_payment',
                    'p_pension_rate',
                    'g_pension_rate',
                    'p_medical_rate',
                    'g_medical_rate',
                    'p_unemployment_rate',
                    'g_unemployment_rate',
                    'p_injury_rate',
                    'g_injury_rate',
                    'p_birth_rate',
                    'g_birth_rate',
                    'p_disability_rate',
                    'g_disability_rate',
                    'social_security_min_base_price',
                    'social_security_max_base_price',
                    'fund_rate',
                    'fund_max_base_price',
                    'fund_min_base_price',
                ]);
              $data['g_rate'] =  $data['g_pension_rate'] +  $data['g_medical_rate'] + $data['g_unemployment_rate'] + $data['g_injury_rate'] + $data['g_birth_rate'] + $data['g_disability_rate'];
              $data['p_rate'] =  $data['p_pension_rate'] +  $data['p_medical_rate'] + $data['p_unemployment_rate'] + $data['p_injury_rate'] + $data['p_birth_rate'] + $data['p_disability_rate'];
              $data['rate'] =   $data['p_rate']+  $data['g_rate'];
              foreach($diff_months as $month){
                  $data['month'] = $month;
                  if($this->insuredConfigModel->where('month',$data['month'])->first()){
//                      err('已经存在该日期');
                  }else{
                      $this->insuredConfigModel->create($data);
                  }
              }
              succ();


    }



    public function edit(){
            //先删除
        $id = $this->request->input('id');

        $data = $this->request->inputs([
            'advance_payment',
            'month',
            'p_pension_rate',
            'g_pension_rate',
            'p_medical_rate',
            'g_medical_rate',
            'p_unemployment_rate',
            'g_unemployment_rate',
            'p_injury_rate',
            'g_injury_rate',
            'p_birth_rate',
            'g_birth_rate',
            'p_disability_rate',
            'g_disability_rate',
            'social_security_min_base_price',
            'social_security_max_base_price',
            'fund_rate',
            'fund_max_base_price',
            'fund_min_base_price',

        ]);
        $data['g_rate'] =  $data['g_pension_rate'] +  $data['g_medical_rate'] + $data['g_unemployment_rate'] + $data['g_injury_rate'] + $data['g_birth_rate'] + $data['g_disability_rate'];
        $data['p_rate'] =  $data['p_pension_rate'] +  $data['p_medical_rate'] + $data['p_unemployment_rate'] + $data['p_injury_rate'] + $data['p_birth_rate'] + $data['p_disability_rate'];
        $data['rate'] =   $data['p_rate']+  $data['g_rate'];
        Db::beginTransaction();
        $this->insuredConfigModel->where('id',$id)->delete();

        try{
            if($this->insuredConfigModel->where('month',$data['month'])->first()){
                err('已经存在该日期');
            }
            $this->insuredConfigModel->create($data);
            Db::commit();
        }catch (\Throwable $ex){
            Db::rollBack();
            err();
        }
        succ();
    }

    public function delete(){
        $id = $this->request->input('id')?:err('id is empty');
        p($id);
        if($this->insuredConfigModel->where('id',$id)->delete()){
            succ();
        }else{
            err('删除失败');
        }
    }

    public function change_status(){
        $id = $this->request->input('id')?:err('id is empty');
        $status = $this->request->input('status',0);
        $this->orderModel->where('id',$id)->update(['status'=>$status])?succ():err();

    }
    public function detail(){
        $id = $this->request->input('id')?:err('id is empty');
        $res = $this->orderDetailModel->where('order_id',$id)->get();

        succ($res);
    }



}
