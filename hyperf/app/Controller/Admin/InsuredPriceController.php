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
use App\Model\InsuredPriceConfig;
use App\Model\InsuredPriceValue;
use App\Model\Order;
use App\Model\OrderDetail;
use App\Model\User;
use App\Model\Waste;
use App\Request\RegiterRequest;
use App\Request\WasteRequest;
use FormBuilder\Driver\CustomComponent;
use FormBuilder\Factory\Elm;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\Utils\Context;
use App\Controller\AbstractController;
use App\Middleware\CheckAdminMiddleware;
use mysql_xdevapi\Exception;

/**
 * @AutoController()
 * @Middleware(CheckAdminMiddleware::class)
 */
class InsuredPriceController extends AbstractController
{

    /**
     * @Inject()
     * @var InsuredPriceConfig
     */
    protected $insuredPriceConfigModel;

    /**
     * @Inject()
     * @var InsuredPriceValue
     */
    protected $insuredPriceValueModel;

    public function get_insured_config(){
        $insuredPriceConfigInfo = $this->insuredPriceConfigModel->select('name','id')->get()->toArray();
        succ($insuredPriceConfigInfo);
    }
    public function create(){
        $id = $this->request->input('id');

        if($id){
            $formData = $this->insuredPriceValueModel->where('id',$id)->first()->toArray();
        }else{
            $formData = [];
        }

        $formStyle = Elm::formStyle();
        $config = Elm::config();
        $formStyle->labelWidth('180px');
        $config->formStyle($formStyle);
        $form =  Elm::createForm($id?'admin/insured_price/edit':'admin/insured_price/add',[]);
        $form->setConfig($config);


        $insuredPriceConfigInfo = $this->insuredPriceConfigModel->select('name','id')->get()->toArray();

        $form->setRule([
            Elm::select('insured_price_config_id', '角色')->options(function ()use($insuredPriceConfigInfo){
                $options = [];
                foreach($insuredPriceConfigInfo as $k=>$v){
                    $options[] = Elm::option($v['id'], $v['name']);
                }
                return $options;
            })->required(),
            Elm::input('month','月数')->required(),
            Elm::input('prices','单月服务费')->required(),
            Elm::hidden('id',$id)
        ]);
        foreach($formData as &$v){
             $v = (string)$v;
        }
        $lists = $form->setTitle($id?'编辑服务费':'添加服务费')->formData($formData);
        p($formData);

        succ(formToData($lists));
    }

    public function lists(){

        $insured_price_config_id = $this->request->input('insured_price_config_id',0);
        $where = [];
        if($insured_price_config_id){
            $where['insured_price_config_id']=$insured_price_config_id;
        }
        $lists = $this->insuredPriceValueModel->with('insuredPriceConfig')
            ->orderBy('month')
            ->where($where)->paginate()->toArray();

        succ($lists);
    }

    public function add(){
                $data = $this->request->inputs(['month','prices','insured_price_config_id']);
              $this->insuredPriceValueModel->create($data)?succ():err();
    }

    public function edit(){
            //先删除
        $id = $this->request->input('id');
        $data = $this->request->inputs(['month','prices','insured_price_config_id']);
        $this->insuredPriceValueModel->where('id',$id)->update($data)?succ():err();
    }

    public function delete(){
        $id = $this->request->input('id')?:err('id is empty');
        if($this->insuredPriceModel->where('id',$id)->delete()){
            succ();
        }else{
            err('删除失败');
        }
    }

}
