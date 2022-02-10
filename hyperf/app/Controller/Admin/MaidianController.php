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

use App\Model\Admin\Banner;
use App\Model\Admin\Vedio;
use App\Model\Maidian;

use Hyperf\Apidog\Annotation\ApiController;
use Hyperf\Apidog\Annotation\PostApi;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\Utils\Context;
use FormBuilder\Driver\CustomComponent;
use Yj\FormBuilder\Factory\Elm;

/**
 * @\Yj\Apidog\Annotation\ApiController(prefix="admin/maidian")
 * @Middleware(CheckAdminMiddleware::class)
 */
class MaidianController extends AbstractController
{

    /**
     * @Inject()
     * @var Maidian
     */
    protected $maidianModel;

    /**
     * @\Yj\Apidog\Annotation\PostApi(path="lists")
     */
    public function lists()
    {
        $type = $this->request->input('type',0);
        $lists = $this->maidianModel->where(function($query)use($type){
        })->orderBy('create_time','desc')->paginate();
        succ($lists);
    }

    /**
     * @\Yj\Apidog\Annotation\PostApi(path="create")
     */
    public function create(){
        $id = $this->request->input('id');
        if($id){
            $formData = $this->maidianModel->where('id',$id)->first()->toArray();
            dump($formData);
        }else{
            $formData = [];
        }
        $form =  Elm::createForm('admin/maidian/save');
        $form->setRule([
            Elm::select('type','类型')->options(function (){
                return collect([0=>'中介卖家',1=>'设计装修'])->transform(function ($label,$value){
                        return compact('label','value');
                })->toArray();
            })->required(),
            Elm::input('name', '名称')->required(),
            Elm::hidden('id',$id)
        ]);
        $lists = $form->setTitle($id?'编辑卖点':'添加卖点')->formData($formData);
        succ(formToData($lists));
    }


    /**
     * @\Yj\Apidog\Annotation\PostApi(path="save")
     */
    public function  save(){
            $params = $this->request->all();
           $id = $params['id'];
           unset($params['id']);
            if($id){
                   $res =  $this->maidianModel->where('id',$id)->update($params);
            }else{
                  $res=  $this->maidianModel->create($params);
            }
            $res?_SUCCESS():_Error();
    }

    public function authorization()
    {
        $uid = Context::get('uid');
        $end_time = $this->maidianModel->where('admin_id',$uid)->value('due_time');
        $message = '会员将于'.$end_time.'到期';
        succ($message);
    }

    public function info(){
            $uid = Context::get('uid');
            $info = $this->maidianModel->where('admin_id',$uid)->first();
            succ($info);
    }

    /**
     * @\Yj\Apidog\Annotation\PostApi(path="delete")
     */
    public function  delete(){
        $id = $this->request->input('id');
        $this->maidianModel->where('id',$id)->delete()?succ():err();
    }

//    public function update(){
//           $uid = Context::get('uid');
//            $params = $this->request->inputs(['company_address','company_name','identity','real_name']);
//           $admin = $this->adminModel->where('admin_id',$uid)->first();
//           empty($admin) && err('不存在该用户');
//
//    }
}
