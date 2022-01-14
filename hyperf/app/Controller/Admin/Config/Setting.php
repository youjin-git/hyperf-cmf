<?php


namespace App\Controller\Admin\Config;


use App\Controller\AbstractController;
use App\Form\Elm;
use App\Middleware\CheckAdminMiddleware;
use App\Model\Admin\Config;
use App\Model\Admin\ConfigClassify;
use App\Model\Admin\ConfigValue;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middleware;

/**
 * @AutoController()
 * @Middleware(CheckAdminMiddleware::class)
 */
class Setting extends AbstractController
{
    /**
     * @Inject()
     * @var ConfigClassify
     */
    protected $configClassify;

    /**
     * @Inject()
     * @var Config
     */
    protected $configModel;

    /**
     * @Inject()
     * @var ConfigValue
     */
    protected $configValueModel;

    const TYPES = [
        'input' => '文本框',
        'number' => '数字框',
        'textarea' => '多行文本框',
        'radio' => '单选框',
        'checkbox' => '多选框',
        'select' => '下拉框',
        'file' => '文件上传',
        'image' => '图片上传',
        'color' => '颜色选择框',
        'enumeration'=>'枚举',
        'value-label'=>'value-label',
    ];

    public function create(){
            $id = $this->request->input('id');
            $form = Elm::createForm($id?'admin/config/setting/edit':'admin/config/setting/add');
            $form->setRule([
                Elm::cascader('config_classify_id', '上级分类')->options(function () {
                    $configClassifyData = $this->configClassify->select(['name as label','id  as value'])->get()->toArray();
                    dump($configClassifyData);
                    return array_merge([['value' => 0, 'label' => '请选择']],$configClassifyData);
                })->props(['props' => ['checkStrictly' => true, 'emitPath' => false]]),
                Elm::select('user_type', '后台类型', 0)->options([
                    ['label' => '总后台配置', 'value' => 0],
                    ['label' => '商户后台配置', 'value' => 1],
                ])->requiredNum(),
                Elm::input('config_name', '配置名称')->required(),
                Elm::input('config_key', '配置key')->required(),
                Elm::input('info', '说明'),
                Elm::select('config_type', '配置类型')->options(function () {
                    $options = [];
                    foreach (self::TYPES as $value => $label) {
                        $options[] = compact('value', 'label');
                    }
                    return $options;
                })->required(),
                Elm::textarea('config_rule', '规则'),
                Elm::number('sort', '排序', 0),
//                Elm::switches('required', '必填', 0)->activeValue(1)->inactiveValue(0)->inactiveText('关闭')->activeText('开启'),
                Elm::switches('status', '是否显示', 1)->activeValue(1)->inactiveValue(0)->inactiveText('关闭')->activeText('开启'),
            ]);

        $lists = $form->setTitle(is_null($id) ? '添加配置' : '编辑配置')->formData([]);
        succ(formToData($lists));

    }
    public function add(){
        $data = $this->request->inputs(['config_key','config_value','config_type','config_classify_id','config_name','status']);

        $this->configModel->create([
            'classify_id'=>$data['config_classify_id'],
            'name'=>$data['config_name'],
            'key'=>$data['config_key'],
            'type'=>$data['config_type'],
            'status'=>$data['status']
        ]);
        $this->configValueModel->create([
            'key'=>$data['config_key'],
            'type'=>$data['config_type'],
            'config_value'=>$data['config_value'],
        ]);

       succ();
    }
}