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
use FormBuilder\Factory\Elm;
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

/**
 * @\Yj\Apidog\Annotation\ApiController(prefix="admin/banner")
 * @Middleware(CheckAdminMiddleware::class)
 */
class BannerController extends AbstractController
{

    /**
     * @Inject()
     * @var Banner
     */
    protected $bannerModel;

    /**
     * @\Yj\Apidog\Annotation\PostApi(path="lists")
     */
    public function lists()
    {
        $type = $this->request->input('type',0);
        $lists = $this->bannerModel->with('imagePath')->where(function($query)use($type){

        })->orderBy('create_time','desc')->paginate();
        succ($lists);
    }

    /**
     * @\Yj\Apidog\Annotation\PostApi(path="create")
     */
    public function create(){
        $id = $this->request->input('id');
        if($id){
        $formData = $this->bannerModel->where('id',$id)->first()->toArray();
        }else{
            $formData = [];
        }
        $form =  Elm::createForm('admin/banner/save');

        $form->setRule([
            \Yj\Form\Elm::YjUpload()->title('图片')->field('image'),
            Elm::input('title', '标题')->required('标题必填'),
            Elm::input('link', '链接')->required('跳转必填'),
            Elm::hidden('id',$id)
        ]);

        $lists = $form->setTitle($id?'编辑banner':'添加banner')->formData($formData);
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
                   $res =  $this->bannerModel->where('id',$id)->update($params);
            }else{
                  $res=  $this->bannerModel->create($params);
            }
            $res?_SUCCESS():_Error();
    }

    public function authorization()
    {
        $uid = Context::get('uid');
        $end_time = $this->adminModel->where('admin_id',$uid)->value('due_time');
        $message = '会员将于'.$end_time.'到期';
        succ($message);
    }

    public function info(){
            $uid = Context::get('uid');
            $info = $this->adminModel->where('admin_id',$uid)->first();
            succ($info);
    }

    /**
     * @\Yj\Apidog\Annotation\PostApi(path="delete")
     */
    public function  delete(){
        $id = $this->request->input('id');
        $this->bannerModel->where('id',$id)->delete()?succ():err();
    }

//    public function update(){
//           $uid = Context::get('uid');
//            $params = $this->request->inputs(['company_address','company_name','identity','real_name']);
//           $admin = $this->adminModel->where('admin_id',$uid)->first();
//           empty($admin) && err('不存在该用户');
//
//    }
}
