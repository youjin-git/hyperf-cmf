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

use App\Exception\YjException;
use App\Exception\Handler\AppExceptionHandler;
use App\Model\Admin;
use App\Model\Model;
use App\Model\User;
use Carbon\Carbon;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use App\Controller\AbstractController;
use Hyperf\HttpServer\Annotation\Middleware;
use HyperfAdmin\BaseUtils\Constants\ErrorCode;
use HyperfAdmin\BaseUtils\JWT;
use FormBuilder\Factory\Elm;
use App\Middleware\CheckAdminMiddleware;



class ConfigController extends AbstractController
{
    /**
     * @Inject()
     * @var Admin\ConfigValue
     */
    protected $configValueModel;

    /**
     * @Inject()
     * @var Admin\ConfigClassify
     */
    protected $configClassifyModel;

    /**
     * @Inject()
     * @var Admin\Config
     */
    protected $configModel;

    public function create($id, $formData = [])
    {
        $form = Elm::createForm('4399');
        $form->setRule([
            Elm::select('pid', '上级分类', 0)->options(function () {
                $data = [];
                $options = [['value' => 0, 'label' => '顶级分类']];
                foreach ($data as $value => $label) {
                    $options[] = compact('value', 'label');
                }
                return $options;
            }),
            Elm::input('classify_name', '配置分类名称')->required(),
            Elm::input('classify_key', '配置分类key')->required(),
            Elm::input('info', '配置分类说明'),
//            Elm::frameInput('icon', '配置分类图标', '/' . config('admin.admin_prefix') . '/setting/icons?field=icon')->icon('el-icon-circle-plus-outline')->height('338px')->width('700px')->modal(['modal' => false]),
            Elm::number('sort', '排序', 0),
            Elm::switches('status', '是否显示', 1)->activeValue(1)->inactiveValue(0),
        ]);
        $lists = $form->setTitle(is_null($id) ? '添加配置分类' : '编辑配置分类')->formData($formData);
        succ(formToData($lists));
    }

    public function customer($id, $formData = [])
    {
        $formData = $this->configValueModel->_get([
            'customer_tel', 'customer_qq', 'customer_wx'
        ]);
        $form = Elm::createForm('4399');
        $form->setRule([
            Elm::input('customer_tel', '手机号码')->disabled(true),
            Elm::input('customer_wx', '微信')->disabled(true),
            Elm::input('customer_qq', 'qq')->disabled(true),
        ]);
        $lists = $form->setTitle('客服联系方式')->formData($formData->toArray());
        succ(formToData($lists));
    }

    public function login()
    {
        $data = systemConfig(['site_name']);
        succ($data);
    }

    public function lists()
    {
        $lists = $this->configModel;
        succ($lists);
    }

    public function all()
    {
        $data = $this->configClassifyModel->get();


    }

    public function update()
    {
        $params = $this->request->all();
        foreach ($params as $key => $v) {
            $v = json_encode($v);
            $this->configValueModel->where('key', $key)->update(['value' => $v]);
        }
        succ();
    }
}
