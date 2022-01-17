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

namespace App\Controller\Admin\Config;

use App\Exception\FooException;
use App\Exception\Handler\AppExceptionHandler;
use App\Form\Elm;
use App\Model\Admin\Config;
use App\Model\Admin\ConfigClassify;
use App\Model\Admin\ConfigValue;
use App\Model\Model;
use App\Model\User;
use Carbon\Carbon;
use FormBuilder\Driver\CustomComponent;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use App\Controller\AbstractController;
use Hyperf\HttpServer\Annotation\Middleware;
use HyperfAdmin\BaseUtils\Constants\ErrorCode;
use HyperfAdmin\BaseUtils\JWT;
use App\Middleware\CheckAdminMiddleware;
use Yj\Apidog\Annotation\ApiController;
use Yj\Apidog\Annotation\PostApi;

/**
 * @ApiController(prefix="/admin/config_classify")
 * @Middleware(CheckAdminMiddleware::class)
 */
class ConfigClassifyController extends AbstractController
{

    protected $configClassifyService;
    /**
     * @Inject()
     * @var ConfigClassify
     */
    protected $configClassifyModel;

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

    /**
     * @PostApi(path="lists")
     * @return void
     */
    public function lists()
    {
            $lists =  $this->configClassifyModel->where('status',1)->get();
            $lists&&($lists = list_to_tree($lists->toArray(),0,'id'));
            succ($lists);
    }

    /**
     * @PostApi(path="create_form")
     * @return void
     * @throws \FormBuilder\Exception\FormBuilderException
     */
    public function createForm(){

        ($config_classify_id = $this->request->input('tab_id')) || err('tab_id is empty');
        $title = $this->configClassifyModel->where('id',$config_classify_id)->value('name');
        $configs = $this->configModel->where('classify_id',$config_classify_id)->where('status',1)->get();


        $more = [];
        foreach($configs as $v){
            $more[] = $v['key'];
        }

        $formData =  $this->configValueModel->more($more);

        $components = $this->getRule($configs->toArray());
        $form = Elm::createForm('/admin/config/update', $components);
        $lists = $form->setTitle($title)->formData($formData);
        succ(formToData($lists));
    }

    public function getRule(array $configs)
    {
        $components = [];
        foreach ($configs as $config) {
            $component = $this->getComponent($config);
            $components[] = $component;
        }
        return $components;

    }
    public function getComponent($config, $merId='admin')
    {
        dump($config['type']);
        if ($config['type'] == 'image')
            $component = Elm::frameImage($config['key'], $config['name'], '/' . config('admin.' . ($merId ? 'merchant' : 'admin') . '_prefix') . '/setting/uploadPicture?field=' . $config['config_key'] . '&type=1')->modal(['modal' => false])->width('896px')->height('480px')->props(['footer' => false]);
        else if ($config['type'] == 'file') {
            $component = Elm::uploadFile($config['key'], $config['name'], $this->configValueModel->_get('site_url').'/util/file/upload');
        }
        else if ($config['type'] == 'upload'){
            $type = 'YjUpload';
            $span = new CustomComponent($type);
////            $span->action( $this->configValueModel->_get('site_url').'/util/file/upload');
            $span->props(['action'=>$this->configValueModel->_get('site_url').'/util/file/upload','name'=>$config['name']]);
            $component =  $span->field($config['key']);

//            $component = $span->appendChild('自定义 span 标签');
//            $component = Elm::uploadImage($config['key'], $config['name'], $this->configValueModel->_get('site_url').'/util/file/upload');
        } else if (in_array($config['type'], ['select', 'checkbox', 'radio'])) {
            $options = array_map(function ($val) {
                [$value, $label] = explode(':', $val, 2);
                return compact('value', 'label');
            }, explode("\n", $config['config_rule']));
            $component = Elm::{$config['type']}($config['key'], $config['name'])->options($options);
        } else if(in_array($config['type'],['textarea','enumeration','value-label'])){
            $component = Elm::textarea($config['key'], $config['name'])->info($config['info']);
            $component->rows(5);
        } else if($config['type'] == 'tinymce'){
            $component =  Elm::tinymce()->title($config['name'])->field($config['key']);
        } else if($config['type'] == 'slider'){
            $component = Elm::{$config['type']}($config['key'], $config['name'])->min(1)->max(20);
        }
        else
            $component = Elm::{$config['type']}($config['key'], $config['name'])->info($config['info']);
//        if ($config['required']) $component->required();
        return $component;
    }

}
