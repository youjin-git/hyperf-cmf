<?php

namespace App\Controller\Admin\System;


use App\Controller\Admin\BaseController;
use App\Dao\College\CollegeSubjectDao;
use App\Dao\System\SystemAdminDao;
use App\Dao\System\SystemRolesDao;
use App\Form\Elm;
use App\Model\System\SystemAdmin;
use Hyperf\Di\Annotation\Inject;
use Yj\Apidog\Annotation\ApiController;
use Yj\Apidog\Annotation\FormData;
use Yj\Apidog\Annotation\PostApi;

/**
 * @ApiController(prefix="admin/system/admin")
 */
class SystemAdminController extends BaseController
{

    /**
     * @Inject()
     * @var SystemAdminDao
     */
    protected $systemAdminDao;

    /**
     * @Inject()
     * @var SystemRolesDao
     */
    protected $systemRolesDao;

    /**
     * @PostApi(path="lists")
     * @return void
     */
    public function lists()
    {
        $params = $this->getValidatorData();
        $data = $this->systemAdminDao->lists($params);
        _SUCCESS($data);
    }



    /**
     * @PostApi(path="form",description="任务表单")
     * @FormData(key="id",rule="")
     */
    public function Form()
    {
        $params = $this->getValidatorData();
        if($id = $params->get('id')){
            $formData =  $this->collegeTaskDao->detail($id);
            _GetLastSql(1);
        }else{
            $formData = collect();
        }
        $form = Elm::createForm($id?'admin/system/admin/edit':'admin/system/admin/add',[],['labelPosition'=>'right']);
        $form->setRule([
//            \Yj\Form\Elm::YjUpload()->title('上传头像')->field('icon'),
//            Elm::input('username','用户名')->required()->col(12),
//            Elm::password('password','密码')->required()->col(12),
//            Elm::input('nickname','昵称')->required()->col(12),
            Elm::radio('gender', '性别')->options(function (){
                return [['value'=>'1','label'=>'男'],['value'=>'2','label'=>'女']];
            })->required()->col(12),
            \Yj\FormBuilder\Factory\Elm::select('roles', '角色')->options(function (){
                return $this->systemRolesDao->page(false)->lists()->transform(function($item){
                    $value = $item->id;
                    $label = $item->name;
                    return compact('value','label');
                })->toArray();
            })->required()->col(12),
//            Elm::select('roles', '角色')->options(function (){
//                return [['value'=>'1','label'=>'男'],['value'=>'2','label'=>'女']];
//            })->required()->col(12),
            Elm::textarea('remark','备注')->rows(3)->required()->showWordLimit(true),
        ]);
        $lists = $form->setTitle(is_null($id) ? '新增管理员' : '编辑管理员')->formData($formData->toArray());
        _SUCCESS(formToData($lists));
    }

    /**
     * @PostApi(path="add",description="添加用户")
     * @FormData(key="icon",rule="required")
     * @FormData(key="username",rule="required")
     * @FormData(key="nickname",rule="required")
     * @FormData(key="remark",rule="required")
     * @FormData(key="gender",rule="required")
     * @FormData(key="password",rule="required")
     */
    public function add(){
        $params = $this->getValidatorData();
        $this->systemAdminDao->add($params);
        _SUCCESS();
    }
}