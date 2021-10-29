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

use App\Form\Elm;
use App\Middleware\CheckLoginMiddleware;
use App\Model\Admin\Menu;
use App\Model\Project;
use App\Model\User;
use App\Model\UserProject;
use App\Service\AdminService;
use FormBuilder\Driver\CustomComponent;

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
class ProjectController extends AbstractController
{
    /**
     * @Inject()
     * @var Project
     */
    protected $projectModel;

    /**
     * @Inject()
     * @var User
     */
    protected $userModel;

    /**
     * @Inject()
     * @var UserProject
     */
    protected $userProjectModel;
    public function listForm(){
        $table['selection'] = true;
        $table['add'] = [
            'isShow'=>true,
            'txt'=>'添加项目',
            'url'=>'/admin/project/create'
        ];
        $table['operation']=[
            'isShow'=>true,
            'label'=>'操作',
            '__config__'=>[
                ['txt'=>'编辑','action'=>'onEdit']
            ]
        ];
        $table['fields'] = [
            [
                'prop'=>'id',
                'label'=>'id',
            ],
            [
                'prop'=>'name',
                'label'=>'名称',
            ],
            [
                'prop'=>'picture_format',
                'label'=>'图片',
                'tag'=>'img'
            ],
            [
                'prop'=>'price',
                'label'=>'每日返利价钱',
            ],
            [
                'prop'=>'status_format',
                'label'=>'状态',
            ],
        ];
        succ(compact('table'));
    }

    public function create(){
        $id = $this->request->input('id');
        if($id){
           $formData = $this->projectModel->where('id',$id)->first()->toArray();
           unset($formData['password']);

        }else{
           $formData = [];
        }
        $form =  Elm::createForm($id?'/admin/project/edit':'/admin/project/add');

        $form->setRule([
            Elm::hidden('id',$id),
            Elm::input('name', '项目名称')->required('项目名称必填'),
            Elm::YjUpload()->field('picture'),
            Elm::input('price', '返现金额'),
            Elm::tinymce()->field('content')->title('内容'),
        ]);



        $lists = $form->setTitle($id?'编辑':'添加'.'项目')->formData($formData);
        succ(formToData($lists));
    }

    /**
     * 用户添加
     *
     */
    public function add(){
        $params = $this->request->all();
        $res = $this->projectModel->create($params);
        $res?succ():err();
    }

    public function edit(){
        $id = $this->request->input('id');
        $data = $this->request->all();

        $this->projectModel->where('id',$id)->update($data)?succ():err();


    }

    public function lists()
    {
        $params = $this->request->all();
        $lists =  $this->projectModel->orderBy('id','desc')->paginate($params['limit']);
        succ($lists);
    }
    public function change_status(){
        $id = $this->request->input('id');
        $status = $this->request->input('status');
        if($status == 0){
            $this->userProjectModel->where('project_id',$id)->update(['status'=>-2]);
        }
        $this->projectModel->where('id',$id)->update(['status'=>$status])!==false?succ():err();
    }
    public function delete(){
        $id = $this->request->input('id');
        $this->userModel->where('id',$id)->delete()?succ():err();

    }
}
