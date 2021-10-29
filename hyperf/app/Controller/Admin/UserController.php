<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Controller\Admin;

use App\Middleware\CheckLoginMiddleware;

use App\Model\Admin\Menu;
use App\Model\InsuredPriceConfig;
use App\Model\User;
use App\Service\AdminService;
use FormBuilder\Driver\CustomComponent;
use FormBuilder\Factory\Elm;
use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Annotation\AutoController;
use App\Controller\AbstractController;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\Utils\Context;
use App\Middleware\CheckAdminMiddleware;

/**
 * @AutoController()
 * @Middleware(CheckAdminMiddleware::class)
 */
class UserController extends AbstractController
{
    /**
     * @Inject()
     * @var User
     */
    protected $userModel;

    /**
     * @Inject()
     * @var InsuredPriceConfig
     */
    protected $insuredPriceConfigModel;


    public function create(){

        $id = $this->request->input('id');
        if($id){
           $formData = $this->userModel->where('id',$id)->first()->toArray();
           unset($formData['password']);

        }else{
           $formData = [];
        }
        $form =  Elm::createForm($id?'admin/user/edit':'admin/user/add');

        $insuredPriceConfigInfo = $this->insuredPriceConfigModel->select('name','id')->get()->toArray();

        $form->setRule([
            Elm::hidden('id',$id),
            Elm::input('phone', '手机号码')->disabled((bool)$id)->required('手机号码必填'),
            Elm::input('nickname', '用户昵称')->required('用户昵称必填'),
            $id?Elm::input('password', '密码'):Elm::input('password', '密码')->required('密码必填'),
            Elm::select('insured_price_config_id', '角色')->options(function ()use($insuredPriceConfigInfo){
                $options = [];
                foreach($insuredPriceConfigInfo as $k=>$v){
                    $options[] = Elm::option($v['id'], $v['name']);
                }
                return $options;
            })->required(),
        ]);
        $formData['insured_price_config_id'] = (string)$formData['insured_price_config_id'];
        $lists = $form->setTitle($id?'编辑用户':'添加用户')->formData($formData);
        succ(formToData($lists));
    }

    /**
     * 用户添加
     *
     */
    public function add(){
        ($real_name = $this->request->input('nickname')) || err('姓名为空');;
        ($phone = $this->request->input('phone', '')) || err('手机号码为空');
        ($password = $this->request->input('password', '')) || err('密码为空');

        if($this->userModel->where('phone',$phone)->first()){
            err('该用户已经注册');
        }

        $res = $this->userModel::query()->create(['nickname'=>$real_name,'phone'=>$phone,'password'=>$password]);
        $res?succ():err();
    }

    public function edit(){
        $id = $this->request->input('id');
        $data = $this->request->all();
        if(!$this->userModel->where('id',$id)->exists()){
            err();
        }

        if($data['password']){
                $data['password'] = md5($data['password']);
        }else{
            unset($data['password']);
        }

        $this->userModel->where('id',$id)->update($data)?succ():err();



    }

    public function lists()
    {
        $params = $this->request->all();
        p($params);

        $lists =  $this->userModel->with('insuredPriceConfig')->paginate();

        succ($lists);
    }
    public function change_status(){

        $id = $this->request->input('id');
        $status = $this->request->input('status');
        $this->userModel->where('id',$id)->update(['status'=>$status])?succ():err();

    }
    public function delete(){
        $id = $this->request->input('id');
        $this->userModel->where('id',$id)->delete()?succ():err();

    }
}
