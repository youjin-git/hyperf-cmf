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

use App\Controller\AbstractController;
use App\Middleware\CheckAdminMiddleware;
use App\Model\Admin;
use App\Model\User;
use App\Service\UserService;
use FormBuilder\Factory\Elm;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\Utils\Context;
use App\Middleware\CheckLoginMiddleware;


/**
 * @AutoController()
 * @Middleware(CheckAdminMiddleware::class)
 */
class AdminController extends AbstractController
{

    /**
     * @Inject()
     * @var Admin\Admin
     */
    protected $adminModel;

    public function lists(){
        $lists = $this->adminModel->with('role')->paginate();
        succ($lists);
    }
    /**
     * 用户添加
     *
     */
    public function add(){
        ($real_name = $this->request->input('real_name')) || err('姓名为空');;
        ($phone = $this->request->input('phone', '')) || err('手机号码为空');
        ($password = $this->request->input('password', '')) || err('密码为空');

        if($this->adminModel->where('phone',$phone)->first()){
            err('该用户已经注册');
        }
        $res = $this->adminModel::query()->create(['real_name'=>$real_name,'account'=>$phone,'phone'=>$phone,'pwd'=>$password]);
        $res?succ():err();
    }


    public function create(){
        $id = $this->request->input('id');
        if($id){
            $formData = $this->adminModel->where('id',$id)->first()->toArray();
            unset($formData['password']);
        }else{
            $formData = [];
        }
        $form =  Elm::createForm($id?'admin/admin/update':'admin/update/add');
        $form->setRule([
            Elm::hidden('id',$id),
            Elm::input('username', '账户')->disabled((bool)$id)->required('手机号码必填'),
            Elm::input('real_name', '姓名')->required('姓名必填'),
            $id?Elm::input('password', '密码'):Elm::input('password', '密码')->required('密码必填'),

        ]);
        $formData['insured_price_config_id'] = (string)$formData['insured_price_config_id'];
        $lists = $form->setTitle($id?'编辑用户':'添加用户')->formData($formData);
        succ(formToData($lists));
    }


    public function update(){

        $uid = Context::get('uid');

        $admin = $this->adminModel->where('id',$uid)->first();
        $admin_id = $uid;

        $params = $this->request->inputs(['password','real_name','status','company_address','company_name','identity','due_time']);

        foreach($params as $key=>$v){
              if(empty($v)){
                  unset($params[$key]);
                  continue;
              }
              if($key == 'due_time'){
                  $params['due_time'] = strtotime($v);
              }
              if($key=='password'){
                  $params['password'] = md5($v);
//                  unset($params[$key]);
              }
        }
        if(empty($params)){
             err('参数为空');
        }
        $res = $this->adminModel::query()->where('id',$admin_id)->update($params);
        $res!==false?succ():err('修改失败');
    }

    public function  delete(){

        ($admin_id = $this->request->input('admin_id')) ||  err('admin_id为空');
        if($admin_id == 2){
            err('管理员禁止删除');
        }
        $res = $this->adminModel::destroy($admin_id);
        $res?succ():err('删除失败');
    }

}
