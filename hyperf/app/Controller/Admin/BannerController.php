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
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\Utils\Context;
use FormBuilder\Driver\CustomComponent;

/**
 * @AutoController()
 * @Middleware(CheckAdminMiddleware::class)
 */
class BannerController extends AbstractController
{

    /**
     * @Inject()
     * @var Banner
     */
    protected $bannerModel;

    public function lists()
    {
        $type = $this->request->input('type',0);
        $lists = $this->bannerModel->where(function($query)use($type){
                if ($type){
                    $query->where('type',$type);
                }
        })->orderBy('create_time','desc')->paginate();
        succ($lists);
    }
    public function create(){
        $id = $this->request->input('id');
        if($id){
        $formData = $this->bannerModel->where('id',$id)->first()->toArray();
        }else{
            $formData = [];
        }
        $form =  Elm::createForm('admin/banner/save');

        $type = 'YjUpload';
        $span = new CustomComponent($type);
////            $span->action( $this->configValueModel->_get('site_url').'/util/file/upload');
        $span->props(['action'=>systemConfig('site_url').'/util/file/upload','name'=>'上传图片']);
        $component =  $span->field('picture');




        $form->setRule([
            \App\Form\Elm::wangeditor(),
            Elm::input('title', '标题')->required('标题必填'),
            $component,
            Elm::input('link', '链接')->required('跳转必填'),
            Elm::hidden('id',$id)
        ]);
        $lists = $form->setTitle($id?'编辑banner':'添加banner')->formData($formData);
        succ(formToData($lists));
    }
    public function  save(){
            $params = $this->request->all();
            p($params);
           $id = $params['id'];
           unset($params['id']);
            if($id){

                   $res =  $this->bannerModel->where('id',$id)->update($params);
            }else{
                  $res=  $this->bannerModel->create($params);
            }
            $res?succ():err();
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
