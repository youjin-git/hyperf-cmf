<?php

namespace App\Controller\Admin\College;

use App\Controller\Admin\BaseController;
use App\Dao\System\SystemAreasDao;
use App\Form\Elm;
use Hyperf\Di\Annotation\Inject;
use Yj\Apidog\Annotation\ApiController;
use Yj\Apidog\Annotation\GetApi;
use Yj\Apidog\Annotation\PostApi;

/**
 * @ApiController(prefix="/admin/task")
 */
class TaskController extends BaseController
{

    /**
     * @Inject()
     * @var SystemAreasDao
     */
    protected $systemAreasDao;

    /**
     * @PostApi(path="form",description="任务表单")
     */
    public function Form()
    {
        $params = $this->getValidatorData();
        $id = $params->get('id');
        $form = Elm::createForm($id?'admin/config/setting/edit':'admin/config/setting/add',[],['labelPosition'=>'right']);
        $form->setRule([
            Elm::input('username','姓名')->required()->col(1),
            Elm::radio('gender', '性别')->options(function (){
                return [['value'=>'1','label'=>'男'],['value'=>'2','label'=>'女']];
            })->required()->col(12),
            Elm::input('ticket', '准考证')->required()->col(12),
            Elm::input('mobile', '手机号')->required()->col(12),
            Elm::input('department_type', '科室')->required(),
            Elm::cascader('city', '省份')->options(function (){
                    return list_to_tree($this->systemAreasDao->DaoLevel(2)->select(['parentcode','code as value','title as label'])->get(),pid:'parentcode',id:'value');
            }),
            Elm::checkbox('departments', '选科')->options(function (){
                return [];
            }),

            Elm::textarea('config_rule', '规则'),
            Elm::number('sort', '排序', 0),
//                Elm::switches('required', '必填', 0)->activeValue(1)->inactiveValue(0)->inactiveText('关闭')->activeText('开启'),
            Elm::switches('status', '是否显示', 1)->activeValue(1)->inactiveValue(0)->inactiveText('关闭')->activeText('开启'),
        ]);
        $lists = $form->setTitle(is_null($id) ? '添加配置' : '编辑配置')->formData([]);
        _SUCCESS(formToData($lists));
    }
}