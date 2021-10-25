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
use App\Middleware\CheckLoginMiddleware;
use App\Model\Admin\News;
use App\Model\Form;
use App\Model\User;
use App\Service\FormService;
use App\Service\UserService;
use FormBuilder\Factory\Elm;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\Utils\Context;
use FormBuilder\Driver\CustomComponent;
use App\Middleware\CheckAdminMiddleware;

/**
 * @AutoController()
 * @Middleware(CheckAdminMiddleware::class)
 */
class NewsController extends AbstractController
{


    /**
     * @Inject()
     * @var News
     */
    protected $newsModel;
    public function lists()
    {
        $type = $this->request->input('type',0);

        $lists = $this->newsModel->where(function($query)use($type){
                if ($type){
                    $query->where('type',$type);
                }
        })->orderBy('create_time','desc')->paginate();
        succ($lists);
    }
    public function create(){
        $id = $this->request->input('id');
        if($id){
        $formData = $this->newsModel->where('id',$id)->first()->toArray();
        }else{
            $formData = [];
        }
        $form =  Elm::createForm('admin/news/save');
        $type = 'tinymce';
        $editor = new CustomComponent($type);
        $form->setRule([

            Elm::input('title', '公告标题')->info('0代表无限制')->required('公告标题必填'),
            Elm::radio('type', '公告类型')->options(function(){
                $options = [];
                foreach(['公告通知'] as $k=>$v){
                    $options[] = Elm::option($k+1, $v);
                }
                return $options;
            })->required('公告类型必填'),
            Elm::hidden('id',$id),
            $editor->field('content')
        ]);
        $formData['data'] = '111111111';
        $lists = $form->setTitle($id?'编辑公告':'添加公告')->formData($formData);
        succ(formToData($lists));
    }

    public function  save(){
            $params = $this->request->inputs(['id','title','content','type']);
           $id = $params['id'];
           unset($params['id']);
            if($id){
                   $res =  $this->newsModel->where('id',$id)->update($params);
            }else{
                    $res=  $this->newsModel->create($params);
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
            $this->newsModel->where('id',$id)->delete()?succ():err();
    }
//    public function update(){
//           $uid = Context::get('uid');
//            $params = $this->request->inputs(['company_address','company_name','identity','real_name']);
//           $admin = $this->adminModel->where('admin_id',$uid)->first();
//           empty($admin) && err('不存在该用户');
//
//    }
}
